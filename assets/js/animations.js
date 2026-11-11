/**
 * Cyber-Aesthetic Animation & Interactive Visual System
 * Profile: Bajrang Kumar Sarraf - Cybersecurity Specialist & Web Developer
 * Powered by: GSAP + ScrollTrigger, HTML5 Canvas, and Hardware-Accelerated Transforms
 */

'use strict';

document.addEventListener('DOMContentLoaded', () => {
    // 1. Accessibility Motion Check
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // 2. Read Dynamic CMS Animation Settings from DOM
    const appEl = document.getElementById('portfolio-app');
    const enableParticles = appEl ? appEl.getAttribute('data-enable-particles') !== '0' : true;
    const enableGlitch = appEl ? appEl.getAttribute('data-glitch') !== '0' : true;
    const enableCardTilt = appEl ? appEl.getAttribute('data-card-tilt') !== '0' : true;

    // 3. Initialize Subsystems with CMS Config
    if (enableParticles) {
        initCyberCanvas(prefersReducedMotion, appEl);
    }
    if (enableGlitch) {
        initGlitchHeadline();
    }
    initHeroTerminalSequence();
    initGSAPScrollAnimations(prefersReducedMotion);
    initTacticalStatCounters();
    initSkillProgressBars();
    initSkillCategoryHighlight();
    if (enableCardTilt) {
        init3DCardTilt(prefersReducedMotion);
    }
    initFluidProjectFiltering();
    initProjectLightbox();
    initTimelineSVGDrawer();
    initContactFormStateMachine();
});

/* ==========================================================================
   1. Interactive Cyber Matrix / Particle Network Canvas
   ========================================================================= */
function initCyberCanvas(reducedMotion, appEl) {
    const canvas = document.getElementById('cyber-canvas');
    const heroSection = document.getElementById('hero');

    if (!canvas || !heroSection || reducedMotion) return;

    const ctx = canvas.getContext('2d');
    let width, height;
    let particles = [];
    let animationFrameId = null;
    let isHeroVisible = true;

    // Read Dynamic Node Count & Color from CMS Controller
    const configuredCount = appEl ? (parseInt(appEl.getAttribute('data-particle-count'), 10) || 60) : 60;
    const configuredColorHex = appEl ? (appEl.getAttribute('data-particle-color') || '#10b981') : '#10b981';

    const particleCount = window.innerWidth < 768 ? Math.max(20, Math.floor(configuredCount * 0.55)) : configuredCount;
    const connectionDistance = 120;
    const mouseInfluenceDistance = 140;

    const mouse = {
        x: null,
        y: null,
        active: false
    };

    function resize() {
        width = canvas.width = heroSection.offsetWidth;
        height = canvas.height = heroSection.offsetHeight;
    }

    window.addEventListener('resize', debounce(resize, 200));
    resize();

    // Particle Object
    class NetworkNode {
        constructor() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.vx = (Math.random() - 0.5) * 0.55;
            this.vy = (Math.random() - 0.5) * 0.55;
            this.radius = Math.random() * 1.8 + 1;
            // Cyan / Emerald tactical color scheme
            this.color = Math.random() > 0.4 ? 'rgba(16, 185, 129, ' : 'rgba(6, 182, 212, ';
            this.baseAlpha = Math.random() * 0.4 + 0.3;
        }

        update() {
            this.x += this.vx;
            this.y += this.vy;

            // Bounce on boundaries
            if (this.x < 0 || this.x > width) this.vx *= -1;
            if (this.y < 0 || this.y > height) this.vy *= -1;

            // Mouse repulsion / proximity drift
            if (mouse.active && mouse.x !== null) {
                const dx = this.x - mouse.x;
                const dy = this.y - mouse.y;
                const dist = Math.hypot(dx, dy);

                if (dist < mouseInfluenceDistance) {
                    const force = (mouseInfluenceDistance - dist) / mouseInfluenceDistance;
                    const angle = Math.atan2(dy, dx);
                    this.x += Math.cos(angle) * force * 1.8;
                    this.y += Math.sin(angle) * force * 1.8;
                }
            }
        }

        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = this.color + this.baseAlpha + ')';
            ctx.fill();
        }
    }

    // Populate Nodes
    for (let i = 0; i < particleCount; i++) {
        particles.push(new NetworkNode());
    }

    // Mouse Event Handlers
    heroSection.addEventListener('mousemove', (e) => {
        const rect = heroSection.getBoundingClientRect();
        mouse.x = e.clientX - rect.left;
        mouse.y = e.clientY - rect.top;
        mouse.active = true;
    });

    heroSection.addEventListener('mouseleave', () => {
        mouse.active = false;
        mouse.x = null;
        mouse.y = null;
    });

    // Render Loop
    function render() {
        if (!isHeroVisible) return;

        ctx.clearRect(0, 0, width, height);

        // Connect adjacent nodes with subtle laser links
        for (let i = 0; i < particles.length; i++) {
            const p1 = particles[i];
            p1.update();
            p1.draw();

            for (let j = i + 1; j < particles.length; j++) {
                const p2 = particles[j];
                const dx = p1.x - p2.x;
                const dy = p1.y - p2.y;
                const dist = Math.hypot(dx, dy);

                if (dist < connectionDistance) {
                    const alpha = (1 - dist / connectionDistance) * 0.22;
                    ctx.beginPath();
                    ctx.moveTo(p1.x, p1.y);
                    ctx.lineTo(p2.x, p2.y);
                    ctx.strokeStyle = `rgba(16, 185, 129, ${alpha})`;
                    ctx.lineWidth = 0.8;
                    ctx.stroke();
                }
            }

            // Connect to mouse if within reach
            if (mouse.active && mouse.x !== null) {
                const dx = p1.x - mouse.x;
                const dy = p1.y - mouse.y;
                const dist = Math.hypot(dx, dy);

                if (dist < mouseInfluenceDistance) {
                    const alpha = (1 - dist / mouseInfluenceDistance) * 0.35;
                    ctx.beginPath();
                    ctx.moveTo(p1.x, p1.y);
                    ctx.lineTo(mouse.x, mouse.y);
                    ctx.strokeStyle = `rgba(6, 182, 212, ${alpha})`;
                    ctx.lineWidth = 1;
                    ctx.stroke();
                }
            }
        }

        animationFrameId = requestAnimationFrame(render);
    }

    // Battery / Performance Throttling: Pause Canvas when hero is off-screen
    const heroObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            isHeroVisible = entry.isIntersecting;
            if (isHeroVisible) {
                cancelAnimationFrame(animationFrameId);
                render();
            } else {
                cancelAnimationFrame(animationFrameId);
            }
        });
    }, { threshold: 0.1 });

    heroObserver.observe(heroSection);
}

