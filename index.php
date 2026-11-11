<?php
/**
 * Main Portfolio Entry Point
 * Profile: Bajrang Kumar Sarraf - Cybersecurity Specialist & Full-Stack Web Developer
 * Modern PHP 8+ Architecture
 */

declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/config/database.php';

// Load default array-driven data
$data = require __DIR__ . '/includes/data.php';

$profile = $data['profile'] ?? [];
$skillsCategories = $data['skills_categories'] ?? [];
$vaptMethodology = $data['vapt_methodology'] ?? [];
$experience = $data['experience'] ?? [];
$projects = $data['projects'] ?? [];
$bugBounty = $data['bug_bounty_research'] ?? [];
$education = $data['education'] ?? [];
$certifications = $data['certifications'] ?? [];
$awards = $data['awards_hackathons'] ?? [];
$languages = $data['languages'] ?? [];
$baseUrl = AppConfig::baseUrl();

$animSettings = [
    'enable_particles' => 1,
    'particle_node_count' => 60,
    'particle_color' => '#10b981',
    'enable_glitch_effect' => 1,
    'enable_card_tilt' => 1,
    'typing_speed_ms' => 80,
    'terminal_custom_commands' => '{}'
];

// If database is initialized, hydrate data dynamically from CMS tables
if (Database::isInitialized()) {
    try {
        $pdo = Database::getConnection();

        // 1. Profile Settings
        $dbProfile = $pdo->query("SELECT * FROM profile_settings WHERE id = 1 LIMIT 1")->fetch();
        if ($dbProfile) {
            $profile['name'] = $dbProfile['full_name'];
            $profile['email'] = $dbProfile['email'];
            $profile['phone'] = $dbProfile['phone'];
            $profile['location'] = $dbProfile['location'];
            $profile['summary'] = $dbProfile['bio'];
            if (!empty($dbProfile['avatar_path'])) {
                $profile['avatar_path'] = $dbProfile['avatar_path'];
            }
            if (!empty($dbProfile['resume_pdf_path'])) {
                $profile['resume_pdf_path'] = $dbProfile['resume_pdf_path'];
            }
            if (!empty($dbProfile['tagline_roles'])) {
                $profile['taglines'] = array_map('trim', explode(',', $dbProfile['tagline_roles']));
            }
        }

        // 2. Animation Settings
        $dbAnim = $pdo->query("SELECT * FROM animation_settings WHERE id = 1 LIMIT 1")->fetch();
        if ($dbAnim) {
            $animSettings = array_merge($animSettings, $dbAnim);
        }

        // 3. Projects
        $dbProjects = $pdo->query("SELECT * FROM projects ORDER BY display_order ASC, id ASC")->fetchAll();
        if (!empty($dbProjects)) {
            $formattedProjects = [];
            foreach ($dbProjects as $dp) {
                $catSlug = 'web';
                if (stripos($dp['category'], 'security') !== false || stripos($dp['category'], 'hardware') !== false) {
                    $catSlug = 'security';
                } elseif (stripos($dp['category'], 'commerce') !== false) {
                    $catSlug = 'ecommerce';
                } elseif (stripos($dp['category'], 'corporate') !== false) {
                    $catSlug = 'corporate';
                }

                $formattedProjects[] = [
                    'id' => $dp['slug'],
                    'title' => $dp['title'],
                    'category' => $catSlug,
                    'category_label' => $dp['category'],
                    'featured' => (bool)$dp['is_featured'],
                    'summary' => mb_substr($dp['description'], 0, 150) . '...',
                    'description' => $dp['description'],
                    'highlights' => [
                        'Hardened production architecture',
                        'Input validation & sanitized parameters',
                        'Responsive mobile-first user experience'
                    ],
                    'tech_stack' => ['PHP 8+', 'Tailwind CSS', 'MySQL', 'Secure PDO'],
                    'demo_url' => $dp['live_url'],
                    'github_url' => $dp['github_url'],
                    'badge' => $dp['category'],
                    'image_path' => !empty($dp['image_path']) ? $dp['image_path'] : 'assets/img/profile-cyber.svg'
                ];
            }
            $projects = $formattedProjects;
        }

        // 4. Skills
        $dbSkills = $pdo->query("SELECT * FROM skills ORDER BY display_order ASC, id ASC")->fetchAll();
        if (!empty($dbSkills)) {
            $grouped = [];
            foreach ($dbSkills as $ds) {
                $c = $ds['category'];
                if (!isset($grouped[$c])) {
                    $grouped[$c] = [
                        'id' => strtolower(preg_replace('/[^a-z0-9]+/', '-', $c)),
                        'name' => $c,
                        'subtitle' => 'Proficiencies and toolchains maintained through continuous auditing',
                        'icon' => stripos($c, 'security') !== false ? 'fa-solid fa-shield-halved' : (stripos($c, 'network') !== false ? 'fa-solid fa-server' : 'fa-solid fa-code'),
                        'accent' => stripos($c, 'security') !== false ? 'emerald' : 'cyan',
                        'skills' => []
                    ];
                }
                $grouped[$c]['skills'][] = [
                    'name' => $ds['name'],
                    'level' => (int)$ds['proficiency_pct'],
                    'badge' => $ds['proficiency_pct'] >= 90 ? 'Expert' : 'Advanced',
                    'icon' => 'fa-solid fa-check',
                    'desc' => 'Technical proficiency rating: ' . (int)$ds['proficiency_pct'] . '% verified capability.'
                ];
            }
            $skillsCategories = array_values($grouped);
        }

        // 5. Experiences
        $dbExp = $pdo->query("SELECT * FROM experiences ORDER BY display_order ASC, id ASC")->fetchAll();
        if (!empty($dbExp)) {
            $formattedExp = [];
            foreach ($dbExp as $de) {
                $bullets = json_decode($de['bullet_points'] ?: '[]', true) ?: [];
                $formattedExp[] = [
                    'role' => $de['role'],
                    'company' => $de['company'],
                    'period' => $de['duration'],
                    'location' => 'Verified Institution',
                    'type' => 'Professional Milestone',
                    'icon' => 'fa-solid fa-briefcase',
                    'highlights' => $bullets,
                    'tags' => ['Enterprise Infrastructure', 'VAPT', 'Security Hardening']
                ];
            }
            $experience = $formattedExp;
        }

        // 6. Certifications
        $dbCerts = $pdo->query("SELECT * FROM certifications ORDER BY id ASC")->fetchAll();
        if (!empty($dbCerts)) {
            $formattedCerts = [];
            foreach ($dbCerts as $dc) {
                $formattedCerts[] = [
                    'title' => $dc['title'],
                    'issuer' => $dc['issuer'],
                    'icon' => stripos($dc['title'], 'red hat') !== false ? 'fa-brands fa-redhat' : 'fa-solid fa-award',
                    'category' => 'Professional Credential',
                    'badge' => $dc['issue_date'],
                    'color' => '#10b981',
                    'desc' => 'Verified accreditation issued by ' . $dc['issuer'] . '.',
                    'badge_image_path' => !empty($dc['badge_image_path']) ? $dc['badge_image_path'] : null,
                    'credential_url' => !empty($dc['credential_url']) ? $dc['credential_url'] : null
                ];
            }
            $certifications = $formattedCerts;
        }
    } catch (Throwable $e) {
        error_log('Database hydration exception: ' . $e->getMessage());
    }
}

// Global Image & Document URLs
$avatarUrl = !empty($profile['avatar_path']) ? $baseUrl . '/' . ltrim($profile['avatar_path'], '/') : $baseUrl . '/assets/img/profile-cyber.svg';
$resumeUrl = !empty($profile['resume_pdf_path']) ? $baseUrl . '/' . ltrim($profile['resume_pdf_path'], '/') : null;
$profile['avatar_url'] = $avatarUrl;
$profile['resume_url'] = $resumeUrl;

// Include Header (applies security headers, sets up styles, navbar)
require_once __DIR__ . '/includes/header.php';
?>

