from http.server import HTTPServer, BaseHTTPRequestHandler
import json
import time
import os
import subprocess
import urllib.request
import threading

# ==============================================================================
# KONFIGURASI SERVER RUNNER DI KOMPUTER RUMAH
# ==============================================================================
PORT = 5000  # Port lokal yang akan diekspos oleh Cloudflare Tunnel
VISION_APP_URL = "https://trees-toll-publications-charlotte.trycloudflare.com"  # Ganti dengan URL domain cPanel Anda nanti
# ==============================================================================

# ANSI colors untuk log terminal yang estetik
COLOR_HEADER = "\033[95m"
COLOR_BLUE = "\033[94m"
COLOR_GREEN = "\033[92m"
COLOR_WARNING = "\033[93m"
COLOR_FAIL = "\033[91m"
COLOR_END = "\033[0m"
COLOR_BOLD = "\033[1m"

def print_log(level, message):
    timestamp = time.strftime("%Y-%m-%d %H:%M:%S")
    if level == "INFO":
        print(f"[{timestamp}] [{COLOR_BLUE}INFO{COLOR_END}] {message}")
    elif level == "SUCCESS":
        print(f"[{timestamp}] [{COLOR_GREEN}SUCCESS{COLOR_END}] {COLOR_BOLD}{message}{COLOR_END}")
    elif level == "WARNING":
        print(f"[{timestamp}] [{COLOR_WARNING}WARNING{COLOR_END}] {message}")
    elif level == "ERROR":
        print(f"[{timestamp}] [{COLOR_FAIL}ERROR{COLOR_END}] {message}")

def send_reply_to_laravel(message_id, reply_text, status_reply="replied"):
    url = f"{VISION_APP_URL}/api/webhook/reply"
    payload = json.dumps({
        "message_id": message_id,
        "reply_message": reply_text,
        "status_reply": status_reply
    }).encode('utf-8')
    
    req = urllib.request.Request(
        url, 
        data=payload,
        headers={
            "Content-Type": "application/json",
            "User-Agent": "VisionHomeRunner/1.0"
        },
        method="POST"
    )
    
    try:
        with urllib.request.urlopen(req) as response:
            if response.status == 200:
                print_log("INFO", f"Berhasil mengirim balik status respon untuk ID {message_id} ({status_reply})")
                return True
    except Exception as e:
        print_log("ERROR", f"Gagal mengirim balik balasan ke Laravel: {str(e)}")
    return False

