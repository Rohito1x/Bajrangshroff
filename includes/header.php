<?php
/**
 * Global Header Component
 * Bajrang Kumar Sarraf - Cybersecurity Specialist & Full-Stack Web Developer
 */

declare(strict_types=1);

if (!defined('APP_ENV')) {
    require_once __DIR__ . '/../config.php';
}

PortfolioSecurity::applySecurityHeaders();

$baseUrl = AppConfig::baseUrl();
$profile = !empty($profile) ? $profile : ($data['profile'] ?? []);
$avatarUrl = !empty($profile['avatar_path']) ? $baseUrl . '/' . ltrim($profile['avatar_path'], '/') : $baseUrl . '/assets/img/profile-cyber.svg';
$resumeUrl = !empty($profile['resume_pdf_path']) ? $baseUrl . '/' . ltrim($profile['resume_pdf_path'], '/') : null;
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- Primary SEO Meta Tags -->
    <title><?= e(AppConfig::SITE_TITLE) ?> | Birgunj, Nepal</title>
    <meta name="title" content="<?= e(AppConfig::SITE_TITLE) ?>">
    <meta name="description" content="<?= e($profile['summary'] ?? 'Cybersecurity professional with hands-on experience in VAPT, network administration, Linux, and secure full-stack web development.') ?>">
    <meta name="keywords" content="Bajrang Kumar Sarraf, Cybersecurity, Penetration Testing, VAPT Analyst, OWASP Top 10, RHCSA, Red Hat Certified, Birgunj Nepal, Web Developer, PHP 8, Wazuh SIEM, Splunk, Burp Suite, Network Security">
    <meta name="author" content="Bajrang Kumar Sarraf">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#080c14">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e($baseUrl) ?>">
    <meta property="og:title" content="<?= e(AppConfig::SITE_TITLE) ?>">
    <meta property="og:description" content="<?= e($profile['summary'] ?? '') ?>">
    <meta property="og:site_name" content="Bajrang Kumar Sarraf Portfolio">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= e($baseUrl) ?>">
    <meta property="twitter:title" content="<?= e(AppConfig::SITE_TITLE) ?>">
    <meta property="twitter:description" content="<?= e($profile['summary'] ?? '') ?>">

    <!-- Schema.org JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Person",
      "name": "Bajrang Kumar Sarraf",
      "jobTitle": "Cybersecurity Trainer, Junior Penetration Tester, VAPT Analyst & Web Developer",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Birgunj",
        "postalCode": "44300",
        "addressCountry": "Nepal"
      },
      "email": "bajrangshroff10@gmail.com",
      "telephone": "+9779816266554",
      "knowsAbout": [
        "Vulnerability Assessment and Penetration Testing (VAPT)",
        "OWASP Top 10",
        "Web Application Security",
        "Linux System Administration",
        "RHCSA",
        "PHP 8 Web Development",
        "Network Security",
        "Wazuh SIEM",
        "Burp Suite Professional"
      ],
      "alumniOf": {
        "@type": "CollegeOrUniversity",
        "name": "Parul University"
      }
    }
    </script>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= e($baseUrl) ?>/assets/img/favicon.svg">

    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Free Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        cyber: {
                            dark: '#080c14',
                            slate: '#0d1322',
                            card: '#0f172a',
                            emerald: '#10b981',
                            neon: '#00ff9d',
                            cyan: '#06b6d4',
                            indigo: '#6366f1'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace']
                    }
                }
            }
        }
    </script>

    <!-- Global Base URL definition for scripts -->
    <script>
        window.__BASE_URL__ = <?= json_encode($baseUrl) ?>;
    </script>

    <!-- Custom Cyber Styling & Animations -->
    <link rel="stylesheet" href="<?= e($baseUrl) ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= e($baseUrl) ?>/assets/css/animations.css">
