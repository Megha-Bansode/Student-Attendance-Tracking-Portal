/**
 * AttendEase - Faculty Portal Interactive JavaScript
 * Modular & Scoped: Runs only when .faculty-portal elements exist.
 */

document.addEventListener('DOMContentLoaded', function() {
    const facultyPortal = document.querySelector('.faculty-portal');
    if (!facultyPortal) return;

    // 1. Mobile Sidebar Toggle & Offcanvas Overlay
    initMobileSidebar();

    // 2. Attendance Marking Sheet Logic & Live Counters
    initAttendanceMarkingSheet();

    // 3. Subject Allocation Search & Filter
    initSubjectAllocationFilter();

    // 4. Edit Attendance Search & Record Filter
    initEditAttendanceFilter();
});

/* ==========================================================================
   1. Mobile Sidebar Toggle Logic
   ========================================================================== */
function initMobileSidebar() {
    const toggler = document.getElementById('facultySidebarToggler');
    const sidebar = document.getElementById('facultySidebar');

    if (!toggler || !sidebar) return;

    // Create backdrop overlay element if not exists
    let overlay = document.querySelector('.faculty-sidebar-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'faculty-sidebar-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            z-index: 1035;
            display: none;
            transition: opacity 0.3s ease;
        `;
        document.body.appendChild(overlay);
    }

    function openSidebar() {
        sidebar.classList.add('show');
        overlay.style.display = 'block';
        setTimeout(() => overlay.style.opacity = '1', 10);
    }

    function closeSidebar() {
        sidebar.classList.remove('show');
        overlay.style.opacity = '0';
        setTimeout(() => overlay.style.display = 'none', 300);
    }

    // Check local storage for desktop collapse state on load
    if (window.innerWidth >= 992 && localStorage.getItem('facultySidebarCollapsed') === 'true') {
        sidebar.classList.add('collapsed');
        const mainContent = document.querySelector('.faculty-main-content');
        if (mainContent) mainContent.classList.add('expanded');
        document.body.classList.add('sidebar-collapsed');
    }
    
    // Remove preload class to enable transitions again
    setTimeout(() => {
        document.documentElement.classList.remove('preload-collapsed');
    }, 50);

    toggler.addEventListener('click', function(e) {
        e.stopPropagation();
        if (window.innerWidth >= 992) {
            // Desktop toggle with persistence
            sidebar.classList.toggle('collapsed');
            const mainContent = document.querySelector('.faculty-main-content');
            if (mainContent) mainContent.classList.toggle('expanded');
            document.body.classList.toggle('sidebar-collapsed');
            
            // Save state
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('facultySidebarCollapsed', isCollapsed ? 'true' : 'false');
        } else {
            // Mobile offcanvas toggle
            if (sidebar.classList.contains('show')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        }
    });

    overlay.addEventListener('click', closeSidebar);

    // Close sidebar on esc key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('show')) {
            closeSidebar();
        }
    });
}

/* ==========================================================================
   2. Attendance Marking Sheet & Live Statistics
   ========================================================================== */
function initAttendanceMarkingSheet() {
    const attendanceForm = document.getElementById('markAttendanceForm');
    const studentTable = document.getElementById('studentAttendanceTable');
    if (!studentTable) return;

    const btnMarkAllPresent = document.getElementById('btnMarkAllPresent');
    const btnMarkAllAbsent = document.getElementById('btnMarkAllAbsent');
    const btnResetAttendance = document.getElementById('btnResetAttendance');

    const totalCountElem = document.getElementById('statTotalStudents');
    const presentCountElem = document.getElementById('statPresentCount');
    const absentCountElem = document.getElementById('statAbsentCount');
    const rateElem = document.getElementById('statAttendanceRate');

    // Attach click handlers to student toggle buttons
    const rows = studentTable.querySelectorAll('tbody tr');
    
    function updateCounters() {
        let present = 0;
        let absent = 0;
        const total = rows.length;

        rows.forEach(row => {
            const activeBtn = row.querySelector('.attendance-toggle-btn.active');
            if (activeBtn) {
                if (activeBtn.classList.contains('btn-present')) {
                    present++;
                } else if (activeBtn.classList.contains('btn-absent')) {
                    absent++;
                }
            }
        });

        if (totalCountElem) totalCountElem.textContent = total;
        if (presentCountElem) presentCountElem.textContent = present;
        if (absentCountElem) absentCountElem.textContent = absent;

        if (rateElem) {
            const rate = total > 0 ? Math.round((present / total) * 100) : 0;
            rateElem.textContent = `${rate}%`;
        }
    }

    // Delegate click events on toggle buttons inside rows
    studentTable.addEventListener('click', function(e) {
        const toggleBtn = e.target.closest('.attendance-toggle-btn');
        if (!toggleBtn) return;

        const row = toggleBtn.closest('tr');
        const siblings = row.querySelectorAll('.attendance-toggle-btn');
        siblings.forEach(b => b.classList.remove('active'));
        toggleBtn.classList.add('active');

        // Update hidden status input if present
        const hiddenInput = row.querySelector('input[type="hidden"].student-status-input');
        if (hiddenInput) {
            if (toggleBtn.classList.contains('btn-present')) hiddenInput.value = 'present';
            else if (toggleBtn.classList.contains('btn-absent')) hiddenInput.value = 'absent';
        }

        updateCounters();
    });

    // Mark All Present
    if (btnMarkAllPresent) {
        btnMarkAllPresent.addEventListener('click', function() {
            rows.forEach(row => {
                const presentBtn = row.querySelector('.btn-present');
                const absentBtn = row.querySelector('.btn-absent');
                if (presentBtn && absentBtn) {
                    absentBtn.classList.remove('active');
                    presentBtn.classList.add('active');
                }
                const hiddenInput = row.querySelector('input[type="hidden"].student-status-input');
                if (hiddenInput) hiddenInput.value = 'present';
            });
            updateCounters();
            showFacultyToast('Marked all students as Present', 'success');
        });
    }

    // Mark All Absent
    if (btnMarkAllAbsent) {
        btnMarkAllAbsent.addEventListener('click', function() {
            rows.forEach(row => {
                const presentBtn = row.querySelector('.btn-present');
                const absentBtn = row.querySelector('.btn-absent');
                if (presentBtn && absentBtn) {
                    presentBtn.classList.remove('active');
                    absentBtn.classList.add('active');
                }
                const hiddenInput = row.querySelector('input[type="hidden"].student-status-input');
                if (hiddenInput) hiddenInput.value = 'absent';
            });
            updateCounters();
            showFacultyToast('Marked all students as Absent', 'warning');
        });
    }

    // Reset
    if (btnResetAttendance) {
        btnResetAttendance.addEventListener('click', function() {
            rows.forEach(row => {
                const presentBtn = row.querySelector('.btn-present');
                const absentBtn = row.querySelector('.btn-absent');
                if (presentBtn && absentBtn) {
                    absentBtn.classList.remove('active');
                    presentBtn.classList.add('active');
                }
                const hiddenInput = row.querySelector('input[type="hidden"].student-status-input');
                if (hiddenInput) hiddenInput.value = 'present';
            });
            updateCounters();
            showFacultyToast('Reset attendance selections', 'info');
        });
    }

    // Form submission validation
    if (attendanceForm) {
        attendanceForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const subject = document.getElementById('markSubjectSelect');
            const division = document.getElementById('markDivisionSelect');
            const lecture = document.getElementById('markLectureSelect');
            const dateInput = document.getElementById('markDateInput');

            if (subject && !subject.value) {
                showFacultyToast('Please select a Subject before saving.', 'danger');
                subject.focus();
                return;
            }

            if (division && !division.value) {
                showFacultyToast('Please select a Division.', 'danger');
                division.focus();
                return;
            }

            if (lecture && !lecture.value) {
                showFacultyToast('Please select a Lecture Slot.', 'danger');
                lecture.focus();
                return;
            }

            if (dateInput && !dateInput.value) {
                showFacultyToast('Please select a valid Date.', 'danger');
                dateInput.focus();
                return;
            }

            // Trigger submit confirmation
            const submitBtn = attendanceForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                const origText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span> Saving...`;

                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = origText;
                    showFacultyToast('Attendance submitted and saved successfully!', 'success');
                }, 900);
            }
        });
    }

    // Initial counter calculation
    updateCounters();
}

