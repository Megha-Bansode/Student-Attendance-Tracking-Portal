/**
 * Scoped JS Interactivity & Starfield Physics for Split Login Page
 * Does not affect index.php or other pages
 */

document.addEventListener('DOMContentLoaded', function() {
    // 1. Role Credentials Map
    const roleCredentials = {
        student: {
            badgeText: 'STUDENT PORTAL',
            badgeClass: 'bg-success-subtle text-success',
            badgeIcon: 'bi-mortarboard-fill',
            demoEmail: '20241004',
            demoPass: 'Student@123',
            emailInputId: 'studentPrn',
            passInputId: 'studentPassword',
            label: 'PRN / Roll Number'
        },
        faculty: {
            badgeText: 'FACULTY PORTAL',
            badgeClass: 'bg-info-subtle text-info',
            badgeIcon: 'bi-briefcase-fill',
            demoEmail: 'faculty@college.edu.in',
            demoPass: 'Faculty@123',
            emailInputId: 'facultyId',
            passInputId: 'facultyPassword',
            label: 'Faculty ID / Email'
        },
        admin: {
            badgeText: 'SUPER ADMIN',
            badgeClass: 'bg-primary-subtle text-primary',
            badgeIcon: 'bi-shield-check',
            demoEmail: 'admin@college.edu.in',
            demoPass: 'Admin@123',
            emailInputId: 'adminEmail',
            passInputId: 'adminPassword',
            label: 'Administrator Email'
        }
    };

    let activeRole = 'student'; // Student default as requested

    const roleTabBtns = document.querySelectorAll('.role-pill-btn');
    const roleBadgeTag = document.getElementById('roleBadgeTag');
    const demoBannerText = document.getElementById('demoBannerText');
    const btnAutofill = document.getElementById('btnAutofillDemo');
    const toastElem = document.getElementById('loginToastNotification');
    const toastMsg = document.getElementById('loginToastMsg');

    // Role Switching
    roleTabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
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
            const config = roleCredentials[activeRole];

            // Update Badge Tag
            if (roleBadgeTag && config) {
                roleBadgeTag.innerHTML = `<i class="bi ${config.badgeIcon}"></i> ${config.badgeText}`;
            }

            // Update Demo Text
            if (demoBannerText && config) {
                demoBannerText.textContent = `Demo: ${config.demoEmail} / ${config.demoPass}`;
            }
        });
    });

    // Auto-fill button click
    if (btnAutofill) {
        btnAutofill.addEventListener('click', function() {
            const config = roleCredentials[activeRole];
            if (!config) return;

            const emailField = document.getElementById(config.emailInputId);
            const passField = document.getElementById(config.passInputId);

            if (emailField) {
                emailField.value = config.demoEmail;
                emailField.classList.add('is-valid');
            }
            if (passField) {
                passField.value = config.demoPass;
                passField.classList.add('is-valid');
            }

            showToast('✨ Credentials auto-filled!');
        });
    }

    // Password Visibility Toggles
    const toggleEyeBtns = document.querySelectorAll('.btn-toggle-eye');
    toggleEyeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
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

    // Form Submit handling (Demo Sign In)
    const forms = document.querySelectorAll('.split-login-form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            const origHtml = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Authenticating...`;

            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = origHtml;
                showToast(`🎉 Logged in successfully as ${activeRole.toUpperCase()}!`);
            }, 1000);
        });
    });

    // Helper Toast function
    function showToast(message) {
        if (!toastElem) return;
        if (toastMsg) toastMsg.textContent = message;
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