</head>
<body class="bg-[#080c14] text-slate-100 cyber-grid-bg min-h-screen flex flex-col selection:bg-emerald-500 selection:text-black antialiased">

    <!-- Top Security Protocol Banner -->
    <div class="w-full bg-[#0d1322] border-b border-slate-800 text-xs py-1.5 px-4">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between text-slate-400 font-mono">
            <div class="flex items-center gap-2">
                <span class="pulse-beacon"></span>
                <span class="text-emerald-400 font-semibold tracking-wider">SYSTEM STATUS: FORTIFIED & ONLINE</span>
                <span class="hidden sm:inline text-slate-600">|</span>
                <span class="hidden sm:inline text-slate-400"><i class="fa-solid fa-location-dot text-emerald-400/80 mr-1"></i>Birgunj, Nepal 44300</span>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <span class="text-slate-400"><i class="fa-solid fa-shield-halved text-emerald-400 mr-1"></i>RHCSA & VAPT Analyst</span>
                <a href="#terminal" class="hidden md:inline-flex items-center text-cyan-400 hover:text-cyan-300 transition-colors">
                    <i class="fa-solid fa-terminal mr-1 text-[10px]"></i> Launch CLI
                </a>
                <a href="<?= e($baseUrl) ?>/admin/" class="inline-flex items-center text-slate-400 hover:text-emerald-400 transition-colors" title="Admin Control Center">
                    <i class="fa-solid fa-lock text-[10px] mr-1"></i> CMS Login
                </a>
            </div>
        </div>
    </div>

    <!-- Sticky Navigation Bar -->
    <header class="sticky top-0 z-40 w-full glass-panel border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18 py-3">
                
                <!-- Brand / Terminal Monogram & Avatar -->
                <a href="<?= e($baseUrl) ?>/index.php" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-lg bg-slate-900 border border-emerald-500/40 flex items-center justify-center text-emerald-400 font-mono font-bold shadow-[0_0_15px_rgba(16,185,129,0.25)] group-hover:border-emerald-400 group-hover:scale-105 transition-all overflow-hidden p-0.5 relative">
                        <img src="<?= e($avatarUrl) ?>" alt="<?= e($profile['name'] ?? 'Bajrang Kumar Sarraf') ?>" class="w-full h-full object-cover rounded-md" onerror="this.onerror=null; this.src='<?= e($baseUrl) ?>/assets/img/profile-cyber.svg';">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-mono text-base font-bold text-white tracking-tight group-hover:text-emerald-400 transition-colors">
                            BAJRANG<span class="text-emerald-400">.SARRAF</span>
                        </span>
                        <span class="text-[11px] font-mono text-slate-400 tracking-wider">
                            SEC_OPS &bull; VAPT &bull; DEV
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center space-x-1 font-mono text-xs">
                    <a href="#about" class="px-3 py-2 rounded-md text-slate-300 hover:text-emerald-400 hover:bg-slate-800/60 transition-colors">
                        <span class="text-emerald-500 mr-1">#</span>about
                    </a>
                    <a href="#skills" class="px-3 py-2 rounded-md text-slate-300 hover:text-emerald-400 hover:bg-slate-800/60 transition-colors">
                        <span class="text-emerald-500 mr-1">#</span>skills
                    </a>
                    <a href="#methodology" class="px-3 py-2 rounded-md text-slate-300 hover:text-emerald-400 hover:bg-slate-800/60 transition-colors">
                        <span class="text-emerald-500 mr-1">#</span>vapt-ops
                    </a>
                    <a href="#experience" class="px-3 py-2 rounded-md text-slate-300 hover:text-emerald-400 hover:bg-slate-800/60 transition-colors">
                        <span class="text-emerald-500 mr-1">#</span>experience
                    </a>
                    <a href="#projects" class="px-3 py-2 rounded-md text-slate-300 hover:text-emerald-400 hover:bg-slate-800/60 transition-colors">
                        <span class="text-emerald-500 mr-1">#</span>projects
                    </a>
                    <a href="#terminal" class="px-3 py-2 rounded-md text-slate-300 hover:text-cyan-400 hover:bg-slate-800/60 transition-colors">
                        <span class="text-cyan-500 mr-1">&gt;</span>cli
                    </a>
                    <a href="#certifications" class="px-3 py-2 rounded-md text-slate-300 hover:text-emerald-400 hover:bg-slate-800/60 transition-colors">
                        <span class="text-emerald-500 mr-1">#</span>certs
                    </a>
                    <a href="#security-lab" class="px-3 py-2 rounded-md text-slate-300 hover:text-amber-400 hover:bg-slate-800/60 transition-colors">
                        <span class="text-amber-500 mr-1">#</span>sec-lab
                    </a>
                </nav>

                <!-- Action Button & Mobile Hamburger -->
                <div class="flex items-center gap-3">
                    <a href="#contact" class="hidden sm:inline-flex cyber-btn cyber-btn-primary text-xs py-2 px-4 font-mono">
                        <i class="fa-solid fa-envelope mr-1.5"></i> Initiate Transmission
                    </a>

                    <!-- Mobile Drawer Toggle -->
                    <button id="mobile-nav-toggle" type="button" aria-expanded="false" aria-label="Toggle navigation menu" class="lg:hidden p-2 rounded-lg bg-slate-900 border border-slate-700 text-slate-300 hover:text-emerald-400 focus:outline-none">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-800 bg-[#080c14]/95 px-4 pt-3 pb-5 space-y-2 font-mono text-sm">
            <a href="#about" class="mobile-nav-link block px-3 py-2 rounded-md text-slate-300 hover:text-emerald-400 hover:bg-slate-800/70">
                <span class="text-emerald-400 mr-2">01.</span>// about
            </a>
            <a href="#skills" class="mobile-nav-link block px-3 py-2 rounded-md text-slate-300 hover:text-emerald-400 hover:bg-slate-800/70">
                <span class="text-emerald-400 mr-2">02.</span>// skills & proficiencies
            </a>
            <a href="#methodology" class="mobile-nav-link block px-3 py-2 rounded-md text-slate-300 hover:text-emerald-400 hover:bg-slate-800/70">
                <span class="text-emerald-400 mr-2">03.</span>// vapt methodology
            </a>
            <a href="#experience" class="mobile-nav-link block px-3 py-2 rounded-md text-slate-300 hover:text-emerald-400 hover:bg-slate-800/70">
                <span class="text-emerald-400 mr-2">04.</span>// experience timeline
            </a>
            <a href="#projects" class="mobile-nav-link block px-3 py-2 rounded-md text-slate-300 hover:text-emerald-400 hover:bg-slate-800/70">
                <span class="text-emerald-400 mr-2">05.</span>// projects & builds
            </a>
            <a href="#terminal" class="mobile-nav-link block px-3 py-2 rounded-md text-slate-300 hover:text-cyan-400 hover:bg-slate-800/70">
                <span class="text-cyan-400 mr-2">06.</span>// interactive cli
            </a>
            <a href="#certifications" class="mobile-nav-link block px-3 py-2 rounded-md text-slate-300 hover:text-emerald-400 hover:bg-slate-800/70">
                <span class="text-emerald-400 mr-2">07.</span>// certs & awards
            </a>
            <a href="#security-lab" class="mobile-nav-link block px-3 py-2 rounded-md text-slate-300 hover:text-amber-400 hover:bg-slate-800/70">
                <span class="text-amber-400 mr-2">08.</span>// security entropy lab
            </a>
            <a href="#contact" class="mobile-nav-link block px-3 py-2.5 rounded-md text-center bg-emerald-500 text-black font-bold mt-3">
                <i class="fa-solid fa-paper-plane mr-1.5"></i> Contact & Hire Bajrang
            </a>
        </div>
    </header>