/* ==========================================================================
   2. Headline Typing & Subtle Glitch
   ========================================================================== */
function initGlitchHeadline() {
    const heroHeading = document.querySelector('.hero-glitch-target');
    if (heroHeading) {
        heroHeading.classList.add('glitch-text');
        heroHeading.setAttribute('data-text', heroHeading.textContent.trim());

        // Intermittent glitch triggers on hover
        heroHeading.addEventListener('mouseenter', () => {
            heroHeading.classList.add('glitch-active');
            setTimeout(() => heroHeading.classList.remove('glitch-active'), 600);
        });
    }
}

/* ==========================================================================
   3. Floating Security Terminal Sequence (Automated Mockup)
   ========================================================================== */
function initHeroTerminalSequence() {
    const terminalLinesContainer = document.getElementById('hero-auto-terminal-lines');
    if (!terminalLinesContainer) return;

    const sequence = [
        { text: '$ nmap -sV -sC -T4 target.local', delay: 800, isCmd: true },
        { text: '[+] Starting Nmap 7.94 scan on target.local (192.168.1.105)', delay: 1200 },
        { text: '[+] Discovered open ports: 22/tcp (SSH), 80/tcp (HTTP), 443/tcp (HTTPS)', delay: 1800, color: 'text-cyan-400' },
        { text: '[+] Running VAPT automated scripts: OWASP Top 10 checklist loaded...', delay: 2400 },
        { text: '[+] Auditing IDOR, XSS, CSRF, & SQL injection vectors...', delay: 3000 },
        { text: '[✓] Status: 0 Critical Alerts. System hardened & fortified.', delay: 3600, color: 'text-emerald-400 font-bold' }
    ];

    terminalLinesContainer.innerHTML = '';

    sequence.forEach((item, index) => {
        setTimeout(() => {
            const line = document.createElement('div');
            line.className = `font-mono text-[11px] leading-relaxed ${item.color || 'text-slate-300'}`;

            if (item.isCmd) {
                line.innerHTML = `<span class="text-emerald-400 font-bold">bajrang@security-node:~$</span> ${escapeHTML(item.text.replace('$ ', ''))}`;
            } else {
                line.textContent = item.text;
            }

            terminalLinesContainer.appendChild(line);

            // Auto-scroll terminal mockup
            terminalLinesContainer.scrollTop = terminalLinesContainer.scrollHeight;
        }, item.delay);
    });
}

