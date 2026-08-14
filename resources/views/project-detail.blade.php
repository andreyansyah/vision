<?php
if (!function_exists('parseMarkdown')) {
    function parseMarkdown($text) {
        // Strip outer code block from Output if it wraps the output
        $text = preg_replace('/(\*\*Output:\*\*\s*\n)```\s*\n(.*?)\n```/s', '$1$2', $text);
        
        $html = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        $codeBlocks = [];
        $placeholderPrefix = '___CODEBLOCKPLACEHOLDER___';
        
        $html = preg_replace_callback('/```(.*?)```/s', function($matches) use (&$codeBlocks, $placeholderPrefix) {
            $index = count($codeBlocks);
            $codeBlocks[$index] = $matches[1];
            return $placeholderPrefix . $index . '___';
        }, $html);

        $html = preg_replace('/### (.*?)\n/', '<h5 style="margin: 16px 0 8px 0; font-weight: 600; color: #1f2937; font-family: \'Poppins\', sans-serif; font-size: 14px;">$1</h5>', $html);
        $html = preg_replace('/\*\*(.*?)\*\*/', '<strong style="font-weight: 600; color: #1f2937;">$1</strong>', $html);
        $html = preg_replace('/`(.*?)`/', '<code style="background: rgba(108, 93, 211, 0.08); padding: 3px 6px; border-radius: 6px; font-family: \'Fira Code\', \'Consolas\', Monaco, monospace; font-size: 0.85em; color: #6c5dd3; font-weight: 500; border: 1px solid rgba(108, 93, 211, 0.05);">$1</code>', $html);
        
        $lines = explode("\n", $html);
        $resultLines = [];
        foreach ($lines as $line) {
            $trimmed = trim($line);
            
            if (str_contains($line, $placeholderPrefix)) {
                $line = preg_replace_callback('/' . $placeholderPrefix . '(\d+)___/', function($m) use ($codeBlocks) {
                    $index = (int)$m[1];
                    return '<pre style="background: rgba(108, 93, 211, 0.03); padding: 14px; border-radius: 12px; font-family: \'Fira Code\', \'Consolas\', Monaco, monospace; font-size: 0.85em; overflow-x: auto; border: 1px solid rgba(108, 93, 211, 0.15); color: #374151; margin: 12px 0; max-width: 100%; line-height: 1.5; box-shadow: inset 0 1px 3px rgba(108, 93, 211, 0.02); white-space: pre-wrap; word-break: break-all; box-sizing: border-box;"><code>' . $codeBlocks[$index] . '</code></pre>';
                }, $line);
                $resultLines[] = $line;
                continue;
            }

            if (empty($trimmed)) {
                $resultLines[] = '<div style="height: 6px;"></div>';
            } elseif ($trimmed === '---') {
                $resultLines[] = '<hr style="border: 0; border-top: 1px solid rgba(108, 93, 211, 0.15); margin: 12px 0;">';
            } elseif (str_starts_with($trimmed, '<div') || str_starts_with($trimmed, '<h5')) {
                $resultLines[] = $line;
            } elseif (str_starts_with($trimmed, '* ') || str_starts_with($trimmed, '- ')) {
                $resultLines[] = '<div style="display: flex; gap: 8px; margin-top: 3px; align-items: flex-start; line-height: 1.4;"><span style="color: #6c5dd3; font-size: 14px;">•</span><span>' . ltrim($trimmed, '*- ') . '</span></div>';
            } else {
                $resultLines[] = '<div style="line-height: 1.4; margin-bottom: 2px;">' . $line . '</div>';
            }
        }
        return implode("", $resultLines);
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vision Tasks - Gemini Assistant</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <!-- FontAwesome Icons -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <!-- Link Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/style.css?v=42') }}" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </head>
  <body>
    <!-- Main Mockup Workspace Container -->
    <div class="mockups-container">
      <!-- PHONE DEVICE: GEMINI SCREEN -->
      <div class="phone-device" id="device-gemini">
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
                x="2"
                y="7"
                width="16"
                height="10"
                rx="2"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              />
              <rect x="4" y="9" width="12" height="6" rx="1" />
              <path
                d="M20 10v4"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
              />
            </svg>
          </div>
        </div>

        <!-- Gemini UI Layout Container -->
        <div class="gemini-screen-wrapper">
          <!-- Header -->
          <header
            class="screen-header"
            style="
              margin-bottom: 0;
              padding-top: 0;
              width: 100%;
              box-sizing: border-box;
              display: flex;
              align-items: center;
              justify-content: space-between;
              padding-bottom: 12px;
              border-bottom: 1px solid rgba(108, 93, 211, 0.05);
              position: relative;
              z-index: 110;
            "
          >
            <div style="display: flex; align-items: center; gap: 8px">
              <!-- Hamburger menu trigger -->
              <button
                class="gemini-hamburger-btn"
                id="gemini-hamburger-trigger"
                aria-label="Menu"
              >
                <span></span>
                <span></span>
              </button>

              <!-- Dropdown Trigger -->
              <div
                class="gemini-dropdown-trigger"
                id="project-dropdown-trigger"
              >
                <span id="gemini-project-title-label">{{ $project->name }}</span>
                <i class="fa-solid fa-chevron-down"></i>
              </div>
            </div>

            <div class="header-right-actions">
              <a
                href="/projects"
                class="gemini-home-circle-btn"
                aria-label="Go to Projects"
              >
                <i class="fa-solid fa-briefcase"></i>
              </a>
            </div>
          </header>

          <!-- Floating Dropdown Selector Menu -->
          <div class="gemini-menu-dropdown" id="gemini-projects-dropdown">
            @foreach ($projects as $p)
            <div class="gemini-menu-item {{ $project->id === $p->id ? 'active' : '' }}" onclick="window.location.href='/project-detail?project={{ urlencode($p->name) }}'" data-project="{{ $p->name }}">
              <div class="gemini-menu-item-info">
                <span class="gemini-menu-item-title">{{ $p->name }}</span>
                <span class="gemini-menu-item-desc">Code: {{ $p->code_project }}</span>
              </div>
              <i class="fa-solid fa-check gemini-menu-item-check"></i>
            </div>
            @endforeach
          </div>

          <!-- Sidebar Menu Container -->
          <div
            class="gemini-sidebar-backdrop"
            id="gemini-sidebar-backdrop"
          ></div>
          <div class="gemini-sidebar" id="gemini-sidebar">
            <div class="gemini-sidebar-header">
              <!-- Dynamic Project Logo Container -->
              <div class="gemini-sidebar-logo-wrap">
                <img
                  id="gemini-sidebar-project-logo"
                  src="{{ asset($project->logo) }}"
                  alt="{{ $project->name }}"
                />
              </div>
              <button
                class="gemini-sidebar-close"
                id="gemini-sidebar-close-btn"
                aria-label="Close Menu"
              >
                <i class="fa-solid fa-xmark"></i>
              </button>
            </div>

            <div class="gemini-sidebar-content">
              <!-- New Conversation button -->
              <button
                class="gemini-sidebar-action-btn"
                id="gemini-new-chat-btn"
                onclick="window.location.href='/project-detail?project={{ urlencode($project->name) }}'"
              >
                <i class="fa-regular fa-pen-to-square"></i>
                <span>Automasi baru</span>
              </button>

              <!-- Other Actions -->
              <div class="gemini-sidebar-menu-list">
                <!-- Search Box -->
                <div class="gemini-sidebar-search-box" style="position: relative; margin: 4px 0 12px 0;">
                  <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #6C5DD3; font-size: 13px;"></i>
                  <input
                    type="text"
                    id="gemini-sidebar-search-input"
                    placeholder="Telusuri automasi..."
                    style="width: 100%; padding: 10px 12px 10px 36px; border-radius: 12px; border: 1px solid rgba(108, 93, 211, 0.15); background: rgba(108, 93, 211, 0.04); font-size: 12px; color: #374151; outline: none; transition: all 0.2s; box-sizing: border-box; font-family: inherit; font-weight: 500;"
                    onfocus="this.style.background='#FFFFFF'; this.style.borderColor='rgba(108, 93, 211, 0.4)'; this.style.boxShadow='0 0 0 3px rgba(108, 93, 211, 0.08)';"
                    onblur="this.style.background='rgba(108, 93, 211, 0.04)'; this.style.borderColor='rgba(108, 93, 211, 0.15)'; this.style.boxShadow='none';"
                  />
                </div>
                <div class="gemini-sidebar-menu-item">
                  <i class="fa-solid fa-network-wired"></i>
                  <span>Alur Kerja</span>
                </div>
                <div class="gemini-sidebar-menu-item" onclick="window.location.href='/task-history?project={{ urlencode($project->name) }}'">
                  <i class="fa-solid fa-clock-rotate-left"></i>
                  <span>Riwayat Tugas</span>
                </div>
              </div>

              <div class="gemini-sidebar-divider"></div>

              <!-- Recent Chats Section -->
              <div class="gemini-sidebar-section-title">
                <span>Terbaru</span>
                <i class="fa-solid fa-chevron-down"></i>
              </div>
              <div class="gemini-sidebar-recent-list">
                @foreach ($sessions as $s)
                <div class="gemini-sidebar-recent-item {{ ($activeSession && $activeSession->id === $s->id) ? 'active' : '' }}" onclick="window.location.href='/project-detail?project={{ urlencode($project->name) }}&session_id={{ $s->id }}'">
                  <span class="recent-text">{{ $s->title }}</span>
                  <i class="fa-solid fa-clock option-icon" style="font-size: 11px; opacity: 0.5;"></i>
                </div>
                @endforeach
              </div>
            </div>
          </div>

          <!-- Content Flow Area -->
          <div class="gemini-content-area" id="gemini-chat-flow">
            @if ($activeSession === null || count($messages) === 0)
            <!-- Initial Sparkle & Center Prompt -->
            <div class="gemini-intro-view" id="gemini-intro">
              <div class="gemini-star-wrapper">
                <svg
                  class="gemini-star"
                  viewBox="0 0 100 100"
                  style="
                    filter: drop-shadow(0 10px 20px rgba(108, 93, 211, 0.25));
                  "
                >
                  <defs>
                    <!-- Ribbon 1: Purple/Violet -->
                    <linearGradient
                      id="ribbon1"
                      x1="0%"
                      y1="0%"
                      x2="100%"
                      y2="100%"
                    >
                      <stop offset="0%" stop-color="#8B5CF6" />
                      <stop offset="100%" stop-color="#4F46E5" />
                    </linearGradient>
                    <!-- Ribbon 2: Pink/Magenta -->
                    <linearGradient
                      id="ribbon2"
                      x1="0%"
                      y1="0%"
                      x2="100%"
                      y2="100%"
                    >
                      <stop offset="0%" stop-color="#EC4899" />
                      <stop offset="100%" stop-color="#F43F5E" />
                    </linearGradient>
                    <!-- Floating Vision Sphere -->
                    <linearGradient
                      id="vision-orb"
                      x1="0%"
                      y1="0%"
                      x2="100%"
                      y2="100%"
                    >
                      <stop offset="0%" stop-color="#00F2FE" />
                      <stop offset="100%" stop-color="#4FACFE" />
                    </linearGradient>
                  </defs>

                  <!-- Left Ribbon -->
                  <path
                    fill="url(#ribbon1)"
                    d="M22 18 C22 14, 28 12, 32 15 L 64 72 C 67 77, 63 84, 56 84 C 52 84, 49 81, 47 77 L 22 23 C 21 21, 22 18, 22 18 Z"
                  />

                  <!-- Right Ribbon (overlapping) -->
                  <path
                    fill="url(#ribbon2)"
                    d="M78 18 C78 14, 72 12, 68 15 L 36 72 C 33 77, 37 84, 44 84 C 48 84, 51 81, 53 77 L 78 23 C 79 21, 78 18, 78 18 Z"
                  />

                  <!-- Floating Glowing Vision Orb -->
                  <circle cx="50" cy="30" r="9" fill="url(#vision-orb)" />
                  <!-- Lens reflection overlay -->
                  <circle cx="47" cy="27" r="3" fill="#FFFFFF" opacity="0.6" />
                </svg>
              </div>
              <h2 class="gemini-prompt-title" id="gemini-prompt-title">
                Mau automasi tugas apa di <strong>{{ $project->name }}</strong>?
              </h2>
            </div>
            @endif

            <!-- Chat bubble lists container -->
            <div
              class="gemini-chat-messages"
              id="chat-messages-container"
              style="{{ ($activeSession && count($messages) > 0) ? 'display: flex; opacity: 1;' : '' }}"
            >
              @if ($activeSession && count($messages) > 0)
                @foreach ($messages as $msg)
                  @if ($msg->sender === 'user')
                    <div class="gemini-message user" data-msg-id="{{ $msg->id }}">
                      @if ($msg->image_path)
                        <div style="margin-bottom: 8px;">
                          <img src="{{ asset($msg->image_path) }}" alt="Uploaded Image" style="max-width: 100%; max-height: 200px; border-radius: 8px; display: block; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.05);" />
                        </div>
                      @endif
                      <div>{{ $msg->message }}</div>
                      <div class="chat-delivery-status" style="font-size: 9px; opacity: 0.6; margin-top: 5px; text-align: right; display: flex; align-items: center; justify-content: flex-end; gap: 4px; font-weight: 500;">
                        @if ($msg->status_reply === 'replied')
                          <i class="fa-solid fa-circle-check" style="color: #34D399;"></i> Completed
                        @elseif ($msg->status_send === 'sent')
                          <i class="fa-solid fa-check" style="color: #60A5FA;"></i> Sent to PC
                        @else
                          <i class="fa-solid fa-clock"></i> Pending Send
                        @endif
                      </div>
                    </div>
                  @else
                    <div class="gemini-message bot" data-msg-id="{{ $msg->id }}">
                      <svg class="gemini-bot-star" viewBox="0 0 100 100" width="18" height="18">
                        <defs>
                          <linearGradient id="ribbon1-bot" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#8B5CF6" />
                            <stop offset="100%" stop-color="#4F46E5" />
                          </linearGradient>
                          <linearGradient id="ribbon2-bot" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#EC4899" />
                            <stop offset="100%" stop-color="#F43F5E" />
                          </linearGradient>
                          <linearGradient id="vision-orb-bot" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#00F2FE" />
                            <stop offset="100%" stop-color="#4FACFE" />
                          </linearGradient>
                        </defs>
                        <path fill="url(#ribbon1-bot)" d="M22 18 C22 14, 28 12, 32 15 L 64 72 C 67 77, 63 84, 56 84 C 52 84, 49 81, 47 77 L 22 23 C 21 21, 22 18, 22 18 Z" />
                        <path fill="url(#ribbon2-bot)" d="M78 18 C78 14, 72 12, 68 15 L 36 72 C 33 77, 37 84, 44 84 C 48 84, 51 81, 53 77 L 78 23 C 79 21, 78 18, 78 18 Z" />
                        <circle cx="50" cy="30" r="9" fill="url(#vision-orb-bot)" />
                        <circle cx="47" cy="27" r="3" fill="#FFFFFF" opacity="0.6" />
                      </svg>
                      <div class="gemini-bot-text">
                        {!! parseMarkdown($msg->message) !!}
                      </div>
                    </div>
                  @endif
                @endforeach
              @endif
            </div>
          </div>

          <form class="gemini-bottom-bar" method="POST" action="{{ route('chat.send') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            @if ($activeSession)
            <input type="hidden" name="chat_session_id" value="{{ $activeSession->id }}">
            @endif

            <!-- Image Preview Area -->
            <div id="gemini-image-preview-container" style="display: none; padding: 8px 12px; background: rgba(255, 255, 255, 0.95); border-radius: 12px; margin-bottom: 8px; border: 1px solid rgba(108, 93, 211, 0.15); align-items: center; gap: 10px; box-shadow: 0 4px 14px rgba(0,0,0,0.06); backdrop-filter: blur(10px); width: fit-content; max-width: calc(100% - 24px); margin-left: auto; margin-right: auto; position: relative;">
                <img id="gemini-image-preview" src="" alt="Preview" style="height: 50px; width: 50px; border-radius: 8px; object-fit: cover;" />
                <div style="flex-grow: 1;">
                    <div id="gemini-image-filename" style="font-size: 11px; font-weight: 500; color: #374151; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">file_name.jpg</div>
                    <div style="font-size: 9px; color: #9CA3AF;">Siap dikirim</div>
                </div>
                <button type="button" id="gemini-remove-image-btn" style="background: none; border: none; color: #EF4444; cursor: pointer; padding: 4px; font-size: 16px; display: flex; align-items: center; justify-content: center; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    <i class="fa-solid fa-circle-xmark"></i>
                </button>
            </div>

            <input type="file" id="gemini-image-input" name="image" accept="image/*" style="display: none;" />

            <div class="gemini-input-pill">
              <button type="button" class="gemini-input-action-btn" id="gemini-plus-btn">
                <i class="fa-solid fa-plus"></i>
              </button>
              <input
                type="text"
                id="gemini-user-input"
                name="message"
                placeholder="Minta Vision Assistant"
                autocomplete="off"
                required
              />
              <button type="button" class="gemini-input-action-btn" id="gemini-mic-btn">
                <i class="fa-solid fa-microphone"></i>
              </button>
              <button
                type="submit"
                class="gemini-send-btn"
                id="gemini-send-btn"
                style="display: none"
              >
                <i class="fa-solid fa-paper-plane"></i>
              </button>
            </div>
          </form>
        </div>

        <!-- Bottom Home Line Indicator -->
        <div class="phone-home-indicator"></div>
      </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @if ($activeSession && count($messages) > 0)
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const chatFlow = document.getElementById('gemini-chat-flow');
        if (chatFlow) {
          chatFlow.scrollTop = chatFlow.scrollHeight;
        }
      });
    </script>
    @endif
  </body>
</html>
