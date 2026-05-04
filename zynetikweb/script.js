// ==========================================
// ZYNETIK WEB - Script Principal
// ==========================================

// --- Toast Notifications ---
function showToast(message, type = 'info') {
    console.log('Toast:', message, type);
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    let icon = 'info';
    if (type === 'success') icon = 'check-circle';
    if (type === 'error') icon = 'alert-circle';
    if (type === 'warning') icon = 'alert-triangle';

    toast.innerHTML = `
        <i data-lucide="${icon}"></i>
        <div class="toast-content">${message}</div>
    `;

    container.appendChild(toast);
    
    if (window.lucide) {
        lucide.createIcons({
            attrs: { class: 'lucide' },
            nameAttr: 'data-lucide'
        });
    }

    // Force reflow
    toast.offsetHeight;
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 4000);
}

const isTouchDevice = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);

// --- Sound Effects (Web Audio API) ---
const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
// --- Sound Effects (Supprimés selon demande) ---
function playClickSound() {
    // Désactivé
}

// Trigger sound on any interaction to unlock audio
['mousedown', 'touchstart', 'keydown'].forEach(evt => {
    document.addEventListener(evt, () => {
        if (audioCtx.state === 'suspended') audioCtx.resume();
    }, { once: true });
});

document.addEventListener('click', (e) => {
    if (e.target.closest('a, button, .filter-btn, .magnetic-btn, .portfolio-card')) {
        playClickSound();
    }
});

// --- Premium UI Extras ---
(function initPremiumExtras() {
    // 1. Favicon (Tech icon)
    const favicon = document.querySelector('link[rel="icon"]') || document.createElement('link');
    favicon.rel = 'icon';
    favicon.href = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2300d2ff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='16 18 22 12 16 6'%3E%3C/polyline%3E%3Cpolyline points='8 6 2 12 8 18'%3E%3C/polyline%3E%3C/svg%3E";
    document.head.appendChild(favicon);

    // 2. Noise Overlay
    const noise = document.createElement('div');
    noise.classList.add('noise');
    document.body.appendChild(noise);

    // 3. Reading Progress Bar
    const progressBar = document.createElement('div');
    progressBar.classList.add('reading-progress');
    document.body.appendChild(progressBar);
    window.addEventListener('scroll', () => {
        const winScroll = document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        progressBar.style.width = scrolled + "%";
    });

    // 4. Data Streams Background
    const streamsContainer = document.createElement('div');
    streamsContainer.classList.add('data-streams');
    document.body.prepend(streamsContainer);
    for (let i = 0; i < 15; i++) {
        const stream = document.createElement('div');
        stream.classList.add('stream');
        stream.style.top = Math.random() * 100 + 'vh';
        stream.style.animationDuration = (Math.random() * 4 + 3) + 's';
        stream.style.animationDelay = (Math.random() * 10) + 's';
        streamsContainer.appendChild(stream);
    }

    // 5. Floating Tech Icons
    const iconContainer = document.createElement('div');
    iconContainer.style.cssText = "position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:-1;opacity:0.1;";
    document.body.appendChild(iconContainer);
    const icons = ['code', 'server', 'database', 'cpu', 'terminal', 'cloud'];
    for(let i=0; i<8; i++) {
        const icon = document.createElement('div');
        icon.innerHTML = `<i data-lucide="${icons[Math.floor(Math.random()*icons.length)]}"></i>`;
        icon.style.cssText = `position:absolute;top:${Math.random()*100}%;left:${Math.random()*100}%;animation:floatingIcon ${10+Math.random()*10}s ease-in-out infinite alternate;`;
        iconContainer.appendChild(icon);
    }
})();

