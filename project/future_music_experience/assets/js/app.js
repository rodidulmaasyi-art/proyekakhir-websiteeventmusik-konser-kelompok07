document.addEventListener('DOMContentLoaded', () => {

    // Dark / light mode toggle
    const applyTheme = (theme) => {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('fmx_theme', theme);
        document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
            const icon = btn.querySelector('[data-theme-icon]');
            const label = btn.querySelector('[data-theme-label]');
            if (icon) icon.textContent = theme === 'light' ? '☀' : '☾';
            if (label) label.textContent = theme === 'light' ? 'Light' : 'Dark';
        });
    };
    applyTheme(localStorage.getItem('fmx_theme') || 'dark');
    document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
        btn.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme') || 'dark';
            applyTheme(current === 'dark' ? 'light' : 'dark');
        });
    });

    // Tailwind-style interactive form validation
    const makeClientError = (message) => {
        const p = document.createElement('p');
        p.className = 'client-field-error';
        p.textContent = message;
        return p;
    };

    const validateField = (field) => {
        const wrap = field.closest('[data-field-wrap]') || field.parentElement;
        if (wrap) wrap.querySelectorAll('.client-field-error').forEach(el => el.remove());

        const label = field.dataset.label || field.getAttribute('name') || 'Field';
        const value = field.value.trim();
        let message = '';

        if (field.dataset.required === 'true' && value === '') {
            message = `${label} wajib diisi.`;
        } else if (field.dataset.email === 'true' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            message = `${label} tidak valid.`;
        } else if (field.dataset.min && value.length < Number(field.dataset.min)) {
            message = `${label} minimal ${field.dataset.min} karakter.`;
        } else if (field.dataset.max && value.length > Number(field.dataset.max)) {
            message = `${label} maksimal ${field.dataset.max} karakter.`;
        } else if (field.dataset.minNumber && Number(value) < Number(field.dataset.minNumber)) {
            message = `${label} minimal ${field.dataset.minNumber}.`;
        } else if (field.dataset.maxNumber && Number(value) > Number(field.dataset.maxNumber)) {
            message = `${label} maksimal ${field.dataset.maxNumber}.`;
        } else if (field.dataset.match) {
            const target = document.querySelector(field.dataset.match);
            if (target && value !== target.value) message = `${label} harus sama dengan password.`;
        }

        field.classList.remove('validation-invalid', 'validation-valid');
        if (message) {
            field.classList.add('validation-invalid');
            if (wrap) wrap.appendChild(makeClientError(message));
            return false;
        }

        if (value !== '') field.classList.add('validation-valid');
        return true;
    };

    document.querySelectorAll('[data-validate-form]').forEach(form => {
        const fields = form.querySelectorAll('[data-validate]');
        fields.forEach(field => {
            field.addEventListener('blur', () => validateField(field));
            field.addEventListener('input', () => {
                if (field.classList.contains('validation-invalid')) validateField(field);
            });
        });

        form.addEventListener('submit', e => {
            let ok = true;
            fields.forEach(field => {
                if (!validateField(field)) ok = false;
            });

            if (!ok) {
                e.preventDefault();
                const first = form.querySelector('.validation-invalid');
                if (first) first.focus();
            }
        });
    });

    const navToggle = document.querySelector('[data-mobile-toggle]');
    const navMenu = document.querySelector('[data-nav-menu]');
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', () => navMenu.classList.toggle('open'));
    }

    // Parallax hero
    const hero = document.querySelector('[data-parallax]');
    if (hero) {
        window.addEventListener('scroll', () => {
            const y = window.scrollY * 0.18;
            hero.style.transform = `translateY(${y}px)`;
        }, {passive:true});
    }

    // Countdown
    document.querySelectorAll('[data-countdown]').forEach(el => {
        const target = new Date(el.dataset.countdown).getTime();
        const boxes = el.querySelectorAll('strong');
        const tick = () => {
            const diff = Math.max(0, target - Date.now());
            const d = Math.floor(diff / (1000 * 60 * 60 * 24));
            const h = Math.floor((diff / (1000 * 60 * 60)) % 24);
            const m = Math.floor((diff / (1000 * 60)) % 60);
            const s = Math.floor((diff / 1000) % 60);
            [d,h,m,s].forEach((v,i) => { if (boxes[i]) boxes[i].textContent = String(v).padStart(2,'0'); });
        };
        tick(); setInterval(tick, 1000);
    });

    // Event filter
    const search = document.querySelector('[data-search]');
    if (search) {
        search.addEventListener('input', () => {
            const q = search.value.toLowerCase();
            document.querySelectorAll('[data-card]').forEach(card => {
                card.style.display = card.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    }

    // Wishlist optimistic UI
    document.querySelectorAll('[data-wishlist-form]').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = form.querySelector('button');
            const data = new FormData(form);
            try {
                const res = await fetch(form.action, { method:'POST', body:data });
                const json = await res.json();
                if (json.ok) {
                    btn.classList.toggle('active', json.status === 'added');
                    btn.textContent = json.status === 'added' ? '♥ Saved' : '♡ Wishlist';
                } else {
                    alert(json.message || 'Gagal menyimpan wishlist');
                }
            } catch (err) {
                form.submit();
            }
        });
    });

    // AI Chat Assistant demo
    const chat = document.querySelector('[data-ai-chat]');
    const openChat = document.querySelector('[data-chat-open]');
    const closeChat = document.querySelector('[data-chat-close]');
    const chatForm = document.querySelector('[data-chat-form]');
    const chatBody = document.querySelector('[data-chat-body]');
    if (openChat && chat) openChat.addEventListener('click', () => chat.classList.add('open'));
    if (closeChat && chat) closeChat.addEventListener('click', () => chat.classList.remove('open'));
    if (chatForm && chatBody) {
        chatForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const input = chatForm.querySelector('input');
            const text = input.value.trim();
            if (!text) return;
            const user = document.createElement('div');
            user.className = 'user-msg';
            user.textContent = text;
            chatBody.appendChild(user);

            const reply = document.createElement('div');
            reply.className = 'bot';
            const t = text.toLowerCase();
            if (t.includes('edm')) reply.textContent = 'Rekomendasi AI: Neon Pulse Night dan Future Music Experience cocok untuk EDM.';
            else if (t.includes('techno')) reply.textContent = 'Rekomendasi AI: Vortex Techno Arena punya laser grid dan QR smart pass.';
            else if (t.includes('pop')) reply.textContent = 'Rekomendasi AI: Hologram Pop Fest cocok untuk pop futuristik.';
            else reply.textContent = 'AI merekomendasikan Future Music Experience 2026 karena paling lengkap dan featured.';
            setTimeout(() => {
                chatBody.appendChild(reply);
                chatBody.scrollTop = chatBody.scrollHeight;
            }, 350);
            input.value = '';
            chatBody.scrollTop = chatBody.scrollHeight;
        });
    }

    // Language toggle demo
    const dict = {
        en: {
            nav_home:'Home', nav_events:'Events', nav_artists:'Artists', nav_schedule:'Schedule', nav_gallery:'Gallery', nav_membership:'Membership', nav_contact:'Contact'
        },
        id: {
            nav_home:'Home', nav_events:'Events', nav_artists:'Artists', nav_schedule:'Schedule', nav_gallery:'Gallery', nav_membership:'Membership', nav_contact:'Contact'
        }
    };
    let lang = localStorage.getItem('fmx_lang') || 'id';
    const toggle = document.querySelector('[data-lang-toggle]');
    if (toggle) {
        toggle.addEventListener('click', () => {
            lang = lang === 'id' ? 'en' : 'id';
            localStorage.setItem('fmx_lang', lang);
            document.documentElement.dataset.lang = lang;
            alert(lang === 'en' ? 'Language switched to English.' : 'Bahasa diganti ke Indonesia.');
        });
    }

    // Form confirm delete
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', e => {
            if (!confirm(btn.dataset.confirm || 'Yakin ingin menghapus data ini?')) e.preventDefault();
        });
    });

    // PWA
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register((window.FMX_BASE_URL || '') + '/service-worker.js').catch(() => {});
    }
});
