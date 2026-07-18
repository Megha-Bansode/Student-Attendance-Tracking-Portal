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
});
