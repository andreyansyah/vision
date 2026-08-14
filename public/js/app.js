document.addEventListener('DOMContentLoaded', () => {
    // ----------------------------------------------------
    // 1. Task Creation Drawer / Bottom Sheet Modal
    // ----------------------------------------------------
    const createTaskSheet = document.getElementById('sheet-create-task');
    const createTrigger = document.getElementById('trigger-create-task');

    if (createTaskSheet && createTrigger) {
        const backdrop = createTaskSheet.querySelector('.sheet-backdrop');
        const handle = createTaskSheet.querySelector('.sheet-handle-bar');

        const openSheet = () => createTaskSheet.classList.add('show-sheet');
        const closeSheet = () => createTaskSheet.classList.remove('show-sheet');

        createTrigger.addEventListener('click', openSheet);
        if (backdrop) backdrop.addEventListener('click', closeSheet);
        if (handle) handle.addEventListener('click', closeSheet);

        // Escape key closes modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeSheet();
        });
    }

    // ----------------------------------------------------
    // 2. Category Tab highlights (index.html)
    // ----------------------------------------------------
    const categoryTabs = document.querySelectorAll('.category-tab');

    categoryTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            categoryTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            // Card filter visual feedback effect
            const cards = document.querySelectorAll('.task-card');
            cards.forEach(card => {
                card.style.opacity = '0.3';
                card.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                }, 200);
            });
        });
    });


    // ----------------------------------------------------
    // ----------------------------------------------------
    // 3. Dynamic Month/Year Calendar Engine (schedule.html)
    // ----------------------------------------------------
    const calendarWidget = document.getElementById('calendar-widget');
    const weeklyWrap = document.querySelector('.calendar-weekly-wrap');
    const monthlyWrap = document.querySelector('.calendar-monthly-wrap');
    const btnWeek = document.getElementById('btn-view-week');
    const btnMonth = document.getElementById('btn-view-month');
    const calendarHandle = document.getElementById('calendar-handle');
    const monthLabel = document.getElementById('calendar-month-label');

    // Picker Dropdown elements
    const triggerPicker = document.getElementById('trigger-month-year-picker');
    const pickerDropdown = document.getElementById('month-year-dropdown-picker');
    const btnPrevMonth = document.getElementById('btn-prev-month');
    const btnNextMonth = document.getElementById('btn-next-month');

    // Calendar state variables
    let currentYear = 2020;
    let currentMonth = 7; // August (0-indexed)
    let selectedDay = 13;

    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const monthShortNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    function showWeeklyView() {
        if (btnWeek && btnMonth && weeklyWrap && monthlyWrap) {
            btnWeek.classList.add('active');
            btnMonth.classList.remove('active');
            weeklyWrap.classList.add('active-view');
            weeklyWrap.classList.remove('hidden-view');
            monthlyWrap.classList.remove('active-view');
        }
    }

    function showMonthlyView() {
        if (btnWeek && btnMonth && weeklyWrap && monthlyWrap) {
            btnWeek.classList.remove('active');
            btnMonth.classList.add('active');
            weeklyWrap.classList.remove('active-view');
            weeklyWrap.classList.add('hidden-view');
            monthlyWrap.classList.add('active-view');
        }
    }

    // Toggle dropdown visibility
    if (triggerPicker && pickerDropdown) {
        triggerPicker.addEventListener('click', (e) => {
            e.stopPropagation();
            pickerDropdown.classList.toggle('show-dropdown');
            const arrow = triggerPicker.querySelector('.dropdown-arrow');
            if (arrow) {
                arrow.style.transform = pickerDropdown.classList.contains('show-dropdown') ? 'rotate(180deg)' : '';
            }
        });

        // Click outside dropdown to close
        document.addEventListener('click', () => {
            pickerDropdown.classList.remove('show-dropdown');
            const arrow = triggerPicker.querySelector('.dropdown-arrow');
            if (arrow) arrow.style.transform = '';
        });

        pickerDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }

    // Prev/Next Month Arrow clicks
    if (btnPrevMonth) {
        btnPrevMonth.addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            // Clamp selected day if it exceeds new month's days count
            const daysInNewMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            if (selectedDay > daysInNewMonth) selectedDay = daysInNewMonth;

            syncPickerOptions();
            renderCalendar();
            triggerTimelineAnimation();
        });
    }

    if (btnNextMonth) {
        btnNextMonth.addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            // Clamp selected day
            const daysInNewMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            if (selectedDay > daysInNewMonth) selectedDay = daysInNewMonth;

            syncPickerOptions();
            renderCalendar();
            triggerTimelineAnimation();
        });
    }

    // Sync active classes inside picker dropdown options
    function syncPickerOptions() {
        if (!pickerDropdown) return;

        // Sync Month options
        pickerDropdown.querySelectorAll('.months-grid .picker-opt').forEach(opt => {
            if (parseInt(opt.getAttribute('data-val')) === currentMonth) {
                opt.classList.add('active-opt');
            } else {
                opt.classList.remove('active-opt');
            }
        });

        // Sync Year options
        pickerDropdown.querySelectorAll('.years-grid .picker-opt').forEach(opt => {
            if (parseInt(opt.getAttribute('data-val')) === currentYear) {
                opt.classList.add('active-opt');
            } else {
                opt.classList.remove('active-opt');
            }
        });
    }

    // Bind clicks to picker options inside pickerDropdown
    if (pickerDropdown) {
        pickerDropdown.querySelectorAll('.months-grid .picker-opt').forEach(opt => {
            opt.addEventListener('click', () => {
                currentMonth = parseInt(opt.getAttribute('data-val'));
                const daysInNewMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
                if (selectedDay > daysInNewMonth) selectedDay = daysInNewMonth;

                syncPickerOptions();
                renderCalendar();
                triggerTimelineAnimation();
                pickerDropdown.classList.remove('show-dropdown');
                const arrow = triggerPicker.querySelector('.dropdown-arrow');
                if (arrow) arrow.style.transform = '';
            });
        });

        pickerDropdown.querySelectorAll('.years-grid .picker-opt').forEach(opt => {
            opt.addEventListener('click', () => {
                currentYear = parseInt(opt.getAttribute('data-val'));
                const daysInNewMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
                if (selectedDay > daysInNewMonth) selectedDay = daysInNewMonth;

                syncPickerOptions();
                renderCalendar();
                triggerTimelineAnimation();
                pickerDropdown.classList.remove('show-dropdown');
                const arrow = triggerPicker.querySelector('.dropdown-arrow');
                if (arrow) arrow.style.transform = '';
            });
        });
    }

    // Main calendar rendering engine
    function renderCalendar() {
        if (!monthLabel) return;

        // 1. Update Month Label text
        monthLabel.textContent = `${monthShortNames[currentMonth]} ${selectedDay}, ${currentYear}`;

        // 2. Render Weekly View Strip
        const weeklyContainer = document.querySelector('.calendar-strip');
        if (weeklyContainer) {
            weeklyContainer.innerHTML = '';

            // Find start of week (Monday) for selected day
            const selectedDateObj = new Date(currentYear, currentMonth, selectedDay);
            const dayOfWeek = selectedDateObj.getDay(); // 0 is Sunday, 1 is Monday
            const mondayOffset = dayOfWeek === 0 ? -6 : 1 - dayOfWeek;

            const daysOfWeekNames = ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"];

            for (let i = 0; i < 7; i++) {
                const targetDay = new Date(currentYear, currentMonth, selectedDay + mondayOffset + i);
                const isSelected = targetDay.getDate() === selectedDay && targetDay.getMonth() === currentMonth && targetDay.getFullYear() === currentYear;

                const col = document.createElement('div');
                col.className = `calendar-day-col ${isSelected ? 'active-day' : ''}`;
                col.setAttribute('data-date', targetDay.getDate());
                col.setAttribute('data-month', targetDay.getMonth());
                col.setAttribute('data-year', targetDay.getFullYear());

                col.innerHTML = `
                    <span class="day-name">${daysOfWeekNames[targetDay.getDay()]}</span>
                    <span class="day-number">${targetDay.getDate()}</span>
                    ${isSelected ? '<span class="active-dot"></span>' : ''}
                `;

                // Rebind click listener
                col.addEventListener('click', () => {
                    selectedDay = targetDay.getDate();
                    currentMonth = targetDay.getMonth();
                    currentYear = targetDay.getFullYear();

                    syncPickerOptions();
                    renderCalendar();
                    triggerTimelineAnimation();
                });

                weeklyContainer.appendChild(col);
            }
        }

        // 3. Render Monthly Grid View
        const monthlyGrid = document.querySelector('.calendar-grid');
        if (monthlyGrid) {
            monthlyGrid.innerHTML = '';

            // First day of currentMonth
            const firstDayIndex = new Date(currentYear, currentMonth, 1).getDay();
            // Total days in currentMonth
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            // Total days in previousMonth
            const daysInPrevMonth = new Date(currentYear, currentMonth, 0).getDate();

            // Render Previous Month dates placeholders
            for (let i = firstDayIndex; i > 0; i--) {
                const dayNum = daysInPrevMonth - i + 1;
                const cell = document.createElement('div');
                cell.className = 'grid-day-col prev-month';
                cell.setAttribute('data-date', dayNum);
                cell.innerHTML = `<span>${dayNum}</span>`;
                monthlyGrid.appendChild(cell);
            }

            // Render Active Month dates
            for (let i = 1; i <= daysInMonth; i++) {
                const isSelected = i === selectedDay;
                const cell = document.createElement('div');
                cell.className = `grid-day-col ${isSelected ? 'active-day' : ''}`;
                cell.setAttribute('data-date', i);

                // Simulate task dots on some days
                let dotsHtml = '';
                if (i === selectedDay) {
                    dotsHtml = `
                        <div class="grid-dots-wrap">
                            <span class="dot-indicator blue-dot"></span>
                            <span class="dot-indicator purple-dot"></span>
                        </div>
                    `;
                } else if (i % 3 === 0) {
                    dotsHtml = `
                        <div class="grid-dots-wrap">
                            <span class="dot-indicator blue-dot"></span>
                        </div>
                    `;
                } else if (i % 4 === 0) {
                    dotsHtml = `
                        <div class="grid-dots-wrap">
                            <span class="dot-indicator purple-dot"></span>
                        </div>
                    `;
                }

                cell.innerHTML = `
                    <span>${i}</span>
                    ${dotsHtml}
                `;

                // Rebind click listener
                cell.addEventListener('click', () => {
                    selectedDay = i;
                    renderCalendar();
                    triggerTimelineAnimation();

                    // Collapse monthly view after click selection
                    setTimeout(() => {
                        showWeeklyView();
                    }, 300);
                });

                monthlyGrid.appendChild(cell);
            }

            // Render Next Month dates placeholders to make 6 rows (42 cells)
            const totalCellsRendered = firstDayIndex + daysInMonth;
            const remainingCells = 42 - totalCellsRendered;
            for (let i = 1; i <= remainingCells; i++) {
                const cell = document.createElement('div');
                cell.className = 'grid-day-col next-month';
                cell.setAttribute('data-date', i);
                cell.innerHTML = `<span>${i}</span>`;
                monthlyGrid.appendChild(cell);
            }
        }
    }

    // Initialize calendar renderer on page load if widget is present
    if (calendarWidget) {
        syncPickerOptions();
        renderCalendar();
    }

    // Mode Toggle Clicks
    if (btnWeek) btnWeek.addEventListener('click', showWeeklyView);
    if (btnMonth) btnMonth.addEventListener('click', showMonthlyView);

    // Tap Handle to Toggle View
    if (calendarHandle) {
        calendarHandle.addEventListener('click', () => {
            if (monthlyWrap && monthlyWrap.classList.contains('active-view')) {
                showWeeklyView();
            } else {
                showMonthlyView();
            }
        });
    }

    // Swipe/Scroll Gestures on Calendar Widget
    if (calendarWidget) {
        let startY = 0;
        let startX = 0;

        calendarWidget.addEventListener('touchstart', (e) => {
            startY = e.touches[0].clientY;
            startX = e.touches[0].clientX;
        }, { passive: true });

        calendarWidget.addEventListener('touchend', (e) => {
            const endY = e.changedTouches[0].clientY;
            const endX = e.changedTouches[0].clientX;
            const diffY = endY - startY;
            const diffX = endX - startX;

            // Trigger swipe if vertical movement is dominant and meets threshold
            if (Math.abs(diffY) > Math.abs(diffX) && Math.abs(diffY) > 35) {
                if (diffY > 0) {
                    showMonthlyView(); // Swipe Down
                } else {
                    showWeeklyView();  // Swipe Up
                }
            }
        }, { passive: true });
    }

    function triggerTimelineAnimation() {
        const timelineList = document.querySelector('.timeline-list');
        if (timelineList) {
            timelineList.style.transform = 'translateY(15px)';
            timelineList.style.opacity = '0.7';
            setTimeout(() => {
                timelineList.style.transform = 'translateY(0)';
                timelineList.style.opacity = '1';
            }, 250);
        }
    }


    // ----------------------------------------------------
    // 5. Modal Form Date picker selection row
    // ----------------------------------------------------
    const modalDates = document.querySelectorAll('.modal-date-col');

    modalDates.forEach(dateCol => {
        dateCol.addEventListener('click', () => {
            modalDates.forEach(d => {
                d.classList.remove('active-m-day');
                const dot = d.querySelector('.active-dot');
                if (dot) dot.remove();
            });

            dateCol.classList.add('active-m-day');
            const dot = document.createElement('span');
            dot.className = 'active-dot';
            dateCol.appendChild(dot);

            const selectedDay = dateCol.querySelector('.m-day-number').textContent;
            const cdateInput = document.getElementById('t-cdate');
            if (cdateInput) {
                cdateInput.value = `${selectedDay} August 2020`;
            }
        });
    });


    // ----------------------------------------------------
    // 6. Form Submissions Insertion
    // ----------------------------------------------------
    const taskForm = document.getElementById('task-creation-form');
    const toast = document.getElementById('toast-message');
    const dashboardTaskList = document.getElementById('dashboard-task-list');
    const timelineList = document.querySelector('.timeline-list');

    function showToast(message) {
        if (toast) {
            toast.textContent = message;
            toast.classList.add('show-toast');
            setTimeout(() => {
                toast.classList.remove('show-toast');
            }, 3000);
        }
    }

    if (taskForm) {
        taskForm.addEventListener('submit', (e) => {
            if (taskForm.getAttribute('action')) {
                return;
            }
            e.preventDefault();

            const titleInput = document.getElementById('t-title');
            const dateInput = document.getElementById('t-cdate');
            const alertInput = document.getElementById('t-alert');

            const title = titleInput.value.trim();
            const dateStr = dateInput.value.trim();
            const getAlert = alertInput ? alertInput.checked : false;

            if (!title) return;

            // If on index.html (Dashboard task list exists)
            if (dashboardTaskList) {
                const newCard = document.createElement('div');
                newCard.className = 'list-item-card';
                newCard.style.animation = 'fadeIn 0.5s ease-out';
                newCard.innerHTML = `
                    <div class="list-item-icon-bg bg-soft-indigo">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="9" y1="9" x2="15" y2="9"/>
                            <line x1="9" y1="13" x2="15" y2="13"/>
                            <line x1="9" y1="17" x2="13" y2="17"/>
                        </svg>
                    </div>
                    <div class="list-item-details">
                        <h6>${title}</h6>
                        <p>${dateStr}</p>
                    </div>
                    <button class="list-item-action-btn" aria-label="Task Actions">
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="currentColor">
                            <circle cx="12" cy="5" r="1.5"/>
                            <circle cx="12" cy="12" r="1.5"/>
                            <circle cx="12" cy="19" r="1.5"/>
                        </svg>
                    </button>
                `;
                dashboardTaskList.insertBefore(newCard, dashboardTaskList.firstChild);
            }

            // If on schedule.html (Timeline list exists)
            if (timelineList) {
                const newTimelineItem = document.createElement('div');
                newTimelineItem.className = 'timeline-item';
                newTimelineItem.style.animation = 'fadeIn 0.5s ease-out';

                const times = ['14:00', '15:30', '16:00', '17:00'];
                const selectedTime = times[Math.floor(Math.random() * times.length)];

                newTimelineItem.innerHTML = `
                    <div class="timeline-time"><time>${selectedTime}</time></div>
                    <div class="timeline-node"><span class="node-bullet"></span></div>
                    <div class="timeline-content-card plain-white-card">
                        <div class="timeline-card-header">
                            <h4>${title}</h4>
                            <span class="timeline-card-time">${selectedTime}</span>
                        </div>
                        <p class="timeline-card-desc-grey">Task created for ${dateStr}. Alert is ${getAlert ? 'enabled' : 'disabled'}.</p>
                    </div>
                `;
                timelineList.appendChild(newTimelineItem);
            }

            // If on project.html (Projects vertical list exists)
            const projectsList = document.getElementById('projects-list');
            if (projectsList) {
                const newCard = document.createElement('div');

                // Pick a random style variation
                const variations = [
                    { img: 'project-logos/xyora.svg', class: 'project-xyora' },
                    { img: 'project-logos/computask.png', class: 'project-computask' },
                    { img: 'project-logos/mkt-central-directory.png', class: 'project-ctigroup' }
                ];
                const selected = variations[Math.floor(Math.random() * variations.length)];

                newCard.className = `project-card-custom ${selected.class}`;
                newCard.style.animation = 'fadeIn 0.5s ease-out';
                newCard.setAttribute('onclick', `window.location.href='project-detail.html?project=${encodeURIComponent(title)}'`);

                newCard.innerHTML = `
                    <div class="project-logo-container">
                      <img src="${selected.img}" alt="${title}" class="project-logo-img" />
                    </div>
                    <div class="project-detail-container">
                      <h4 class="project-name">${title}</h4>
                      <span class="project-status-dot"></span>
                    </div>
                `;
                projectsList.insertBefore(newCard, projectsList.firstChild);
            }

            // If on notes.html (Notes vertical list exists)
            const notesList = document.getElementById('notes-list');
            if (notesList) {
                const swipeWrapper = document.createElement('div');
                swipeWrapper.className = 'note-swipe-wrapper';
                swipeWrapper.style.animation = 'fadeIn 0.5s ease-out';

                const descInput = document.getElementById('t-desc');
                let descVal = 'New note created. Keep jotting down your creative thoughts!';
                if (descInput) {
                    let text = '';
                    if (typeof $ !== 'undefined' && $.fn.summernote) {
                        const rawHtml = $('#t-desc').summernote('code');
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = rawHtml;
                        text = tempDiv.textContent || tempDiv.innerText || '';
                    } else {
                        text = descInput.value.trim();
                    }
                    if (text) descVal = text;
                }

                swipeWrapper.innerHTML = `
                    <div class="note-delete-action">
                      <i class="fa-solid fa-trash-can"></i>
                    </div>
                    <div class="note-card-custom">
                      <div class="note-card-header">
                        <h4 class="note-title">${title}</h4>
                      </div>
                      <p class="note-snippet">${descVal}</p>
                      <span class="note-date">${dateStr}</span>
                    </div>
                `;
                notesList.insertBefore(swipeWrapper, notesList.firstChild);

                // Bind swipe event to newly created element
                bindSwipeEvent(swipeWrapper);

                if (descInput) {
                    if (typeof $ !== 'undefined' && $.fn.summernote) {
                        $('#t-desc').summernote('code', '');
                    } else {
                        descInput.value = '';
                    }
                }
            }

            // Reset inputs and close sheet
            titleInput.value = '';
            createTaskSheet.classList.remove('show-sheet');

            // Show dynamic Toast
            let successMsg = `Task "${title}" created successfully!`;
            if (projectsList) successMsg = `Project "${title}" created successfully!`;
            if (notesList) successMsg = `Note "${title}" created successfully!`;
            showToast(successMsg);
        });
    }

    // ----------------------------------------------------
    // Swipe-to-Delete for Note Cards
    // ----------------------------------------------------
    function bindSwipeEvent(wrapper) {
        const card = wrapper.querySelector('.note-card-custom');
        const deleteBtn = wrapper.querySelector('.note-delete-action');
        if (!card || !deleteBtn) return;

        let startX = 0;
        let currentX = 0;
        let isDragging = false;
        let isSwiped = false;

        // Touch events
        card.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
            card.style.transition = 'none';
        }, { passive: true });

        card.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            const x = e.touches[0].clientX;
            let diff = x - startX;
            if (isSwiped) diff -= 80;

            if (diff > 0) diff = 0;
            if (diff < -120) diff = -120;

            currentX = diff;
            card.style.transform = `translateX(${diff}px)`;
        }, { passive: true });

        card.addEventListener('touchend', () => {
            isDragging = false;
            card.style.transition = 'transform 0.3s cubic-bezier(0.25, 1, 0.5, 1)';

            if (currentX < -40) {
                card.style.transform = 'translateX(-80px)';
                isSwiped = true;
            } else {
                card.style.transform = 'translateX(0px)';
                isSwiped = false;
            }
        });

        // Mouse events for desktop browsers
        card.addEventListener('mousedown', (e) => {
            startX = e.clientX;
            isDragging = true;
            card.style.transition = 'none';
        });

        const handleMouseMove = (e) => {
            if (!isDragging) return;
            const x = e.clientX;
            let diff = x - startX;
            if (isSwiped) diff -= 80;

            if (diff > 0) diff = 0;
            if (diff < -120) diff = -120;

            currentX = diff;
            card.style.transform = `translateX(${diff}px)`;
        };

        const handleMouseUp = () => {
            if (!isDragging) return;
            isDragging = false;
            card.style.transition = 'transform 0.3s cubic-bezier(0.25, 1, 0.5, 1)';

            if (currentX < -40) {
                card.style.transform = 'translateX(-80px)';
                isSwiped = true;
            } else {
                card.style.transform = 'translateX(0px)';
                isSwiped = false;
            }
        };

        window.addEventListener('mousemove', handleMouseMove);
        window.addEventListener('mouseup', handleMouseUp);

        // Unbind window event listeners when card is removed from DOM to prevent memory leaks
        const observer = new MutationObserver((mutations, obs) => {
            if (!document.body.contains(wrapper)) {
                window.removeEventListener('mousemove', handleMouseMove);
                window.removeEventListener('mouseup', handleMouseUp);
                obs.disconnect();
            }
        });
        observer.observe(document.body, { childList: true, subtree: true });

        // Handle deletion on trash icon click
        deleteBtn.addEventListener('click', () => {
            wrapper.style.transition = 'max-height 0.3s ease, margin-bottom 0.3s ease, opacity 0.3s ease';
            wrapper.style.maxHeight = '0';
            wrapper.style.marginBottom = '0';
            wrapper.style.opacity = '0';
            setTimeout(() => {
                wrapper.remove();
            }, 300);
        });
    }

    function initSwipeToDelete() {
        const wrappers = document.querySelectorAll('.note-swipe-wrapper');
        wrappers.forEach(wrapper => {
            bindSwipeEvent(wrapper);
        });
    }

    // Call on load
    initSwipeToDelete();

    // Initialize Summernote Lite if textarea exists and jQuery/summernote is loaded
    if (typeof $ !== 'undefined' && $.fn.summernote && document.getElementById('t-desc')) {
        $('#t-desc').summernote({
            placeholder: 'Write your thoughts here...',
            tabsize: 2,
            height: 260,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });
    }

    // ----------------------------------------------------
    // Gemini Chat Assistant Simulation (project-detail.html)
    // ----------------------------------------------------
    const deviceGemini = document.getElementById('device-gemini');
    if (deviceGemini) {
        const chatFlow = document.getElementById('gemini-chat-flow');
        const introView = document.getElementById('gemini-intro');
        const messagesContainer = document.getElementById('chat-messages-container');
        const userInput = document.getElementById('gemini-user-input');
        const sendBtn = document.getElementById('gemini-send-btn');
        const micBtn = document.getElementById('gemini-mic-btn');
        const promptTitle = document.getElementById('gemini-prompt-title');

        // Dynamic Dropdown Elements
        const dropdownTrigger = document.getElementById('project-dropdown-trigger');
        const hamburgerTrigger = document.getElementById('gemini-hamburger-trigger');
        const dropdownMenu = document.getElementById('gemini-projects-dropdown');
        const titleLabel = document.getElementById('gemini-project-title-label');

        // Get URL parameters to customize bot context
        const urlParams = new URLSearchParams(window.location.search);
        let projectName = urlParams.get('project') || 'Xyora';

        // Initialize active state and title
        const projectLogos = {
            'Xyora': 'project-logos/xyora.svg',
            'Computask': 'project-logos/computask.png',
            'MKT Central Directory': 'project-logos/mkt-central-directory.png'
        };

        function updateActiveProject(name) {
            projectName = name;

            // Update title label
            if (titleLabel) {
                titleLabel.textContent = projectName;
            }

            // Update central prompt
            if (promptTitle) {
                promptTitle.innerHTML = `Mau automasi tugas apa di <strong>${projectName}</strong>?`;
            }

            // Update sidebar logo
            const sidebarLogo = document.getElementById('gemini-sidebar-project-logo');
            if (sidebarLogo) {
                sidebarLogo.src = projectLogos[projectName] || 'project-logos/xyora.svg';
                sidebarLogo.alt = projectName;
            }

            // Update dropdown items active class
            const items = document.querySelectorAll('.gemini-menu-item');
            items.forEach(item => {
                if (item.getAttribute('data-project') === projectName) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });

            // Sync URL query param without reload
            const newUrl = `${window.location.pathname}?project=${encodeURIComponent(projectName)}`;
            window.history.pushState({ path: newUrl }, '', newUrl);
        }

        // Run init
        updateActiveProject(projectName);

        // Dropdown toggle logic
        const toggleDropdown = (e) => {
            e.stopPropagation();
            if (dropdownMenu) {
                const isOpen = dropdownMenu.style.display === 'block';
                if (isOpen) {
                    dropdownMenu.style.display = 'none';
                    dropdownTrigger.classList.remove('open');
                } else {
                    dropdownMenu.style.display = 'block';
                    dropdownTrigger.classList.add('open');
                }
            }
        };

        if (dropdownTrigger) {
            dropdownTrigger.addEventListener('click', toggleDropdown);
        }

        // Sidebar Navigation Drawer logic
        const sidebar = document.getElementById('gemini-sidebar');
        const sidebarBackdrop = document.getElementById('gemini-sidebar-backdrop');
        const sidebarCloseBtn = document.getElementById('gemini-sidebar-close-btn');
        const newChatBtn = document.getElementById('gemini-new-chat-btn');

        // Sidebar Chat Search Logic
        const searchInput = document.getElementById('gemini-sidebar-search-input');
        const recentListContainer = document.querySelector('.gemini-sidebar-recent-list');
        
        if (searchInput && recentListContainer) {
            const initialRecentHtml = recentListContainer.innerHTML;
            const activeSessionId = urlParams.get('session_id');
            
            searchInput.addEventListener('input', () => {
                const query = searchInput.value.trim();
                
                if (query.length === 0) {
                    recentListContainer.innerHTML = initialRecentHtml;
                    return;
                }
                
                // Ambil active project ID dari hidden input
                const projectIdInput = document.querySelector('input[name="project_id"]');
                if (!projectIdInput) return;
                const projectId = projectIdInput.value;
                
                // Kirim request pencarian ke server
                fetch(`/chat/sessions/search?project_id=${projectId}&query=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            recentListContainer.innerHTML = '';
                            
                            if (data.sessions.length === 0) {
                                recentListContainer.innerHTML = `
                                    <div style="font-size: 11px; color: #9CA3AF; text-align: center; padding: 16px 0;">
                                        Tidak ada hasil
                                    </div>
                                `;
                                return;
                            }
                            
                            data.sessions.forEach(s => {
                                const isActive = activeSessionId && activeSessionId == s.id;
                                const item = document.createElement('div');
                                item.className = `gemini-sidebar-recent-item ${isActive ? 'active' : ''}`;
                                item.setAttribute('onclick', `window.location.href='/project-detail?project=${encodeURIComponent(projectName)}&session_id=${s.id}'`);
                                
                                item.innerHTML = `
                                    <span class="recent-text">${s.title}</span>
                                    <i class="fa-solid fa-clock option-icon" style="font-size: 11px; opacity: 0.5;"></i>
                                `;
                                recentListContainer.appendChild(item);
                            });
                        }
                    })
                    .catch(err => console.error("Error searching sessions:", err));
            });
        }

        const openSidebar = (e) => {
            e.stopPropagation();
            if (sidebar && sidebarBackdrop) {
                sidebar.classList.add('open');
                sidebarBackdrop.classList.add('open');
            }
        };

        const closeSidebar = () => {
            if (sidebar && sidebarBackdrop) {
                sidebar.classList.remove('open');
                sidebarBackdrop.classList.remove('open');
            }
        };

        if (hamburgerTrigger) {
            hamburgerTrigger.addEventListener('click', openSidebar);
        }
        if (sidebarCloseBtn) {
            sidebarCloseBtn.addEventListener('click', closeSidebar);
        }
        if (sidebarBackdrop) {
            sidebarBackdrop.addEventListener('click', closeSidebar);
        }
        if (newChatBtn) {
            newChatBtn.addEventListener('click', () => {
                closeSidebar();

                // Reset chat flow
                messagesContainer.innerHTML = '';
                if (introView) {
                    introView.style.display = 'flex';
                    introView.style.opacity = '1';
                }
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', () => {
            if (dropdownMenu) {
                dropdownMenu.style.display = 'none';
                if (dropdownTrigger) dropdownTrigger.classList.remove('open');
            }
        });

        // Dropdown item selection logic
        const menuItems = document.querySelectorAll('.gemini-menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.stopPropagation();
                const selectedProject = item.getAttribute('data-project');

                if (dropdownMenu) dropdownMenu.style.display = 'none';
                if (dropdownTrigger) dropdownTrigger.classList.remove('open');

                if (selectedProject !== projectName) {
                    // Update project context
                    updateActiveProject(selectedProject);

                    // Reset chat messages
                    messagesContainer.innerHTML = '';

                    // Restore and fade in intro view
                    if (introView) {
                        introView.style.display = 'flex';
                        introView.style.opacity = '1';
                    }
                }
            });
        });

        // Toggle send button visibility based on input length
        userInput.addEventListener('input', () => {
            if (userInput.value.trim().length > 0) {
                sendBtn.style.display = 'flex';
                micBtn.style.display = 'none';
            } else {
                sendBtn.style.display = 'none';
                micBtn.style.display = 'flex';
            }
        });

        // Speech to Text (Speech Recognition) Implementation
        let recognition = null;
        let isListening = false;

        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (SpeechRecognition) {
            recognition = new SpeechRecognition();
            recognition.continuous = false; // Berhenti merekam otomatis jika user selesai bicara
            recognition.interimResults = false; // Hanya ambil hasil final
            recognition.lang = 'id-ID'; // Gunakan Bahasa Indonesia

            const micIcon = micBtn.querySelector('i') || micBtn;

            recognition.onstart = () => {
                isListening = true;
                micBtn.style.color = '#EF4444'; // Ubah warna jadi merah
                micBtn.classList.add('mic-listening-pulse'); // Tambah efek denyut scale
                micIcon.className = 'fa-solid fa-microphone-lines'; // Icon soundwaves
                userInput.placeholder = 'Mendengarkan suara Anda...';
            };

            recognition.onend = () => {
                isListening = false;
                micBtn.style.color = ''; // Reset warna
                micBtn.classList.remove('mic-listening-pulse'); // Hapus efek denyut scale
                micIcon.className = 'fa-solid fa-microphone'; // Reset icon
                userInput.placeholder = 'Minta Vision Assistant';

                // Tampilkan tombol kirim jika ada teks
                if (userInput.value.trim().length > 0) {
                    sendBtn.style.display = 'flex';
                    micBtn.style.display = 'none';
                }
            };

            recognition.onresult = (event) => {
                const resultText = event.results[0][0].transcript;
                if (userInput.value.trim().length > 0) {
                    userInput.value += ' ' + resultText;
                } else {
                    userInput.value = resultText;
                }
            };

            recognition.onerror = (event) => {
                console.error("Speech recognition error:", event.error);
                if (event.error === 'not-allowed') {
                    showToast("Akses mikrofon ditolak. Izinkan mikrofon di browser Anda.");
                } else if (event.error === 'no-speech') {
                    // Do nothing or quiet timeout
                } else {
                    showToast("Gagal mendengarkan: " + event.error);
                }
            };

            micBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (isListening) {
                    recognition.stop();
                } else {
                    try {
                        recognition.start();
                    } catch (err) {
                        console.error("Speech recognition start failed:", err);
                    }
                }
            });
        } else {
            micBtn.addEventListener('click', (e) => {
                e.preventDefault();
                showToast("Browser Anda tidak mendukung fitur Speech to Text.");
            });
        }

        // Image Upload & Preview Implementation
        const plusBtn = document.getElementById('gemini-plus-btn');
        const imageInput = document.getElementById('gemini-image-input');
        const previewContainer = document.getElementById('gemini-image-preview-container');
        const previewImg = document.getElementById('gemini-image-preview');
        const filenameLabel = document.getElementById('gemini-image-filename');
        const removeImageBtn = document.getElementById('gemini-remove-image-btn');

        if (plusBtn && imageInput) {
            plusBtn.addEventListener('click', (e) => {
                e.preventDefault();
                imageInput.click();
            });
        }

        if (imageInput) {
            imageInput.addEventListener('change', () => {
                const file = imageInput.files[0];
                if (file) {
                    previewImg.src = URL.createObjectURL(file);
                    filenameLabel.textContent = file.name;
                    previewContainer.style.display = 'flex';
                    
                    // Tampilkan tombol kirim, sembunyikan mic
                    sendBtn.style.display = 'flex';
                    micBtn.style.display = 'none';
                } else {
                    previewContainer.style.display = 'none';
                }
            });
        }

        if (removeImageBtn && imageInput) {
            removeImageBtn.addEventListener('click', (e) => {
                e.preventDefault();
                imageInput.value = '';
                previewContainer.style.display = 'none';
                
                // Kembalikan tampilan mic/send sesuai dengan teks input
                if (userInput.value.trim().length === 0) {
                    sendBtn.style.display = 'none';
                    micBtn.style.display = 'flex';
                }
            });
        }


        // Helper to escape HTML safely
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function (m) { return map[m]; });
        }

        // Helper to parse Markdown into HTML
        function parseMarkdownJS(text) {
            // Strip outer code block from Output if it wraps the output
            text = text.replace(/(\*\*Output:\*\*\s*\n)```\s*\n([\s\S]*?)\n```/g, '$1$2');

            let html = escapeHtml(text);

            // 1. Extract block code: ```code```
            let codeBlocks = [];
            const placeholderPrefix = '___CODEBLOCKPLACEHOLDER___';

            html = html.replace(/```([\s\S]*?)```/g, function (match, code) {
                const index = codeBlocks.length;
                codeBlocks.push(code);
                return placeholderPrefix + index + '___';
            });

            // 2. Parse other inline markdown elements
            html = html.replace(/\*\*(.*?)\*\*/g, '<strong style="font-weight: 600; color: #1f2937;">$1</strong>');
            html = html.replace(/`(.*?)`/g, '<code style="background: rgba(108, 93, 211, 0.08); padding: 3px 6px; border-radius: 6px; font-family: \'Fira Code\', \'Consolas\', Monaco, monospace; font-size: 0.85em; color: #6c5dd3; font-weight: 500; border: 1px solid rgba(108, 93, 211, 0.05);">$1</code>');
            html = html.replace(/### (.*?)\n/g, '<h5 style="margin: 16px 0 8px 0; font-weight: 600; color: #1f2937; font-family: \'Poppins\', sans-serif; font-size: 14px;">$1</h5>');

            // 3. Process line by line
            let lines = html.split('\n');
            let resultLines = [];
            for (let i = 0; i < lines.length; i++) {
                let line = lines[i];
                let trimmed = line.trim();

                if (trimmed.indexOf(placeholderPrefix) !== -1) {
                    line = line.replace(new RegExp(placeholderPrefix + '(\\d+)___', 'g'), function (match, indexStr) {
                        const index = parseInt(indexStr);
                        return '<pre style="background: rgba(108, 93, 211, 0.03); padding: 14px; border-radius: 12px; font-family: \'Fira Code\', \'Consolas\', Monaco, monospace; font-size: 0.85em; overflow-x: auto; border: 1px solid rgba(108, 93, 211, 0.15); color: #374151; margin: 12px 0; max-width: 100%; line-height: 1.5; box-shadow: inset 0 1px 3px rgba(108, 93, 211, 0.02); white-space: pre-wrap; word-break: break-all; box-sizing: border-box;"><code>' + codeBlocks[index] + '</code></pre>';
                    });
                    resultLines.push(line);
                    continue;
                }

                if (trimmed === '') {
                    resultLines.push('<div style="height: 6px;"></div>');
                } else if (trimmed === '---') {
                    resultLines.push('<hr style="border: 0; border-top: 1px solid rgba(108, 93, 211, 0.15); margin: 12px 0;">');
                } else if (trimmed.startsWith('<div') || trimmed.startsWith('<h5')) {
                    resultLines.push(line);
                } else if (trimmed.startsWith('* ') || trimmed.startsWith('- ')) {
                    resultLines.push('<div style="display: flex; gap: 8px; margin-top: 3px; align-items: flex-start; line-height: 1.4;"><span style="color: #6c5dd3; font-size: 14px;">•</span><span>' + trimmed.substring(2) + '</span></div>');
                } else {
                    resultLines.push('<div style="line-height: 1.4; margin-bottom: 2px;">' + lines[i] + '</div>');
                }
            }

            return resultLines.join('');
        }

        let pollingInterval = null;

        function startPollingForReplies(sessionId) {
            if (pollingInterval) clearInterval(pollingInterval);

            const poll = () => {
                fetch(`/chat/sessions/${sessionId}/messages`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            let hasNewMessage = false;
                            let hasPending = false;

                            data.messages.forEach(msg => {
                                // Tandai jika ada pesan user yang masih menunggu jawaban dari PC (pending atau processing)
                                if (msg.sender === 'user' && (msg.status_reply === 'pending' || msg.status_reply === 'processing')) {
                                    hasPending = true;
                                }

                                // 1. Cari elemen pesan di DOM
                                let existingBubble = messagesContainer.querySelector(`[data-msg-id="${msg.id}"]`);

                                if (!existingBubble) {
                                    hasNewMessage = true;
                                    // Render pesan baru
                                    if (msg.sender === 'user') {
                                        appendUserMessage(msg.message, msg.id, msg.status_send, msg.status_reply, msg.image_path);
                                    } else {
                                        appendBotMessage(msg.message, msg.id);
                                    }
                                } else {
                                    // 2. Jika pesan sudah ada, update status pengirimannya (terutama untuk pesan user)
                                    if (msg.sender === 'user') {
                                        const statusContainer = existingBubble.querySelector('.chat-delivery-status');
                                        if (statusContainer) {
                                            if (msg.status_reply === 'replied') {
                                                statusContainer.innerHTML = `<i class="fa-solid fa-circle-check" style="color: #34D399;"></i> Completed`;
                                            } else if (msg.status_send === 'sent') {
                                                statusContainer.innerHTML = `<i class="fa-solid fa-check" style="color: #60A5FA;"></i> Sent to PC`;
                                            }
                                        }
                                    }
                                }
                            });

                            if (hasNewMessage) {
                                scrollToBottom();
                            }

                            // Jika tidak ada pesan pending, matikan polling untuk menghemat resource
                            if (!hasPending && pollingInterval) {
                                clearInterval(pollingInterval);
                                pollingInterval = null;
                            }
                        }
                    })
                    .catch(err => {
                        console.error("Error polling messages:", err);
                        if (pollingInterval) {
                            clearInterval(pollingInterval);
                            pollingInterval = null;
                        }
                    });
            };

            pollingInterval = setInterval(poll, 1500);
            poll(); // Jalankan sekali langsung di awal
        }

        // Jalankan polling otomatis jika halaman dibuka dengan session_id aktif
        const activeSessionId = urlParams.get('session_id');
        if (activeSessionId) {
            startPollingForReplies(activeSessionId);
        }

        // Handle AJAX Message Submission
        const handleSendMessage = () => {
            const query = userInput.value.trim();
            const imageInput = document.getElementById('gemini-image-input');
            const previewContainer = document.getElementById('gemini-image-preview-container');
            const imageFile = imageInput && imageInput.files ? imageInput.files[0] : null;

            if (!query && !imageFile) return;

            // 1. Buat FormData sebelum input di-clear agar file gambar tetap terkirim!
            const form = userInput.closest('form');
            const formData = new FormData(form);
            formData.set('message', query || '[Kirim Gambar]');

            // 2. Generate local preview URL jika ada gambar
            let previewUrl = null;
            if (imageFile) {
                previewUrl = URL.createObjectURL(imageFile);
            }

            // 3. Clear inputs & previews di UI
            userInput.value = '';
            if (imageInput) imageInput.value = '';
            if (previewContainer) previewContainer.style.display = 'none';
            sendBtn.style.display = 'none';
            micBtn.style.display = 'flex';

            // Fade out intro if visible
            if (introView && introView.style.display !== 'none') {
                $(introView).fadeOut(300, () => {
                    introView.style.display = 'none';
                });
            }

            // Generate temp ID for visual feedback
            const tempId = 'temp-' + Date.now();
            appendUserMessage(query || '[Gambar]', tempId, 'pending', 'pending', previewUrl);
            scrollToBottom();

            fetch(form.getAttribute('action'), {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Update the temp bubble with the real message ID and Sent to PC status
                        const tempBubble = messagesContainer.querySelector(`[data-msg-id="${tempId}"]`);
                        if (tempBubble) {
                            tempBubble.setAttribute('data-msg-id', data.message_id);
                            const statusContainer = tempBubble.querySelector('.chat-delivery-status');
                            if (statusContainer) {
                                if (data.status_send === 'sent') {
                                    statusContainer.innerHTML = `<i class="fa-solid fa-check" style="color: #60A5FA;"></i> Sent to PC`;
                                }
                            }
                        }

                        // Set/update the session ID hidden input
                        let sessionInput = form.querySelector('input[name="chat_session_id"]');
                        if (!sessionInput) {
                            sessionInput = document.createElement('input');
                            sessionInput.type = 'hidden';
                            sessionInput.name = 'chat_session_id';
                            form.appendChild(sessionInput);
                        }
                        sessionInput.value = data.session_id;

                        // Sync URL parameter without reload
                        const urlParams = new URLSearchParams(window.location.search);
                        if (urlParams.get('session_id') != data.session_id) {
                            urlParams.set('session_id', data.session_id);
                            const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
                            window.history.pushState({ path: newUrl }, '', newUrl);
                        }

                        // Start polling
                        startPollingForReplies(data.session_id);
                    }
                })
                .catch(err => {
                    console.error("Error sending message via AJAX:", err);
                    const tempBubble = messagesContainer.querySelector(`[data-msg-id="${tempId}"]`);
                    if (tempBubble) {
                        const statusContainer = tempBubble.querySelector('.chat-delivery-status');
                        if (statusContainer) {
                            statusContainer.innerHTML = `<i class="fa-solid fa-circle-exclamation" style="color: var(--status-critical);"></i> Gagal Terkirim`;
                        }
                    }
                });
        };

        // Intercept form submit
        const chatForm = userInput.closest('form');
        if (chatForm) {
            chatForm.addEventListener('submit', (e) => {
                e.preventDefault();
                handleSendMessage();
            });
        }

        sendBtn.addEventListener('click', (e) => {
            e.preventDefault();
            handleSendMessage();
        });

        userInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                handleSendMessage();
            }
        });

        function appendUserMessage(text, id, status_send, status_reply, image_path) {
            const bubble = document.createElement('div');
            bubble.className = 'gemini-message user';
            bubble.setAttribute('data-msg-id', id);

            let statusHtml = '';
            if (status_reply === 'replied') {
                statusHtml = `<i class="fa-solid fa-circle-check" style="color: #34D399;"></i> Completed`;
            } else if (status_send === 'sent') {
                statusHtml = `<i class="fa-solid fa-check" style="color: #60A5FA;"></i> Sent to PC`;
            } else {
                statusHtml = `<i class="fa-solid fa-clock"></i> Pending Send`;
            }

            let imageHtml = '';
            if (image_path) {
                const src = image_path.startsWith('http') || image_path.startsWith('blob:') ? image_path : '/' + image_path;
                imageHtml = `
                    <div style="margin-bottom: 8px;">
                        <img src="${src}" alt="Uploaded Image" style="max-width: 100%; max-height: 200px; border-radius: 8px; display: block; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.05);" />
                    </div>
                `;
            }

            bubble.innerHTML = `
                ${imageHtml}
                <div>${escapeHtml(text)}</div>
                <div class="chat-delivery-status" style="font-size: 9px; opacity: 0.6; margin-top: 5px; text-align: right; display: flex; align-items: center; justify-content: flex-end; gap: 4px; font-weight: 500;">
                    ${statusHtml}
                </div>
            `;
            messagesContainer.appendChild(bubble);
            messagesContainer.style.display = 'flex';
            messagesContainer.style.opacity = '1';
        }

        function appendBotMessage(text, id) {
            const botContainer = document.createElement('div');
            botContainer.className = 'gemini-message bot';
            botContainer.setAttribute('data-msg-id', id);

            botContainer.innerHTML = `
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
                  ${parseMarkdownJS(text)}
                </div>
            `;
            messagesContainer.appendChild(botContainer);
            messagesContainer.style.display = 'flex';
            messagesContainer.style.opacity = '1';
        }

        function scrollToBottom() {
            chatFlow.scrollTop = chatFlow.scrollHeight;
        }
    }
});
