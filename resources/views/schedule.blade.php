<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vision Tasks - Schedule</title>
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
  </head>
  <body>
    <!-- Main Mockup Workspace Container -->
    <div class="mockups-container">
      <!-- PHONE DEVICE: TIMELINE SCREEN ("Today") -->
      <div class="phone-device" id="device-schedule">
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
          <!-- Timeline Header -->
          <header class="screen-header">
            <a
              href="index.html"
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

          <!-- Date and Title header -->
          <div class="timeline-header">
            <h2>Today</h2>
          </div>

          <!-- Unified Calendar Widget Section -->
          <div class="calendar-widget-section" id="calendar-widget">
            <!-- Calendar Header (Month + Segmented Toggle Control) -->
            <div class="calendar-widget-header">
              <div class="calendar-month-nav">
                <button
                  class="icon-btn-sm"
                  id="btn-prev-month"
                  aria-label="Previous Month"
                >
                  <svg
                    viewBox="0 0 24 24"
                    width="16"
                    height="16"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2.5"
                  >
                    <polyline points="15 18 9 12 15 6" />
                  </svg>
                </button>
                <div class="month-year-selector" id="trigger-month-year-picker">
                  <span class="calendar-month-title" id="calendar-month-label"
                    >August 2020</span
                  >
                  <svg
                    viewBox="0 0 24 24"
                    width="14"
                    height="14"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2.5"
                    class="dropdown-arrow"
                  >
                    <polyline points="6 9 12 15 18 9" />
                  </svg>
                </div>
                <button
                  class="icon-btn-sm"
                  id="btn-next-month"
                  aria-label="Next Month"
                >
                  <svg
                    viewBox="0 0 24 24"
                    width="16"
                    height="16"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2.5"
                  >
                    <polyline points="9 18 15 12 9 6" />
                  </svg>
                </button>
              </div>
              <!-- Toggle segmented control -->
              <div class="calendar-view-toggle">
                <button class="toggle-btn active" id="btn-view-week">
                  Week
                </button>
                <button class="toggle-btn" id="btn-view-month">Month</button>
              </div>
            </div>

            <!-- Month/Year Dropdown Picker Overlay -->
            <div class="month-year-dropdown" id="month-year-dropdown-picker">
              <div class="picker-section">
                <span class="picker-label">Month</span>
                <div class="picker-grid months-grid">
                  <span class="picker-opt" data-val="0">Jan</span>
                  <span class="picker-opt" data-val="1">Feb</span>
                  <span class="picker-opt" data-val="2">Mar</span>
                  <span class="picker-opt" data-val="3">Apr</span>
                  <span class="picker-opt" data-val="4">May</span>
                  <span class="picker-opt" data-val="5">Jun</span>
                  <span class="picker-opt" data-val="6">Jul</span>
                  <span class="picker-opt active-opt" data-val="7">Aug</span>
                  <span class="picker-opt" data-val="8">Sep</span>
                  <span class="picker-opt" data-val="9">Oct</span>
                  <span class="picker-opt" data-val="10">Nov</span>
                  <span class="picker-opt" data-val="11">Dec</span>
                </div>
              </div>
              <div class="picker-section">
                <span class="picker-label">Year</span>
                <div class="picker-grid years-grid">
                  <span class="picker-opt" data-val="2018">2018</span>
                  <span class="picker-opt" data-val="2019">2019</span>
                  <span class="picker-opt active-opt" data-val="2020"
                    >2020</span
                  >
                  <span class="picker-opt" data-val="2021">2021</span>
                  <span class="picker-opt" data-val="2022">2022</span>
                  <span class="picker-opt" data-val="2023">2023</span>
                  <span class="picker-opt" data-val="2024">2024</span>
                  <span class="picker-opt" data-val="2025">2025</span>
                  <span class="picker-opt" data-val="2026">2026</span>
                </div>
              </div>
            </div>

            <!-- 1. WEEKLY STRIP VIEW (Default) -->
            <div class="calendar-weekly-wrap active-view">
              <div class="calendar-strip">
                <div class="calendar-day-col" data-date="10">
                  <span class="day-name">MON</span>
                  <span class="day-number">10</span>
                </div>
                <div class="calendar-day-col" data-date="11">
                  <span class="day-name">TUE</span>
                  <span class="day-number">11</span>
                </div>
                <div class="calendar-day-col" data-date="12">
                  <span class="day-name">WED</span>
                  <span class="day-number">12</span>
                </div>
                <div class="calendar-day-col active-day" data-date="13">
                  <span class="day-name">THU</span>
                  <span class="day-number">13</span>
                  <span class="active-dot"></span>
                </div>
                <div class="calendar-day-col" data-date="14">
                  <span class="day-name">FRI</span>
                  <span class="day-number">14</span>
                </div>
                <div class="calendar-day-col" data-date="15">
                  <span class="day-name">SAT</span>
                  <span class="day-number">15</span>
                </div>
                <div class="calendar-day-col" data-date="16">
                  <span class="day-name">SUN</span>
                  <span class="day-number">16</span>
                </div>
              </div>
            </div>

            <!-- 2. MONTHLY EXPANDABLE GRID VIEW (Hidden by default) -->
            <div class="calendar-monthly-wrap">
              <!-- Grid header names -->
              <div class="calendar-grid-header">
                <span>S</span><span>M</span><span>T</span><span>W</span
                ><span>T</span><span>F</span><span>S</span>
              </div>
              <!-- 7x5 Grid cells of August 2020 -->
              <div class="calendar-grid">
                <!-- July placeholders -->
                <div class="grid-day-col prev-month" data-date="26">
                  <span>26</span>
                </div>
                <div class="grid-day-col prev-month" data-date="27">
                  <span>27</span>
                </div>
                <div class="grid-day-col prev-month" data-date="28">
                  <span>28</span>
                </div>
                <div class="grid-day-col prev-month" data-date="29">
                  <span>29</span>
                </div>
                <div class="grid-day-col prev-month" data-date="30">
                  <span>30</span>
                </div>
                <div class="grid-day-col prev-month" data-date="31">
                  <span>31</span>
                </div>
                <!-- August active days -->
                <div class="grid-day-col" data-date="1"><span>1</span></div>
                <div class="grid-day-col" data-date="2"><span>2</span></div>
                <div class="grid-day-col" data-date="3"><span>3</span></div>
                <div class="grid-day-col" data-date="4"><span>4</span></div>
                <div class="grid-day-col" data-date="5"><span>5</span></div>
                <div class="grid-day-col" data-date="6"><span>6</span></div>
                <div class="grid-day-col" data-date="7"><span>7</span></div>
                <div class="grid-day-col" data-date="8"><span>8</span></div>
                <div class="grid-day-col" data-date="9"><span>9</span></div>
                <div class="grid-day-col" data-date="10">
                  <span>10</span>
                  <div class="grid-dots-wrap">
                    <span class="dot-indicator blue-dot"></span>
                  </div>
                </div>
                <div class="grid-day-col" data-date="11">
                  <span>11</span>
                  <div class="grid-dots-wrap">
                    <span class="dot-indicator purple-dot"></span>
                  </div>
                </div>
                <div class="grid-day-col" data-date="12"><span>12</span></div>
                <div class="grid-day-col active-day" data-date="13">
                  <span>13</span>
                  <div class="grid-dots-wrap">
                    <span class="dot-indicator blue-dot"></span>
                    <span class="dot-indicator purple-dot"></span>
                  </div>
                </div>
                <div class="grid-day-col" data-date="14"><span>14</span></div>
                <div class="grid-day-col" data-date="15"><span>15</span></div>
                <div class="grid-day-col" data-date="16"><span>16</span></div>
                <div class="grid-day-col" data-date="17">
                  <span>17</span>
                  <div class="grid-dots-wrap">
                    <span class="dot-indicator purple-dot"></span>
                  </div>
                </div>
                <div class="grid-day-col" data-date="18"><span>18</span></div>
                <div class="grid-day-col" data-date="19"><span>19</span></div>
                <div class="grid-day-col" data-date="20"><span>20</span></div>
                <div class="grid-day-col" data-date="21"><span>21</span></div>
                <div class="grid-day-col" data-date="22"><span>22</span></div>
                <div class="grid-day-col" data-date="23"><span>23</span></div>
                <div class="grid-day-col" data-date="24"><span>24</span></div>
                <div class="grid-day-col" data-date="25"><span>25</span></div>
                <div class="grid-day-col" data-date="26"><span>26</span></div>
                <div class="grid-day-col" data-date="27"><span>27</span></div>
                <div class="grid-day-col" data-date="28"><span>28</span></div>
                <div class="grid-day-col" data-date="29"><span>29</span></div>
                <div class="grid-day-col" data-date="30"><span>30</span></div>
                <div class="grid-day-col" data-date="31"><span>31</span></div>
                <!-- September placeholders -->
                <div class="grid-day-col next-month" data-date="1">
                  <span>1</span>
                </div>
                <div class="grid-day-col next-month" data-date="2">
                  <span>2</span>
                </div>
                <div class="grid-day-col next-month" data-date="3">
                  <span>3</span>
                </div>
                <div class="grid-day-col next-month" data-date="4">
                  <span>4</span>
                </div>
                <div class="grid-day-col next-month" data-date="5">
                  <span>5</span>
                </div>
              </div>
            </div>

            <!-- Swipe/Drag Handle at the bottom of the calendar section -->
            <div class="calendar-drag-handle" id="calendar-handle">
              <span class="drag-bar"></span>
            </div>
          </div>

          <!-- Vertical Timeline Scroll Area -->
          <div class="timeline-scroll-wrapper">
            <div class="timeline-list">
              <!-- Primary high-contrast meeting card -->
              <div class="timeline-item">
                <div class="timeline-time"><time>09:30</time></div>
                <div class="timeline-node"><span class="node-ring"></span></div>
                <div
                  class="timeline-content-card gradient-dark-indigo text-light"
                >
                  <div class="timeline-card-header">
                    <h4>Design Meeting</h4>
                    <span class="timeline-card-time">09:30</span>
                  </div>
                  <p class="timeline-card-desc">
                    Make a landing page app and mobile
                  </p>
                  <div class="timeline-card-footer">
                    <div class="avatar-group">
                      <img
                        src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=80"
                        alt="Team 1"
                        class="avatar-stack"
                      />
                      <img
                        src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=80"
                        alt="Team 2"
                        class="avatar-stack"
                      />
                      <img
                        src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&q=80&w=80"
                        alt="Team 3"
                        class="avatar-stack"
                      />
                      <img
                        src="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=crop&q=80&w=80"
                        alt="Team 4"
                        class="avatar-stack"
                      />
                    </div>
                  </div>
                </div>
              </div>

              <!-- General Meeting Cards -->
              <div class="timeline-item">
                <div class="timeline-time"><time>10:30</time></div>
                <div class="timeline-node">
                  <span class="node-bullet"></span>
                </div>
                <div class="timeline-content-card plain-white-card">
                  <div class="timeline-card-header">
                    <h4>Team Meeting</h4>
                    <span class="timeline-card-time">10:30</span>
                  </div>
                  <p class="timeline-card-desc-grey">
                    Lorem ipsum dolor sit amet consectetuer.
                  </p>
                  <div class="timeline-card-options-wrap">
                    <button
                      class="list-item-action-btn"
                      aria-label="Task Actions"
                    >
                      <svg
                        viewBox="0 0 24 24"
                        width="20"
                        height="20"
                        fill="currentColor"
                      >
                        <circle cx="5" cy="12" r="1.5" />
                        <circle cx="12" cy="12" r="1.5" />
                        <circle cx="19" cy="12" r="1.5" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <div class="timeline-item">
                <div class="timeline-time"><time>11:00</time></div>
                <div class="timeline-node">
                  <span class="node-bullet"></span>
                </div>
                <div class="timeline-content-card plain-white-card">
                  <div class="timeline-card-header">
                    <h4>Client Update</h4>
                    <span class="timeline-card-time">11:00</span>
                  </div>
                  <p class="timeline-card-desc-grey">
                    Lorem ipsum dolor sit amet consectetuer.
                  </p>
                  <div class="timeline-card-options-wrap">
                    <button
                      class="list-item-action-btn"
                      aria-label="Task Actions"
                    >
                      <svg
                        viewBox="0 0 24 24"
                        width="20"
                        height="20"
                        fill="currentColor"
                      >
                        <circle cx="5" cy="12" r="1.5" />
                        <circle cx="12" cy="12" r="1.5" />
                        <circle cx="19" cy="12" r="1.5" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <div class="timeline-item">
                <div class="timeline-time"><time>12:30</time></div>
                <div class="timeline-node">
                  <span class="node-bullet"></span>
                </div>
                <div class="timeline-content-card plain-white-card">
                  <div class="timeline-card-header">
                    <h4>Stakeholder Interview</h4>
                    <span class="timeline-card-time">12:30</span>
                  </div>
                  <p class="timeline-card-desc-grey">
                    Lorem ipsum dolor sit amet consectetuer.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sticky Bottom Navigation Bar (links to other pages) -->
        <nav class="phone-bottom-nav">
          <a href="/" class="nav-btn" title="Home">
            <i class="fa-solid fa-house"></i>
          </a>
          <a href="/schedule" class="nav-btn active" title="Calendar">
            <i class="fa-regular fa-calendar"></i>
          </a>
          <a href="/projects" class="nav-btn" title="Projects">
            <i class="fa-solid fa-briefcase"></i>
          </a>
          <a href="/notes" class="nav-btn" title="Notes">
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
                <div class="form-group">
                  <label class="field-label">Title</label>
                  <input
                    type="text"
                    class="text-input"
                    id="t-title"
                    placeholder="Task Title"
                    required
                  />
                </div>
                <div class="form-group">
                  <label class="field-label">Creation date</label>
                  <input
                    type="text"
                    class="text-input"
                    id="t-cdate"
                    value="17 August 2020"
                  />
                </div>
                <div class="form-group">
                  <div class="date-time-header">
                    <label class="field-label">Start date & time</label>
                    <div class="month-selector-dropdown">
                      <span class="month-label">August</span>
                      <svg
                        class="dropdown-chevron"
                        viewBox="0 0 24 24"
                        width="16"
                        height="16"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2.5"
                      >
                        <polyline points="6 9 12 15 18 9" />
                      </svg>
                    </div>
                  </div>
                  <div class="modal-date-picker-row">
                    <div class="modal-date-col" data-day="10">
                      <span class="m-day-name">MON</span
                      ><span class="m-day-number">10</span>
                    </div>
                    <div class="modal-date-col active-m-day" data-day="11">
                      <span class="m-day-name">TUE</span
                      ><span class="m-day-number">11</span
                      ><span class="active-dot"></span>
                    </div>
                    <div class="modal-date-col" data-day="12">
                      <span class="m-day-name">WED</span
                      ><span class="m-day-number">12</span>
                    </div>
                    <div class="modal-date-col" data-day="13">
                      <span class="m-day-name">THU</span
                      ><span class="m-day-number">13</span>
                    </div>
                    <div class="modal-date-col" data-day="14">
                      <span class="m-day-name">FRI</span
                      ><span class="m-day-number">14</span>
                    </div>
                    <div class="modal-date-col" data-day="15">
                      <span class="m-day-name">SAT</span
                      ><span class="m-day-number">15</span>
                    </div>
                    <div class="modal-date-col" data-day="16">
                      <span class="m-day-name">SUN</span
                      ><span class="m-day-number">16</span>
                    </div>
                  </div>
                </div>
                <div class="form-toggle-group">
                  <span class="toggle-label">Get alert for this task</span>
                  <label class="switch">
                    <input type="checkbox" id="t-alert" checked />
                    <span class="slider round"></span>
                  </label>
                </div>
                <button type="submit" class="cta-submit-btn">
                  Create a task
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Notification Toast -->
    <div class="notification-toast" id="toast-message">
      Task created successfully!
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
  </body>
</html>
