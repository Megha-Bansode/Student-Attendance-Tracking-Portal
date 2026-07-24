// Splash Screen Handler
(function() {
    const path = window.location.pathname.toLowerCase();
    const isIndex = path.endsWith('index.php') || path.endsWith('student-attendance-tracking-portal') || path.endsWith('student-attendance-tracking-portal/') || path.endsWith('/');
    
    if (isIndex) {
        if (!sessionStorage.getItem('splash_shown')) {
            document.documentElement.classList.add('splash-active');
            document.addEventListener('DOMContentLoaded', () => {
                document.body.classList.add('splash-active');
                setTimeout(() => {
                    const splash = document.getElementById('splash-root');
                    if (splash) {
                        splash.remove();
                    }
                    document.body.classList.remove('splash-active');
                    document.documentElement.classList.remove('splash-active');
                }, 4200);
            });
            sessionStorage.setItem('splash_shown', 'true');
        } else {
            document.addEventListener('DOMContentLoaded', () => {
                const splash = document.getElementById('splash-root');
                if (splash) {
                    splash.remove();
                }
            });
        }
    } else {
        document.addEventListener('DOMContentLoaded', () => {
            const splash = document.getElementById('splash-root');
            if (splash) {
                splash.remove();
            }
        });
    }
})();

document.addEventListener('DOMContentLoaded', () => {
    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    const updateNavbarClass = () => {
        if (window.scrollY > 30) {
            navbar.classList.add('shadow-lg');
            navbar.style.padding = '0.5rem 0';
        } else {
            navbar.classList.remove('shadow-lg');
            navbar.style.padding = '1rem 0';
        }
    };
    window.addEventListener('scroll', updateNavbarClass);
    updateNavbarClass(); // Initial run

    // Smooth scrolling for navigation links
    const scrollLinks = document.querySelectorAll('a[href^="#"]');
    scrollLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const targetId = link.getAttribute('href');
            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                // Collapse mobile menu if open
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                    const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                    if (bsCollapse) {
                        bsCollapse.hide();
                    }
                }

                // Scroll to target element with offset for header
                const headerHeight = navbar.offsetHeight || 70;
                const elementPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
                const offsetPosition = elementPosition - headerHeight;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Active link highlighting on scroll
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');
    
    const highlightNavLink = () => {
        let scrollY = window.pageYOffset;
        const headerHeight = navbar.offsetHeight || 70;

        sections.forEach(current => {
            const sectionHeight = current.offsetHeight;
            const sectionTop = current.offsetTop - headerHeight - 20;
            const sectionId = current.getAttribute('id');

            if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${sectionId}`) {
                        link.classList.add('active');
                    }
                });
            }
        });

        // Handle active state for Home when scrolled to top
        if (scrollY < 100) {
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#home') {
                    link.classList.add('active');
                }
            });
        }
    };
    window.addEventListener('scroll', highlightNavLink);
    highlightNavLink(); // Initial run

    // Micro-animation for counter statistics
    const counters = document.querySelectorAll('.counter-number');
    const animateCounters = () => {
        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText.replace(/[+%]/g, '');
                
                // Determine speed of increment
                const speed = 200; // lower is slower
                const increment = target / speed;

                if (count < target) {
                    const newValue = Math.ceil(count + increment);
                    if (counter.getAttribute('data-target').includes('%')) {
                        counter.innerText = newValue + '%';
                    } else if (counter.getAttribute('data-target').includes('+')) {
                        counter.innerText = newValue + '+';
                    } else {
                        counter.innerText = newValue;
                    }
                    setTimeout(updateCount, 10);
                } else {
                    counter.innerText = counter.getAttribute('data-target');
                }
            };
            
            // Trigger animation
            updateCount();
        });
    };

    // Intersection observer for counters to start animation only when visible
    const observerOptions = {
        threshold: 0.5
    };

    const statsSection = document.querySelector('.stats-counter-section');
    if (statsSection) {
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        observer.observe(statsSection);
    }

    // 2. Interactive Multi-Device Mockup Simulator
    const deviceConfigs = {
        laptop: {
            frameClass: 'device-mockup-laptop',
            badge: '<i class="bi bi-laptop"></i> Full Admin Control',
            badgeClass: 'bg-primary-subtle text-primary border border-primary-subtle',
            desc: 'Access the complete attendance ledger, configure departments, manage faculty registers, and download overall reports in real-time.',
            html: `
                <div class="p-2" style="font-family: var(--font-body);">
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
                        <span class="fw-bold" style="font-size:0.75rem; color:var(--primary);">AttendEase Admin Console</span>
                        <span class="badge bg-success" style="font-size:0.5rem;">Online</span>
                    </div>
                    <div class="row g-2">
                        <div class="col-4">
                            <div class="p-1 rounded text-center" style="background:#f1f5f9; border:1px solid #e2e8f0;">
                                <div class="text-muted" style="font-size:0.5rem; text-transform:uppercase;">Students</div>
                                <div class="fw-bold text-dark" style="font-size:0.8rem;">1,540</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-1 rounded text-center" style="background:#f1f5f9; border:1px solid #e2e8f0;">
                                <div class="text-muted" style="font-size:0.5rem; text-transform:uppercase;">Faculty</div>
                                <div class="fw-bold text-dark" style="font-size:0.8rem;">120</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-1 rounded text-center" style="background:#f1f5f9; border:1px solid #e2e8f0;">
                                <div class="text-muted" style="font-size:0.5rem; text-transform:uppercase;">Avg Attendance</div>
                                <div class="fw-bold text-success" style="font-size:0.8rem;">95.0%</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 p-2 rounded" style="background:#1e293b; color:#cbd5e1; font-size:0.6rem;">
                        <div class="d-flex justify-content-between mb-1">
                            <span>System Logs</span>
                            <span class="text-warning">Warnings: 2</span>
                        </div>
                        <div style="font-family:monospace; line-height:1.2;">
                            <div>[INFO] Auto-generated compliance report.</div>
                            <div>[WARN] Aarav Mehta attendance dropped below 75%.</div>
                            <div>[INFO] Faculty portal synchronized successfully.</div>
                        </div>
                    </div>
                </div>
            `
        },
        tablet: {
            frameClass: 'device-mockup-tablet',
            badge: '<i class="bi bi-tablet"></i> Department Management',
            badgeClass: 'bg-info-subtle text-info border border-info-subtle',
            desc: 'Review stats per department, track class progress, edit roles, and customize active thresholds from the department workspace.',
            html: `
                <div class="p-3" style="font-family: var(--font-body);">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-1 border-bottom">
                        <span class="fw-bold" style="font-size:0.8rem; color:#0ea5e9;">Department Overview</span>
                        <i class="bi bi-grid-fill" style="color:#0ea5e9;"></i>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="p-2 rounded bg-white border d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold" style="font-size:0.7rem;">Information Technology</div>
                                <div class="text-muted" style="font-size:0.55rem;">420 Students • 15 Classes</div>
                            </div>
                            <span class="badge bg-success-subtle text-success" style="font-size:0.55rem;">96.2% Avg</span>
                        </div>
                        <div class="p-2 rounded bg-white border d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold" style="font-size:0.7rem;">Computer Science</div>
                                <div class="text-muted" style="font-size:0.55rem;">510 Students • 18 Classes</div>
                            </div>
                            <span class="badge bg-success-subtle text-success" style="font-size:0.55rem;">94.8% Avg</span>
                        </div>
                        <div class="p-2 rounded bg-white border d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold" style="font-size:0.7rem;">Electronics & Telecom</div>
                                <div class="text-muted" style="font-size:0.55rem;">320 Students • 10 Classes</div>
                            </div>
                            <span class="badge bg-warning-subtle text-warning" style="font-size:0.55rem;">89.5% Avg</span>
                        </div>
                    </div>
                </div>
            `
        },
        phone: {
            frameClass: 'device-mockup-phone',
            badge: '<i class="bi bi-phone"></i> Mobile Punching',
            badgeClass: 'bg-success-subtle text-success border border-success-subtle',
            desc: 'Faculty members can record attendance instantly from their mobile browser. Students can track their personal dashboards on the go.',
            html: `
                <div class="p-2 text-center" style="font-family: var(--font-body);">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-1">
                        <span class="fw-bold" style="font-size:0.7rem; color:var(--success);">Student Check-In</span>
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center mx-auto my-3" style="width:70px; height:70px; border:2px solid var(--success);">
                        <i class="bi bi-check-lg" style="font-size:2.2rem;"></i>
                    </div>
                    <h6 class="mb-1 text-dark" style="font-size:0.75rem;">Checked In Successfully</h6>
                    <div class="text-muted mb-3" style="font-size:0.55rem;">Today, 09:12 AM</div>
                    
                    <div class="p-2 bg-light rounded text-start" style="font-size:0.6rem; border:1px solid #e2e8f0;">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Subject:</span>
                            <span class="fw-bold">Software Eng. (IT-402)</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Professor:</span>
                            <span class="fw-bold">Dr. R. K. Patel</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Personal Ratio:</span>
                            <span class="text-success fw-bold">88.5% (Safe)</span>
                        </div>
                    </div>
                </div>
            `
        },
        watch: {
            frameClass: 'device-mockup-watch',
            badge: '<i class="bi bi-smartwatch"></i> Quick Notifications',
            badgeClass: 'bg-warning-subtle text-warning border border-warning-subtle',
            desc: 'Stay informed with instant push alerts, low attendance warnings, and real-time verification notifications delivered directly to your wrist.',
            html: `
                <div class="p-2 text-center text-white" style="font-family: var(--font-body); background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); min-height:100%; border-radius:18px;">
                    <div style="font-size:0.55rem; color:#38bdf8; font-weight:700;" class="mb-1">ATTENDEASE</div>
                    <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center mx-auto my-1" style="width:36px; height:36px;">
                        <i class="bi bi-exclamation-circle-fill" style="font-size:1.1rem;"></i>
                    </div>
                    <div class="fw-bold" style="font-size:0.55rem; line-height:1.2; color:#f1f5f9;">Attendance Alert</div>
                    <div class="text-warning-emphasis" style="font-size:0.48rem; line-height:1.2;">Your average has dropped below 75% threshold!</div>
                    <div style="font-size:0.4rem; color:#94a3b8;" class="mt-1">10m ago</div>
                </div>
            `
        }
    };

    const deviceFrame = document.getElementById('deviceMockupFrame');
    const screenContent = document.getElementById('mockupScreenContent');
    const featureTag = document.getElementById('deviceFeatureTag');
    const featureDesc = document.getElementById('deviceFeatureDesc');
    const navButtons = document.querySelectorAll('.device-nav-btn');

    const updateDeviceMockup = (device) => {
        const config = deviceConfigs[device];
        if (!config || !deviceFrame || !screenContent) return;

        // Apply transition styling
        screenContent.style.opacity = 0;
        screenContent.style.transform = 'scale(0.95)';

        setTimeout(() => {
            // Remove old classes and add new one
            deviceFrame.className = config.frameClass;
            screenContent.innerHTML = config.html;
            
            if (featureTag) {
                featureTag.className = `badge ${config.badgeClass} rounded-pill px-3 py-1 mb-2`;
                featureTag.innerHTML = config.badge;
            }
            if (featureDesc) {
                featureDesc.textContent = config.desc;
            }

            screenContent.style.transition = 'all 0.3s ease';
            screenContent.style.opacity = 1;
            screenContent.style.transform = 'scale(1)';
        }, 150);
    };

    // Initialize switcher buttons
    navButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            navButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            updateDeviceMockup(this.getAttribute('data-device'));
        });
    });

    // Run initial mockup setup
    updateDeviceMockup('laptop');

    // 3. Interactive Gravity Particle Background Simulation (Antigravity Style)
    const initGravityCanvas = () => {
        const canvas = document.getElementById('gravity-particle-canvas');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        let width = canvas.width = canvas.parentElement.offsetWidth;
        let height = canvas.height = canvas.parentElement.offsetHeight;

        let particles = [];
        const colors = [
            'rgba(234, 67, 53, ',   // Red
            'rgba(251, 188, 5, ',   // Yellow
            'rgba(52, 168, 83, ',   // Green
            'rgba(66, 133, 244, ',  // Blue
            'rgba(161, 66, 244, '   // Purple
        ];

        class Particle {
            constructor() {
                this.x = Math.random() * width;
                this.y = Math.random() * height;
                this.radius = Math.random() * 2 + 1.2;
                this.colorPrefix = colors[Math.floor(Math.random() * colors.length)];
                this.alpha = Math.random() * 0.5 + 0.3;
                this.angle = Math.random() * Math.PI * 2;
                this.speed = Math.random() * 0.4 + 0.2;
                this.vx = Math.cos(this.angle) * this.speed;
                this.vy = Math.sin(this.angle) * this.speed;
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                ctx.fillStyle = this.colorPrefix + this.alpha + ')';
                ctx.fill();
            }

            update(mouseX, mouseY) {
                // Natural movement
                this.x += this.vx;
                this.y += this.vy;

                // Bounce off bounds
                if (this.x < 0 || this.x > width) this.vx = -this.vx;
                if (this.y < 0 || this.y > height) this.vy = -this.vy;

                // Gravitational pull & swirl effect if mouse is active
                if (mouseX !== undefined && mouseY !== undefined) {
                    const dx = mouseX - this.x;
                    const dy = mouseY - this.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    const gravityRadius = 250;

                    if (dist < gravityRadius) {
                        const force = (gravityRadius - dist) / gravityRadius;
                        // Attract towards mouse
                        this.x += (dx / dist) * force * 1.5;
                        this.y += (dy / dist) * force * 1.5;

                        // Swirling/orbit rotation around mouse
                        const angle = Math.atan2(dy, dx);
                        this.x += Math.sin(angle) * force * 1.2;
                        this.y -= Math.cos(angle) * force * 1.2;
                    }
                }
            }
        }

        const createParticles = () => {
            particles = [];
            const count = Math.min(Math.floor(width * height / 8000), 120);
            for (let i = 0; i < count; i++) {
                particles.push(new Particle());
            }
        };

        createParticles();

        let mouseX, mouseY;
        const heroSection = document.getElementById('home');
        if (heroSection) {
            heroSection.addEventListener('mousemove', (e) => {
                const rect = canvas.getBoundingClientRect();
                mouseX = e.clientX - rect.left;
                mouseY = e.clientY - rect.top;
            });
            heroSection.addEventListener('mouseleave', () => {
                mouseX = undefined;
                mouseY = undefined;
            });
        }

        const resize = () => {
            if (!canvas.parentElement) return;
            width = canvas.width = canvas.parentElement.offsetWidth;
            height = canvas.height = canvas.parentElement.offsetHeight;
            createParticles();
        };
        window.addEventListener('resize', resize);

        const animate = () => {
            ctx.clearRect(0, 0, width, height);
            particles.forEach(p => {
                p.update(mouseX, mouseY);
                p.draw();
            });
            requestAnimationFrame(animate);
        };
        animate();
    };
    initGravityCanvas();

});
