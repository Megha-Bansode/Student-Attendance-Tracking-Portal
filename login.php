<?php include 'includes/header.php'; ?>

<!-- Login Page Content -->
<section class="section-padding d-flex align-items-center" style="min-height: 90vh; padding-top: 10rem;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <!-- Glassmorphism Login Card -->
                <div class="dashboard-preview p-4 p-md-5" id="login-container-card">
                    <div class="text-center mb-4">
                        <img src="assets/images/logo.svg" alt="Portal Logo" width="48" height="48" class="mb-3">
                        <h2 class="h3 text-white fw-bold mb-1">Welcome Back</h2>
                        <p class="text-muted small">Access the Student Attendance Portal</p>
                    </div>

                    <!-- Role Tab Buttons -->
                    <ul class="nav nav-pills nav-fill mb-4 p-1 bg-dark bg-opacity-50 rounded-pill border border-secondary" id="loginRoleTabs" role="tablist" style="border-width: 1px !important;">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-2 rounded-pill small" id="student-tab" data-bs-toggle="tab" data-bs-target="#student-form" type="button" role="tab" aria-controls="student-form" aria-selected="true" style="font-size:0.85rem;">
                                <i class="bi bi-person-fill"></i> Student
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-2 rounded-pill small" id="faculty-tab" data-bs-toggle="tab" data-bs-target="#faculty-form" type="button" role="tab" aria-controls="faculty-form" aria-selected="false" style="font-size:0.85rem;">
                                <i class="bi bi-briefcase-fill"></i> Faculty
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-2 rounded-pill small" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin-form" type="button" role="tab" aria-controls="admin-form" aria-selected="false" style="font-size:0.85rem;">
                                <i class="bi bi-shield-lock-fill"></i> Admin
                            </button>
                        </li>
                    </ul>

                    <!-- Form Contents -->
                    <div class="tab-content" id="loginRoleTabContents">
                        
                        <!-- 1. Student Login Form -->
                        <div class="tab-pane fade show active" id="student-form" role="tabpanel" aria-labelledby="student-tab">
                            <form action="#" method="POST" id="form-student-login">
                                <div class="mb-3">
                                    <label for="studentPrn" class="form-label text-white small fw-semibold">PRN / Roll Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-hash"></i></span>
                                        <input type="text" class="form-control bg-dark text-white border-secondary" id="studentPrn" placeholder="e.g. 20241004" required style="outline: none; box-shadow: none;">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="studentPassword" class="form-label text-white small fw-semibold">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" class="form-control bg-dark text-white border-secondary" id="studentPassword" placeholder="••••••••" required style="outline: none; box-shadow: none;">
                                    </div>
                                    <div class="text-end mt-1">
                                        <a href="#" class="text-accent small text-decoration-none" style="font-size: 0.8rem;">Forgot Password?</a>
                                    </div>
                                </div>
                                <button type="submit" class="btn-premium w-100 justify-content-center py-2.5" id="btn-student-submit">
                                    Student Login <i class="bi bi-box-arrow-in-right"></i>
                                </button>
                            </form>
                        </div>

                        <!-- 2. Faculty Login Form -->
                        <div class="tab-pane fade" id="faculty-form" role="tabpanel" aria-labelledby="faculty-tab">
                            <form action="#" method="POST" id="form-faculty-login">
                                <div class="mb-3">
                                    <label for="facultyId" class="form-label text-white small fw-semibold">Faculty ID / Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-envelope-fill"></i></span>
                                        <input type="text" class="form-control bg-dark text-white border-secondary" id="facultyId" placeholder="e.g. prof.sharma@attendease.edu" required style="outline: none; box-shadow: none;">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="facultyPassword" class="form-label text-white small fw-semibold">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" class="form-control bg-dark text-white border-secondary" id="facultyPassword" placeholder="••••••••" required style="outline: none; box-shadow: none;">
                                    </div>
                                    <div class="text-end mt-1">
                                        <a href="#" class="text-accent small text-decoration-none" style="font-size: 0.8rem;">Forgot Password?</a>
                                    </div>
                                </div>
                                <button type="submit" class="btn-premium w-100 justify-content-center py-2.5" id="btn-faculty-submit">
                                    Faculty Login <i class="bi bi-box-arrow-in-right"></i>
                                </button>
                            </form>
                        </div>

                        <!-- 3. Admin Login Form -->
                        <div class="tab-pane fade" id="admin-form" role="tabpanel" aria-labelledby="admin-tab">
                            <form action="#" method="POST" id="form-admin-login">
                                <div class="mb-3">
                                    <label for="adminEmail" class="form-label text-white small fw-semibold">Administrator Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-shield-fill"></i></span>
                                        <input type="email" class="form-control bg-dark text-white border-secondary" id="adminEmail" placeholder="admin@attendease.edu" required style="outline: none; box-shadow: none;">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="adminPassword" class="form-label text-white small fw-semibold">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" class="form-control bg-dark text-white border-secondary" id="adminPassword" placeholder="••••••••" required style="outline: none; box-shadow: none;">
                                    </div>
                                    <div class="text-end mt-1">
                                        <a href="#" class="text-accent small text-decoration-none" style="font-size: 0.8rem;">Forgot Password?</a>
                                    </div>
                                </div>
                                <button type="submit" class="btn-premium w-100 justify-content-center py-2.5" id="btn-admin-submit">
                                    Admin Login <i class="bi bi-box-arrow-in-right"></i>
                                </button>
                            </form>
                        </div>

                    </div>

                    <!-- Return to Landing Page -->
                    <div class="text-center mt-4">
                        <a href="index.php" class="text-muted small text-decoration-none" id="login-back-home">
                            <i class="bi bi-arrow-left"></i> Back to Homepage
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