/* ==========================================================================
   4. Global GSAP + ScrollTrigger Sequences
   ========================================================================== */
function initGSAPScrollAnimations(reducedMotion) {
    if (typeof gsap === 'undefined') {
        console.warn('GSAP not loaded. Relying on default CSS display.');
        return;
    }

    if (typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);
    }

    if (reducedMotion) return;

    // A. Section Scanning Laser Bars & Titles
    document.querySelectorAll('.section-reveal-header').forEach(header => {
        const laserBar = header.querySelector('.scanning-laser-bar');

        gsap.from(header, {
            scrollTrigger: {
                trigger: header,
                start: 'top 85%',
                toggleActions: 'play none none none'
            },
            opacity: 0,
            y: 30,
            duration: 0.8,
            ease: 'power3.out',
            onComplete: () => {
                if (laserBar) laserBar.classList.add('active');
            }
        });
    });

    // B. Staggered Cards Entrances (Projects, Certifications, Experience)
    const staggerGroups = [
        { selector: '#projects-grid .project-card', trigger: '#projects-grid' },
        { selector: '#skills .glass-panel', trigger: '#skills' },
        { selector: '#experience .glass-panel', trigger: '#experience' },
        { selector: '#certifications .glass-panel', trigger: '#certifications' },
        { selector: '#methodology .glass-panel', trigger: '#methodology' }
    ];

    staggerGroups.forEach(group => {
        const elements = document.querySelectorAll(group.selector);
        const trigger = document.querySelector(group.trigger);

        if (elements.length && trigger) {
            gsap.from(elements, {
                scrollTrigger: {
                    trigger: trigger,
                    start: 'top 80%',
                    toggleActions: 'play none none none'
                },
                opacity: 0,
                y: 35,
                duration: 0.75,
                stagger: 0.12,
                ease: 'power2.out',
                clearProps: 'transform'
            });
        }
    });

    // C. Quick Stats Stagger on Hero
    const statCards = document.querySelectorAll('.hero-stat-card');
    if (statCards.length) {
        gsap.from(statCards, {
            opacity: 0,
            scale: 0.9,
            y: 20,
            duration: 0.6,
            stagger: 0.1,
            delay: 0.4,
            ease: 'back.out(1.4)'
        });
    }
}

/* ==========================================================================
   5. Tactical Numeric Stat Counters
   ========================================================================== */
function initTacticalStatCounters() {
    const counterElements = document.querySelectorAll('[data-counter-target]');
    if (!counterElements.length) return;

    const counterObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const targetValue = parseInt(el.getAttribute('data-counter-target'), 10);
                const suffix = el.getAttribute('data-counter-suffix') || '';
                const duration = 1800; // ms
                const startTime = performance.now();

                function updateCount(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    // Ease-out cubic calculation
                    const easedProgress = 1 - Math.pow(1 - progress, 3);
                    const currentCount = Math.floor(easedProgress * targetValue);

                    el.textContent = `${currentCount}${suffix}`;

                    if (progress < 1) {
                        requestAnimationFrame(updateCount);
                    } else {
                        el.textContent = `${targetValue}${suffix}`;
                    }
                }

                requestAnimationFrame(updateCount);
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.3 });

    counterElements.forEach(el => counterObserver.observe(el));
}

/* ==========================================================================
   6. Skill Progress Bars Scroll-Triggered Fill
   ========================================================================== */
function initSkillProgressBars() {
    const progressBars = document.querySelectorAll('.skill-progress-bar');
    if (!progressBars.length) return;

    const skillObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const bar = entry.target;
                const targetWidth = bar.getAttribute('data-target-width') || '0%';
                bar.style.width = targetWidth;
                observer.unobserve(bar);
            }
        });
    }, { threshold: 0.2 });

    progressBars.forEach(bar => skillObserver.observe(bar));
}

/* ==========================================================================
   7. Skill Category Cross-Highlight Linkage
   ========================================================================== */
function initSkillCategoryHighlight() {
    const categoryCards = document.querySelectorAll('[data-skill-category-id]');
    const projectCards = document.querySelectorAll('.project-card');

    categoryCards.forEach(catCard => {
        const catId = catCard.getAttribute('data-skill-category-id');

        catCard.addEventListener('mouseenter', () => {
            projectCards.forEach(proj => {
                const projCategory = proj.getAttribute('data-category');
                if (
                    (catId === 'security' && projCategory === 'security') ||
                    (catId === 'development' && (projCategory === 'web' || projCategory === 'ecommerce'))
                ) {
                    proj.classList.add('skill-category-highlight');
                }
            });
        });

        catCard.addEventListener('mouseleave', () => {
            projectCards.forEach(proj => proj.classList.remove('skill-category-highlight'));
        });
    });
}