def run_antigravity_cli_thread(message_id, project_code, project_dir, message_text, chat_session_id, image_url=None):
    # Cek dan download gambar jika dikirimkan
    relative_image_path = None
    if image_url:
        try:
            import urllib.parse
            parsed_url = urllib.parse.urlparse(image_url)
            image_path_part = parsed_url.path
            
            # Paksa download via VISION_APP_URL tunnel karena user membuka Laravel lewat localhost/127.0.0.1
            base_url = VISION_APP_URL.rstrip('/')
            # Encode path untuk menangani spasi atau karakter spesial
            encoded_path = urllib.parse.quote(image_path_part.lstrip('/'))
            download_url = base_url + '/' + encoded_path
            
            filename = os.path.basename(image_path_part)
            if not filename:
                filename = "downloaded_image.jpg"
                
            # Organisasikan folder uploads/chat_images/{project_code} di luar folder proyek masing-masing
            base_dir = os.path.dirname(os.path.abspath(__file__))
            local_upload_dir = os.path.join(base_dir, "uploads", "chat_images", project_code.lower().strip())
            os.makedirs(local_upload_dir, exist_ok=True)
            
            local_image_path = os.path.join(local_upload_dir, filename)
            
            print_log("INFO", f"Mengunduh gambar dari {download_url} ke {local_image_path}...")
            
            # Gunakan ssl unverified context & custom User-Agent agar tidak diblokir Cloudflare/SSL error di Windows
            import ssl
            context = ssl._create_unverified_context()
            req = urllib.request.Request(
                download_url,
                headers={"User-Agent": "VisionHomeRunner/1.0"}
            )
            
            with urllib.request.urlopen(req, context=context) as response, open(local_image_path, 'wb') as out_file:
                out_file.write(response.read())
                
            print_log("SUCCESS", f"Gambar sukses diunduh ke {local_image_path}")
            
            # Hitung path relatif dari project_dir ke local_image_path agar asisten bisa mengaksesnya secara relative jika diperlukan
            relative_image_path = os.path.relpath(local_image_path, project_dir).replace('\\', '/')
            
            # Tambahkan referensi teks ke prompt asisten
            message_text += f"\n\n[Referensi Gambar Tambahan:\n- Path Absolut Windows: '{local_image_path}'\n- Path Relatif dari Proyek: '{relative_image_path}'\nHarap periksa dan analisislah gambar tersebut untuk membantu menyelesaikan tugas ini jika relevan.]"
        except Exception as img_err:
            print_log("ERROR", f"Gagal mengunduh gambar dari {download_url if 'download_url' in locals() else image_url}: {str(img_err)}")

    # Siapkan perintah agy cli
    # --dangerously-skip-permissions: Auto-approve permissions (karena ini berjalan otomatis)
    # --mode accept-edits: Mengizinkan perubahan file secara langsung
    # --add-dir: Menentukan workspace folder target agar file dibuat di sana
    # --conversation: Isolate conversation history for each chat session
    # --print: Menjalankan command satu kali non-interaktif
    cmd = ["agy", "--dangerously-skip-permissions", "--mode", "accept-edits", "--add-dir", project_dir, "--conversation", f"vision_session_{chat_session_id}", "--print", message_text]
    
    print_log("INFO", f"Menjalankan Antigravity CLI di folder: {project_dir}")
    print_log("INFO", f"Perintah: agy --dangerously-skip-permissions --mode accept-edits --add-dir \"{project_dir}\" --conversation \"vision_session_{chat_session_id}\" --print \"{message_text}\"")
    
    try:
        # Kirim status awal bahwa PC sedang mengeksekusi
        initial_reply = f"🤖 **[Vision Assistant - Memulai Eksekusi]**\nTugas sedang diproses oleh Antigravity CLI di PC Rumah..."
        send_reply_to_laravel(message_id, initial_reply, "processing")

        # Jalankan secara synchronous di dalam thread agar bisa mendapatkan outputnya
        process = subprocess.Popen(cmd, cwd=project_dir, stdout=subprocess.PIPE, stderr=subprocess.PIPE, encoding='utf-8', errors='replace')
        stdout, stderr = process.communicate()
        
        if process.returncode == 0:
            print_log("SUCCESS", f"Antigravity CLI sukses untuk pesan ID: {message_id}")
            output_clean = stdout.strip() if stdout.strip() else "Tugas sukses dijalankan tanpa output teks."
            reply_text = f"🤖 **[Vision Assistant - Eksekusi Sukses]**\nAntigravity CLI berhasil menyelesaikan tugas di proyek `{project_code}`!\n\n**Output:**\n```\n{output_clean}\n```"
            
            # Cek jika folder adalah repository Git, lakukan auto push jika ada perubahan
            git_dir = os.path.join(project_dir, ".git")
            if os.path.exists(git_dir) and os.path.isdir(git_dir):
                git_path = r"C:\Program Files\Git\cmd\git.exe"
                if not os.path.exists(git_path):
                    git_path = "git"  # Fallback ke PATH
                
                # Cek apakah ada perubahan
                status_proc = subprocess.run([git_path, "status", "--porcelain"], cwd=project_dir, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)
                if status_proc.returncode == 0 and status_proc.stdout.strip():
                    print_log("INFO", f"Mendeteksi perubahan kode di proyek {project_code}. Memulai auto push...")
                    
                    # Konfigurasi user git lokal jika belum diset
                    config_check = subprocess.run([git_path, "config", "user.name"], cwd=project_dir, stdout=subprocess.PIPE, text=True)
                    if not config_check.stdout.strip():
                        subprocess.run([git_path, "config", "--local", "user.name", "Vision Assistant"], cwd=project_dir)
                        subprocess.run([git_path, "config", "--local", "user.email", "assistant@vision-home-runner.local"], cwd=project_dir)
                    
                    # Stage, Commit, & Push
                    subprocess.run([git_path, "add", "."], cwd=project_dir)
                    commit_message = f"Auto push: {message_text}"
                    subprocess.run([git_path, "commit", "-m", commit_message], cwd=project_dir)
                    
                    push_proc = subprocess.run([git_path, "push"], cwd=project_dir, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)
                    if push_proc.returncode == 0:
                        print_log("SUCCESS", f"Auto push sukses untuk proyek {project_code}!")
                        reply_text += f"\n\n🚀 **[Auto Push Sukses]**\nPerubahan kode telah otomatis di-push ke Git repository!"
                    else:
                        print_log("ERROR", f"Auto push gagal untuk proyek {project_code}: {push_proc.stderr}")
                        push_err = push_proc.stderr.strip() if push_proc.stderr.strip() else push_proc.stdout.strip()
                        reply_text += f"\n\n⚠️ **[Auto Push Gagal]**\nPerubahan kode gagal di-push ke Git.\nError: `{push_err}`"
        else:
            print_log("ERROR", f"Antigravity CLI gagal (code {process.returncode}) untuk pesan ID: {message_id}")
            error_clean = stderr.strip() if stderr.strip() else stdout.strip()
            reply_text = f"❌ **[Vision Assistant - Eksekusi Gagal]**\nTerjadi kesalahan saat menjalankan Antigravity CLI.\n\n**Error:**\n```\n{error_clean}\n```"
            
        send_reply_to_laravel(message_id, reply_text)
        
    except Exception as run_error:
        print_log("ERROR", f"Gagal menjalankan Antigravity CLI: {str(run_error)}")
        reply_text = f"❌ **[Vision Assistant - Error System]**\nGagal memicu eksekusi: {str(run_error)}"
        send_reply_to_laravel(message_id, reply_text)

