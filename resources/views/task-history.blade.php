<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vision Tasks - Riwayat Tugas</title>
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
    <link rel="stylesheet" href="{{ asset('css/style.css?v=43') }}" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </head>
  <body>
    <!-- Main Mockup Workspace Container -->
    <div class="mockups-container">
      <!-- PHONE DEVICE: TASK HISTORY SCREEN -->
      <div class="phone-device" id="device-task-history">
        <!-- Simulated Notch (Visible on desktop) -->
        <div class="phone-notch"></div>

        <!-- Simulated Phone Status Bar (Visible on desktop) -->
        <div class="phone-status-bar">
          <span class="status-time">09:41</span>
          <div class="status-icons">
            <i class="fa-solid fa-wifi"></i>
            <i class="fa-solid fa-battery-three-quarters"></i>
          </div>
        </div>

        <!-- Phone Screen Area -->
        <div class="phone-screen-content" style="padding-bottom: 24px;">
          <!-- Header -->
          <header class="screen-header">
            <a
              href="/project-detail?project={{ urlencode($project->name) }}"
              class="icon-btn header-back-btn"
              aria-label="Back to Chat"
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
            </div>
          </header>

          <!-- Section Title -->
          <div class="section-title-wrap" style="margin-bottom: 20px;">
            <span class="timeline-meta" style="font-weight: 500; font-size: 11px; color: var(--text-secondary);">Komputer Rumah</span>
            <h2 style="font-family: var(--font-heading); font-size: 24px; font-weight: 700; color: var(--text-primary); margin-top: 2px;">Riwayat Tugas</h2>
          </div>

          <!-- Task Scrollable List Container -->
          <div class="tasks-list-scroll-wrapper" style="overflow-y: auto; flex: 1; padding-right: 2px; margin-bottom: 10px;">
            <div class="tasks-vertical-list" style="display: flex; flex-direction: column; gap: 14px;">
              
              @if (count($tasks) === 0)
                <!-- Empty State -->
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; text-align: center; height: 100%;">
                  <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(108, 93, 211, 0.05); display: flex; align-items: center; justify-content: center; margin-bottom: 16px; color: #6c5dd3; font-size: 32px;">
                    <i class="fa-solid fa-clipboard-list"></i>
                  </div>
                  <h4 style="font-size: 15px; font-weight: 600; color: #374151; margin-bottom: 4px;">Belum Ada Riwayat Tugas</h4>
                  <p style="font-size: 12px; color: #9CA3AF; line-height: 1.4; max-width: 200px;">Kirim pesan baru di chat proyek asisten untuk memulai eksekusi tugas.</p>
                </div>
              @else
                @foreach ($tasks as $task)
                  @php
                    $status = $task->getExecutionStatus();
                    $project = $task->chatSession ? $task->chatSession->project : null;
                    $projectName = $project ? $project->name : 'Unknown Project';
                    $projectLogo = $project ? $project->logo : 'project-logos/xyora.svg';
                  @endphp
                  
                  <div class="task-history-card" 
                       onclick="window.location.href='/project-detail?project={{ urlencode($projectName) }}{{ $task->chat_session_id ? '&session_id=' . $task->chat_session_id : '' }}'"
                       style="background: #FFFFFF; border-radius: 16px; border: 1px solid rgba(108, 93, 211, 0.08); padding: 14px 16px; display: flex; flex-direction: column; gap: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.015); transition: all 0.2s; cursor: pointer;"
                       onmouseover="this.style.borderColor='rgba(108, 93, 211, 0.25)'; this.style.boxShadow='0 6px 16px rgba(108, 93, 211, 0.04)';"
                       onmouseout="this.style.borderColor='rgba(108, 93, 211, 0.08)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.015)';"
                  >
                    <!-- Card Header -->
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                      <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 28px; height: 28px; border-radius: 6px; display: flex; align-items: center; justify-content: center; background: rgba(108, 93, 211, 0.05); overflow: hidden;">
                          <img src="{{ asset($projectLogo) }}" alt="{{ $projectName }}" style="max-width: 80%; max-height: 80%; object-fit: contain;" />
                        </div>
                        <span style="font-weight: 600; font-size: 13px; color: #1F2937;">{{ $projectName }}</span>
                      </div>
                      
                      <!-- Status Badge -->
                      @if ($status === 'success')
                        <span style="font-size: 10px; font-weight: 600; padding: 4px 8px; border-radius: 20px; background: rgba(52, 211, 153, 0.1); color: #059669; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-circle-check"></i> Sukses</span>
                      @elseif ($status === 'failed')
                        <span style="font-size: 10px; font-weight: 600; padding: 4px 8px; border-radius: 20px; background: rgba(239, 68, 68, 0.1); color: #DC2626; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-circle-xmark"></i> Gagal</span>
                      @else
                        <span style="font-size: 10px; font-weight: 600; padding: 4px 8px; border-radius: 20px; background: rgba(59, 130, 246, 0.1); color: #2563EB; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-circle-notch fa-spin"></i> Berjalan</span>
                      @endif
                    </div>

                    <!-- Card Body -->
                    <div style="font-size: 12px; color: #4B5563; line-height: 1.45; word-break: break-word;">
                      {{ Str::limit($task->message, 120) }}
                    </div>

                    <!-- Card Footer -->
                    <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px dashed rgba(108, 93, 211, 0.08); padding-top: 8px; margin-top: 2px;">
                      <span style="font-size: 10px; color: #9CA3AF; display: flex; align-items: center; gap: 4px;">
                        <i class="fa-regular fa-clock"></i> {{ $task->created_at->diffForHumans() }}
                      </span>
                      <span style="font-size: 11px; font-weight: 600; color: #6C5DD3;">
                        Lihat Chat <i class="fa-solid fa-chevron-right" style="font-size: 8px; margin-left: 2px;"></i>
                      </span>
                    </div>
                  </div>
                @endforeach
              @endif

              <!-- Loading Indicator -->
              <div id="loading-indicator" style="display: none; text-align: center; padding: 16px 0; font-size: 12px; color: #6C5DD3; font-weight: 600;">
                <i class="fa-solid fa-circle-notch fa-spin"></i> Memuat tugas lainnya...
              </div>

            </div>
          </div>
        </div>

        <!-- Bottom Home Line Indicator -->
        <div class="phone-home-indicator"></div>
      </div>
    </div>

    <!-- Infinite Scroll Script -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
          const scrollWrapper = document.querySelector('.tasks-list-scroll-wrapper');
          const listContainer = document.querySelector('.tasks-vertical-list');
          const loader = document.getElementById('loading-indicator');
          
          let currentPage = 1;
          let lastPage = {{ $tasks->lastPage() }};
          let isLoading = false;
          
          if (!scrollWrapper || !listContainer) return;
          
          scrollWrapper.addEventListener('scroll', function() {
              const scrollTop = scrollWrapper.scrollTop;
              const scrollHeight = scrollWrapper.scrollHeight;
              const clientHeight = scrollWrapper.clientHeight;
              
              // Load more when scrolled close to bottom (within 40px)
              if (scrollHeight - scrollTop - clientHeight < 40) {
                  loadMoreTasks();
              }
          });
          
          function loadMoreTasks() {
              if (isLoading || currentPage >= lastPage) return;
              
              isLoading = true;
              if (loader) loader.style.display = 'block';
              
              const nextPage = currentPage + 1;
              const projectParam = encodeURIComponent("{{ $project->name }}");
              
              fetch(`/task-history?project=${projectParam}&page=${nextPage}`, {
                  headers: {
                      'X-Requested-With': 'XMLHttpRequest'
                  }
              })
              .then(res => res.json())
              .then(resData => {
                  isLoading = false;
                  if (loader) loader.style.display = 'none';
                  
                  if (resData && resData.data) {
                      currentPage = resData.current_page;
                      lastPage = resData.last_page;
                      
                      resData.data.forEach(task => {
                          const card = document.createElement('div');
                          card.className = 'task-history-card';
                          card.style.cssText = `
                              background: #FFFFFF;
                              border-radius: 16px;
                              border: 1px solid rgba(108, 93, 211, 0.08);
                              padding: 14px 16px;
                              display: flex;
                              flex-direction: column;
                              gap: 10px;
                              box-shadow: 0 4px 12px rgba(0,0,0,0.015);
                              transition: all 0.2s;
                              cursor: pointer;
                          `;
                          
                          // Hover effect script binding
                          card.onmouseover = function() {
                              this.style.borderColor = 'rgba(108, 93, 211, 0.25)';
                              this.style.boxShadow = '0 6px 16px rgba(108, 93, 211, 0.04)';
                          };
                          card.onmouseout = function() {
                              this.style.borderColor = 'rgba(108, 93, 211, 0.08)';
                              this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.015)';
                          };
                          
                          // Redirect onclick
                          const redirectUrl = `/project-detail?project=${encodeURIComponent(task.project_name)}` + (task.chat_session_id ? `&session_id=${task.chat_session_id}` : '');
                          card.onclick = function() {
                              window.location.href = redirectUrl;
                          };
                          
                          // Status Badge HTML
                          let statusBadge = '';
                          if (task.status === 'success') {
                              statusBadge = `<span style="font-size: 10px; font-weight: 600; padding: 4px 8px; border-radius: 20px; background: rgba(52, 211, 153, 0.1); color: #059669; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-circle-check"></i> Sukses</span>`;
                          } else if (task.status === 'failed') {
                              statusBadge = `<span style="font-size: 10px; font-weight: 600; padding: 4px 8px; border-radius: 20px; background: rgba(239, 68, 68, 0.1); color: #DC2626; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-circle-xmark"></i> Gagal</span>`;
                          } else {
                              statusBadge = `<span style="font-size: 10px; font-weight: 600; padding: 4px 8px; border-radius: 20px; background: rgba(59, 130, 246, 0.1); color: #2563EB; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-circle-notch fa-spin"></i> Berjalan</span>`;
                          }
                          
                          card.innerHTML = `
                              <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                  <div style="width: 28px; height: 28px; border-radius: 6px; display: flex; align-items: center; justify-content: center; background: rgba(108, 93, 211, 0.05); overflow: hidden;">
                                    <img src="${task.project_logo}" alt="${task.project_name}" style="max-width: 80%; max-height: 80%; object-fit: contain;" />
                                  </div>
                                  <span style="font-weight: 600; font-size: 13px; color: #1F2937;">${task.project_name}</span>
                                </div>
                                ${statusBadge}
                              </div>
                              <div style="font-size: 12px; color: #4B5563; line-height: 1.45; word-break: break-word;">
                                ${task.short_message}
                              </div>
                              <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px dashed rgba(108, 93, 211, 0.08); padding-top: 8px; margin-top: 2px;">
                                <span style="font-size: 10px; color: #9CA3AF; display: flex; align-items: center; gap: 4px;">
                                  <i class="fa-regular fa-clock"></i> ${task.created_at_human}
                                </span>
                                <span style="font-size: 11px; font-weight: 600; color: #6C5DD3;">
                                  Lihat Chat <i class="fa-solid fa-chevron-right" style="font-size: 8px; margin-left: 2px;"></i>
                                </span>
                              </div>
                          `;
                          
                          // Append to list container before loader
                          listContainer.insertBefore(card, loader);
                      });
                  }
              })
              .catch(err => {
                  isLoading = false;
                  if (loader) loader.style.display = 'none';
                  console.error("Gagal memuat tugas tambahan:", err);
              });
          }
      });
    </script>
  </body>
</html>