// --- Cursor, Cards & Interactions (Desktop Only) ---
if (!isTouchDevice) {
    const cursor = document.createElement('div');
    cursor.classList.add('custom-cursor');
    cursor.style.opacity = '0';
    document.body.appendChild(cursor);

    let lastX = 0, lastY = 0;
    
    document.addEventListener('mousemove', (e) => {
        // Cursor Position
        cursor.style.opacity = '1';
        cursor.style.left = e.clientX + 'px';
        cursor.style.top = e.clientY + 'px';

        // Cursor Particle Trail
        const dist = Math.hypot(e.clientX - lastX, e.clientY - lastY);
        if (dist > 30) {
            const p = document.createElement('div');
            p.classList.add('cursor-particle');
            p.style.left = e.clientX + 'px';
            p.style.top = e.clientY + 'px';
            document.body.appendChild(p);
            setTimeout(() => p.remove(), 600);
            lastX = e.clientX; lastY = e.clientY;
        }

        // Card Glow Effect
        document.querySelectorAll('.service-card, .pricing-card, .portfolio-card').forEach(card => {
            const rect = card.getBoundingClientRect();
            card.style.setProperty('--x', `${e.clientX - rect.left}px`);
            card.style.setProperty('--y', `${e.clientY - rect.top}px`);
        });

        // Hero Image Tilt
        const heroImg = document.querySelector('.hero-image img');
        if (heroImg) {
            const rx = (window.innerHeight / 2 - e.clientY) / 40;
            const ry = (e.clientX - window.innerWidth / 2) / 40;
            heroImg.style.transform = `perspective(1000px) rotateX(${rx}deg) rotateY(${ry}deg) scale(1.02)`;
        }
    });

    // Cursor Hover detection
    document.addEventListener('mouseover', (e) => {
        if (e.target.closest('a, button, .service-card, .portfolio-card, .info-card, .filter-btn, input, textarea, select')) {
            cursor.classList.add('hovering');
        }
    });
    document.addEventListener('mouseout', (e) => {
        if (e.target.closest('a, button, .service-card, .portfolio-card, .info-card, .filter-btn, input, textarea, select')) {
            cursor.classList.remove('hovering');
        }
    });

    // Magnetic Buttons
    document.querySelectorAll('.magnetic-btn').forEach(btn => {
        btn.addEventListener('mousemove', (e) => {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            btn.style.transform = `translate(${x * 0.3}px, ${y * 0.3}px)`;
        });
        btn.addEventListener('mouseleave', () => {
            btn.style.transform = 'translate(0px, 0px)';
        });
    });
}

// --- Hamburger Menu (Mobile) ---
const hamburger = document.querySelector('.hamburger');
const sidebar = document.querySelector('.sidebar');
const overlay = document.querySelector('.sidebar-overlay');

if (hamburger && sidebar) {
    hamburger.addEventListener('click', () => {
        playClickSound();
        hamburger.classList.toggle('active');
        sidebar.classList.toggle('open');
        if (overlay) overlay.classList.toggle('active');
    });
    if (overlay) {
        overlay.addEventListener('click', () => {
            hamburger.classList.remove('active');
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    }
    sidebar.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            sidebar.classList.remove('open');
            if (overlay) overlay.classList.remove('active');
        });
    });
}

// --- Animated Counters ---
const counters = document.querySelectorAll('.counter');
const counterObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const targetAttr = entry.target.getAttribute('data-target');
            if (targetAttr === "NVMe") {
                entry.target.innerText = "NVMe";
            } else {
                const target = parseFloat(targetAttr);
                const isFloat = targetAttr.includes('.');
                const duration = 2000;
                let current = 0;
                const timer = setInterval(() => {
                    current += target / 50;
                    if (current >= target) {
                        entry.target.innerText = isFloat ? target.toFixed(1) : Math.round(target);
                        clearInterval(timer);
                    } else {
                        entry.target.innerText = isFloat ? current.toFixed(1) : Math.round(current);
                    }
                }, 40);
            }
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.5 });
counters.forEach(c => counterObserver.observe(c));

// --- Scroll Reveal ---
const revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .stagger-children');
const revealObs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('visible');
            revealObs.unobserve(e.target);
        }
    });
}, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' });
revealEls.forEach(el => revealObs.observe(el));

// --- Floating Particles (Ambience) ---
(function initAmbience() {
    const count = isTouchDevice ? 8 : 15;
    for (let i = 0; i < count; i++) {
        const p = document.createElement('div');
        p.classList.add('particle');
        p.style.left = Math.random() * 100 + 'vw';
        p.style.width = (Math.random() * 2 + 1) + 'px';
        p.style.height = p.style.width;
        p.style.top = Math.random() * 100 + 'vh';
        p.style.animationDuration = (Math.random() * 15 + 10) + 's';
        p.style.animationDelay = (Math.random() * 10) + 's';
        document.body.appendChild(p);
    }
})();


