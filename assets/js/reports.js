/**
 * Faculty Attendance Reports & Analytics JavaScript Module
 * Student Attendance Tracking Portal
 */

document.addEventListener('DOMContentLoaded', function() {
    initTableFilters();
    initMonthlyChart();
    initStudentSearch();
});

/**
 * Real-time Table Filter System
 */
function initTableFilters() {
    const searchInputs = document.querySelectorAll('.report-search-input');
    const filterSelects = document.querySelectorAll('.report-filter-select');
    const dateInputs = document.querySelectorAll('.report-date-input');

    searchInputs.forEach(input => {
        input.addEventListener('keyup', applyFilters);
    });

    filterSelects.forEach(select => {
        select.addEventListener('change', applyFilters);
    });

    dateInputs.forEach(input => {
        input.addEventListener('change', applyFilters);
    });
}

function applyFilters() {
    const historyTable = document.getElementById('historyTableBody');
    const monthlyTable = document.getElementById('monthlyTableBody');
    const studentTable = document.getElementById('studentTableBody');

    if (historyTable) filterHistoryTable(historyTable);
    if (monthlyTable) filterMonthlyTable(monthlyTable);
    if (studentTable) filterStudentTable(studentTable);
}

function filterHistoryTable(tbody) {
    const searchVal = (document.getElementById('searchQuery')?.value || '').toLowerCase();
    const subjectVal = (document.getElementById('filterSubject')?.value || 'ALL').toLowerCase();
    const statusVal = (document.getElementById('filterStatus')?.value || 'ALL').toLowerCase();
    const startDate = document.getElementById('startDate')?.value;
    const endDate = document.getElementById('endDate')?.value;

    let visibleCount = 0;
    const rows = tbody.querySelectorAll('tr.data-row');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const rowSubject = (row.getAttribute('data-subject') || '').toLowerCase();
        const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();
        const rowDate = row.getAttribute('data-date');

        let matchesSearch = !searchVal || text.includes(searchVal);
        let matchesSubject = subjectVal === 'all' || rowSubject.includes(subjectVal);
        let matchesStatus = statusVal === 'all' || rowStatus === statusVal;
        let matchesDate = true;

        if (startDate && rowDate && rowDate < startDate) matchesDate = false;
        if (endDate && rowDate && rowDate > endDate) matchesDate = false;

        if (matchesSearch && matchesSubject && matchesStatus && matchesDate) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    updateEmptyState('historyEmptyState', visibleCount);
}

function filterMonthlyTable(tbody) {
    const searchVal = (document.getElementById('searchMonthly')?.value || '').toLowerCase();
    const courseVal = (document.getElementById('filterCourse')?.value || 'ALL').toLowerCase();
    const thresholdVal = document.getElementById('filterThreshold')?.value || 'ALL';

    let visibleCount = 0;
    const rows = tbody.querySelectorAll('tr.data-row');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const rowCourse = (row.getAttribute('data-course') || '').toLowerCase();
        const rowPercent = parseFloat(row.getAttribute('data-percent') || 0);

        let matchesSearch = !searchVal || text.includes(searchVal);
        let matchesCourse = courseVal === 'all' || rowCourse.includes(courseVal);
        let matchesThreshold = true;

        if (thresholdVal === 'defaulter') {
            matchesThreshold = rowPercent < 75;
        } else if (thresholdVal === 'warning') {
            matchesThreshold = rowPercent >= 75 && rowPercent < 85;
        } else if (thresholdVal === 'good') {
            matchesThreshold = rowPercent >= 85;
        }

        if (matchesSearch && matchesCourse && matchesThreshold) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    updateEmptyState('monthlyEmptyState', visibleCount);
}