/* ==========================================================================
   8. 3D Perspective Card Tilt & Mouse Coordinates Shine
   ========================================================================== */
function init3DCardTilt(reducedMotion) {
    if (reducedMotion) return;

    const tiltCards = document.querySelectorAll('.tilt-card');

    tiltCards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            // Maximum tilt angle (degrees)
            const maxTilt = 7;
            const rotateX = ((y - centerY) / centerY) * -maxTilt;
            const rotateY = ((x - centerX) / centerX) * maxTilt;

            card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) scale3d(1.02, 1.02, 1.02)`;

            // Update radial shine positioning
            card.style.setProperty('--mouse-x', `${x}px`);
            card.style.setProperty('--mouse-y', `${y}px`);
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
        });
    });
}

/* ==========================================================================
   9. Fluid Category Switching (Project Filters)
   ========================================================================== */
function initFluidProjectFiltering() {
    const filterButtons = document.querySelectorAll('.project-filter-btn');
    const projectCards = document.querySelectorAll('.project-card');

    if (!filterButtons.length || !projectCards.length) return;

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const filter = btn.getAttribute('data-filter');

            // Toggle active pill styling
            filterButtons.forEach(b => {
                b.classList.remove('bg-emerald-500/20', 'text-emerald-300', 'border-emerald-500/40');
                b.classList.add('bg-slate-800/60', 'text-slate-400', 'border-slate-700/50');
            });
            btn.classList.add('bg-emerald-500/20', 'text-emerald-300', 'border-emerald-500/40');
            btn.classList.remove('bg-slate-800/60', 'text-slate-400', 'border-slate-700/50');

            // GSAP Animated Switch
            if (typeof gsap !== 'undefined') {
                projectCards.forEach(card => {
                    const category = card.getAttribute('data-category');
                    const isMatch = filter === 'all' || category === filter;

                    if (isMatch) {
                        card.style.display = 'flex';
                        gsap.to(card, {
                            opacity: 1,
                            scale: 1,
                            duration: 0.35,
                            ease: 'power2.out',
                            clearProps: 'transform'
                        });
                    } else {
                        gsap.to(card, {
                            opacity: 0,
                            scale: 0.94,
                            duration: 0.25,
                            ease: 'power2.in',
                            onComplete: () => {
                                card.style.display = 'none';
                            }
                        });
                    }
                });
            }
        });
    });
}

/* ==========================================================================
   10. Project Image & PoC Lightbox Modal
   ========================================================================== */
function initProjectLightbox() {
    const modal = document.getElementById('project-lightbox-modal');
    const closeBtn = document.getElementById('modal-close-btn');
    const triggerButtons = document.querySelectorAll('.lightbox-trigger');

    if (!modal) return;

    const modalTitle = document.getElementById('modal-project-title');
    const modalCategory = document.getElementById('modal-project-category');
    const modalDescription = document.getElementById('modal-project-description');
    const modalTech = document.getElementById('modal-project-tech');
    const modalHighlights = document.getElementById('modal-project-highlights');
    const modalLink = document.getElementById('modal-project-link');
    const modalImgWrap = document.getElementById('modal-project-img-wrap');
    const modalImg = document.getElementById('modal-project-img');

    triggerButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const projectDataRaw = btn.getAttribute('data-project-json');
            if (!projectDataRaw) return;

            try {
                const p = JSON.parse(decodeURIComponent(projectDataRaw));

                if (modalTitle) modalTitle.textContent = p.title;
                if (modalCategory) modalCategory.textContent = p.badge || p.category_label;
                if (modalDescription) modalDescription.textContent = p.description || p.summary;

                if (modalImg && modalImgWrap) {
                    if (p.image_path) {
                        const fullImgUrl = (p.image_path.startsWith('http://') || p.image_path.startsWith('https://')) 
                            ? p.image_path 
                            : (window.__BASE_URL__ || '') + '/' + p.image_path.replace(/^\//, '');
                        modalImg.src = fullImgUrl;
                        modalImg.alt = p.title || 'Project Screenshot';
                        modalImgWrap.classList.remove('hidden');
                    } else {
                        modalImgWrap.classList.add('hidden');
                    }
                }

                if (modalHighlights && p.highlights) {
                    modalHighlights.innerHTML = p.highlights.map(h => `
                        <li class="flex items-start gap-2 text-xs text-slate-300">
                            <i class="fa-solid fa-shield-check text-emerald-400 text-xs mt-0.5"></i>
                            <span>${escapeHTML(h)}</span>
                        </li>
                    `).join('');
                }

                if (modalTech && p.tech_stack) {
                    modalTech.innerHTML = p.tech_stack.map(t => `
                        <span class="px-2 py-0.5 rounded bg-slate-900 border border-slate-700 text-[11px] font-mono text-cyan-300">${escapeHTML(t)}</span>
                    `).join('');
                }

                if (modalLink) {
                    if (p.demo_url) {
                        modalLink.href = p.demo_url;
                        modalLink.classList.remove('hidden');
                        modalLink.textContent = 'Visit Live Application';
                    } else if (p.github_url) {
                        modalLink.href = p.github_url;
                        modalLink.classList.remove('hidden');
                        modalLink.textContent = 'View Repository / Lab Details';
                    } else {
                        modalLink.classList.add('hidden');
                    }
                }

                modal.classList.add('modal-active');
                document.body.style.overflow = 'hidden';
            } catch (err) {
                console.error('Lightbox data parse error:', err);
            }
        });
    });

    function closeModal() {
        modal.classList.remove('modal-active');
        document.body.style.overflow = '';
    }

    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('modal-active')) {
            closeModal();
        }
    });
}

/* ==========================================================================
   11. Interactive Vertical Timeline SVG Path Drawer
   ========================================================================== */
function initTimelineSVGDrawer() {
    const timelineSvg = document.getElementById('timeline-svg');
    const timelinePath = document.getElementById('timeline-path');

    if (!timelineSvg || !timelinePath || typeof ScrollTrigger === 'undefined') return;

    const pathLength = timelinePath.getTotalLength();
    timelinePath.style.strokeDasharray = pathLength;
    timelinePath.style.strokeDashoffset = pathLength;

    ScrollTrigger.create({
        trigger: '#experience',
        start: 'top 70%',
        end: 'bottom 85%',
        scrub: 0.6,
        onUpdate: (self) => {
            const drawLength = pathLength * self.progress;
            timelinePath.style.strokeDashoffset = pathLength - drawLength;
        }
    });
}

/* ==========================================================================
   12. Hardened Contact Form Feedback State Machine
   ========================================================================== */
function initContactFormStateMachine() {
    const form = document.getElementById('contact-form');
    const submitBtn = document.getElementById('contact-submit-btn');

    if (!form || !submitBtn) return;

    // Listen for custom status updates from main.js or form events
    window.addEventListener('contactFormSubmitting', () => {
        submitBtn.disabled = true;
        submitBtn.className = 'w-full sm:w-auto cyber-btn btn-state-submitting btn-radar-sweep font-mono text-xs py-3 px-6';
        submitBtn.innerHTML = `
            <i class="fa-solid fa-circle-notch fa-spin mr-2"></i>
            <span>Encrypting & Sending Transmission...</span>
        `;
    });

    window.addEventListener('contactFormSuccess', () => {
        submitBtn.disabled = false;
        submitBtn.className = 'w-full sm:w-auto cyber-btn btn-state-success font-mono text-xs py-3 px-6';
        submitBtn.innerHTML = `
            <i class="fa-solid fa-circle-check mr-2"></i>
            <span>Transmission Delivered & Encrypted!</span>
        `;

        setTimeout(() => {
            submitBtn.className = 'w-full sm:w-auto cyber-btn cyber-btn-primary font-mono text-xs py-3 px-6';
            submitBtn.innerHTML = `
                <i class="fa-solid fa-paper-plane mr-2"></i>
                <span>Transmit Encrypted Message</span>
            `;
        }, 5000);
    });

    window.addEventListener('contactFormError', () => {
        submitBtn.disabled = false;
        submitBtn.className = 'w-full sm:w-auto cyber-btn cyber-btn-primary font-mono text-xs py-3 px-6';
        submitBtn.innerHTML = `
            <i class="fa-solid fa-triangle-exclamation text-amber-300 mr-2"></i>
            <span>Retry Transmission</span>
        `;
    });
}

// Utility: Debounce function for performance
function debounce(func, wait) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

// Utility: HTML Escaper
function escapeHTML(str) {
    return str.replace(/[&<>'"]/g, 
        tag => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            "'": '&#39;',
            '"': '&quot;'
        }[tag] || tag)
    );
}
