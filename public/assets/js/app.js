document.addEventListener('DOMContentLoaded', () => {
    initDefaultMenu();
    initLandingNavbar();
    initLandingScrollAnimations();
    initLandingMobileMenu();
    initServiceCardDelays();
});

function initDefaultMenu() {
    const toggle = document.querySelector('[data-menu-toggle]');
    const menu = document.querySelector('[data-menu]');

    if (toggle && menu && !toggle.id) {
        toggle.addEventListener('click', () => {
            const expanded = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', String(!expanded));
            menu.classList.toggle('is-open');
        });
    }
}

function initLandingNavbar() {
    const navbar = document.getElementById('navbar');
    if (!navbar) return;

    const handleScroll = () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
}

function initLandingMobileMenu() {
    const toggle = document.getElementById('menu-toggle');
    const close = document.getElementById('menu-close');
    const menu = document.getElementById('mobile-menu');
    const overlay = document.getElementById('menu-overlay');

    if (!toggle || !menu) return;

    const openMenu = () => {
        menu.classList.remove('translate-x-full');
        menu.classList.add('translate-x-0');
        overlay?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        toggle.setAttribute('aria-expanded', 'true');
    };

    const closeMenu = () => {
        menu.classList.remove('translate-x-0');
        menu.classList.add('translate-x-full');
        overlay?.classList.add('hidden');
        document.body.style.overflow = '';
        toggle.setAttribute('aria-expanded', 'false');
    };

    toggle.addEventListener('click', () => {
        const isOpen = !menu.classList.contains('translate-x-full');
        isOpen ? closeMenu() : openMenu();
    });

    close?.addEventListener('click', closeMenu);
    overlay?.addEventListener('click', closeMenu);
    menu.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));
}

function initLandingScrollAnimations() {
    const elements = document.querySelectorAll('.scroll-animate, .scroll-animate-left, .scroll-animate-right');
    if (!elements.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, index * 100);
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px',
    });

    elements.forEach((element) => observer.observe(element));
}

function initServiceCardDelays() {
    const cards = document.querySelectorAll('.service-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.08}s`;
    });
}
