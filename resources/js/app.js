import * as bootstrap from 'bootstrap';
import AOS from 'aos';

window.bootstrap = bootstrap;

document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 700,
        once: true,
        offset: 50,
    });

    // Animated statistics counters
    const counters = document.querySelectorAll('[data-counter]');
    if (counters.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const el = entry.target;
                const target = parseInt(el.getAttribute('data-counter'), 10);
                const suffix = el.getAttribute('data-suffix') || '';
                let current = 0;
                const step = Math.max(1, Math.ceil(target / 45));
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        el.textContent = target.toLocaleString() + suffix;
                        clearInterval(timer);
                    } else {
                        el.textContent = current.toLocaleString() + suffix;
                    }
                }, 22);
                obs.unobserve(el);
            });
        }, { threshold: 0.25 });

        counters.forEach((el) => observer.observe(el));
    }

    // Promotional countdown timers
    document.querySelectorAll('[data-countdown]').forEach((container) => {
        const targetDate = new Date(container.getAttribute('data-countdown')).getTime();
        if (isNaN(targetDate)) return;

        const update = () => {
            const now = Date.now();
            const diff = Math.max(0, targetDate - now);
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
            const mins = Math.floor((diff / (1000 * 60)) % 60);
            const secs = Math.floor((diff / 1000) % 60);

            const dEl = container.querySelector('[data-cd-days]');
            const hEl = container.querySelector('[data-cd-hours]');
            const mEl = container.querySelector('[data-cd-mins]');
            const sEl = container.querySelector('[data-cd-secs]');

            if (dEl) dEl.textContent = String(days).padStart(2, '0');
            if (hEl) hEl.textContent = String(hours).padStart(2, '0');
            if (mEl) mEl.textContent = String(mins).padStart(2, '0');
            if (sEl) sEl.textContent = String(secs).padStart(2, '0');
        };

        update();
        setInterval(update, 1000);
    });

    // Track WhatsApp clicks
    document.querySelectorAll('[data-wa-track]').forEach((link) => {
        link.addEventListener('click', () => {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const payload = {
                context_type: link.getAttribute('data-wa-type') || 'floating',
                context_id: link.getAttribute('data-wa-id') || null,
                context_label: link.getAttribute('data-wa-label') || null,
                page_url: window.location.href,
            };

            fetch('/whatsapp-track', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
                keepalive: true,
            }).catch(() => {});
        });
    });

    // Promotional Popup Modal (shows once per session on homepage)
    const promoModalEl = document.getElementById('promoPopupModal');
    if (promoModalEl && !sessionStorage.getItem('el_promo_popup_seen')) {
        setTimeout(() => {
            const modal = new bootstrap.Modal(promoModalEl);
            modal.show();
            sessionStorage.setItem('el_promo_popup_seen', '1');
        }, 4500);
    }
});
