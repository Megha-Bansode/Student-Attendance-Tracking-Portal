/**
 * reports.js  --  Attendance Reports & Trend Analysis Module
 * Depends on: Chart.js (loaded via CDN in each page)
 * No AJAX, no API calls, no authentication logic.
 */

/* ============================================================
   SHARED UTILITY  -- Toast notification for action feedback
   ============================================================ */

/**
 * Show a toast with a title and body message.
 * @param {string} title
 * @param {string} body
 * @param {number} duration  ms before auto-hide
 */
function showReportToast(title, body, duration) {
    duration = duration || 3500;
    var toast = document.getElementById('report-toast');
    if (!toast) return;
    document.getElementById('report-toast-title').textContent = title;
    document.getElementById('report-toast-body').textContent  = body;
    toast.classList.add('show');
    setTimeout(function () { toast.classList.remove('show'); }, duration);
}

/* ============================================================
   FILTER PANEL  -- Apply / Reset / Export button handlers
   All buttons show "Ready for backend integration" toast.
   ============================================================ */

function initFilterPanel() {
    var btnApply = document.getElementById('btn-filter-apply');
    var btnReset = document.getElementById('btn-filter-reset');
    var btnPDF   = document.getElementById('btn-export-pdf');
    var btnExcel = document.getElementById('btn-export-excel');

    if (btnApply) {
        btnApply.addEventListener('click', function (e) {
            e.preventDefault();
            var originalText = btnApply.innerHTML;
            btnApply.innerHTML = '<i class="bi bi-arrow-repeat spin-icon-anim"></i> Processing...';
            btnApply.disabled = true;

            var panels = document.querySelectorAll('.table-responsive, .chart-wrapper, .report-stat-value');
            panels.forEach(function(p) {
                p.style.opacity = '0.3';
                p.style.transition = 'opacity 0.3s ease';
            });

            setTimeout(function() {
                btnApply.innerHTML = originalText;
                btnApply.disabled = false;
                panels.forEach(function(p) { p.style.opacity = '1'; });
                showReportToast('Data Refreshed', 'Filters applied successfully. Ready for backend API integration.');
            }, 800);
        });
    }

    if (btnReset) {
        btnReset.addEventListener('click', function (e) {
            e.preventDefault();
            var form = document.getElementById('report-filter-form');
            if (form) form.reset();
            showReportToast('Filters Reset', 'All filter values have been cleared.');
        });
    }

    if (btnPDF) {
        btnPDF.addEventListener('click', function (e) {
            e.preventDefault();
            var originalText = btnPDF.innerHTML;
            btnPDF.innerHTML = '<i class="bi bi-arrow-repeat spin-icon-anim"></i> Exporting...';
            btnPDF.disabled = true;
            setTimeout(function() {
                btnPDF.innerHTML = originalText;
                btnPDF.disabled = false;
                showReportToast('Export PDF', 'PDF generated successfully. Requires server-side processing for real data.');
            }, 1200);
        });
    }

    if (btnExcel) {
        btnExcel.addEventListener('click', function (e) {
            e.preventDefault();
            var originalText = btnExcel.innerHTML;
            btnExcel.innerHTML = '<i class="bi bi-arrow-repeat spin-icon-anim"></i> Exporting...';
            btnExcel.disabled = true;
            setTimeout(function() {
                btnExcel.innerHTML = originalText;
                btnExcel.disabled = false;
                showReportToast('Export Excel', 'Excel file generated successfully. Requires server-side processing for real data.');
            }, 1200);
        });
    }
}

/* ============================================================
   TABLE SEARCH  -- Client-side row filter (UI only)
   ============================================================ */

function initTableSearch(inputId, tableId) {
    var input = document.getElementById(inputId);
    var table = document.getElementById(tableId);
    if (!input || !table) return;

    input.addEventListener('input', function () {
        var query  = this.value.toLowerCase().trim();
        var rows   = table.querySelectorAll('tbody tr');
        var count  = 0;

        rows.forEach(function (row) {
            var text = row.textContent.toLowerCase();
            var show = query === '' || text.includes(query);
            row.style.display = show ? '' : 'none';
            if (show) count++;
        });

        /* Update the row-count label if present */
        var label = document.getElementById('table-row-count');
        if (label) label.textContent = 'Showing ' + count + ' records';
    });
}

/* ============================================================
   CHART.JS DEFAULTS  -- Match the dark project theme
   ============================================================ */

function applyChartDefaults() {
    if (typeof Chart === 'undefined') return;

    Chart.defaults.color            = '#94a3b8';    /* --text-muted */
    Chart.defaults.font.family      = "'Inter', sans-serif";
    Chart.defaults.font.size        = 12;
    Chart.defaults.borderColor      = 'rgba(255,255,255,0.06)';
    Chart.defaults.plugins.legend.labels.color = '#e2e8f0';
    Chart.defaults.plugins.legend.labels.boxWidth = 12;
    Chart.defaults.plugins.legend.labels.padding  = 16;
    Chart.defaults.plugins.tooltip.backgroundColor = '#1e293b';
    Chart.defaults.plugins.tooltip.titleColor      = '#f8fafc';
    Chart.defaults.plugins.tooltip.bodyColor       = '#94a3b8';
    Chart.defaults.plugins.tooltip.borderColor     = 'rgba(255,255,255,0.08)';
    Chart.defaults.plugins.tooltip.borderWidth     = 1;
    Chart.defaults.plugins.tooltip.padding         = 10;
    Chart.defaults.plugins.tooltip.cornerRadius    = 8;
}

