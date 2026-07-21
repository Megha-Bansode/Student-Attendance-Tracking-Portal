/**
 * Scoped JS Interactivity & Starfield Physics for Split Login Page
 * Does not affect index.php or other pages
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Role Left Panel & Details Map
    const roleDetails = {
        student: {
            badgeText: 'STUDENT PORTAL',
            badgeClass: 'bg-success-subtle text-success',
            badgeIcon: 'bi-mortarboard-fill',
            title: 'Student Attendance',
            subtitle: 'Tracking Portal',
            headline: 'Manage Attendance <br><span class="gradient-title-accent">Effortlessly</span>',
            desc: 'A comprehensive college management system for tracking attendance, managing departments, courses, faculty, and students.',
            pills: [
                { icon: 'bi-diagram-3-fill', text: 'Department Management' },
                { icon: 'bi-person-plus-fill', text: 'Student Registration' },
                { icon: 'bi-person-workspace', text: 'Faculty Portal' },
                { icon: 'bi-bar-chart-line-fill', text: 'Attendance Reports' },
                { icon: 'bi-shield-check', text: 'Role-Based Access' }
            ],
            stats: [
                { value: '1,340+', label: 'Students' },
                { value: '70+', label: 'Faculty' },
                { value: '7', label: 'Departments' }
            ],
            themeClass: 'theme-student'
        },
        faculty: {
            badgeText: 'FACULTY PORTAL',
            badgeClass: 'bg-info-subtle text-info',
            badgeIcon: 'bi-briefcase-fill',
            title: 'Faculty Portal',
            subtitle: 'Academic Dashboard',
            headline: 'Empower Learning <br><span class="gradient-title-accent">& Scheduling</span>',
            desc: 'Mark attendance in real-time, view student analytics, generate reports, and manage classes efficiently.',
            pills: [
                { icon: 'bi-clock-history', text: 'Real-time Attendance' },
                { icon: 'bi-graph-up-arrow', text: 'Performance Analytics' },
                { icon: 'bi-calendar-event', text: 'Class Scheduling' },
                { icon: 'bi-file-earmark-pdf-fill', text: 'Report Export' },
                { icon: 'bi-chat-left-text-fill', text: 'Faculty Notifications' }
            ],
            stats: [
                { value: '45', label: 'Active Courses' },
                { value: '180+', label: 'Lectures/Month' },
                { value: '98%', label: 'Average Accuracy' }
            ],
            themeClass: 'theme-faculty'
        },
        admin: {
            badgeText: 'SUPER ADMIN',
            badgeClass: 'bg-primary-subtle text-primary',
            badgeIcon: 'bi-shield-check',
            title: 'Control Panel',
            subtitle: 'System Management',
            headline: 'Oversee Entire <br><span class="gradient-title-accent">Institution</span>',
            desc: 'Configure institution structures, add departments, enroll faculty/students, audit system logs, and manage permissions.',
            pills: [
                { icon: 'bi-database-fill-gear', text: 'System Settings' },
                { icon: 'bi-people-fill', text: 'User Directory' },
                { icon: 'bi-key-fill', text: 'Access Management' },
                { icon: 'bi-journal-text', text: 'Audit Logging' },
                { icon: 'bi-cloud-arrow-up-fill', text: 'DB Backups' }
            ],
            stats: [
                { value: '100%', label: 'System Uptime' },
                { value: '14', label: 'Active Modules' },
                { value: '256-bit', label: 'Data Encryption' }
            ],
            themeClass: 'theme-admin'
        }
    };

    let activeRole = 'student'; // Student default as requested

    const roleTabBtns = document.querySelectorAll('.role-pill-btn');
    const roleBadgeTag = document.getElementById('roleBadgeTag');
    const toastElem = document.getElementById('loginToastNotification');
    const toastMsg = document.getElementById('loginToastMsg');

    // Left panel elements to animate and change
    const splitLeftPanel = document.querySelector('.split-left-panel');
    const leftPanelContent = document.querySelector('.left-panel-content');
    const brandIcon = document.querySelector('.brand-icon-box i');
    const brandTitle = document.querySelector('.brand-text-title');
    const brandSubtitle = document.querySelector('.brand-text-subtitle');
    const heroTitle = document.querySelector('.panel-hero-title');
    const heroDesc = document.querySelector('.panel-hero-desc');
    const pillsWrapper = document.querySelector('.panel-pills-wrapper');
    const statsRow = document.querySelector('.panel-stats-row');

    // Role Switching
    roleTabBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const selectedRole = this.getAttribute('data-role');
            if (!selectedRole || selectedRole === activeRole) return;

            // Active button state
            roleTabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Fade out current form pane and show target pane
            const currentPane = document.querySelector('.tab-fade-pane.active');
            const targetPane = document.getElementById(`pane-${selectedRole}`);

            if (currentPane) currentPane.classList.remove('active');
            if (targetPane) targetPane.classList.add('active');

            activeRole = selectedRole;
            const config = roleDetails[activeRole];

            // Update Badge Tag
            if (roleBadgeTag && config) {
                roleBadgeTag.innerHTML = `<i class="bi ${config.badgeIcon}"></i> ${config.badgeText}`;
            }

            // Animate Left Panel Content Switching
            if (leftPanelContent && splitLeftPanel && config) {
                // Add fade-out class to left-panel elements
                leftPanelContent.classList.add('left-content-fade-out');
                statsRow.classList.add('left-content-fade-out');

                setTimeout(() => {
                    // Update Text, Icon & HTML
                    if (brandIcon) brandIcon.className = `bi ${config.badgeIcon}`;
                    if (brandTitle) brandTitle.textContent = config.title;
                    if (brandSubtitle) brandSubtitle.textContent = config.subtitle;
                    if (heroTitle) heroTitle.innerHTML = config.headline;
                    if (heroDesc) heroDesc.textContent = config.desc;

                    // Update Pills
                    if (pillsWrapper) {
                        pillsWrapper.innerHTML = config.pills.map(pill =>
                            `<span class="panel-pill"><i class="bi ${pill.icon}"></i> ${pill.text}</span>`
                        ).join('');
                    }

                    // Update Stats
                    if (statsRow) {
                        statsRow.innerHTML = config.stats.map(stat =>
                            `<div>
                                <div class="stat-item-val">${stat.value}</div>
                                <div class="stat-item-lbl">${stat.label}</div>
                            </div>`
                        ).join('');
                    }

                    // Update left panel background class theme
                    splitLeftPanel.className = `split-left-panel ${config.themeClass}`;

                    // Fade back in
                    leftPanelContent.classList.remove('left-content-fade-out');
                    statsRow.classList.remove('left-content-fade-out');
                }, 250);
            }
        });
    });

    // Password Visibility Toggles
    const toggleEyeBtns = document.querySelectorAll('.btn-toggle-eye');
    toggleEyeBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const inputField = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (inputField) {
                if (inputField.type === 'password') {
                    inputField.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    inputField.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            }
        });
    });

    // Form Submit handling (For backend readiness - log role and prevent default for mock demo, but let submit proceed if needed)
    const forms = document.querySelectorAll('.split-login-form');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            // Note: In real backend integration, this event listener can be removed or used for async login.
            // For now, we simulate authentication transition.
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            const origHtml = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Authenticating...`;

            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = origHtml;

                if (activeRole === 'faculty') {
                    const idInput = document.getElementById('facultyId');
                    const passInput = document.getElementById('facultyPassword');
                    const idVal = idInput ? idInput.value.trim().toLowerCase() : '';
                    const passVal = passInput ? passInput.value.trim() : '';

                    if (idVal === 'faculty@login' && (passVal === 'faculty@123' || passVal === 'faculty123' || passVal === 'faculty@123')) {
                        window.location.href = 'faculty-dashboard.php';
                    } else {
                        showToast(`❌ Invalid Faculty credentials. Please try again.`, true);
                    }
                } else if (activeRole === 'student') {
                    showToast(`❌ Invalid Student ZPRN or Password.`, true);
                }
            }, 1000);
        });
    });

    // Helper Toast function
    function showToast(message, isError = false) {
        if (!toastElem) return;
        if (toastMsg) toastMsg.textContent = message;

        if (isError) {
            toastElem.style.background = '#ef4444';
            toastElem.style.boxShadow = '0 10px 25px rgba(239, 68, 68, 0.3)';
        } else {
            toastElem.style.background = '#10b981';
            toastElem.style.boxShadow = '0 10px 25px rgba(16, 185, 129, 0.3)';
        }

        toastElem.classList.add('show');
        setTimeout(() => {
            toastElem.classList.remove('show');
        }, 3000);
    }

    // 2. Interactive Background Motion Dots (Starfield Canvas)
    initInteractiveStarfield();
});

function initInteractiveStarfield() {
    const canvas = document.getElementById('loginInteractiveBgCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    let width = canvas.width = window.innerWidth;
    let height = canvas.height = window.innerHeight;

    let targetMouseX = width / 2;
    let targetMouseY = height / 2;
    let mouseX = width / 2;
    let mouseY = height / 2;

    const particleCount = Math.min(110, Math.floor((width * height) / 11000));
    const particles = [];

    const colors = [
        'rgba(56, 189, 248, ',  // Cyan
        'rgba(129, 140, 248, ', // Indigo
        'rgba(248, 250, 252, ', // White
        'rgba(14, 165, 233, '   // Sky Blue
    ];

    for (let i = 0; i < particleCount; i++) {
        particles.push({
            x: Math.random() * width,
            y: Math.random() * height,
            radius: Math.random() * 1.75 + 0.75,
            colorPrefix: colors[Math.floor(Math.random() * colors.length)],
            alpha: Math.random() * 0.65 + 0.2,
            twinkleSpeed: (Math.random() * 0.015 + 0.005) * (Math.random() < 0.5 ? 1 : -1),
            vx: (Math.random() - 0.5) * 0.35,
            vy: (Math.random() - 0.5) * 0.35,
            parallaxFactor: Math.random() * 0.045 + 0.015
        });
    }

    function resize() {
        if (!canvas) return;
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    }

    window.addEventListener('resize', resize);

    document.addEventListener('mousemove', (e) => {
        targetMouseX = e.clientX;
        targetMouseY = e.clientY;
    });

    function animate() {
        ctx.clearRect(0, 0, width, height);

        // Smooth cursor easing (Lerp)
        mouseX += (targetMouseX - mouseX) * 0.04;
        mouseY += (targetMouseY - mouseY) * 0.04;

        const offsetX = (mouseX - width / 2);
        const offsetY = (mouseY - height / 2);

        // Draw dynamic constellation connections
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const p1 = particles[i];
                const p2 = particles[j];

                const p1X = p1.x + offsetX * p1.parallaxFactor;
                const p1Y = p1.y + offsetY * p1.parallaxFactor;
                const p2X = p2.x + offsetX * p2.parallaxFactor;
                const p2Y = p2.y + offsetY * p2.parallaxFactor;

                const dx = p1X - p2X;
                const dy = p1Y - p2Y;
                const dist = Math.sqrt(dx * dx + dy * dy);

                if (dist < 115) {
                    const lineAlpha = (1 - dist / 115) * 0.14;
                    ctx.strokeStyle = `rgba(56, 189, 248, ${lineAlpha})`;
                    ctx.lineWidth = 0.6;
                    ctx.beginPath();
                    ctx.moveTo(p1X, p1Y);
                    ctx.lineTo(p2X, p2Y);
                    ctx.stroke();
                }
            }
        }

        // Render & update individual star dots
        particles.forEach(p => {
            p.x += p.vx;
            p.y += p.vy;

            // Wrap around edges seamlessly
            if (p.x < -20) p.x = width + 20;
            if (p.x > width + 20) p.x = -20;
            if (p.y < -20) p.y = height + 20;
            if (p.y > height + 20) p.y = -20;

            // Twinkle effect
            p.alpha += p.twinkleSpeed;
            if (p.alpha > 0.85 || p.alpha < 0.2) {
                p.twinkleSpeed = -p.twinkleSpeed;
            }

            const renderX = p.x + offsetX * p.parallaxFactor;
            const renderY = p.y + offsetY * p.parallaxFactor;

            // Outer soft glow halo
            ctx.beginPath();
            ctx.arc(renderX, renderY, p.radius * 2.4, 0, Math.PI * 2);
            ctx.fillStyle = p.colorPrefix + (p.alpha * 0.28) + ')';
            ctx.fill();

            // Core bright star dot
            ctx.beginPath();
            ctx.arc(renderX, renderY, p.radius, 0, Math.PI * 2);
            ctx.fillStyle = p.colorPrefix + p.alpha + ')';
            ctx.fill();
        });

        requestAnimationFrame(animate);
    }

    animate();
}
