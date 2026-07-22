<?php
/**
 * AttendEase - Super Admin Semester & Division Management
 */
$page_title      = 'Semester & Division Management';
$page_breadcrumb = 'AttendEase / Super Admin / Semesters & Divisions';
$page_icon       = 'bi-columns-gap';

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
                            <h2 class="h4 fw-bold text-white font-outfit mb-1">Manage Semesters &amp; Divisions</h2>
                            <p class="mb-0 text-slate-300 small">Configure academic terms, define class divisions, and assign class coordinators for HOD oversight.</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <button class="btn btn-premium" data-bs-toggle="modal" data-bs-target="#addDivModal"><i class="bi bi-plus-circle me-1"></i> Add Division</button>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="faculty-card mb-4">
                    <div class="faculty-card-header mb-3 pb-2">
                        <h3 class="faculty-card-title"><i class="bi bi-funnel" style="color:#818cf8;"></i> Search &amp; Filter</h3>
                    </div>
                    <form id="filterForm" class="row g-3">
                        <div class="col-md-5">
                            <label class="faculty-form-label">Search Division / Coordinator</label>
                            <input type="text" id="searchInput" class="faculty-input" placeholder="Type coordinator name or div...">
                        </div>
                        <div class="col-md-4">
                            <label class="faculty-form-label">Filter Department</label>
                            <select id="deptSelect" class="faculty-select">
                                <option value="">All Departments</option>
                                <option value="CSE">Computer Engineering (CSE)</option>
                                <option value="IT">Information Technology (IT)</option>
                                <option value="ENTC">Electronics &amp; Telecom (ENTC)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="faculty-form-label">Filter Semester</label>
                            <select id="semSelect" class="faculty-select">
                                <option value="">All Semesters</option>
                                <option value="3">Semester III</option>
                                <option value="4">Semester IV</option>
                                <option value="5">Semester V</option>
                                <option value="6">Semester VI</option>
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Division Listing -->
                <div class="faculty-card">
                    <div class="faculty-table-responsive">
                        <table class="faculty-table" id="divisionsTable">
                            <thead>
                                <tr>
                                    <th>Term / Semester</th>
                                    <th>Division</th>
                                    <th>Department</th>
                                    <th>Class Coordinator</th>
                                    <th>Students count</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-dept="CSE" data-sem="5">
                                    <td><span class="text-white fw-bold">Semester V</span></td>
                                    <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill">Div A</span></td>
                                    <td>Computer Engineering</td>
                                    <td><span style="color:#cbd5e1;">Prof. Rajesh Sharma</span></td>
                                    <td><span class="fw-bold text-white">64</span></td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Active</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-info py-1 px-2.5 rounded-2" onclick="alert('Editing Division: Sem V - Div A')"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-danger py-1 px-2.5 rounded-2 ms-1" onclick="if(confirm('Are you sure you want to delete this division?')) { alert('Division deleted!'); }"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                <tr data-dept="IT" data-sem="4">
                                    <td><span class="text-white fw-bold">Semester IV</span></td>
                                    <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill">Div B</span></td>
                                    <td>Information Technology</td>
                                    <td><span style="color:#cbd5e1;">Dr. Sarah Jenkins</span></td>
                                    <td><span class="fw-bold text-white">58</span></td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Active</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-info py-1 px-2.5 rounded-2" onclick="alert('Editing Division: Sem IV - Div B')"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-danger py-1 px-2.5 rounded-2 ms-1" onclick="if(confirm('Are you sure you want to delete this division?')) { alert('Division deleted!'); }"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                <tr data-dept="ENTC" data-sem="6">
                                    <td><span class="text-white fw-bold">Semester VI</span></td>
                                    <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill">Div C</span></td>
                                    <td>Electronics &amp; Telecom</td>
                                    <td><span style="color:#cbd5e1;">Dr. Amit Patel</span></td>
                                    <td><span class="fw-bold text-white">45</span></td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Active</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-info py-1 px-2.5 rounded-2" onclick="alert('Editing Division: Sem VI - Div C')"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-danger py-1 px-2.5 rounded-2 ms-1" onclick="if(confirm('Are you sure you want to delete this division?')) { alert('Division deleted!'); }"><i class="bi bi-trash"></i></button>
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

<!-- Add Division Modal -->
<div class="modal fade" id="addDivModal" tabindex="-1" aria-labelledby="addDivModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-outfit" id="addDivModalLabel">Create New Division</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="event.preventDefault(); alert('Division created successfully!'); bootstrap.Modal.getInstance(document.getElementById('addDivModal')).hide();">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-semibold text-slate-300">Semester</label>
                        <select class="form-select bg-dark text-white border-secondary" required>
                            <option value="">-- Choose Semester --</option>
                            <option value="1">Semester I</option>
                            <option value="2">Semester II</option>
                            <option value="3">Semester III</option>
                            <option value="4">Semester IV</option>
                            <option value="5">Semester V</option>
                            <option value="6">Semester VI</option>
                            <option value="7">Semester VII</option>
                            <option value="8">Semester VIII</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold text-slate-300">Division Name</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="e.g. Div A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold text-slate-300">Department</label>
                        <select class="form-select bg-dark text-white border-secondary" required>
                            <option value="">-- Choose Department --</option>
                            <option value="CSE">Computer Engineering (CSE)</option>
                            <option value="IT">Information Technology (IT)</option>
                            <option value="ENTC">Electronics &amp; Telecom (ENTC)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold text-slate-300">Class Coordinator (Faculty)</label>
                        <select class="form-select bg-dark text-white border-secondary" required>
                            <option value="">-- Assign Coordinator --</option>
                            <option value="rajesh">Prof. Rajesh Sharma</option>
                            <option value="sarah">Dr. Sarah Jenkins</option>
                            <option value="amit">Dr. Amit Patel</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-premium">Create Division</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<script>
// Filter Functionality
const searchInput = document.getElementById('searchInput');
const deptSelect = document.getElementById('deptSelect');
const semSelect = document.getElementById('semSelect');
const rows = document.querySelectorAll('#divisionsTable tbody tr');

function applyFilters() {
    const query = searchInput.value.toLowerCase().trim();
    const dept = deptSelect.value;
    const sem = semSelect.value;

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        const rowDept = row.getAttribute('data-dept');
        const rowSem = row.getAttribute('data-sem');

        const matchesSearch = !query || text.includes(query);
        const matchesDept = !dept || rowDept === dept;
        const matchesSem = !sem || rowSem === sem;

        if (matchesSearch && matchesDept && matchesSem) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

searchInput.addEventListener('input', applyFilters);
deptSelect.addEventListener('change', applyFilters);
semSelect.addEventListener('change', applyFilters);
</script>
<?php include '../../includes/footer.php'; ?>