// --- Internationalization (i18n) ---
const translations = {
    fr: {
        welcome: "Bienvenue",
        connexion: "Connexion",
        home: "Accueil",
        services: "Services & Hébergement",
        portfolio: "Réalisations",
        contact: "Contact",
        hero_title: 'Votre partenaire digital pour la <span class="text-blue">Création</span> et l\'<span class="text-blue">Hébergement</span>.',
        hero_desc: "Des sites internet sur-mesure ultra-rapides, propulsés par nos serveurs de dernière génération. Performance, sécurité et disponibilité garanties.",
        hero_cta_primary: "Découvrir nos offres",
        hero_cta_secondary: "Voir nos réalisations",
        auth_login_title: "Connexion",
        auth_login_subtitle: "Accédez à votre espace Zynetik Web",
        auth_register_title: "Inscription",
        auth_register_subtitle: "Rejoignez l'infrastructure haute performance",
        email_prof: "Email professionnel",
        password: "Mot de passe",
        fullName: "Nom complet",
        create_account: "Créer mon compte",
        back_home: "Retour à l'accueil",
        promo_text: "- 10% sur votre première commande avec le code",
        stat_uptime: "Uptime Garanti",
        stat_speed: "Temps de chargement",
        stat_storage: "Stockage Ultra-Rapide",
        stat_support: "Support Technique",
        port_badge: "Portfolio",
        port_title: 'Infrastructures & <span class="text-blue">Sites Web</span>',
        port_desc: "Découvrez nos récents déploiements, alliant design sur-mesure et hébergement haute performance.",
        port_filter_all: "Tous les projets",
        port_filter_vitrine: "Vitrines & Corporate",
        port_filter_ecommerce: "E-commerce",
        port_filter_app: "Applications Web",
        contact_badge: "Contact & Support",
        contact_title: 'Démarrons votre <span class="text-blue">projet</span>',
        contact_desc: "Développement sur-mesure ou infrastructure serveur : nos ingénieurs vous répondent sous 24h.",
        contact_form_title: "Demande d'informations",
        contact_form_desc: "Décrivez vos besoins pour obtenir un devis précis.",
        contact_label_name: "Nom / Société",
        contact_ph_name: "Ex: Entreprise Tech",
        contact_label_email: "E-mail pro",
        contact_label_phone: "Téléphone",
        contact_label_service: "Besoin principal",
        contact_opt_select: "Sélectionnez...",
        contact_opt_vitrine: "Création Site Vitrine",
        contact_opt_ecom: "Création E-Commerce",
        contact_opt_dev: "Développement Sur-Mesure",
        contact_opt_host: "Hébergement / Serveur Dédié",
        contact_opt_mig: "Migration & Infogérance",
        contact_label_msg: "Détails du projet",
        contact_ph_msg: "Objectifs, trafic estimé, fonctionnalités requises...",
        contact_btn_send: "Envoyer la demande",
        contact_info_hq: "Datacenter & Siège",
        contact_info_support: "Support Technique 24/7",
        contact_info_reply: "Réponse Rapide",
        contact_info_reply_desc: "Devis gratuit sous 24h ouvrées"
    },
    en: {
        welcome: "Welcome",
        connexion: "Login",
        home: "Home",
        services: "Services & Hosting",
        portfolio: "Portfolio",
        contact: "Contact",
        hero_title: 'Your digital partner for <span class="text-blue">Creation</span> and <span class="text-blue">Hosting</span>.',
        hero_desc: "Ultra-fast custom websites, powered by our latest generation servers. Performance, security, and availability guaranteed.",
        hero_cta_primary: "Discover our offers",
        hero_cta_secondary: "See our work",
        auth_login_title: "Login",
        auth_login_subtitle: "Access your Zynetik Web space",
        auth_register_title: "Registration",
        auth_register_subtitle: "Join the high-performance infrastructure",
        email_prof: "Professional Email",
        password: "Password",
        fullName: "Full Name",
        create_account: "Create my account",
        back_home: "Back to home",
        promo_text: "- 10% on your first order with code",
        stat_uptime: "Guaranteed Uptime",
        stat_speed: "Loading Speed",
        stat_storage: "Ultra-Fast Storage",
        stat_support: "Technical Support",
        port_badge: "Portfolio",
        port_title: 'Infrastructure & <span class="text-blue">Websites</span>',
        port_desc: "Discover our recent deployments, combining custom design and high-performance hosting.",
        port_filter_all: "All projects",
        port_filter_vitrine: "Showcase & Corporate",
        port_filter_ecommerce: "E-commerce",
        port_filter_app: "Web Applications",
        contact_badge: "Contact & Support",
        contact_title: 'Let\'s start your <span class="text-blue">project</span>',
        contact_desc: "Custom development or server infrastructure: our engineers reply within 24h.",
        contact_form_title: "Information Request",
        contact_form_desc: "Describe your needs to get an accurate quote.",
        contact_label_name: "Name / Company",
        contact_ph_name: "Ex: Tech Corp",
        contact_label_email: "Business Email",
        contact_label_phone: "Phone Number",
        contact_label_service: "Main Requirement",
        contact_opt_select: "Select...",
        contact_opt_vitrine: "Showcase Website",
        contact_opt_ecom: "E-Commerce Creation",
        contact_opt_dev: "Custom Development",
        contact_opt_host: "Hosting / Dedicated Server",
        contact_opt_mig: "Migration & Management",
        contact_label_msg: "Project Details",
        contact_ph_msg: "Objectives, estimated traffic, required features...",
        contact_btn_send: "Send Request",
        contact_info_hq: "Datacenter & HQ",
        contact_info_support: "24/7 Tech Support",
        contact_info_reply: "Fast Response",
        contact_info_reply_desc: "Free quote within 24 business hours"
    }
};