function filterStudentTable(tbody) {
    const searchVal = (document.getElementById('searchStudentInput')?.value || '').toLowerCase();
    const deptVal = (document.getElementById('filterDept')?.value || 'ALL').toLowerCase();
    const statusVal = (document.getElementById('filterStudentStatus')?.value || 'ALL').toLowerCase();

    let visibleCount = 0;
    const rows = tbody.querySelectorAll('tr.data-row');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const rowDept = (row.getAttribute('data-dept') || '').toLowerCase();
        const rowPercent = parseFloat(row.getAttribute('data-percent') || 0);

        let matchesSearch = !searchVal || text.includes(searchVal);
        let matchesDept = deptVal === 'all' || rowDept.includes(deptVal);
        let matchesStatus = true;

        if (statusVal === 'defaulter') {
            matchesStatus = rowPercent < 75;
        } else if (statusVal === 'safe') {
            matchesStatus = rowPercent >= 75;
        }

        if (matchesSearch && matchesDept && matchesStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    updateEmptyState('studentEmptyState', visibleCount);
}

function updateEmptyState(elementId, count) {
    const el = document.getElementById(elementId);
    if (el) {
        el.style.display = count === 0 ? '' : 'none';
    }
}

/**
 * Chart Initialization for Monthly Analytics
 */
function initMonthlyChart() {
    const ctx = document.getElementById('monthlyTrendChart');
    if (!ctx) return;

    // Check if Chart.js is loaded
    if (typeof Chart !== 'undefined') {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['1 Jul', '3 Jul', '5 Jul', '8 Jul', '10 Jul', '12 Jul', '15 Jul', '17 Jul', '19 Jul', '22 Jul'],
                datasets: [
                    {
                        label: 'CS-101 Data Structures',
                        data: [92, 88, 95, 90, 85, 94, 91, 89, 96, 92],
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.15)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'CS-102 Database Systems',
                        data: [85, 82, 89, 87, 84, 88, 86, 90, 88, 85],
                        borderColor: '#0ea5e9',
                        backgroundColor: 'rgba(14, 165, 233, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#94a3b8', font: { family: 'Inter' } }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { color: '#94a3b8' }
                    },
                    y: {
                        min: 50,
                        max: 100,
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { color: '#94a3b8', callback: v => v + '%' }
                    }
                }
            }
        });
    }
}

/**
 * CSV Export Functionality
 */
function exportTableToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) return;

    let csv = [];
    const rows = table.querySelectorAll('tr');

    rows.forEach(row => {
        // Skip hidden rows
        if (row.style.display === 'none') return;

        let rowData = [];
        const cols = row.querySelectorAll('th, td');
        
        cols.forEach(col => {
            // Exclude action column buttons if marked with .no-export
            if (col.classList.contains('no-export')) return;
            
            let text = col.innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/"/g, '""').trim();
            rowData.push('"' + text + '"');
        });

        if (rowData.length > 0) {
            csv.push(rowData.join(','));
        }
    });

    const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.download = filename || 'attendance_report.csv';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

/**
 * Print Report Helper
 */
function printReport() {
    window.print();
}

/**
 * Modal Populator for Session Details
 */
function showSessionDetails(date, subject, topic, total, present, absent) {
    document.getElementById('modalSessionDate').innerText = date;
    document.getElementById('modalSessionSubject').innerText = subject;
    document.getElementById('modalSessionTopic').innerText = topic;
    document.getElementById('modalSessionTotal').innerText = total;
    document.getElementById('modalSessionPresent').innerText = present;
    document.getElementById('modalSessionAbsent').innerText = absent;

    const modal = new bootstrap.Modal(document.getElementById('sessionDetailModal'));
    modal.show();
}

/**
 * Modal Populator for Student Summary
 */
function showStudentDetails(name, rollNo, prn, dept, sem, totalHeld, attended, percent) {
    document.getElementById('modalStudentName').innerText = name;
    document.getElementById('modalStudentRoll').innerText = rollNo;
    document.getElementById('modalStudentPRN').innerText = prn;
    document.getElementById('modalStudentDept').innerText = dept + ' (Sem ' + sem + ')';
    document.getElementById('modalStudentTotal').innerText = totalHeld;
    document.getElementById('modalStudentAttended').innerText = attended;
    document.getElementById('modalStudentPercent').innerText = percent + '%';

    const statusBadge = document.getElementById('modalStudentBadge');
    if (parseFloat(percent) < 75) {
        statusBadge.className = 'badge-status badge-status-defaulter';
        statusBadge.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Defaulter (< 75%)';
    } else if (parseFloat(percent) < 85) {
        statusBadge.className = 'badge-status badge-status-warning';
        statusBadge.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i> Warning (75-85%)';
    } else {
        statusBadge.className = 'badge-status badge-status-safe';
        statusBadge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Good (> 85%)';
    }

    const modal = new bootstrap.Modal(document.getElementById('studentDetailModal'));
    modal.show();
}
