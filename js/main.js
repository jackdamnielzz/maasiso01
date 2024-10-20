document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navLinks = document.querySelector('.nav-links');
    if (mobileMenuToggle && navLinks) {
        mobileMenuToggle.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            this.classList.toggle('active');
        });
    }

    // Fade-in effect
    const fadeElements = document.querySelectorAll('.fade-in');
    const fadeInObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-visible');
                fadeInObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    fadeElements.forEach(element => {
        fadeInObserver.observe(element);
    });

    console.log('MaasISO website loaded successfully');
});