function setLanguage(lang) {
    document.cookie = `site_lang=${lang};path=/;max-age=${60*60*24*30}`; // 30 jours
    location.reload();
}

function initLanguage() {
    const lang = document.cookie.split('; ').find(row => row.startsWith('site_lang='))?.split('=')[1] || 'fr';
    
    // Injecter le switcher dans le header si possible, sinon en fallback body
    const headerContainer = document.querySelector('.lang-switcher-header');
    const switcher = document.createElement('div');
    switcher.className = 'lang-switcher';
    switcher.innerHTML = `
        <button class="lang-btn ${lang === 'fr' ? 'active' : ''}" onclick="setLanguage('fr')" title="Français">
            <img src="https://flagcdn.com/fr.svg" alt="FR">
        </button>
        <button class="lang-btn ${lang === 'en' ? 'active' : ''}" onclick="setLanguage('en')" title="English">
            <img src="https://flagcdn.com/gb.svg" alt="EN">
        </button>
    `;
    
    const isRegisterPage = window.location.pathname.includes('register.html');
    
    if (isRegisterPage) {
        switcher.style.position = 'fixed';
        switcher.style.bottom = '30px';
        switcher.style.left = '30px';
        switcher.style.top = 'auto';
        switcher.style.zIndex = '9999';
        document.body.appendChild(switcher);
    } else if (headerContainer) {
        headerContainer.appendChild(switcher);
    } else {
        // Fallback pour les pages sans le nouveau header
        switcher.style.position = 'fixed';
        switcher.style.top = '20px';
        switcher.style.left = '20px';
        switcher.style.zIndex = '9999';
        document.body.appendChild(switcher);
    }

    // Traduire les éléments avec data-i18n en préservant les icônes
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (translations[lang] && translations[lang][key]) {
            if (el.tagName === 'INPUT' && (el.type === 'text' || el.type === 'email' || el.type === 'password')) {
                el.placeholder = translations[lang][key];
            } else {
                el.innerHTML = translations[lang][key];
            }
        }
    });

    if (window.lucide) {
        lucide.createIcons();
    }
}

function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    if (slides.length === 0) return;

    let currentSlide = 0;
    setInterval(() => {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add('active');
    }, 5000);
}

document.addEventListener('DOMContentLoaded', () => {
    initLanguage();
    initHeroSlider();
});
