<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vision Tasks - Notes</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Link Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/style.css?v=42') }}" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Summernote Lite CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
    <!-- Main Mockup Workspace Container -->
    <div class="mockups-container">
      <!-- PHONE DEVICE: NOTES SCREEN -->
      <div class="phone-device" id="device-notes">
        <!-- Simulated Notch (Visible on desktop) -->
        <div class="phone-notch"></div>

        <!-- Simulated Phone Status Bar (Visible on desktop) -->
        <div class="phone-status-bar">
          <span class="status-time">09:41</span>
          <div class="status-icons">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
              <path
                d="M12 21a2 2 0 1 1-2-2 2 2 0 0 1 2 2zm1-5a3.99 4 0 0 0-4-4 3.99 4 0 0 0-4 4 1 1 0 0 0 2 0 2 2 0 0 1 2-2 2 2 0 0 1 2 2 1 1 0 0 0 2 0zm1-5a7.97 8 0 0 0-8-8 7.97 8 0 0 0-8 8 1 1 0 0 0 2 0 5.98 6 0 0 1 6-6 5.98 6 0 0 1 6 6 1 1 0 0 0 2 0z"
              />
            </svg>
            <svg
              viewBox="0 0 24 24"
              width="16"
              height="16"
              fill="currentColor"
              style="transform: rotate(90deg)"
            >
              <rect
                x="6"
                y="2"
                width="12"
                height="20"
                rx="3"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              />
              <rect x="8" y="5" width="8" height="14" rx="1.5" />
            </svg>
          </div>
        </div>

        <!-- Phone Screen Area -->
        <div class="phone-screen-content">
          <!-- Notes Header -->
          <header class="screen-header">
            <a
              href="/"
              class="icon-btn header-back-btn"
              aria-label="Back to Dashboard"
            >
              <svg
                viewBox="0 0 24 24"
                width="24"
                height="24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <line x1="19" y1="12" x2="5" y2="12" />
                <polyline points="12 19 5 12 12 5" />
              </svg>
            </a>
            <div class="header-right-actions">
              <button class="icon-btn" aria-label="Notifications">
                <svg
                  viewBox="0 0 24 24"
                  width="22"
                  height="22"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                  <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                </svg>
              </button>
              <button class="icon-btn" aria-label="Filter">
                <svg
                  viewBox="0 0 24 24"
                  width="22"
                  height="22"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <line x1="4" y1="21" x2="4" y2="14" />
                  <line x1="4" y1="10" x2="4" y2="3" />
                  <line x1="12" y1="21" x2="12" y2="12" />
                  <line x1="12" y1="8" x2="12" y2="3" />
                  <line x1="20" y1="21" x2="20" y2="16" />
                  <line x1="20" y1="12" x2="20" y2="3" />
                  <line x1="1" y1="14" x2="7" y2="14" />
                  <line x1="9" y1="8" x2="15" y2="8" />
                  <line x1="17" y1="12" x2="23" y2="12" />
                </svg>
              </button>
            </div>
          </header>

          <!-- Section Title -->
          <div class="section-title-wrap" style="margin-bottom: 24px;">
            <span class="timeline-meta" style="font-weight: 500; font-size: 11px; color: var(--text-secondary);">Workspace</span>
            <h2 style="font-family: var(--font-heading); font-size: 24px; font-weight: 700; color: var(--text-primary); margin-top: 2px;">My Notes</h2>
          </div>

          <!-- Notes Scrollable List Container -->
          <div class="notes-list-scroll-wrapper" style="overflow-y: auto; flex: 1; padding-right: 2px; margin-bottom: 10px;">
            <div class="notes-vertical-list" id="notes-list" style="display: flex; flex-direction: column; gap: 14px;">
              
              @if (count($notes) === 0)
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; text-align: center; height: 100%;">
                  <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(108, 93, 211, 0.05); display: flex; align-items: center; justify-content: center; margin-bottom: 16px; color: #6c5dd3; font-size: 32px;">
                    <i class="fa-regular fa-clipboard"></i>
                  </div>
                  <h4 style="font-size: 15px; font-weight: 600; color: #374151; margin-bottom: 4px;">Belum Ada Catatan</h4>
                  <p style="font-size: 12px; color: #9CA3AF; line-height: 1.4; max-width: 200px;">Klik tombol + untuk membuat catatan baru pertama Anda.</p>
                </div>
              @else
                @foreach ($notes as $note)
                <div class="note-swipe-wrapper" data-id="{{ $note->id }}">
                  <div class="note-delete-action">
                    <i class="fa-solid fa-trash-can"></i>
                  </div>
                  <div class="note-card-custom" data-title="{{ $note->title }}" data-content="{{ $note->content }}">
                    <div class="note-card-header">
                      <h4 class="note-title">{{ $note->title }}</h4>
                    </div>
                    <p class="note-snippet">{{ Str::limit(strip_tags($note->content), 120) }}</p>
                    <span class="note-date">{{ $note->updated_at->format('d F Y') }}</span>
                  </div>
                </div>
                @endforeach
              @endif

            </div>
          </div>
        </div>

        <!-- Floating Action Button (FAB) -->
        <button class="fab-btn" id="trigger-create-task" title="Add Note">
          <i class="fa-solid fa-plus"></i>
        </button>

        <!-- Sticky Bottom Navigation Bar (links to other pages) -->
        <nav class="phone-bottom-nav">
          <a href="/" class="nav-btn" title="Home">
            <i class="fa-solid fa-house"></i>
          </a>
          <a href="/schedule" class="nav-btn" title="Calendar">
            <i class="fa-regular fa-calendar"></i>
          </a>
          <a href="/projects" class="nav-btn" title="Projects">
            <i class="fa-solid fa-briefcase"></i>
          </a>
          <a href="/notes" class="nav-btn active" title="Notes">
            <i class="fa-regular fa-pen-to-square"></i>
          </a>
        </nav>

        <!-- Bottom Home Line Indicator -->
        <div class="phone-home-indicator"></div>

        <!-- Task Form sliding bottom sheet overlay -->
        <div class="bottom-sheet" id="sheet-create-task">
          <div class="sheet-backdrop"></div>
          <div class="sheet-content">
            <div class="sheet-handle-bar">
              <span class="sheet-handle"></span>
            </div>
            <div class="sheet-body">
              <form id="task-creation-form">
                <input type="hidden" id="edit-note-id" value="" />
                <div class="form-group">
                  <label class="field-label">Title</label>
                  <input
                    type="text"
                    class="text-input"
                    id="t-title"
                    placeholder="Note Title"
                    required
                  />
                </div>
                <div class="form-group">
                  <label class="field-label">Note Content</label>
                  <textarea
                    class="text-input"
                    id="t-desc"
                    placeholder="Write your thoughts here..."
                    rows="6"
                    style="resize: none; height: 120px; padding: 12px 16px; border-radius: 14px; font-family: inherit; font-size: 13px; line-height: 1.6; box-sizing: border-box;"
                    required
                  ></textarea>
                </div>
                <button type="submit" class="cta-submit-btn" id="btn-submit-note" style="margin-top: 10px;">
                  Create note
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Notification Toast -->
    <div class="notification-toast" id="toast-message">
      Note created successfully!
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    <script>
      $(document).ready(function() {
          const sheet = document.getElementById('sheet-create-task');
          const trigger = document.getElementById('trigger-create-task');
          const titleInput = document.getElementById('t-title');
          const descInput = document.getElementById('t-desc');
          const editIdInput = document.getElementById('edit-note-id');
          const submitBtn = document.getElementById('btn-submit-note');
          const form = document.getElementById('task-creation-form');
          
          // Setup CSRF token header for all AJAX requests
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          
          // Helper: Show toast notification
          function showToast(message) {
              const toast = document.getElementById('toast-message');
              if (toast) {
                  toast.textContent = message;
                  toast.classList.add('show-toast');
                  setTimeout(() => {
                      toast.classList.remove('show-toast');
                  }, 3000);
              }
          }
          
          // Close sheet helper
          function closeSheet() {
              if (sheet) sheet.classList.remove('show-sheet');
          }
          
          // Open sheet helper
          function openSheet() {
              if (sheet) sheet.classList.add('show-sheet');
          }
          
          // 1. FAB Create Click: Reset fields and set to create mode
          if (trigger) {
              trigger.addEventListener('click', function(e) {
                  e.stopPropagation();
                  editIdInput.value = '';
                  titleInput.value = '';
                  if ($('#t-desc').data('summernote')) {
                      $('#t-desc').summernote('code', '');
                  } else {
                      descInput.value = '';
                  }
                  submitBtn.textContent = 'Create note';
                  openSheet();
              });
          }
          
          // 2. Note Card Click: Fill fields and set to edit mode
          $('.note-card-custom').on('click', function() {
              const wrapper = $(this).closest('.note-swipe-wrapper');
              const noteId = wrapper.attr('data-id');
              const title = $(this).attr('data-title');
              const content = $(this).attr('data-content');
              
              editIdInput.value = noteId;
              titleInput.value = title;
              
              if ($('#t-desc').data('summernote')) {
                  $('#t-desc').summernote('code', content || '');
              } else {
                  descInput.value = content || '';
              }
              
              submitBtn.textContent = 'Save changes';
              openSheet();
          });
          
          // 3. Form Submit Handler (AJAX Create or Update)
          if (form) {
              form.addEventListener('submit', function(e) {
                  e.preventDefault();
                  
                  const noteId = editIdInput.value;
                  const title = titleInput.value.trim();
                  const content = $('#t-desc').data('summernote') ? $('#t-desc').summernote('code') : descInput.value.trim();
                  
                  if (!title) return;
                  
                  const isEdit = noteId && noteId.length > 0;
                  const url = isEdit ? `/notes/${noteId}` : '/notes';
                  const type = isEdit ? 'PUT' : 'POST';
                  
                  $.ajax({
                      url: url,
                      type: type,
                      data: {
                          title: title,
                          content: content
                      },
                      success: function(res) {
                          if (res.status === 'success') {
                              closeSheet();
                              
                              // SweetAlert2 Toast
                              Swal.fire({
                                  toast: true,
                                  position: 'top-end',
                                  icon: 'success',
                                  title: isEdit ? "Catatan berhasil diperbarui!" : "Catatan berhasil ditambahkan!",
                                  showConfirmButton: false,
                                  timer: 1500,
                                  timerProgressBar: true
                              });
                              
                              setTimeout(() => {
                                  window.location.reload();
                              }, 1500);
                          }
                      },
                      error: function(err) {
                          console.error("Gagal menyimpan catatan:", err);
                          Swal.fire({
                              icon: 'error',
                              title: 'Gagal',
                              text: 'Terjadi kesalahan saat menyimpan catatan.',
                              confirmButtonColor: '#6C5DD3'
                          });
                      }
                  });
              });
          }
          
          // 4. Overwrite default click delete action in bindSwipeEvent
          // Mengubah fungsi klik icon trash agar memanggil AJAX DELETE ke backend dengan SweetAlert2
          $('.note-delete-action').off('click').on('click', function(e) {
              e.stopPropagation();
              const wrapper = $(this).closest('.note-swipe-wrapper');
              const noteId = wrapper.attr('data-id');
              
              if (!noteId) {
                  wrapper.remove();
                  return;
              }
              
              Swal.fire({
                  title: 'Apakah Anda yakin?',
                  text: "Catatan ini akan dihapus secara permanen!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#ef4444',
                  cancelButtonColor: '#9CA3AF',
                  confirmButtonText: 'Ya, hapus!',
                  cancelButtonText: 'Batal'
              }).then((result) => {
                  if (result.isConfirmed) {
                      $.ajax({
                          url: `/notes/${noteId}`,
                          type: 'DELETE',
                          success: function(res) {
                              if (res.status === 'success') {
                                  wrapper.css({
                                      transition: 'max-height 0.3s ease, margin-bottom 0.3s ease, opacity 0.3s ease',
                                      maxHeight: '0',
                                      marginBottom: '0',
                                      opacity: '0'
                                  });
                                  setTimeout(() => {
                                      wrapper.remove();
                                      // Tampilkan empty state jika tidak ada catatan tersisa
                                      if ($('.note-swipe-wrapper').length === 0) {
                                          window.location.reload();
                                      }
                                  }, 300);
                                  
                                  // SweetAlert2 Toast
                                  Swal.fire({
                                      toast: true,
                                      position: 'top-end',
                                      icon: 'success',
                                      title: 'Catatan berhasil dihapus!',
                                      showConfirmButton: false,
                                      timer: 1500,
                                      timerProgressBar: true
                                  });
                              }
                          },
                          error: function(err) {
                              console.error("Gagal menghapus catatan:", err);
                              Swal.fire({
                                  icon: 'error',
                                  title: 'Gagal',
                                  text: 'Gagal menghapus catatan.',
                                  confirmButtonColor: '#6C5DD3'
                              });
                          }
                      });
                  }
              });
          });
      });
    </script>
  </body>
</html>