/* ============================================================
   SUBJECT ANALYSIS PAGE  -- Bar Chart
   ============================================================ */

function initSubjectBarChart() {
    var ctx = document.getElementById('subjectBarChart');
    if (!ctx || typeof Chart === 'undefined') return;

    var labels = ['Data Structures', 'Algorithms', 'DBMS', 'OS', 'Computer Networks', 'Web Tech', 'AI/ML Basics', 'TOC'];
    var values = [88, 76, 91, 82, 79, 93, 85, 71];

    var gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 320);
    gradient.addColorStop(0,   'rgba(99,102,241,0.85)');
    gradient.addColorStop(1,   'rgba(14,165,233,0.65)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Attendance %',
                data: values,
                backgroundColor: gradient,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            return ' ' + ctx.parsed.y + '%';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    ticks: { maxRotation: 30, color: '#94a3b8' }
                },
                y: {
                    min: 55,
                    max: 100,
                    grid: { color: 'rgba(255,255,255,0.06)' },
                    ticks: {
                        callback: function (val) { return val + '%'; },
                        color: '#94a3b8'
                    }
                }
            },
            animation: { duration: 900, easing: 'easeOutQuart' }
        }
    });
}

/* ============================================================
   MONTHLY TRENDS PAGE  -- Line Chart
   ============================================================ */

function initMonthlyLineChart() {
    var ctx = document.getElementById('monthlyLineChart');
    if (!ctx || typeof Chart === 'undefined') return;

    var labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    var values = [84, 87, 82, 90, 88, 79, 75, 85, 91, 89, 86, 83];

    var canvasCtx = ctx.getContext('2d');
    var areaGrad  = canvasCtx.createLinearGradient(0, 0, 0, 320);
    areaGrad.addColorStop(0,   'rgba(99,102,241,0.35)');
    areaGrad.addColorStop(1,   'rgba(99,102,241,0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Attendance %',
                    data: values,
                    borderColor: '#6366f1',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#818cf8',
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    fill: true,
                    backgroundColor: areaGrad,
                    tension: 0.4
                },
                {
                    label: 'Target (85%)',
                    data: Array(12).fill(85),
                    borderColor: 'rgba(14,165,233,0.55)',
                    borderWidth: 1.5,
                    borderDash: [6, 4],
                    pointRadius: 0,
                    fill: false,
                    tension: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            return ' ' + ctx.dataset.label + ': ' + ctx.parsed.y + '%';
                        }
                    }
                }
            },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#94a3b8' } },
                y: {
                    min: 60, max: 100,
                    grid: { color: 'rgba(255,255,255,0.06)' },
                    ticks: {
                        callback: function (val) { return val + '%'; },
                        color: '#94a3b8'
                    }
                }
            },
            animation: { duration: 1000, easing: 'easeOutQuart' }
        }
    });
}

/* ============================================================
   DEPARTMENT REPORTS PAGE  -- Doughnut Chart
   ============================================================ */

function initDeptDoughnutChart() {
    var ctx = document.getElementById('deptDoughnutChart');
    if (!ctx || typeof Chart === 'undefined') return;

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Computer Engg', 'AI & ML', 'Info Tech', 'ENTC'],
            datasets: [{
                data: [88, 92, 85, 90],
                backgroundColor: [
                    'rgba(99,102,241,0.8)',
                    'rgba(14,165,233,0.8)',
                    'rgba(139,92,246,0.8)',
                    'rgba(16,185,129,0.8)'
                ],
                borderColor: [
                    'rgba(99,102,241,1)',
                    'rgba(14,165,233,1)',
                    'rgba(139,92,246,1)',
                    'rgba(16,185,129,1)'
                ],
                borderWidth: 2,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '68%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 20 }
                },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            return ' ' + ctx.label + ': ' + ctx.parsed + '%';
                        }
                    }
                }
            },
            animation: { duration: 1000, animateRotate: true }
        }
    });
}

/* ============================================================
   PROGRESS BAR ANIMATION  -- animate dept progress bars on load
   ============================================================ */

function animateDeptProgressBars() {
    var bars = document.querySelectorAll('.dept-progress-bar[data-width]');
    bars.forEach(function (bar) {
        bar.style.width = '0';
        setTimeout(function () {
            bar.style.width = bar.getAttribute('data-width');
        }, 200);
    });
}

/* ============================================================
   VIEW REPORT button  -- dept cards
   ============================================================ */

function initViewReportBtns() {
    var btns = document.querySelectorAll('.btn-view-report');
    btns.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            showReportToast('View Report', 'Ready for backend integration. Individual department report generation requires server-side data.');
        });
    });
}

/* ============================================================
   BOOT  -- called on DOMContentLoaded on every reports page
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {
    applyChartDefaults();
    initFilterPanel();

    /* Page-specific inits */
    initSubjectBarChart();
    initTableSearch('subject-search', 'subject-table');

    initMonthlyLineChart();
    initTableSearch('monthly-search', 'monthly-table');

    initDeptDoughnutChart();
    initTableSearch('dept-search', 'dept-table');
    animateDeptProgressBars();
    initViewReportBtns();
});