/* ==========================================================================
   3. Subject Allocation Search & Filter
   ========================================================================== */
function initSubjectAllocationFilter() {
    const searchInput = document.getElementById('allocationSearchInput');
    const semFilter = document.getElementById('allocationSemFilter');
    const divFilter = document.getElementById('allocationDivFilter');
    const cards = document.querySelectorAll('.allocation-card-item');

    if (!cards.length) return;

    function applyFilters() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const sem = semFilter ? semFilter.value : '';
        const div = divFilter ? divFilter.value : '';

        let matchCount = 0;

        cards.forEach(card => {
            const title = card.getAttribute('data-subject-name') || card.textContent.toLowerCase();
            const code = card.getAttribute('data-subject-code') || '';
            const cardSem = card.getAttribute('data-semester') || '';
            const cardDiv = card.getAttribute('data-division') || '';

            const matchesQuery = !query || title.toLowerCase().includes(query) || code.toLowerCase().includes(query);
            const matchesSem = !sem || cardSem === sem;
            const matchesDiv = !div || cardDiv === div;

            if (matchesQuery && matchesSem && matchesDiv) {
                card.style.display = 'block';
                matchCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Toggle Empty state view if present
        const emptyState = document.getElementById('allocationEmptyState');
        if (emptyState) {
            emptyState.style.display = matchCount === 0 ? 'block' : 'none';
        }
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (semFilter) semFilter.addEventListener('change', applyFilters);
    if (divFilter) divFilter.addEventListener('change', applyFilters);
}

/* ==========================================================================
   4. Edit Attendance Filter Logic
   ========================================================================== */
function initEditAttendanceFilter() {
    const editSearchForm = document.getElementById('editSearchForm');
    const recordsSection = document.getElementById('editAttendanceRecordsSection');

    if (editSearchForm) {
        editSearchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchBtn = editSearchForm.querySelector('button[type="submit"]');
            const origText = searchBtn.innerHTML;

            searchBtn.disabled = true;
            searchBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span> Searching...`;

            setTimeout(() => {
                searchBtn.disabled = false;
                searchBtn.innerHTML = origText;
                if (recordsSection) {
                    recordsSection.style.display = 'block';
                    recordsSection.scrollIntoView({ behavior: 'smooth' });
                }
                showFacultyToast('Loaded attendance record for selected criteria.', 'info');
            }, 600);
        });
    }
}

/* ==========================================================================
   Helper: Toast Notification Dispatcher
   ========================================================================== */
function showFacultyToast(message, type = 'info') {
    let toastContainer = document.getElementById('facultyToastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'facultyToastContainer';
        toastContainer.style.cssText = `
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 1090;
            display: flex;
            flex-direction: column;
            gap: 10px;
        `;
        document.body.appendChild(toastContainer);
    }

    const toast = document.createElement('div');
    toast.className = `faculty-toast toast-${type}`;
    
    let bg = '#1e293b';
    let icon = 'bi-info-circle-fill';
    let borderColor = 'rgba(255,255,255,0.1)';

    if (type === 'success') {
        bg = 'rgba(16, 185, 129, 0.95)';
        icon = 'bi-check-circle-fill';
        borderColor = '#059669';
    } else if (type === 'danger') {
        bg = 'rgba(239, 68, 68, 0.95)';
        icon = 'bi-exclamation-triangle-fill';
        borderColor = '#dc2626';
    } else if (type === 'warning') {
        bg = 'rgba(245, 158, 11, 0.95)';
        icon = 'bi-exclamation-circle-fill';
        borderColor = '#d97706';
    } else if (type === 'info') {
        bg = 'rgba(14, 165, 233, 0.95)';
        icon = 'bi-info-circle-fill';
        borderColor = '#0284c7';
    }

    toast.style.cssText = `
        background: ${bg};
        color: #ffffff;
        padding: 0.85rem 1.25rem;
        border-radius: 12px;
        border: 1px solid ${borderColor};
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        font-size: 0.875rem;
        min-width: 280px;
        max-width: 400px;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    `;

    toast.innerHTML = `<i class="bi ${icon}" style="font-size: 1.15rem;"></i> <span>${message}</span>`;
    toastContainer.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    }, 10);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}
