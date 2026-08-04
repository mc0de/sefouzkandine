/**
 * Šefo Užkandinė storefront behaviour: scroll reveals, sticky header state
 * and the mobile navigation panel. No dependencies on purpose.
 */

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/** Reveal elements as they enter the viewport. */
function initReveals() {
    const targets = document.querySelectorAll('.sefo-reveal');

    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        targets.forEach((target) => target.classList.add('is-in'));

        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (! entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-in');
                observer.unobserve(entry.target);
            });
        },
        { rootMargin: '0px 0px -12% 0px', threshold: 0.12 },
    );

    targets.forEach((target) => observer.observe(target));
}

/** Add a shadow + compact the logo once the page leaves the top. */
function initHeader() {
    const header = document.querySelector('[data-site-header]');

    if (! header) {
        return;
    }

    const sync = () => header.classList.toggle('is-stuck', window.scrollY > 24);

    sync();
    window.addEventListener('scroll', sync, { passive: true });
}

/** Toggle the mobile navigation panel. */
function initMobileNav() {
    const toggle = document.querySelector('[data-nav-toggle]');
    const panel = document.querySelector('[data-nav-panel]');

    if (! toggle || ! panel) {
        return;
    }

    const setOpen = (open) => {
        panel.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    };

    toggle.addEventListener('click', () => {
        setOpen(! panel.classList.contains('is-open'));
    });

    panel.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setOpen(false));
    });
}

initReveals();
initHeader();
initMobileNav();