class WebhookRequestHandler(BaseHTTPRequestHandler):
    def do_POST(self):
        # Periksa endpoint
        if self.path == '/webhook':
            content_length = int(self.headers['Content-Length'])
            post_data = self.rfile.read(content_length)
            
            try:
                # Parse data kiriman dari Laravel
                payload = json.loads(post_data.decode('utf-8'))
                message_id = payload.get("id")
                message_text = payload.get("message")
                project_code = payload.get("code_project", "unknown")
                chat_session_id = payload.get("chat_session_id", "default")
                image_url = payload.get("image_url")
                
                print("\n" + "-"*50)
                print_log("SUCCESS", f"Menerima Webhook Perintah Instan! Proyek: [{project_code}]")
                print(f" > ID Pesan : {message_id}")
                print(f" > Perintah : {message_text}")
                if image_url:
                    print(f" > Gambar   : {image_url}")
                print("-"*50)
                
                # Kirim HTTP 200 OK ke Laravel bahwa webhook sukses diterima
                self.send_response(200)
                self.send_header('Content-Type', 'application/json')
                self.end_headers()
                response_body = json.dumps({"status": "received", "message_id": message_id})
                self.wfile.write(response_body.encode('utf-8'))
                
                # Eksekusi Antigravity CLI di folder proyek secara asynchronous menggunakan thread
                base_dir = os.path.dirname(os.path.abspath(__file__))
                project_dir = os.path.join(base_dir, project_code.lower().strip())
                
                # Pastikan folder proyek ada
                if not os.path.exists(project_dir):
                    print_log("WARNING", f"Folder proyek [{project_dir}] tidak ditemukan. Membuat folder baru...")
                    os.makedirs(project_dir, exist_ok=True)
                
                # Jalankan thread eksekusi agar tidak memblokir respon HTTP dari webhook
                t = threading.Thread(target=run_antigravity_cli_thread, args=(message_id, project_code, project_dir, message_text, chat_session_id, image_url))
                t.daemon = True
                t.start()
                
            except Exception as e:
                print_log("ERROR", f"Gagal memproses webhook: {str(e)}")
                self.send_response(500)
                self.end_headers()
        else:
            self.send_response(404)
            self.end_headers()

def run(server_class=HTTPServer, handler_class=WebhookRequestHandler, port=PORT):
    server_address = ('', port)
    httpd = server_class(server_address, handler_class)
    
    print(f"\n{COLOR_HEADER}======================================================{COLOR_END}")
    print(f"{COLOR_HEADER}{COLOR_BOLD}     VISION TASK - WEBHOOK RECEIVER (ALWAYS-ON)       {COLOR_END}")
    print(f"{COLOR_HEADER}======================================================{COLOR_END}")
    print_log("INFO", f"Server mendengarkan di: http://localhost:{port}")
    print_log("INFO", f"Ekspos port {port} ini menggunakan Cloudflare Tunnel:")
    print(f"   {COLOR_BOLD}cloudflared tunnel --url http://localhost:{port}{COLOR_END}")
    print_log("INFO", "Menunggu kiriman perintah instan (Menerima Data Saja)...")
    
    try:
        httpd.serve_forever()
    except KeyboardInterrupt:
        print_log("WARNING", "Server PC Runner dihentikan secara manual.")
        httpd.server_close()

if __name__ == "__main__":
    run()