<main class="flex-grow" id="portfolio-app" 
    data-enable-particles="<?= (int)($animSettings['enable_particles'] ?? 1) ?>" 
    data-particle-count="<?= (int)($animSettings['particle_node_count'] ?? 60) ?>" 
    data-particle-color="<?= htmlspecialchars($animSettings['particle_color'] ?? '#10b981', ENT_QUOTES, 'UTF-8') ?>" 
    data-typing-speed="<?= (int)($animSettings['typing_speed_ms'] ?? 80) ?>" 
    data-glitch="<?= (int)($animSettings['enable_glitch_effect'] ?? 1) ?>" 
    data-card-tilt="<?= (int)($animSettings['enable_card_tilt'] ?? 1) ?>" 
    data-taglines='<?= htmlspecialchars(json_encode($profile['taglines'] ?? []), ENT_QUOTES, 'UTF-8') ?>' 
    data-terminal-custom='<?= htmlspecialchars($animSettings['terminal_custom_commands'] ?? '{}', ENT_QUOTES, 'UTF-8') ?>'>

    <!-- =========================================================================
         1. HERO SECTION
         ========================================================================= -->
    <section id="hero" class="relative pt-12 pb-20 md:pt-20 md:pb-28 overflow-hidden border-b border-slate-800/60">
        <!-- Interactive Cyber Matrix / Particle Network Canvas -->
        <canvas id="cyber-canvas"></canvas>

        <!-- Ambient Cyber Glows -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/3 right-10 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left Column: Introduction & Call to Action -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <!-- Profile Identity Avatar & Availability Pill -->
                    <div class="flex items-center gap-4 flex-wrap">
                        <div class="relative group shrink-0">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl overflow-hidden border-2 border-emerald-500/60 p-0.5 bg-slate-900 shadow-[0_0_20px_rgba(16,185,129,0.35)] group-hover:border-emerald-400 group-hover:scale-105 transition-all">
                                <img src="<?= e($avatarUrl) ?>" alt="<?= e($profile['name']) ?>" class="w-full h-full object-cover rounded-xl" onerror="this.onerror=null; this.src='<?= e($baseUrl) ?>/assets/img/profile-cyber.svg';">
                            </div>
                            <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5" title="Online & Ready for Security Audits">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500 border-2 border-slate-950"></span>
                            </span>
                        </div>
                        <div class="space-y-1">
                            <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-900/90 border border-emerald-500/30 text-xs font-mono text-emerald-300">
                                <span class="pulse-beacon"></span>
                                <span><?= e($profile['status']) ?></span>
                            </div>
                            <div class="text-[11px] font-mono text-slate-400 flex items-center gap-2 pl-1">
                                <span class="text-emerald-400 font-bold">&bull;</span>
                                <span><?= e($profile['location']) ?></span>
                                <span class="text-slate-600">|</span>
                                <span class="text-cyan-400 font-semibold">Active SecOps</span>
                            </div>
                        </div>
                    </div>

                    <!-- Main Heading with Glitch -->
                    <div>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                            Hi, I'm <span class="hero-glitch-target text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400" data-text="<?= e($profile['name']) ?>"><?= e($profile['name']) ?></span>
                        </h1>
                        <!-- Typing Subtitle -->
                        <div class="mt-3 flex items-center text-lg sm:text-2xl font-mono text-slate-300 h-9">
                            <span class="text-emerald-400 mr-2">&gt;</span>
                            <span id="typing-text" class="text-white font-semibold"></span>
                            <span class="terminal-cursor"></span>
                        </div>
                    </div>

                    <!-- Professional Summary -->
                    <p class="text-slate-300 text-base sm:text-lg leading-relaxed max-w-2xl font-sans">
                        <?= e($profile['summary']) ?>
                    </p>

                    <!-- Tech Stack Highlights Badges -->
                    <div class="flex flex-wrap gap-2 pt-1 font-mono text-xs">
                        <span class="cyber-badge badge-emerald"><i class="fa-solid fa-shield-halved"></i> VAPT & OWASP</span>
                        <span class="cyber-badge badge-cyan"><i class="fa-brands fa-redhat"></i> RHCSA Linux</span>
                        <span class="cyber-badge badge-indigo"><i class="fa-brands fa-php"></i> PHP 8+ Secure OOP</span>
                        <span class="cyber-badge badge-amber"><i class="fa-solid fa-network-wired"></i> Wazuh & Splunk</span>
                        <span class="cyber-badge badge-emerald"><i class="fa-solid fa-bug"></i> Burp Suite & Nmap</span>
                    </div>

                    <!-- Call To Action Buttons -->
                    <div class="flex flex-wrap gap-4 pt-4">
                        <a href="#projects" class="cyber-btn cyber-btn-primary">
                            <i class="fa-solid fa-folder-open"></i> Inspect Projects
                        </a>
                        <a href="#terminal" class="cyber-btn cyber-btn-secondary">
                            <i class="fa-solid fa-terminal text-cyan-400"></i> Open Terminal CLI
                        </a>
                        <?php if (!empty($resumeUrl)): ?>
                            <a href="<?= e($resumeUrl) ?>" target="_blank" download class="cyber-btn cyber-btn-secondary text-emerald-400 hover:text-emerald-300">
                                <i class="fa-solid fa-file-pdf"></i> Download Resume
                            </a>
                        <?php else: ?>
                            <a href="#contact" class="cyber-btn cyber-btn-secondary">
                                <i class="fa-solid fa-envelope text-emerald-400"></i> Get In Touch
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Direct Contact Snippet -->
                    <div class="pt-4 flex flex-wrap items-center gap-6 text-xs font-mono text-slate-400 border-t border-slate-800/80">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-emerald-400"></i>
                            <span><?= e($profile['location']) ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-emerald-400"></i>
                            <a href="mailto:<?= e($profile['email']) ?>" class="hover:text-emerald-300"><?= e($profile['email']) ?></a>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-phone text-emerald-400"></i>
                            <a href="tel:<?= e($profile['phone']) ?>" class="hover:text-emerald-300"><?= e($profile['phone']) ?></a>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Interactive Terminal Preview HUD with Auto Sequence -->
                <div class="lg:col-span-5">
                    <div class="terminal-container shadow-2xl relative">
                        <div class="scanlines-overlay absolute inset-0 rounded-lg"></div>
                        <!-- Terminal Header -->
                        <div class="terminal-header">
                            <div class="terminal-dots">
                                <span class="terminal-dot dot-red"></span>
                                <span class="terminal-dot dot-yellow"></span>
                                <span class="terminal-dot dot-green"></span>
                            </div>
                            <span class="text-xs font-mono text-slate-400">bajrang@security-node: ~ (zsh)</span>
                            <span class="text-xs font-mono text-emerald-400"><i class="fa-solid fa-lock text-[10px]"></i> SSL/TLS</span>
                        </div>

                        <!-- Terminal Body with Automated Live Typing Sequence -->
                        <div class="terminal-body font-mono text-xs text-slate-300 space-y-3">
                            <!-- Automated Terminal Output Container -->
                            <div id="hero-auto-terminal-lines" class="space-y-2 min-h-[140px]">
                                <div class="text-slate-400">
                                    <span class="text-emerald-400 font-bold">bajrang@security-node:~$</span> nmap -sV -sC -T4 target.local
                                </div>
                                <div class="text-cyan-400">[+] Discovering open ports: 22/tcp, 80/tcp, 443/tcp...</div>
                                <div class="text-slate-300">[+] Running VAPT scripts: OWASP Top 10 checklist loaded.</div>
                                <div class="text-emerald-400 font-bold">[✓] Status: 0 Critical Alerts. System hardened.</div>
                            </div>

                            <div class="pt-2 border-t border-slate-800/80">
                                <div class="text-cyan-300 bg-slate-900/80 p-2.5 rounded border border-slate-800 text-[11px] leading-relaxed">
                                    <span class="text-emerald-400 font-bold">// Target:</span> Bajrang Kumar Sarraf &bull; <span class="text-yellow-300">RHCSA Certified</span><br>
                                    <span class="text-slate-400 font-bold">// VAPT Scope:</span> OWASP Top 10, Network Penetration Testing & Secure Web Development
                                </div>
                            </div>

                            <div class="pt-1 flex items-center justify-between border-t border-slate-800/80 text-[11px]">
                                <span class="text-slate-400">Want interactive command shell?</span>
                                <a href="#terminal" class="text-cyan-400 hover:text-cyan-300 font-bold underline flex items-center gap-1">
                                    <span>Scroll to CLI</span>
                                    <i class="fa-solid fa-arrow-down text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Quick Metrics Grid with Tactical Counter Attributes -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-16">
                <?php foreach ($profile['quick_stats'] ?? [] as $stat): 
                    preg_match('/^(\d+)(.*)$/', (string)$stat['value'], $matches);
                    $numVal = $matches[1] ?? '0';
                    $suffix = $matches[2] ?? '';
                ?>
                    <div class="hero-stat-card glass-panel p-5 rounded-xl border border-slate-800 relative group">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-mono uppercase tracking-wider text-slate-400"><?= e($stat['metric']) ?></span>
                            <i class="<?= e($stat['icon']) ?> text-emerald-400/80 text-lg group-hover:scale-110 transition-transform"></i>
                        </div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-white font-mono" data-counter-target="<?= e($numVal) ?>" data-counter-suffix="<?= e($suffix) ?>">
                            <?= e($stat['value']) ?>
                        </div>
                        <p class="text-xs text-slate-400 mt-1 font-sans"><?= e($stat['detail']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>


    <!-- =========================================================================
         2. ABOUT & SECURITY PHILOSOPHY SECTION
         ========================================================================= -->
    <section id="about" class="py-20 border-b border-slate-800/60 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="mb-14 section-reveal-header">
                <div class="inline-flex items-center gap-2 text-xs font-mono text-emerald-400 uppercase tracking-widest mb-2">
                    <span class="w-6 h-px bg-emerald-400"></span>
                    <span>01 // PROFILE & PHILOSOPHY</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    Offensive Mindset. Defensive Architecture.
                </h2>
                <div class="scanning-laser-container">
                    <div class="scanning-laser-bar"></div>
                </div>
                <p class="text-slate-400 mt-2 max-w-2xl text-sm sm:text-base">
                    Bridging the gap between adversarial offensive testing and resilient software development.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                
                <!-- Left: Detailed Bio & Philosophy -->
                <div class="lg:col-span-7 space-y-6 text-slate-300 text-sm sm:text-base leading-relaxed">
                    <div class="glass-panel p-6 sm:p-8 rounded-xl border border-slate-800 space-y-4">
                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-fingerprint text-emerald-400"></i>
                            <span>The Core Philosophy</span>
                        </h3>
                        <p>
                            <?= e($profile['philosophy']) ?>
                        </p>
                        <p>
                            My background blends rigorous academic training in <strong class="text-emerald-300">Cyber Security & Forensics from Parul University</strong>, enterprise systems administration as a <strong class="text-cyan-300">Red Hat Certified System Administrator (RHCSA)</strong>, and real-world hands-on engineering across high-impact web portals and network infrastructure.
                        </p>
                        <p>
                            Whether conducting black-box penetration tests on multi-tier web applications, modeling threats for developer cohorts, or deploying secure PHP 8 architectures, I focus on reproducible validation, actionable remediation, and zero-compromise engineering.
                        </p>
                    </div>

                    <!-- 4 Core Pillars Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="glass-panel p-5 rounded-lg border border-slate-800">
                            <div class="w-9 h-9 rounded bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 mb-3">
                                <i class="fa-solid fa-crosshairs"></i>
                            </div>
                            <h4 class="text-white font-bold text-sm mb-1">VAPT & Web Security</h4>
                            <p class="text-xs text-slate-400">OWASP Top 10 auditing, network port analysis, API vulnerability research, and verified PoC validation.</p>
                        </div>
                        <div class="glass-panel p-5 rounded-lg border border-slate-800">
                            <div class="w-9 h-9 rounded bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400 mb-3">
                                <i class="fa-brands fa-linux"></i>
                            </div>
                            <h4 class="text-white font-bold text-sm mb-1">Systems & Hardening</h4>
                            <p class="text-xs text-slate-400">RHCSA certified Linux administration, Windows Server Active Directory, VLANs, and firewall ACLs.</p>
                        </div>
                        <div class="glass-panel p-5 rounded-lg border border-slate-800">
                            <div class="w-9 h-9 rounded bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400 mb-3">
                                <i class="fa-solid fa-shield-virus"></i>
                            </div>
                            <h4 class="text-white font-bold text-sm mb-1">SIEM & Threat Monitoring</h4>
                            <p class="text-xs text-slate-400">Wazuh EDR, Splunk enterprise log correlation, host intrusion detection, and incident response.</p>
                        </div>
                        <div class="glass-panel p-5 rounded-lg border border-slate-800">
                            <div class="w-9 h-9 rounded bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400 mb-3">
                                <i class="fa-solid fa-code"></i>
                            </div>
                            <h4 class="text-white font-bold text-sm mb-1">Secure Development</h4>
                            <p class="text-xs text-slate-400">Modular PHP 8+ OOP, SQLi/CSRF defense, embedded C++ IoT tooling, and vanilla JavaScript.</p>
                        </div>
                    </div>

                </div>

                <!-- Right: Identity Details Card & Languages Spoken -->
                <div class="lg:col-span-5 space-y-6">
                    
                    <!-- Bio Details Card -->
                    <div class="glass-panel p-6 rounded-xl border border-slate-800 space-y-4 font-mono text-xs">
                        <div class="flex items-center gap-4 border-b border-slate-800 pb-4">
                            <div class="relative shrink-0">
                                <img src="<?= e($avatarUrl) ?>" alt="<?= e($profile['name']) ?>" class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl border-2 border-emerald-500/50 p-1 bg-slate-900 object-cover shadow-[0_0_20px_rgba(16,185,129,0.25)]" onerror="this.onerror=null; this.src='<?= e($baseUrl) ?>/assets/img/profile-cyber.svg';">
                                <span class="absolute -bottom-1 -right-1 bg-slate-900 rounded-full p-1 border border-emerald-500/40 text-emerald-400 text-[10px] shadow" title="Security Verified">
                                    <i class="fa-solid fa-shield-check"></i>
                                </span>
                            </div>
                            <div class="flex-grow">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400 uppercase tracking-wider">// CANDIDATE DOSSIER</span>
                                    <span class="cyber-badge badge-emerald">VERIFIED</span>
                                </div>
                                <span class="text-white font-bold text-sm sm:text-base font-sans block mt-1"><?= e($profile['name']) ?></span>
                                <span class="block text-[11px] text-emerald-400 font-mono">Node ID: #BKS-NEPAL-44300</span>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between py-1 border-b border-slate-800/60">
                                <span class="text-slate-400">Full Name</span>
                                <span class="text-white font-bold"><?= e($profile['name']) ?></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-800/60">
                                <span class="text-slate-400">Location</span>
                                <span class="text-emerald-300"><?= e($profile['location']) ?></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-800/60 items-center">
                                <span class="text-slate-400">Email</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-200"><?= e($profile['email']) ?></span>
                                    <button class="copy-trigger text-emerald-400 hover:text-emerald-300" data-copy="<?= e($profile['email']) ?>" title="Copy Email">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-800/60 items-center">
                                <span class="text-slate-400">Phone</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-200"><?= e($profile['phone']) ?></span>
                                    <button class="copy-trigger text-emerald-400 hover:text-emerald-300" data-copy="<?= e($profile['phone']) ?>" title="Copy Phone">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-800/60">
                                <span class="text-slate-400">Primary Specialization</span>
                                <span class="text-cyan-300">VAPT & Forensics</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-slate-400">Linux Credential</span>
                                <span class="text-red-400 font-bold">RHCSA Certified</span>
                            </div>
                        </div>

                        <?php if (!empty($resumeUrl)): ?>
                            <div class="pt-2">
                                <a href="<?= e($resumeUrl) ?>" target="_blank" download class="cyber-btn cyber-btn-secondary w-full text-center text-xs py-2 text-emerald-400 hover:text-emerald-300">
                                    <i class="fa-solid fa-file-pdf mr-1.5"></i> Download Verified Resume (PDF)
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="pt-3">
                            <a href="#contact" class="w-full cyber-btn cyber-btn-primary text-xs py-2.5">
                                <i class="fa-solid fa-paper-plane mr-2"></i> Book Security Assessment / Consultation
                            </a>
                        </div>
                    </div>

                    <!-- Languages Spoken Widget -->
                    <div class="glass-panel p-6 rounded-xl border border-slate-800 space-y-4">
                        <h4 class="text-sm font-bold text-white uppercase tracking-wider font-mono flex items-center gap-2">
                            <i class="fa-solid fa-language text-cyan-400"></i>
                            <span>Languages Spoken</span>
                        </h4>
                        <div class="space-y-3">
                            <?php foreach ($languages as $lang): ?>
                                <div>
                                    <div class="flex justify-between text-xs font-mono mb-1">
                                        <span class="text-slate-300"><?= e($lang['flag']) ?> <?= e($lang['name']) ?></span>
                                        <span class="text-slate-400"><?= e($lang['level']) ?></span>
                                    </div>
                                    <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-gradient-to-r from-emerald-500 to-cyan-500 h-1.5 rounded-full" style="width: <?= (int)$lang['percent'] ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>


    <!-- =========================================================================
         3. TECHNICAL PROFICIENCIES & SKILLS MATRIX SECTION
         ========================================================================= -->
    <section id="skills" class="py-20 border-b border-slate-800/60 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="mb-14 section-reveal-header">
                <div class="inline-flex items-center gap-2 text-xs font-mono text-emerald-400 uppercase tracking-widest mb-2">
                    <span class="w-6 h-px bg-emerald-400"></span>
                    <span>02 // CAPABILITIES & ARSENAL</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    Technical Proficiencies Matrix
                </h2>
                <div class="scanning-laser-container">
                    <div class="scanning-laser-bar"></div>
                </div>
                <p class="text-slate-400 mt-2 max-w-2xl text-sm sm:text-base">
                    Comprehensive offensive security, systems infrastructure administration, and clean software engineering.
                </p>
            </div>

            <!-- Categories Tabs / Grid -->
            <div class="space-y-12">
                <?php foreach ($skillsCategories as $category): ?>
                    <div class="glass-panel p-6 sm:p-8 rounded-2xl border border-slate-800 space-y-6" data-skill-category-id="<?= e($category['id']) ?>">
                        
                        <!-- Category Header -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-800 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-slate-900 border border-slate-700 flex items-center justify-center text-emerald-400 text-lg">
                                    <i class="<?= e($category['icon']) ?>"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white"><?= e($category['name']) ?></h3>
                                    <p class="text-xs text-slate-400 font-sans"><?= e($category['subtitle']) ?></p>
                                </div>
                            </div>
                            <span class="cyber-badge badge-<?= e($category['accent']) ?> self-start sm:self-auto font-mono">
                                <?= count($category['skills']) ?> Core Proficiencies
                            </span>
                        </div>

                        <!-- Skills Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <?php foreach ($category['skills'] as $skill): ?>
                                <div class="bg-slate-900/60 border border-slate-800/90 rounded-xl p-4 hover:border-emerald-500/40 hover:bg-slate-900/90 transition-all flex flex-col justify-between group">
                                    <div>
                                        <div class="flex items-start justify-between gap-2 mb-2">
                                            <i class="<?= e($skill['icon']) ?> text-emerald-400 text-base mt-1 group-hover:scale-110 transition-transform"></i>
                                            <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-slate-800 text-slate-300 border border-slate-700">
                                                <?= e($skill['badge']) ?>
                                            </span>
                                        </div>
                                        <h4 class="text-sm font-bold text-white mb-1 group-hover:text-emerald-300 transition-colors">
                                            <?= e($skill['name']) ?>
                                        </h4>
                                        <p class="text-xs text-slate-400 leading-relaxed font-sans mb-3">
                                            <?= e($skill['desc']) ?>
                                        </p>
                                    </div>

                                    <!-- Proficiency Bar -->
                                    <div class="pt-2 border-t border-slate-800/60">
                                        <div class="flex justify-between text-[11px] font-mono text-slate-400 mb-1">
                                            <span>Proficiency</span>
                                            <span class="text-emerald-400"><?= (int)$skill['level'] ?>%</span>
                                        </div>
                                        <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                            <div class="skill-progress-bar bg-gradient-to-r from-emerald-500 to-cyan-400 h-1.5 rounded-full" data-target-width="<?= (int)$skill['level'] ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>


    <!-- =========================================================================
         4. VAPT METHODOLOGY & THREAT MODELING WORKFLOW SECTION
         ========================================================================= -->
    <section id="methodology" class="py-20 border-b border-slate-800/60 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="mb-14 section-reveal-header">
                <div class="inline-flex items-center gap-2 text-xs font-mono text-emerald-400 uppercase tracking-widest mb-2">
                    <span class="w-6 h-px bg-emerald-400"></span>
                    <span>03 // SECURITY AUDITING WORKFLOW</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    Structured VAPT Methodology
                </h2>
                <div class="scanning-laser-container">
                    <div class="scanning-laser-bar"></div>
                </div>
                <p class="text-slate-400 mt-2 max-w-2xl text-sm sm:text-base">
                    A rigorous 5-stage lifecycle ensuring no vulnerability is left undocumented and every finding has verifiable proof.
                </p>
            </div>

            <!-- 5 Phase Step Flow -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 relative">
                <?php foreach ($vaptMethodology as $idx => $step): ?>
                    <div class="glass-panel p-5 rounded-xl border border-slate-800 flex flex-col justify-between relative group hover:border-emerald-500/40">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-2xl font-black font-mono text-emerald-400/30 group-hover:text-emerald-400 transition-colors">
                                    <?= e($step['phase']) ?>
                                </span>
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            </div>
                            <h3 class="text-sm font-bold text-white mb-2"><?= e($step['name']) ?></h3>
                            <p class="text-xs text-slate-400 leading-relaxed font-sans mb-4">
                                <?= e($step['desc']) ?>
                            </p>
                        </div>

                        <!-- Tools Tagging -->
                        <div class="pt-3 border-t border-slate-800/80">
                            <span class="text-[10px] font-mono text-slate-500 block mb-1 uppercase">Tooling:</span>
                            <div class="flex flex-wrap gap-1">
                                <?php foreach ($step['tools'] as $tool): ?>
                                    <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-slate-800 text-slate-300">
                                        <?= e($tool) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- VAPT Guarantee Banner -->
            <div class="mt-8 p-5 rounded-xl bg-slate-900/80 border border-emerald-500/30 flex flex-col sm:flex-row items-center justify-between gap-4 font-mono text-xs">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-shield-halved text-emerald-400 text-xl"></i>
                    <div>
                        <span class="text-white font-bold block">Zero False-Positive Guarantee</span>
                        <span class="text-slate-400">All findings backed by step-by-step reproducible Proof-of-Concepts (PoCs) and CVSS v3.1 metrics.</span>
                    </div>
                </div>
                <a href="#contact" class="cyber-btn cyber-btn-primary text-xs py-2 px-4 whitespace-nowrap">
                    Request Security Audit
                </a>
            </div>

        </div>
    </section>


    <!-- =========================================================================
         5. CAREER EXPERIENCE SECTION
         ========================================================================= -->
    <section id="experience" class="py-20 border-b border-slate-800/60 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="mb-14 section-reveal-header">
                <div class="inline-flex items-center gap-2 text-xs font-mono text-emerald-400 uppercase tracking-widest mb-2">
                    <span class="w-6 h-px bg-emerald-400"></span>
                    <span>04 // PROVEN TRACK RECORD</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    Professional Experience
                </h2>
                <div class="scanning-laser-container">
                    <div class="scanning-laser-bar"></div>
                </div>
                <p class="text-slate-400 mt-2 max-w-2xl text-sm sm:text-base">
                    Real-world enterprise network operations, hands-on cybersecurity instruction, and penetration testing.
                </p>
            </div>

            <!-- Timeline with Interactive Dynamic SVG Stem -->
            <div class="relative space-y-8 pl-4 md:pl-8">
                <!-- Glowing Scroll-Driven SVG Timeline Line -->
                <div class="absolute left-2 md:left-4 top-4 bottom-4 w-1 -translate-x-1/2 pointer-events-none hidden sm:block">
                    <svg id="timeline-svg" class="w-full h-full" preserveAspectRatio="none">
                        <line id="timeline-path" class="timeline-svg-path" x1="50%" y1="0%" x2="50%" y2="100%" stroke="url(#timelineGrad)" stroke-width="3" />
                        <defs>
                            <linearGradient id="timelineGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#10b981" />
                                <stop offset="50%" stop-color="#06b6d4" />
                                <stop offset="100%" stop-color="#6366f1" />
                            </linearGradient>
                        </defs>
                    </svg>
                </div>

                <?php foreach ($experience as $exp): ?>
                    <div class="glass-panel p-6 sm:p-8 rounded-2xl border border-slate-800 relative hover:border-emerald-500/40 transition-all ml-0 sm:ml-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-slate-800 pb-4 mb-5">
                            <div class="flex items-start gap-4">
                                <!-- Pulsing Milestone Node Indicator -->
                                <div class="timeline-pulse-node mt-1.5 shrink-0" title="Milestone Verified"></div>
                                <div>
                                    <span class="cyber-badge badge-emerald font-mono mb-2"><?= e($exp['type']) ?></span>
                                    <h3 class="text-xl sm:text-2xl font-bold text-white"><?= e($exp['role']) ?></h3>
                                    <p class="text-sm font-mono text-emerald-400 mt-0.5">
                                        <i class="fa-solid fa-building mr-1"></i> <?= e($exp['company']) ?> &bull; <i class="fa-solid fa-location-dot ml-1 mr-1"></i> <?= e($exp['location']) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="font-mono text-xs text-slate-400 bg-slate-900 px-3 py-1.5 rounded-lg border border-slate-800 self-start md:self-auto">
                                <i class="fa-regular fa-calendar-days text-emerald-400 mr-1.5"></i> <?= e($exp['period']) ?>
                            </div>
                        </div>

                        <!-- Highlights List -->
                        <div class="space-y-2 mb-6">
                            <?php foreach ($exp['highlights'] as $highlight): ?>
                                <div class="flex items-start gap-3 text-slate-300 text-sm leading-relaxed">
                                    <span class="text-emerald-400 mt-1 text-xs"><i class="fa-solid fa-chevron-right"></i></span>
                                    <span><?= e($highlight) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Technology Tags -->
                        <div class="pt-4 border-t border-slate-800/80 flex flex-wrap items-center gap-2">
                            <span class="text-xs font-mono text-slate-500 mr-2">Key Competencies:</span>
                            <?php foreach ($exp['tags'] as $tag): ?>
                                <span class="text-xs font-mono px-2.5 py-1 rounded bg-slate-800/80 text-slate-300 border border-slate-700/60">
                                    #<?= e($tag) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>


    <!-- =========================================================================
         6. SELECTED WEB & SECURITY PROJECTS SECTION
         ========================================================================= -->
    <section id="projects" class="py-20 border-b border-slate-800/60 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4 section-reveal-header">
                <div>
                    <div class="inline-flex items-center gap-2 text-xs font-mono text-emerald-400 uppercase tracking-widest mb-2">
                        <span class="w-6 h-px bg-emerald-400"></span>
                        <span>05 // ARTIFACTS & DELIVERABLES</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                        Featured Web & Security Projects
                    </h2>
                    <div class="scanning-laser-container">
                        <div class="scanning-laser-bar"></div>
                    </div>
                    <p class="text-slate-400 mt-2 max-w-2xl text-sm sm:text-base">
                        A curated showcase of production web applications, hardware security tooling, and e-commerce architectures.
                    </p>
                </div>

                <!-- Filter Controls -->
                <div class="flex flex-wrap gap-2 font-mono text-xs">
                    <button type="button" class="project-filter-btn px-3 py-1.5 rounded-lg border bg-emerald-500/20 text-emerald-300 border-emerald-500/40 transition-all" data-filter="all">
                        All Projects (<?= count($projects) ?>)
                    </button>
                    <button type="button" class="project-filter-btn px-3 py-1.5 rounded-lg border bg-slate-800/60 text-slate-400 border-slate-700/50 hover:text-white transition-all" data-filter="security">
                        Security & IoT
                    </button>
                    <button type="button" class="project-filter-btn px-3 py-1.5 rounded-lg border bg-slate-800/60 text-slate-400 border-slate-700/50 hover:text-white transition-all" data-filter="web">
                        Web Platforms
                    </button>
                    <button type="button" class="project-filter-btn px-3 py-1.5 rounded-lg border bg-slate-800/60 text-slate-400 border-slate-700/50 hover:text-white transition-all" data-filter="ecommerce">
                        E-Commerce
                    </button>
                    <button type="button" class="project-filter-btn px-3 py-1.5 rounded-lg border bg-slate-800/60 text-slate-400 border-slate-700/50 hover:text-white transition-all" data-filter="corporate">
                        Corporate & NGO
                    </button>
                </div>
            </div>

            <!-- Projects Grid with 3D Tilt & Lightbox Triggers -->
            <div id="projects-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($projects as $project): 
                    $projectImg = !empty($project['image_path']) ? $baseUrl . '/' . ltrim($project['image_path'], '/') : $baseUrl . '/assets/img/profile-cyber.svg';
                ?>
                    <div class="perspective-container">
                        <div class="project-card tilt-card glass-panel rounded-2xl border border-slate-800 overflow-hidden flex flex-col justify-between group hover:border-emerald-500/50 relative h-full" data-category="<?= e($project['category']) ?>">
                            
                            <!-- Radial Holographic Shine Overlay -->
                            <div class="tilt-card-shine"></div>

                            <!-- Project Screenshot / Graphic Preview Header -->
                            <div class="h-44 w-full overflow-hidden bg-slate-950 border-b border-slate-800 relative">
                                <img src="<?= e($projectImg) ?>" alt="<?= e($project['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90 group-hover:opacity-100" onerror="this.onerror=null; this.src='<?= e($baseUrl) ?>/assets/img/profile-cyber.svg';">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0c1220] via-[#0c1220]/40 to-transparent"></div>
                                <div class="absolute top-3 left-3 z-10">
                                    <span class="cyber-badge <?= $project['category'] === 'security' ? 'badge-amber' : ($project['category'] === 'web' ? 'badge-cyan' : 'badge-emerald') ?>">
                                        <?= e($project['badge']) ?>
                                    </span>
                                </div>
                                <?php if (!empty($project['featured'])): ?>
                                    <div class="absolute top-3 right-3 z-10">
                                        <span class="cyber-badge badge-emerald text-[10px] font-bold"><i class="fa-solid fa-star text-[9px] mr-1"></i>Featured</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Interactive Card Hover Action Overlay -->
                            <div class="project-action-overlay">
                                <button type="button" class="lightbox-trigger cyber-btn cyber-btn-primary text-xs py-2.5 px-4 font-mono shadow-lg" data-project-json="<?= rawurlencode(json_encode($project)) ?>">
                                    <i class="fa-solid fa-expand mr-1.5"></i> Inspect PoC & Architecture
                                </button>
                                <?php if (!empty($project['demo_url'])): ?>
                                    <a href="<?= e($project['demo_url']) ?>" target="_blank" rel="noopener noreferrer" class="cyber-btn cyber-btn-secondary text-xs py-2 px-4 font-mono">
                                        <i class="fa-solid fa-arrow-up-right-from-square mr-1.5"></i> Launch Live Target
                                    </a>
                                <?php endif; ?>
                            </div>

                            <!-- Card Body -->
                            <div class="p-6 pb-0 relative z-10 flex-grow">
                                <h3 class="text-xl font-bold text-white group-hover:text-emerald-400 transition-colors">
                                    <?= e($project['title']) ?>
                                </h3>

                                <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                                    <?= e($project['summary']) ?>
                                </p>

                                <!-- Key Highlights -->
                                <div class="mt-4 space-y-1.5 text-xs text-slate-400">
                                    <?php foreach (array_slice($project['highlights'], 0, 3) as $point): ?>
                                        <div class="flex items-start gap-2">
                                            <i class="fa-solid fa-check text-emerald-400 text-[10px] mt-1"></i>
                                            <span><?= e($point) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Card Footer: Tech Stack & Links -->
                            <div class="p-6 pt-5 relative z-10">
                                <div class="flex flex-wrap gap-1.5 mb-5 font-mono text-[11px]">
                                    <?php foreach ($project['tech_stack'] as $tech): ?>
                                        <span class="px-2 py-0.5 rounded bg-slate-900 text-slate-300 border border-slate-800">
                                            <?= e($tech) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>

                                <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between font-mono text-xs">
                                    <button type="button" class="lightbox-trigger text-emerald-400 hover:text-emerald-300 inline-flex items-center gap-1 font-bold" data-project-json="<?= rawurlencode(json_encode($project)) ?>">
                                        <span>Details</span>
                                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                    </button>

                                    <?php if (!empty($project['github_url'])): ?>
                                        <a href="<?= e($project['github_url']) ?>" target="_blank" rel="noopener noreferrer" class="text-slate-400 hover:text-white" title="Source / Repository">
                                            <i class="fa-brands fa-github text-base"></i>
                                        </a>
                                    <?php elseif (!empty($project['demo_url'])): ?>
                                        <a href="<?= e($project['demo_url']) ?>" target="_blank" rel="noopener noreferrer" class="text-cyan-400 hover:text-cyan-300 text-xs" title="Live Website">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>


    <!-- =========================================================================
         7. INTERACTIVE TERMINAL WIDGET SECTION
         ========================================================================= -->
    <section id="terminal" class="py-20 border-b border-slate-800/60 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="mb-8 section-reveal-header">
                <div class="inline-flex items-center gap-2 text-xs font-mono text-cyan-400 uppercase tracking-widest mb-2">
                    <span class="w-6 h-px bg-cyan-400"></span>
                    <span>06 // INTERACTIVE CONSOLE EMULATOR</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    Hacker Terminal & Command Shell
                </h2>
                <div class="scanning-laser-container">
                    <div class="scanning-laser-bar"></div>
                </div>
                <p class="text-slate-400 mt-2 max-w-2xl text-sm sm:text-base">
                    Type commands or click quick chips below to query skills, execute mock VAPT scans, and explore dossier records.
                </p>
            </div>

            <!-- Quick Command Chips -->
            <div class="flex flex-wrap items-center gap-2 mb-4 font-mono text-xs">
                <span class="text-slate-500 mr-1">Quick Run:</span>
                <button type="button" class="terminal-chip px-2.5 py-1 rounded bg-slate-800 hover:bg-emerald-500/20 text-emerald-300 border border-slate-700 hover:border-emerald-500/50 transition-colors" data-cmd="help">help</button>
                <button type="button" class="terminal-chip px-2.5 py-1 rounded bg-slate-800 hover:bg-emerald-500/20 text-emerald-300 border border-slate-700 hover:border-emerald-500/50 transition-colors" data-cmd="whoami">whoami</button>
                <button type="button" class="terminal-chip px-2.5 py-1 rounded bg-slate-800 hover:bg-emerald-500/20 text-emerald-300 border border-slate-700 hover:border-emerald-500/50 transition-colors" data-cmd="ls -la">ls -la</button>
                <button type="button" class="terminal-chip px-2.5 py-1 rounded bg-slate-800 hover:bg-cyan-500/20 text-cyan-300 border border-slate-700 hover:border-cyan-500/50 transition-colors" data-cmd="neofetch">neofetch</button>
                <button type="button" class="terminal-chip px-2.5 py-1 rounded bg-slate-800 hover:bg-emerald-500/20 text-emerald-300 border border-slate-700 hover:border-emerald-500/50 transition-colors" data-cmd="cat about.txt">cat about.txt</button>
                <button type="button" class="terminal-chip px-2.5 py-1 rounded bg-slate-800 hover:bg-cyan-500/20 text-cyan-300 border border-slate-700 hover:border-cyan-500/50 transition-colors" data-cmd="nmap target.local">nmap target.local</button>
                <button type="button" class="terminal-chip px-2.5 py-1 rounded bg-slate-800 hover:bg-emerald-500/20 text-emerald-300 border border-slate-700 hover:border-emerald-500/50 transition-colors" data-cmd="vapt">vapt</button>
                <button type="button" class="terminal-chip px-2.5 py-1 rounded bg-slate-800 hover:bg-emerald-500/20 text-emerald-300 border border-slate-700 hover:border-emerald-500/50 transition-colors" data-cmd="projects">projects</button>
                <button type="button" class="terminal-chip px-2.5 py-1 rounded bg-slate-800 hover:bg-yellow-500/20 text-yellow-300 border border-slate-700 hover:border-yellow-500/50 transition-colors" data-cmd="skills">skills</button>
                <button type="button" class="terminal-chip px-2.5 py-1 rounded bg-slate-800 hover:bg-emerald-500/20 text-emerald-300 border border-slate-700 hover:border-emerald-500/50 transition-colors" data-cmd="tree">tree</button>
                <button type="button" class="terminal-chip px-2.5 py-1 rounded bg-slate-800 hover:bg-indigo-500/20 text-indigo-300 border border-slate-700 hover:border-indigo-500/50 transition-colors" data-cmd="ps aux">ps aux</button>
                <button type="button" class="terminal-chip px-2.5 py-1 rounded bg-slate-800 hover:bg-emerald-500/20 text-emerald-300 border border-slate-700 hover:border-emerald-500/50 transition-colors" data-cmd="matrix">matrix</button>
                <button type="button" class="terminal-chip px-2.5 py-1 rounded bg-slate-800 hover:bg-red-500/20 text-red-300 border border-slate-700 hover:border-red-500/50 transition-colors" data-cmd="clear">clear</button>
            </div>

            <!-- Terminal Window Container -->
            <div class="terminal-container shadow-2xl relative border border-slate-700" id="terminal-window">
                <div class="scanlines-overlay absolute inset-0 rounded-lg pointer-events-none"></div>
                
                <div class="terminal-header">
                    <div class="terminal-dots">
                        <span class="terminal-dot dot-red"></span>
                        <span class="terminal-dot dot-yellow"></span>
                        <span class="terminal-dot dot-green"></span>
                    </div>
                    <span class="text-xs font-mono text-slate-400" id="terminal-title-bar">bajrang@security-node: ~ (interactive bash shell)</span>
                    <span class="text-[11px] font-mono text-emerald-400 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>TTY: /dev/pts/0</span>
                    </span>
                </div>

                <div class="terminal-body font-mono text-xs" id="terminal-body-container">
                    <div class="text-slate-400 mb-2 leading-relaxed" id="terminal-welcome-msg">
                        Linux security-node 6.8.0-kali3-amd64 #1 SMP PREEMPT x86_64 GNU/Linux.<br>
                        Security Shell initialized. Connected to node <span class="text-emerald-400 font-bold">127.0.0.1 (Birgunj, Nepal)</span>.<br>
                        Type <span class="text-emerald-400 font-bold">help</span> to list commands, <span class="text-cyan-400 font-bold">ls -la</span> to explore files, or use the quick chips.
                    </div>
                    
                    <!-- Dynamic Output Container -->
                    <div id="terminal-output" class="space-y-1"></div>

                    <!-- Input Line -->
                    <div class="flex items-center gap-2 mt-2 pt-2 border-t border-slate-800/80" id="terminal-input-row">
                        <span id="terminal-prompt" class="text-emerald-400 font-bold font-mono shrink-0 select-none">bajrang@security-node:~$</span>
                        <input type="text" id="terminal-input" autocomplete="off" spellcheck="false" placeholder="type any Linux command (e.g. ls -la, cat about.txt, nmap, neofetch)..." class="flex-grow bg-transparent text-slate-100 focus:outline-none font-mono text-xs caret-emerald-400">
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!-- =========================================================================
         8. BUG BOUNTY & VULNERABILITY RESEARCH SPOTLIGHT
         ========================================================================= -->
    <section id="research" class="py-20 border-b border-slate-800/60 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="mb-14 section-reveal-header">
                <div class="inline-flex items-center gap-2 text-xs font-mono text-emerald-400 uppercase tracking-widest mb-2">
                    <span class="w-6 h-px bg-emerald-400"></span>
                    <span>07 // ETHICAL DISCLOSURES & RESEARCH</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    Bug Bounty & Vulnerability Research
                </h2>
                <div class="scanning-laser-container">
                    <div class="scanning-laser-bar"></div>
                </div>
                <p class="text-slate-400 mt-2 max-w-2xl text-sm sm:text-base">
                    Authorized security research identifying critical flaw vectors with reproducible Proof-of-Concepts (PoCs) under responsible disclosure policies.
                </p>
            </div>

            <!-- Bug Bounty Overview Card -->
            <div class="glass-panel p-6 sm:p-8 rounded-2xl border border-slate-800 mb-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 font-mono text-xs">
                    <?php foreach ($bugBounty['stats'] ?? [] as $st): ?>
                        <div class="p-4 rounded-xl bg-slate-900 border border-slate-800">
                            <span class="text-slate-500 uppercase tracking-wider block mb-1"><?= e($st['label']) ?></span>
                            <span class="text-emerald-400 font-bold text-sm"><?= e($st['val']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <p class="text-slate-300 text-sm leading-relaxed">
                    <?= e($bugBounty['overview']) ?>
                </p>
            </div>

            <!-- Disclosed Vulnerability Classes Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($bugBounty['vulnerabilities_found'] ?? [] as $vuln): ?>
                    <div class="glass-panel p-6 rounded-xl border border-slate-800 flex flex-col justify-between hover:border-emerald-500/40">
                        <div>
                            <div class="flex items-center justify-between mb-3 font-mono text-xs">
                                <span class="cyber-badge badge-<?= e($vuln['badge_color']) ?>"><?= e($vuln['severity']) ?></span>
                                <span class="text-slate-400">CVSS: <strong class="text-white"><?= e($vuln['cvss']) ?></strong></span>
                            </div>
                            <h3 class="text-base font-bold text-white mb-2"><?= e($vuln['type']) ?></h3>
                            <p class="text-xs text-slate-300 leading-relaxed mb-4">
                                <?= e($vuln['summary']) ?>
                            </p>
                        </div>

                        <div class="space-y-3 pt-3 border-t border-slate-800 font-sans text-xs">
                            <div>
                                <span class="font-mono text-slate-400 block text-[10px] uppercase">Business Impact:</span>
                                <span class="text-red-300"><?= e($vuln['impact']) ?></span>
                            </div>
                            <div>
                                <span class="font-mono text-slate-400 block text-[10px] uppercase">Engineered Remediation:</span>
                                <span class="text-emerald-300"><?= e($vuln['fix']) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>


    <!-- =========================================================================
         9. CERTIFICATIONS, EDUCATION & AWARDS SECTION
         ========================================================================= -->
    <section id="certifications" class="py-20 border-b border-slate-800/60 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="mb-14 section-reveal-header">
                <div class="inline-flex items-center gap-2 text-xs font-mono text-emerald-400 uppercase tracking-widest mb-2">
                    <span class="w-6 h-px bg-emerald-400"></span>
                    <span>08 // CREDENTIALS & ACADEMICS</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    Education & Certifications
                </h2>
                <div class="scanning-laser-container">
                    <div class="scanning-laser-bar"></div>
                </div>
                <p class="text-slate-400 mt-2 max-w-2xl text-sm sm:text-base">
                    Recognized credentials in enterprise Linux administration, cybersecurity, and advanced computer networks.
                </p>
            </div>

            <!-- Degree Feature Card -->
            <?php foreach ($education as $edu): ?>
                <div class="glass-panel p-6 sm:p-8 rounded-2xl border border-slate-800 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:border-emerald-500/40">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-2xl shrink-0">
                            <i class="<?= e($edu['icon']) ?>"></i>
                        </div>
                        <div>
                            <span class="cyber-badge badge-emerald font-mono mb-1"><?= e($edu['specialization']) ?></span>
                            <h3 class="text-xl font-bold text-white"><?= e($edu['degree']) ?></h3>
                            <p class="text-sm font-mono text-emerald-400">
                                <i class="fa-solid fa-school mr-1"></i> <?= e($edu['institution']) ?> &bull; <?= e($edu['location']) ?>
                            </p>
                            <p class="text-xs text-slate-300 mt-2 max-w-2xl font-sans">
                                <?= e($edu['desc']) ?>
                            </p>
                        </div>
                    </div>
                    <div class="font-mono text-xs text-slate-400 bg-slate-900 px-4 py-2 rounded-lg border border-slate-800 self-start md:self-auto">
                        Graduated
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Certifications Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <?php foreach ($certifications as $cert): 
                    $hasBadgeImg = !empty($cert['badge_image_path']) && $cert['badge_image_path'] !== 'assets/img/profile-cyber.svg';
                    $certBadgeSrc = !empty($cert['badge_image_path']) ? $baseUrl . '/' . ltrim($cert['badge_image_path'], '/') : '';
                ?>
                    <div class="glass-panel p-6 rounded-xl border border-slate-800 flex items-start gap-4 hover:border-emerald-500/40 transition-all">
                        <?php if ($hasBadgeImg): ?>
                            <div class="w-12 h-12 rounded-lg bg-slate-900 border border-slate-700 flex items-center justify-center shrink-0 p-1 overflow-hidden shadow-[0_0_10px_rgba(16,185,129,0.15)]">
                                <img src="<?= e($certBadgeSrc) ?>" alt="<?= e($cert['title']) ?>" class="w-full h-full object-contain" onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\'<?= e($cert['icon']) ?>\'></i>';">
                            </div>
                        <?php else: ?>
                            <div class="w-11 h-11 rounded-lg bg-slate-900 border border-slate-700 flex items-center justify-center text-xl shrink-0" style="color: <?= e($cert['color']) ?>">
                                <i class="<?= e($cert['icon']) ?>"></i>
                            </div>
                        <?php endif; ?>
                        <div class="space-y-1 flex-grow">
                            <div class="flex items-center gap-2">
                                <span class="cyber-badge badge-cyan font-mono text-[10px]"><?= e($cert['badge']) ?></span>
                                <span class="text-xs font-mono text-slate-500"><?= e($cert['category']) ?></span>
                            </div>
                            <h4 class="text-base font-bold text-white"><?= e($cert['title']) ?></h4>
                            <p class="text-xs font-mono text-emerald-400"><?= e($cert['issuer']) ?></p>
                            <p class="text-xs text-slate-400 font-sans pt-1 leading-relaxed"><?= e($cert['desc']) ?></p>
                            <?php if (!empty($cert['credential_url'])): ?>
                                <div class="pt-1">
                                    <a href="<?= e($cert['credential_url']) ?>" target="_blank" rel="noopener noreferrer" class="text-[11px] font-mono text-cyan-400 hover:text-cyan-300 inline-flex items-center gap-1.5 hover:underline">
                                        <span>Verify Accreditation</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Hackathon Awards Strip -->
            <div>
                <h3 class="text-sm font-mono text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-trophy text-yellow-400"></i>
                    <span>Hackathon Honours & Competitions</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($awards as $award): ?>
                        <div class="glass-panel p-5 rounded-xl border border-slate-800 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-yellow-500/10 border border-yellow-500/30 flex items-center justify-center text-yellow-400 text-lg shrink-0">
                                <i class="<?= e($award['icon']) ?>"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="cyber-badge badge-amber font-mono text-[10px]"><?= e($award['badge']) ?></span>
                                    <span class="text-xs font-mono text-slate-400"><?= e($award['issuer']) ?></span>
                                </div>
                                <h4 class="text-sm font-bold text-white mt-0.5"><?= e($award['title']) ?></h4>
                                <p class="text-xs text-slate-400 font-sans mt-0.5"><?= e($award['desc']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </section>


    <!-- =========================================================================
         10. INTERACTIVE SECURITY LAB & ENTROPY ANALYZER
         ========================================================================= -->
    <section id="security-lab" class="py-20 border-b border-slate-800/60 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="mb-14 section-reveal-header">
                <div class="inline-flex items-center gap-2 text-xs font-mono text-amber-400 uppercase tracking-widest mb-2">
                    <span class="w-6 h-px bg-amber-400"></span>
                    <span>09 // INTERACTIVE DEFENSIVE LAB</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    Security Entropy Lab & Headers Inspector
                </h2>
                <div class="scanning-laser-container">
                    <div class="scanning-laser-bar"></div>
                </div>
                <p class="text-slate-400 mt-2 max-w-2xl text-sm sm:text-base">
                    Test password entropy against high-speed offline GPU clusters and review defensive HTTP headers applied across this portfolio.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Tool 1: Entropy & Crack Time Analyzer -->
                <div class="lg:col-span-6 glass-panel p-6 sm:p-8 rounded-2xl border border-slate-800 space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-key text-amber-400 text-xl"></i>
                            <div>
                                <h3 class="text-lg font-bold text-white">Password Entropy & Crack-Time Estimator</h3>
                                <p class="text-xs text-slate-400 font-mono">Shannon Entropy Calculation: E = L &times; log<sub>2</sub>(R)</p>
                            </div>
                        </div>
                        <span class="cyber-badge badge-amber font-mono">CLIENT-SIDE</span>
                    </div>

                    <!-- Input -->
                    <div>
                        <label for="entropy-input" class="block text-xs font-mono text-slate-300 mb-2">
                            Enter sample password or passphrase (processed locally only, never stored):
                        </label>
                        <input type="text" id="entropy-input" placeholder="Type test passphrase (e.g. CorrectHorseBatteryStaple#99)" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-lg text-white font-mono text-sm focus:outline-none focus:border-emerald-500">
                    </div>

                    <!-- Visual Strength Bar -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span id="entropy-strength-label" class="text-xs font-mono text-slate-500">Waiting for input...</span>
                            <span id="entropy-bits" class="text-xs font-mono text-emerald-400 font-bold">0 bits</span>
                        </div>
                        <div class="w-full bg-slate-800 h-2 rounded-full overflow-hidden">
                            <div id="entropy-bar" class="h-full bg-slate-600 transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Crack Metrics Grid -->
                    <div class="grid grid-cols-2 gap-4 pt-2 font-mono text-xs">
                        <div class="p-4 rounded-xl bg-slate-900 border border-slate-800">
                            <span class="text-slate-500 block mb-1">Time to Crack (100B H/s GPU):</span>
                            <span id="entropy-crack-time" class="text-white font-bold text-sm">Instant</span>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-900 border border-slate-800">
                            <span class="text-slate-500 block mb-1">Recommended Length:</span>
                            <span class="text-emerald-400 font-bold text-sm">&gt; 16 Characters</span>
                        </div>
                    </div>

                    <p class="text-xs text-slate-400 font-sans leading-relaxed">
                        <strong class="text-slate-300">Auditor's Advice:</strong> Passphrases consisting of 4 or 5 random dictionary words combined with distinct symbols yield superior entropy over 8-character complex passwords and resist targeted dictionary attacks.
                    </p>
                </div>

                <!-- Tool 2: Active Security Headers Inspection -->
                <div class="lg:col-span-6 glass-panel p-6 sm:p-8 rounded-2xl border border-slate-800 space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-shield-heart text-emerald-400 text-xl"></i>
                            <div>
                                <h3 class="text-lg font-bold text-white">Hardened HTTP Security Headers</h3>
                                <p class="text-xs text-slate-400 font-mono">Enforced directly by PortfolioSecurity::applySecurityHeaders()</p>
                            </div>
                        </div>
                        <span class="cyber-badge badge-emerald font-mono">ACTIVE</span>
                    </div>

                    <div class="space-y-3 font-mono text-xs">
                        <div class="p-3 rounded-lg bg-slate-900 border border-slate-800">
                            <div class="flex justify-between items-center text-emerald-400 font-bold">
                                <span>Content-Security-Policy (CSP)</span>
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <p class="text-slate-400 text-[11px] font-sans mt-1">
                                Restricts source execution to trusted endpoints, neutralizing Cross-Site Scripting (XSS) and injection vectors.
                            </p>
                        </div>

                        <div class="p-3 rounded-lg bg-slate-900 border border-slate-800">
                            <div class="flex justify-between items-center text-emerald-400 font-bold">
                                <span>X-Frame-Options: SAMEORIGIN</span>
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <p class="text-slate-400 text-[11px] font-sans mt-1">
                                Disallows unauthorized iframe embedding to prevent clickjacking and UI redressing attacks.
                            </p>
                        </div>

                        <div class="p-3 rounded-lg bg-slate-900 border border-slate-800">
                            <div class="flex justify-between items-center text-emerald-400 font-bold">
                                <span>X-Content-Type-Options: nosniff</span>
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <p class="text-slate-400 text-[11px] font-sans mt-1">
                                Stops browsers from MIME-sniffing responses away from the declared content-type.
                            </p>
                        </div>

                        <div class="p-3 rounded-lg bg-slate-900 border border-slate-800">
                            <div class="flex justify-between items-center text-emerald-400 font-bold">
                                <span>Referrer-Policy: strict-origin-when-cross-origin</span>
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <p class="text-slate-400 text-[11px] font-sans mt-1">
                                Protects user confidentiality by pruning sensitive referrer headers on cross-origin requests.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>


    <!-- =========================================================================
         11. SECURE CONTACT FORM SECTION
         ========================================================================= -->
    <section id="contact" class="py-20 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="mb-14 section-reveal-header">
                <div class="inline-flex items-center gap-2 text-xs font-mono text-emerald-400 uppercase tracking-widest mb-2">
                    <span class="w-6 h-px bg-emerald-400"></span>
                    <span>10 // DIRECT TRANSMISSION</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    Initiate Contact & Engagement
                </h2>
                <div class="scanning-laser-container">
                    <div class="scanning-laser-bar"></div>
                </div>
                <p class="text-slate-400 mt-2 max-w-2xl text-sm sm:text-base">
                    Available for VAPT security audits, web application engineering, corporate cybersecurity training, and penetration testing consultations.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                
                <!-- Left: Direct Info Cards -->
                <div class="lg:col-span-5 space-y-6">
                    
                    <div class="glass-panel p-6 sm:p-8 rounded-2xl border border-slate-800 space-y-6">
                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-satellite-dish text-emerald-400"></i>
                            <span>Communication Coordinates</span>
                        </h3>

                        <div class="space-y-4 font-mono text-xs">
                            <!-- Location -->
                            <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 flex items-start gap-4">
                                <div class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shrink-0">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div>
                                    <span class="text-slate-500 block uppercase text-[10px]">Station Location</span>
                                    <span class="text-white font-bold text-sm"><?= e($profile['location']) ?></span>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Nepal Standard Time (UTC+5:45)</p>
                                </div>
                            </div>

                            <!-- Email with Copy -->
                            <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 flex items-start justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400 shrink-0">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <div>
                                        <span class="text-slate-500 block uppercase text-[10px]">Email Dispatch</span>
                                        <a href="mailto:<?= e($profile['email']) ?>" class="text-emerald-400 hover:text-emerald-300 font-bold text-xs sm:text-sm break-all"><?= e($profile['email']) ?></a>
                                    </div>
                                </div>
                                <button class="copy-trigger text-slate-400 hover:text-emerald-400 p-2" data-copy="<?= e($profile['email']) ?>" title="Copy Email">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>

                            <!-- Phone with WhatsApp link -->
                            <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 flex items-start justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shrink-0">
                                        <i class="fa-solid fa-phone"></i>
                                    </div>
                                    <div>
                                        <span class="text-slate-500 block uppercase text-[10px]">Direct Phone & WhatsApp</span>
                                        <a href="tel:<?= e($profile['phone']) ?>" class="text-white hover:text-emerald-300 font-bold text-sm"><?= e($profile['phone']) ?></a>
                                    </div>
                                </div>
                                <a href="https://wa.me/9779816266554" target="_blank" rel="noopener noreferrer" class="text-emerald-400 hover:text-emerald-300 p-2" title="Chat on WhatsApp">
                                    <i class="fa-brands fa-whatsapp text-lg"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Response Protocol Note -->
                        <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800/80 font-mono text-[11px] text-slate-400 flex items-center gap-3">
                            <i class="fa-solid fa-clock-rotate-left text-emerald-400 text-base"></i>
                            <span>Standard Response Time: Under 24 hours across all channels.</span>
                        </div>
                    </div>

                </div>

                <!-- Right: Secure Form -->
                <div class="lg:col-span-7">
                    <div class="glass-panel p-6 sm:p-8 rounded-2xl border border-slate-800 relative">
                        
                        <!-- Status Alert Box for AJAX responses -->
                        <div id="form-status-box" class="hidden mb-6"></div>

                        <!-- PHP Session Flash fallback if submitted without JS -->
                        <?php if (!empty($_SESSION['flash_message'])): ?>
                            <div class="mb-6 p-4 rounded-lg <?= $_SESSION['flash_status'] === 'success' ? 'bg-emerald-500/15 border-emerald-500/40 text-emerald-300' : 'bg-red-500/15 border-red-500/40 text-red-300' ?> border text-xs font-mono">
                                <?= e($_SESSION['flash_message']) ?>
                            </div>
                            <?php 
                            unset($_SESSION['flash_message'], $_SESSION['flash_status'], $_SESSION['flash_errors']);
                            ?>
                        <?php endif; ?>

                        <form id="contact-form" action="<?= e($baseUrl) ?>/handlers/contact_process.php" method="POST" class="space-y-5" novalidate>
                            
                            <!-- CSRF Hidden Token -->
                            <?= csrf_field() ?>

                            <!-- Anti-Spam Honeypot Field (invisible to humans, catches spam bots) -->
                            <div class="hidden" aria-hidden="true">
                                <label for="website_hp">Leave this field blank</label>
                                <input type="text" id="website_hp" name="website_hp" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <!-- Name -->
                                <div class="cyber-input-wrapper">
                                    <label for="input-name" class="block text-xs font-mono text-slate-300 mb-2">
                                        Your Full Name <span class="text-emerald-400">*</span>
                                    </label>
                                    <input type="text" id="input-name" name="name" required placeholder="e.g. Alex Morgan" class="input-field-border w-full px-4 py-3 bg-slate-900/90 border border-slate-700 rounded-lg text-white font-sans text-sm focus:outline-none transition-all">
                                    <span id="error-name" class="field-error-msg text-red-400 text-xs font-mono mt-1 block"></span>
                                </div>

                                <!-- Email -->
                                <div class="cyber-input-wrapper">
                                    <label for="input-email" class="block text-xs font-mono text-slate-300 mb-2">
                                        Your Email Address <span class="text-emerald-400">*</span>
                                    </label>
                                    <input type="email" id="input-email" name="email" required placeholder="name@company.com" class="input-field-border w-full px-4 py-3 bg-slate-900/90 border border-slate-700 rounded-lg text-white font-sans text-sm focus:outline-none transition-all">
                                    <span id="error-email" class="field-error-msg text-red-400 text-xs font-mono mt-1 block"></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <!-- Service Requirement -->
                                <div class="cyber-input-wrapper">
                                    <label for="input-service" class="block text-xs font-mono text-slate-300 mb-2">
                                        Engagement Scope
                                    </label>
                                    <select id="input-service" name="service" class="input-field-border w-full px-4 py-3 bg-slate-900/90 border border-slate-700 rounded-lg text-slate-200 font-sans text-sm focus:outline-none transition-all">
                                        <option value="VAPT & Web Penetration Testing">VAPT & Web Penetration Testing</option>
                                        <option value="Network Infrastructure Audit">Network Infrastructure Audit</option>
                                        <option value="Full-Stack Web Development">Full-Stack Web Development (PHP 8)</option>
                                        <option value="Corporate Cybersecurity Training">Corporate Cybersecurity Training</option>
                                        <option value="General Technical Consultation">General Technical Consultation</option>
                                    </select>
                                </div>

                                <!-- Subject -->
                                <div class="cyber-input-wrapper">
                                    <label for="input-subject" class="block text-xs font-mono text-slate-300 mb-2">
                                        Subject Line <span class="text-emerald-400">*</span>
                                    </label>
                                    <input type="text" id="input-subject" name="subject" required placeholder="Project / Audit Inquiry" class="input-field-border w-full px-4 py-3 bg-slate-900/90 border border-slate-700 rounded-lg text-white font-sans text-sm focus:outline-none transition-all">
                                    <span id="error-subject" class="field-error-msg text-red-400 text-xs font-mono mt-1 block"></span>
                                </div>
                            </div>

                            <!-- Message Body -->
                            <div class="cyber-input-wrapper">
                                <label for="input-message" class="block text-xs font-mono text-slate-300 mb-2">
                                    Scope & Project Details <span class="text-emerald-400">*</span>
                                </label>
                                <textarea id="input-message" name="message" rows="5" required placeholder="Describe target architecture, timelines, or security objectives..." class="input-field-border w-full px-4 py-3 bg-slate-900/90 border border-slate-700 rounded-lg text-white font-sans text-sm focus:outline-none transition-all"></textarea>
                                <span id="error-message" class="field-error-msg text-red-400 text-xs font-mono mt-1 block"></span>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <span class="text-[11px] font-mono text-slate-500 flex items-center gap-1.5">
                                    <i class="fa-solid fa-lock text-emerald-400"></i>
                                    <span>CSRF Protected &bull; Rate-Limited &bull; Encrypted Storage</span>
                                </span>
                                <button type="submit" id="contact-submit-btn" class="w-full sm:w-auto cyber-btn cyber-btn-primary font-mono text-xs py-3 px-6">
                                    <i class="fa-solid fa-paper-plane mr-2"></i> Transmit Encrypted Message
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>

        </div>
    </section>

</main>

<?php
// Include Footer
require_once __DIR__ . '/includes/footer.php';
