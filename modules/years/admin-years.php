<?php
/**
 * AttendEase - Super Admin Academic Year Management
 */
$page_title      = 'Academic Year Management';
$page_breadcrumb = 'AttendEase / Super Admin / Academic Years';
$page_icon       = 'bi-calendar-range';

include '../../includes/header.php';
?>
<link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/dashboard.css">

<script>
document.body.classList.add('faculty-portal-body');
if (window.innerWidth >= 992 && localStorage.getItem('facultySidebarCollapsed') === 'true') {
    document.documentElement.classList.add('preload-collapsed');
}
</script>

<div class="faculty-portal">
    <div class="faculty-portal-wrapper">

        <!-- Super Admin Sidebar -->
        <?php include '../../includes/admin-sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="faculty-main-content">

            <!-- Topbar Page Title Band -->
            <?php include '../../includes/admin-topbar.php'; ?>

            <!-- Content Body -->
            <main class="faculty-content-body">

                <!-- Welcome/Stats Banner -->
                <div class="faculty-card welcome-banner-card mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="h4 fw-bold text-white font-outfit mb-1">Academic Year Management</h2>
                            <p class="mb-0 text-slate-300 small">Manage university calendar years, set start &amp; end dates, and configure the active term.</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <button class="btn btn-premium" data-bs-toggle="modal" data-bs-target="#addYearModal"><i class="bi bi-plus-circle me-1"></i> Add Academic Year</button>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="faculty-card mb-4">
                    <div class="faculty-card-header mb-3 pb-2">
                        <h3 class="faculty-card-title"><i class="bi bi-funnel" style="color:#818cf8;"></i> Filters</h3>
                    </div>
                    <form id="filterForm" class="row g-3">
                        <div class="col-md-8">
                            <label class="faculty-form-label">Search Year</label>
                            <input type="text" id="searchInput" class="faculty-input" placeholder="Type academic year name...">
                        </div>
                        <div class="col-md-4">
                            <label class="faculty-form-label">Filter Status</label>
                            <select id="statusSelect" class="faculty-select">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Academic Years Table -->
                <div class="faculty-card">
                    <div class="faculty-table-responsive">
                        <table class="faculty-table" id="yearsTable">
                            <thead>
                                <tr>
                                    <th>Academic Year</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-status="active">
                                    <td><span class="text-white fw-bold">2026 - 2027</span></td>
                                    <td style="color:#cbd5e1;">June 01, 2026</td>
                                    <td style="color:#cbd5e1;">April 30, 2027</td>
                                    <td>Current active academic cycle</td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Active</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-info py-1 px-2.5 rounded-2" onclick="alert('Editing Year: 2026 - 2027')"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-secondary py-1 px-2.5 rounded-2 ms-1" onclick="alert('Year already active')">Set Active</button>
                                    </td>
                                </tr>
                                <tr data-status="inactive">
                                    <td><span class="text-white fw-bold">2025 - 2026</span></td>
                                    <td style="color:#cbd5e1;">June 01, 2025</td>
                                    <td style="color:#cbd5e1;">April 30, 2026</td>
                                    <td>Previous academic cycle</td>
                                    <td><span class="faculty-badge badge-secondary-subtle"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Inactive</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-info py-1 px-2.5 rounded-2" onclick="alert('Editing Year: 2025 - 2026')"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-success py-1 px-2.5 rounded-2 ms-1" onclick="alert('Activating 2025 - 2026 academic year...')">Set Active</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<!-- Add Academic Year Modal -->
<div class="modal fade" id="addYearModal" tabindex="-1" aria-labelledby="addYearModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-outfit" id="addYearModalLabel">Add Academic Year</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="event.preventDefault(); alert('Academic year added successfully!'); bootstrap.Modal.getInstance(document.getElementById('addYearModal')).hide();">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-semibold text-slate-300">Academic Year Title</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="e.g. 2027 - 2028" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label font-semibold text-slate-300">Start Date</label>
                            <input type="date" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-semibold text-slate-300">End Date</label>
                            <input type="date" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold text-slate-300">Description</label>
                        <textarea class="form-control bg-dark text-white border-secondary" placeholder="Details about this year..." rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-premium">Add Year</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<script>
// Filter Functionality
const searchInput = document.getElementById('searchInput');
const statusSelect = document.getElementById('statusSelect');
const rows = document.querySelectorAll('#yearsTable tbody tr');

function applyFilters() {
    const query = searchInput.value.toLowerCase().trim();
    const status = statusSelect.value;

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        const rowStatus = row.getAttribute('data-status');

        const matchesSearch = !query || text.includes(query);
        const matchesStatus = !status || rowStatus === status;

        if (matchesSearch && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

searchInput.addEventListener('input', applyFilters);
statusSelect.addEventListener('change', applyFilters);
</script>
<?php include '../../includes/footer.php'; ?>
