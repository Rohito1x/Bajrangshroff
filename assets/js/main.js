/**
 * Main Client Application Logic
 * Bajrang Kumar Sarraf - Cybersecurity Specialist & Web Developer
 */

document.addEventListener('DOMContentLoaded', () => {
    initTypingEffect();
    initMobileNav();
    initTerminal();
    initProjectFilter();
    initContactForm();
    initEntropyAnalyzer();
    initCopyButtons();
    initSmoothScroll();
});

/* ==========================================================================
   1. Dynamic Typing Effect (Hero Section)
   ========================================================================== */
function initTypingEffect() {
    const textElement = document.getElementById('typing-text');
    if (!textElement) return;

    const appEl = document.getElementById('portfolio-app');
    let words = [
        'Cybersecurity Trainer',
        'Junior Penetration Tester',
        'VAPT Analyst',
        'Full-Stack Web Developer',
        'Linux System Administrator',
        'Security Researcher'
    ];

    if (appEl && appEl.getAttribute('data-taglines')) {
        try {
            const parsed = JSON.parse(appEl.getAttribute('data-taglines'));
            if (Array.isArray(parsed) && parsed.length > 0) words = parsed;
        } catch(e) {}
    }

    const baseTypingSpeed = appEl ? (parseInt(appEl.getAttribute('data-typing-speed'), 10) || 100) : 100;

    let wordIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    let typingSpeed = baseTypingSpeed;

    function type() {
        const currentWord = words[wordIndex];

        if (isDeleting) {
            textElement.textContent = currentWord.substring(0, charIndex - 1);
            charIndex--;
            typingSpeed = Math.max(30, Math.floor(baseTypingSpeed * 0.5));
        } else {
            textElement.textContent = currentWord.substring(0, charIndex + 1);
            charIndex++;
            typingSpeed = baseTypingSpeed;
        }

        if (!isDeleting && charIndex === currentWord.length) {
            typingSpeed = 1800; // Pause at end of word
            isDeleting = true;
        } else if (isDeleting && charIndex === 0) {
            isDeleting = false;
            wordIndex = (wordIndex + 1) % words.length;
            typingSpeed = 400; // Pause before next word
        }

        setTimeout(type, typingSpeed);
    }

    type();
}

/* ==========================================================================
   2. Mobile Navigation Drawer
   ========================================================================== */
function initMobileNav() {
    const toggleBtn = document.getElementById('mobile-nav-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuLinks = document.querySelectorAll('.mobile-nav-link');

    if (!toggleBtn || !mobileMenu) return;

    toggleBtn.addEventListener('click', () => {
        const isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';
        toggleBtn.setAttribute('aria-expanded', !isExpanded);
        mobileMenu.classList.toggle('hidden');
    });

    menuLinks.forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            toggleBtn.setAttribute('aria-expanded', 'false');
        });
    });
}

/* ==========================================================================
   3. Real Linux Terminal & Command Shell Virtual Engine
   ========================================================================== */
function initTerminal() {
    const terminalOutput = document.getElementById('terminal-output');
    const terminalInput = document.getElementById('terminal-input');
    const terminalPrompt = document.getElementById('terminal-prompt');
    const terminalTitleBar = document.getElementById('terminal-title-bar');
    const terminalChips = document.querySelectorAll('.terminal-chip');
    const terminalBodyContainer = document.getElementById('terminal-body-container') || terminalOutput?.parentElement;

    if (!terminalOutput || !terminalInput) return;

    // Shell Execution State
    let currentUser = 'bajrang';
    let currentHost = 'security-node';
    let cwd = '/home/bajrang';
    let previousDir = '/home/bajrang';
    let commandHistory = [];
    let historyIndex = -1;
    let awaitingPassword = false;
    let pendingRootCommand = null;
    let matrixRunning = false;
    let matrixInterval = null;

    // Base URL resolution
    const baseUrl = window.__BASE_URL__ || '';

    // Virtual Linux Filesystem (VFS) State
    const vfs = {
        type: 'dir', perms: 'drwxr-xr-x', owner: 'root', group: 'root', date: 'Nov 11 08:00',
        children: {
            'bin': {
                type: 'dir', perms: 'drwxr-xr-x', owner: 'root', group: 'root', date: 'Nov 11 08:00',
                children: {
                    'bash': { type: 'file', perms: '-rwxr-xr-x', owner: 'root', group: 'root', size: 1245000, date: 'Nov 11 08:00', content: 'ELF 64-bit LSB executable, x86-64, version 1 (SYSV)' },
                    'sh': { type: 'file', perms: '-rwxr-xr-x', owner: 'root', group: 'root', size: 125000, date: 'Nov 11 08:00', content: 'POSIX compliant command language interpreter' },
                    'ls': { type: 'file', perms: '-rwxr-xr-x', owner: 'root', group: 'root', size: 142000, date: 'Nov 11 08:00', content: 'List information about files (the current directory by default)' },
                    'cat': { type: 'file', perms: '-rwxr-xr-x', owner: 'root', group: 'root', size: 43000, date: 'Nov 11 08:00', content: 'Concatenate FILE(s) to standard output' },
                    'echo': { type: 'file', perms: '-rwxr-xr-x', owner: 'root', group: 'root', size: 38000, date: 'Nov 11 08:00', content: 'Display a line of text' },
                    'pwd': { type: 'file', perms: '-rwxr-xr-x', owner: 'root', group: 'root', size: 38000, date: 'Nov 11 08:00', content: 'Print name of current/working directory' },
                    'mkdir': { type: 'file', perms: '-rwxr-xr-x', owner: 'root', group: 'root', size: 52000, date: 'Nov 11 08:00', content: 'Create the DIRECTORY(ies), if they do not already exist' },
                    'touch': { type: 'file', perms: '-rwxr-xr-x', owner: 'root', group: 'root', size: 41000, date: 'Nov 11 08:00', content: 'Update access and modification times of each FILE' },
                    'rm': { type: 'file', perms: '-rwxr-xr-x', owner: 'root', group: 'root', size: 68000, date: 'Nov 11 08:00', content: 'Remove (unlink) the FILE(s)' },
                    'cp': { type: 'file', perms: '-rwxr-xr-x', owner: 'root', group: 'root', size: 94000, date: 'Nov 11 08:00', content: 'Copy SOURCE to DEST' },
                    'mv': { type: 'file', perms: '-rwxr-xr-x', owner: 'root', group: 'root', size: 98000, date: 'Nov 11 08:00', content: 'Rename SOURCE to DEST, or move SOURCE(s) to DIRECTORY' },
                    'grep': { type: 'file', perms: '-rwxr-xr-x', owner: 'root', group: 'root', size: 180000, date: 'Nov 11 08:00', content: 'Print lines matching a pattern' },
                    'ps': { type: 'file', perms: '-rwxr-xr-x', owner: 'root', group: 'root', size: 145000, date: 'Nov 11 08:00', content: 'Report a snapshot of the current processes' },
                    'nmap': { type: 'file', perms: '-rwxr-xr-x', owner: 'root', group: 'root', size: 3120000, date: 'Nov 11 08:00', content: 'Network exploration tool and security / port scanner' },
                    'wazuh-control': { type: 'file', perms: '-rwxr-xr-x', owner: 'root', group: 'root', size: 890000, date: 'Nov 11 08:00', content: 'Wazuh Host-based Intrusion Detection & EDR Agent controller' },
                    'burpsuite': { type: 'file', perms: '-rwxr-xr-x', owner: 'root', group: 'root', size: 154000000, date: 'Nov 11 08:00', content: 'Burp Suite Professional Web Application Security Testing Platform' },
                    'neofetch': { type: 'file', perms: '-rwxr-xr-x', owner: 'root', group: 'root', size: 120000, date: 'Nov 11 08:00', content: 'CLI system information tool' }
                }
            },
            'etc': {
                type: 'dir', perms: 'drwxr-xr-x', owner: 'root', group: 'root', date: 'Nov 11 08:00',
                children: {
                    'os-release': {
                        type: 'file', perms: '-rw-r--r--', owner: 'root', group: 'root', size: 382, date: 'Nov 11 08:00',
                        content: `NAME="Red Hat Enterprise Linux"
VERSION="9.4 (Plow)"
ID="rhel"
ID_LIKE="fedora"
VERSION_ID="9.4"
PRETTY_NAME="Red Hat Enterprise Linux 9.4 (Plow)"
ANSI_COLOR="0;31"
CPE_NAME="cpe:/o:redhat:enterprise_linux:9::baseos"
HOME_URL="https://www.redhat.com/"
BUG_REPORT_URL="https://bugzilla.redhat.com/"
REDHAT_BUGZILLA_PRODUCT="Red Hat Enterprise Linux 9"`
                    },
                    'hostname': {
                        type: 'file', perms: '-rw-r--r--', owner: 'root', group: 'root', size: 28, date: 'Nov 11 08:00',
                        content: 'security-node.birgunj.local\n'
                    },
                    'hosts': {
                        type: 'file', perms: '-rw-r--r--', owner: 'root', group: 'root', size: 220, date: 'Nov 11 08:00',
                        content: `127.0.0.1   localhost localhost.localdomain
127.0.1.1   security-node.birgunj.local security-node
10.0.0.1    firewall.gateway.internal
10.0.0.2    wazuh-manager.secops.internal
10.0.0.3    splunk-indexer.secops.internal
::1         localhost ip6-localhost ip6-loopback`
                    },
                    'resolv.conf': {
                        type: 'file', perms: '-rw-r--r--', owner: 'root', group: 'root', size: 68, date: 'Nov 11 08:00',
                        content: `nameserver 1.1.1.1
nameserver 8.8.8.8
search birgunj.local`
                    },
                    'passwd': {
                        type: 'file', perms: '-rw-r--r--', owner: 'root', group: 'root', size: 840, date: 'Nov 11 08:00',
                        content: `root:x:0:0:root:/root:/bin/bash
daemon:x:1:1:daemon:/usr/sbin:/usr/sbin/nologin
bin:x:2:2:bin:/bin:/usr/sbin/nologin
sys:x:3:3:sys:/dev:/usr/sbin/nologin
sshd:x:107:65534::/run/sshd:/usr/sbin/nologin
wazuh:x:998:998:Wazuh agent daemon:/var/ossec:/sbin/nologin
splunk:x:999:999:Splunk Forwarder:/opt/splunkforwarder:/bin/false
bajrang:x:1000:1000:Bajrang Kumar Sarraf (Cybersecurity Specialist),,,:/home/bajrang:/bin/bash`
                    },
                    'shadow': {
                        type: 'file', perms: '-rw-------', owner: 'root', group: 'root', size: 360, date: 'Nov 11 08:00',
                        content: `root:$6$vaptSalt$N5sW2zK9f4.P3R9b8mK...:19842:0:99999:7:::
daemon:*:19842:0:99999:7:::
sshd:*:19842:0:99999:7:::
wazuh:*:19842:0:99999:7:::
splunk:*:19842:0:99999:7:::
bajrang:$6$hardened$8dK7s21xJq40pLs7...:19842:0:99999:7:::`
                    },
                    'sudoers': {
                        type: 'file', perms: '-r--r-----', owner: 'root', group: 'root', size: 380, date: 'Nov 11 08:00',
                        content: `# /etc/sudoers - Security Policy Specification
root    ALL=(ALL:ALL) ALL
%admin  ALL=(ALL) ALL
%sudo   ALL=(ALL:ALL) ALL
bajrang ALL=(ALL:ALL) ALL`
                    },
                    'issue': {
                        type: 'file', perms: '-rw-r--r--', owner: 'root', group: 'root', size: 60, date: 'Nov 11 08:00',
                        content: `Red Hat Enterprise Linux 9.4 (Plow) - Kernel \\r on an \\m (\\l)\n`
                    }
                }
            },
            'home': {
                type: 'dir', perms: 'drwxr-xr-x', owner: 'root', group: 'root', date: 'Nov 11 08:00',
                children: {
                    'bajrang': {
                        type: 'dir', perms: 'drwxr-xr-x', owner: 'bajrang', group: 'bajrang', date: 'Nov 11 18:20',
                        children: {
                            'README.md': {
                                type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 1320, date: 'Nov 11 18:20',
                                content: `# Security Operations Terminal - Bajrang Kumar Sarraf
Location: Birgunj, Nepal 44300 | Status: Online & Fortified

This shell executes standard Linux bash commands in an interactive sandbox.
Try running:
  - whoami, neofetch, uname -a, ps aux, top, free -h, df -h
  - ls -la, cd projects, cat about.txt, pwd, tree, find .
  - nmap target.local, vapt, wazuh, burpsuite, ping 8.8.8.8
  - echo "hello world" > notes.txt, cat notes.txt
  - cat /etc/passwd | grep bajrang
  - sudo su (enter password to gain root privilege)
  - matrix (falling digital code screen)`
                            },
                            'about.txt': {
                                type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 1060, date: 'Nov 11 18:20',
                                content: `================================================================================
IDENTITY DOSSIER: BAJRANG KUMAR SARRAF
================================================================================
Roles:       Cybersecurity Trainer | Junior Penetration Tester | VAPT Analyst | Web Developer
Location:    Birgunj, Nepal 44300
Phone:       +977 9816266554
Email:       bajrangshroff10@gmail.com
Status:      ONLINE - Available for Security Audits & Engineering Roles
Degrees:     B.Sc. in IT (Cyber Security and Forensics) - Parul University
Accredit:    Red Hat Certified System Administrator (RHCSA)
Languages:   English (Fluent), Nepali (Native), Hindi (Fluent), Bhojpuri (Native)
Summary:     Cybersecurity professional with hands-on experience in Vulnerability
             Assessment and Penetration Testing (VAPT), technical training, enterprise
             network administration, and secure full-stack web development.`
                            },
                            'bio.md': {
                                type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 940, date: 'Nov 11 18:20',
                                content: `## Professional Background & Philosophy
"Security is not an afterthought or a static checklist; it is an ongoing mindset."

By combining offensive adversarial thinking with defensive engineering and clean
OOP code, I build systems resilient against modern threats while breaking down
real-world vulnerabilities before threat actors can exploit them.

Experienced in analyzing web apps/networks, validating vulnerabilities with
Proof-of-Concepts (PoCs), and drafting actionable remediation reports.`
                            },
                            'contact.txt': {
                                type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 520, date: 'Nov 11 18:20',
                                content: `COMMUNICATION CHANNELS:
- Email:     bajrangshroff10@gmail.com
- Phone:     +977 9816266554
- WhatsApp:  https://wa.me/9779816266554
- Location:  Birgunj, Nepal 44300
- LinkedIn:  https://linkedin.com/in/bajrang-kumar-sarraf
- GitHub:    https://github.com/bajrang-sarraf`
                            },
                            'skills.txt': {
                                type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 1140, date: 'Nov 11 18:20',
                                content: `TECHNICAL PROFICIENCIES & CAPABILITIES:

[1] OFFENSIVE & DEFENSIVE SECURITY:
    - VAPT (Web Applications & Networks), OWASP Top 10 (SQLi, XSS, CSRF, IDOR, SSRF)
    - Vulnerability Research, Bug Hunting, PoC Validation, CVSS v3.1 Scoring
    - Burp Suite Pro, Nmap, Wazuh SIEM, Splunk Enterprise, Metasploit, Wireshark

[2] SYSTEMS & NETWORKING:
    - Linux Administration (RHCSA Certified: systemd, LVM, firewalld, SELinux)
    - Windows Server, Active Directory Fundamentals, Group Policy
    - TCP/IP, DNS, DHCP, HTTP/HTTPS, SSL/TLS Ciphers, VLANs, Switching & Routing

[3] DEVELOPMENT & CLOUD:
    - PHP 8+ (OOP, Secure PDO), Java (IIT Kharagpur NPTEL), C++ (Arduino/ESP8266)
    - Vanilla JavaScript (ES6+), Tailwind CSS, HTML5, MySQL Hardening
    - AWS Cloud Fundamentals (EC2, S3, IAM, VPC)`
                            },
                            '.bashrc': {
                                type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 450, date: 'Nov 11 18:20',
                                content: `# ~/.bashrc
export HISTSIZE=1000
alias ll='ls -la'
alias la='ls -A'
alias l='ls -CF'
alias nmap='nmap -sV -sC'
alias scan='nmap -T4 -F'
alias cls='clear'`
                            },
                            '.bash_history': {
                                type: 'file', perms: '-rw-------', owner: 'bajrang', group: 'bajrang', size: 280, date: 'Nov 11 18:20',
                                content: `whoami
uname -a
neofetch
cd projects
ls -la
nmap -sV target.local
wazuh
cat about.txt`
                            },
                            'projects': {
                                type: 'dir', perms: 'drwxr-xr-x', owner: 'bajrang', group: 'bajrang', date: 'Nov 11 18:20',
                                children: {
                                    'esp8266-wifi-tool.md': {
                                        type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 640, date: 'Nov 11 18:20',
                                        content: `# ESP8266 Wi-Fi Security Tool
Category: Hardware & Security (IoT)
Tech: C++, Arduino, IEEE 802.11 Protocols
Description: Custom hardware security auditing device built to demonstrate 802.11
Wi-Fi weaknesses (deauth frames, beacon frames) in controlled lab environments.`
                                    },
                                    'news4nepal.md': {
                                        type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 580, date: 'Nov 11 18:20',
                                        content: `# News4Nepal (news4nepal.com)
Category: Web Development (Media)
Tech: PHP 8+, MySQL, JavaScript, Tailwind CSS
Description: Production news publishing portal engineered with hardened PHP
architecture, dynamic category routing, and SQL injection prevention.`
                                    },
                                    'visionpoint.md': {
                                        type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 560, date: 'Nov 11 18:20',
                                        content: `# VisionPoint Education Portal (visionpoint.edu.np)
Category: Corporate & Education Web Portal
Tech: PHP, MySQL, JavaScript, CSRF-hardened workflows
Description: Education service portal supporting course catalogs and student intake.`
                                    },
                                    'hakkiveda.md': {
                                        type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 480, date: 'Nov 11 18:20',
                                        content: `# HakkiVeda Nepal
Category: E-Commerce
Tech: PHP 8, MySQL, Vanilla JS, Cart Flow
Description: Herbal & wellness e-commerce platform with session-hardened cart.`
                                    },
                                    'nanotech.md': {
                                        type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 490, date: 'Nov 11 18:20',
                                        content: `# Nano Tech IT Solution
Category: Hardware & Tech E-Commerce
Tech: PHP, MySQL, REST Endpoints
Description: IT equipment catalog with dynamic hardware comparisons.`
                                    },
                                    'namaste.md': {
                                        type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 440, date: 'Nov 11 18:20',
                                        content: `# Namaste Education Foundation
Category: Corporate Consultancy
Tech: PHP, MySQL, Responsive UI
Description: Educational consultancy web portal with validated intake forms.`
                                    },
                                    'nayakadam.md': {
                                        type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 430, date: 'Nov 11 18:20',
                                        content: `# Naya Kadam Nepal
Category: Community Outreach / Non-Profit
Tech: PHP, MySQL, Vanilla JS
Description: Non-profit platform coordinating social initiatives in Nepal.`
                                    }
                                }
                            },
                            'certifications': {
                                type: 'dir', perms: 'drwxr-xr-x', owner: 'bajrang', group: 'bajrang', date: 'Nov 11 18:20',
                                children: {
                                    'rhcsa-redhat.txt': {
                                        type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 420, date: 'Nov 11 18:20',
                                        content: `CREDENTIAL: Red Hat Certified System Administrator (RHCSA)
Issuer:     Red Hat / IT Security College of Computer Science
Validation: Certified Enterprise Linux Administrator
Scope:      Storage configuration, systemd management, firewalld, SELinux, user security`
                                    },
                                    'ethical-hacking.txt': {
                                        type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 360, date: 'Nov 11 18:20',
                                        content: `CREDENTIAL: Ethical Hacking Training
Issuer:     Broadway Infosys
Status:     Certified
Focus:      Network pentesting, web exploitation, recon, vulnerability assessment`
                                    },
                                    'nptel-java.txt': {
                                        type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 320, date: 'Nov 11 18:20',
                                        content: `CREDENTIAL: Programming in Java (Elite Certified)
Issuer:     IIT Kharagpur / NPTEL
Focus:      OOP principles, multi-threading, concurrency, memory management`
                                    },
                                    'nptel-networks.txt': {
                                        type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 340, date: 'Nov 11 18:20',
                                        content: `CREDENTIAL: Computer Networks and Communication (Elite Certified)
Issuer:     IIT Kharagpur / NPTEL
Focus:      TCP/IP stack, congestion control, routing algorithms, packet protocols`
                                    }
                                }
                            },
                            'vapt_lab': {
                                type: 'dir', perms: 'drwxr-xr-x', owner: 'bajrang', group: 'bajrang', date: 'Nov 11 18:20',
                                children: {
                                    'target_scope.txt': {
                                        type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 380, date: 'Nov 11 18:20',
                                        content: `AUTHORIZED VAPT ASSESSMENT SCOPE:
- Target:       *.target.internal, 192.168.1.0/24
- Out of Scope: Third-party payment gateways, DoS/DDoS testing
- Methodology:  OWASP Top 10 Web Application Security Testing Guide v4.2`
                                    },
                                    'owasp_top10_audit.log': {
                                        type: 'file', perms: '-rw-r--r--', owner: 'bajrang', group: 'bajrang', size: 560, date: 'Nov 11 18:20',
                                        content: `[PASS] A01:2021-Broken Access Control (IDOR audit clean)
[PASS] A02:2021-Cryptographic Failures (TLS 1.3 enforced)
[PASS] A03:2021-Injection (Parameterized PDO prepared queries)
[PASS] A04:2021-Insecure Design (Threat modeling applied)
[PASS] A05:2021-Security Misconfiguration (Hardened headers active)
[WARN] A06:2021-Vulnerable Components (Dependency patch available)
[PASS] A07:2021-Identification & Auth Failures (Rate limits active)
[PASS] A08:2021-Software & Data Integrity (Verified SHA256 checksums)
[PASS] A09:2021-Security Logging Failures (Wazuh SIEM active)
[PASS] A10:2021-Server-Side Request Forgery (SSRF)`
                                    }
                                }
                            }
                        }
                    }
                }
            },
            'var': {
                type: 'dir', perms: 'drwxr-xr-x', owner: 'root', group: 'root', date: 'Nov 11 08:00',
                children: {
                    'log': {
                        type: 'dir', perms: 'drwxr-xr-x', owner: 'root', group: 'root', date: 'Nov 11 08:00',
                        children: {
                            'auth.log': {
                                type: 'file', perms: '-rw-r-----', owner: 'root', group: 'adm', size: 1450, date: 'Nov 11 20:15',
                                content: `Nov 11 20:00:01 security-node CRON[1401]: (pam_unix) session opened for user root
Nov 11 20:14:22 security-node sshd[2841]: Accepted publickey for bajrang from 192.168.1.50 port 52314 ssh2: ED25519
Nov 11 20:14:22 security-node sshd[2841]: pam_unix(sshd:session): session opened for user bajrang by (uid=0)
Nov 11 20:15:02 security-node sudo[2955]: bajrang : TTY=pts/0 ; PWD=/home/bajrang ; USER=root ; COMMAND=/usr/bin/nmap
Nov 11 20:15:02 security-node sudo[2955]: pam_unix(sudo:session): session opened for user root by (uid=0)`
                            },
                            'syslog': {
                                type: 'file', perms: '-rw-r-----', owner: 'root', group: 'adm', size: 1220, date: 'Nov 11 20:15',
                                content: `Nov 11 08:00:00 security-node kernel: [    0.000000] Linux version 6.8.0-kali3-amd64 (devel@kali.org) #1 SMP PREEMPT
Nov 11 08:00:01 security-node kernel: [    0.000000] Command line: BOOT_IMAGE=/boot/vmlinuz ro quiet security=selinux
Nov 11 08:00:02 security-node systemd[1]: Started Wazuh Host EDR Agent Daemon.
Nov 11 08:00:02 security-node systemd[1]: Started Splunk Universal Forwarder.
Nov 11 08:00:03 security-node systemd[1]: Reached target Multi-User System.`
                            },
                            'wazuh.log': {
                                type: 'file', perms: '-rw-r-----', owner: 'wazuh', group: 'wazuh', size: 980, date: 'Nov 11 20:15',
                                content: `2026/11/11 20:00:15 wazuh-agent: INFO: Reading configuration file.
2026/11/11 20:00:16 wazuh-agent: INFO: Using notify time: 10 and max time to reconnect: 60
2026/11/11 20:00:16 wazuh-agent: INFO: Connected to manager server (10.0.0.2:1514/tcp).
2026/11/11 20:05:00 wazuh-agent: INFO: File integrity monitoring scan started.
2026/11/11 20:05:42 wazuh-agent: INFO: FIM scan finished: 0 unauthorized file tampering events detected.`
                            }
                        }
                    }
                }
            },
            'proc': {
                type: 'dir', perms: 'dr-xr-xr-x', owner: 'root', group: 'root', date: 'Nov 11 08:00',
                children: {
                    'cpuinfo': {
                        type: 'file', perms: '-r--r--r--', owner: 'root', group: 'root', size: 840, date: 'Nov 11 08:00',
                        content: `processor       : 0
vendor_id       : GenuineIntel
cpu family      : 6
model           : 186
model name      : 13th Gen Intel(R) Core(TM) i7-13700H
stepping        : 2
cpu MHz         : 4988.240
cache size      : 24576 KB
cpu cores       : 16
flags           : fpu vme de pse tsc msr pae mce cx8 apic sep mtrr pge mca cmov pat pse36 clflush mmx fxsr sse sse2 ht syscall nx lm constant_tsc aes avx avx2`
                    },
                    'meminfo': {
                        type: 'file', perms: '-r--r--r--', owner: 'root', group: 'root', size: 520, date: 'Nov 11 08:00',
                        content: `MemTotal:       32768000 kB
MemFree:        24512400 kB
MemAvailable:   28140200 kB
Buffers:          345600 kB
Cached:          3840200 kB
SwapTotal:       8388608 kB
SwapFree:        8388608 kB`
                    },
                    'version': {
                        type: 'file', perms: '-r--r--r--', owner: 'root', group: 'root', size: 120, date: 'Nov 11 08:00',
                        content: `Linux version 6.8.0-kali3-amd64 (devel@kali.org) (gcc-13) #1 SMP PREEMPT\n`
                    },
                    'uptime': {
                        type: 'file', perms: '-r--r--r--', owner: 'root', group: 'root', size: 30, date: 'Nov 11 08:00',
                        content: `3642104.28 58492012.44\n`
                    }
                }
            },
            'root': {
                type: 'dir', perms: 'drwx------', owner: 'root', group: 'root', date: 'Nov 11 08:00',
                children: {
                    'root_flag.txt': {
                        type: 'file', perms: '-rw-------', owner: 'root', group: 'root', size: 280, date: 'Nov 11 08:00',
                        content: `🚩 FLAG{B4JR4NG_R00T_S3CUR1TY_N0D3_P0W3R}
Congratulations, Operator! You have full superuser control on this terminal sandbox.
System status: All services hardened. Continuous monitoring active via Wazuh & Splunk.`
                    }
                }
            },
            'tmp': {
                type: 'dir', perms: 'drwxrwxrwt', owner: 'root', group: 'root', date: 'Nov 11 08:00',
                children: {}
            }
        }
    };

    // Helper: Normalize absolute path from relative or '~'
    function resolvePath(target, base) {
        if (!target || target === '.') return base;
        let p = target.trim();
        const homeDir = (currentUser === 'root') ? '/root' : '/home/bajrang';

        if (p === '~' || p.startsWith('~/')) {
            p = homeDir + p.slice(1);
        } else if (!p.startsWith('/')) {
            p = (base === '/' ? '' : base) + '/' + p;
        }

        const parts = p.split('/').filter(Boolean);
        const resolved = [];
        for (const seg of parts) {
            if (seg === '.') continue;
            if (seg === '..') {
                if (resolved.length > 0) resolved.pop();
            } else {
                resolved.push(seg);
            }
        }
        return '/' + resolved.join('/');
    }

    // Helper: Retrieve node from VFS
    function getNode(path) {
        if (path === '/') return vfs;
        const parts = path.split('/').filter(Boolean);
        let curr = vfs;
        for (const part of parts) {
            if (!curr.children || !curr.children[part]) {
                return null;
            }
            curr = curr.children[part];
        }
        return curr;
    }

    // Helper: Get parent directory node and target filename
    function getParentNode(path) {
        const norm = resolvePath(path, cwd);
        const parts = norm.split('/').filter(Boolean);
        if (parts.length === 0) return null; // Root has no parent
        const name = parts.pop();
        const parentPath = '/' + parts.join('/');
        const parent = getNode(parentPath);
        return { parent, name, parentPath, fullPath: norm };
    }

    function getCurrentDate() {
        const now = new Date();
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const m = months[now.getMonth()];
        const d = String(now.getDate()).padStart(2, '0');
        const h = String(now.getHours()).padStart(2, '0');
        const min = String(now.getMinutes()).padStart(2, '0');
        return `${m} ${d} ${h}:${min}`;
    }

    // Helper: Compute dynamic prompt string
    function getPromptString() {
        const homeDir = (currentUser === 'root') ? '/root' : '/home/bajrang';
        let displayPath = cwd;
        if (displayPath === homeDir) {
            displayPath = '~';
        } else if (displayPath.startsWith(homeDir + '/')) {
            displayPath = '~' + displayPath.slice(homeDir.length);
        }
        const symbol = (currentUser === 'root') ? '#' : '$';
        return `${currentUser}@${currentHost}:${displayPath}${symbol}`;
    }

    function updatePrompt() {
        if (terminalPrompt) {
            terminalPrompt.textContent = getPromptString();
            if (currentUser === 'root') {
                terminalPrompt.className = 'text-red-400 font-bold font-mono shrink-0 select-none';
            } else {
                terminalPrompt.className = 'text-emerald-400 font-bold font-mono shrink-0 select-none';
            }
        }
        if (terminalTitleBar) {
            terminalTitleBar.textContent = `${getPromptString()} (interactive bash shell)`;
        }
    }

    // Update on start
    updatePrompt();

    // Focus input on clicking anywhere inside terminal body
    if (terminalBodyContainer) {
        terminalBodyContainer.addEventListener('click', (e) => {
            if (e.target !== terminalInput) {
                terminalInput.focus();
            }
        });
    }

    // Custom commands from CMS
    const customCommands = {};
    const appEl = document.getElementById('portfolio-app');
    if (appEl && appEl.getAttribute('data-terminal-custom')) {
        try {
            const parsed = JSON.parse(appEl.getAttribute('data-terminal-custom'));
            if (parsed && typeof parsed === 'object') {
                for (const [k, v] of Object.entries(parsed)) {
                    customCommands[k.toLowerCase().trim()] = v;
                }
            }
        } catch(e) {}
    }

    /* -------------------------------------------------------------------------
       Linux Shell Command Execution Engine
       ------------------------------------------------------------------------- */
    function executeShellLine(rawInput) {
        const trimmed = rawInput.trim();

        // Print command prompt line
        const cmdEcho = document.createElement('div');
        cmdEcho.className = 'my-1 font-mono text-xs';
        const promptColor = currentUser === 'root' ? 'text-red-400 font-bold' : 'text-emerald-400 font-bold';
        
        if (awaitingPassword) {
            cmdEcho.innerHTML = `<span class="${promptColor}">[sudo] password for bajrang:</span> ********`;
        } else {
            cmdEcho.innerHTML = `<span class="${promptColor}">${escapeHTML(getPromptString())}</span> ${escapeHTML(rawInput)}`;
        }
        terminalOutput.appendChild(cmdEcho);

        if (matrixRunning) {
            stopMatrix();
        }

        // Handle password input for sudo
        if (awaitingPassword) {
            awaitingPassword = false;
            terminalInput.type = 'text';
            terminalInput.placeholder = "type any Linux command (e.g. ls -la, cat about.txt, nmap, neofetch)...";
            
            // Accept any password attempt in this sandbox simulation
            currentUser = 'root';
            updatePrompt();
            
            const authSuccess = document.createElement('div');
            authSuccess.className = 'text-emerald-400 text-xs mb-2 font-mono';
            authSuccess.innerHTML = `[✓] Authentication verified. Superuser privileges granted (UID=0, GID=0).`;
            terminalOutput.appendChild(authSuccess);

            if (pendingRootCommand) {
                const toRun = pendingRootCommand;
                pendingRootCommand = null;
                runSingleCommand(toRun);
            }
            scrollToBottom();
            return;
        }

        if (!trimmed) {
            scrollToBottom();
            return;
        }

        // Support command pipelines: cmd1 | cmd2
        if (trimmed.includes('|')) {
            executePipelinedCommands(trimmed);
            scrollToBottom();
            return;
        }

        // Support command redirection: cmd > file or cmd >> file
        if (trimmed.includes('>') || trimmed.includes('>>')) {
            executeRedirectedCommand(trimmed);
            scrollToBottom();
            return;
        }

        runSingleCommand(trimmed);
        scrollToBottom();
    }

    function scrollToBottom() {
        if (terminalBodyContainer) {
            terminalBodyContainer.scrollTop = terminalBodyContainer.scrollHeight;
        }
    }

    function runSingleCommand(cmdStr) {
        // Tokenize command line preserving quotes
        const tokens = tokenizeCommand(cmdStr);
        if (tokens.length === 0) return;

        const cmd = tokens[0].toLowerCase();
        const args = tokens.slice(1);

        // Check custom commands from CMS first
        if (customCommands[cmd]) {
            printOutput(`<pre class="text-emerald-300 font-mono text-xs whitespace-pre-wrap leading-relaxed">${escapeHTML(customCommands[cmd])}</pre>`);
            return;
        }

        // Standard Linux & Security Commands Dispatcher
        switch (cmd) {
            case 'help':
                cmdHelp(args);
                break;
            case 'clear':
            case 'cls':
                terminalOutput.innerHTML = '';
                break;
            case 'ls':
            case 'dir':
                cmdLs(args);
                break;
            case 'cd':
                cmdCd(args);
                break;
            case 'pwd':
                printOutput(`<span class="text-slate-200">${escapeHTML(cwd)}</span>`);
                break;
            case 'cat':
                cmdCat(args);
                break;
            case 'head':
                cmdHead(args);
                break;
            case 'tail':
                cmdTail(args);
                break;
            case 'echo':
                cmdEcho(args);
                break;
            case 'mkdir':
                cmdMkdir(args);
                break;
            case 'touch':
                cmdTouch(args);
                break;
            case 'rm':
                cmdRm(args);
                break;
            case 'cp':
                cmdCp(args);
                break;
            case 'mv':
                cmdMv(args);
                break;
            case 'grep':
                cmdGrep(args);
                break;
            case 'whoami':
                printOutput(`<span class="text-emerald-400 font-bold">${escapeHTML(currentUser)}</span>`);
                break;
            case 'id':
                cmdId();
                break;
            case 'uname':
                cmdUname(args);
                break;
            case 'hostname':
                printOutput(`<span class="text-slate-200">${escapeHTML(currentHost)}.birgunj.local</span>`);
                break;
            case 'uptime':
                cmdUptime();
                break;
            case 'date':
                printOutput(`<span class="text-slate-200">${new Date().toUTCString()}</span>`);
                break;
            case 'free':
                cmdFree(args);
                break;
            case 'df':
                cmdDf(args);
                break;
            case 'ps':
                cmdPs(args);
                break;
            case 'top':
            case 'htop':
                cmdTop();
                break;
            case 'ifconfig':
            case 'ip':
                cmdIfconfig();
                break;
            case 'netstat':
            case 'ss':
                cmdNetstat(args);
                break;
            case 'history':
                cmdHistory();
                break;
            case 'env':
            case 'printenv':
            case 'export':
                cmdEnv();
                break;
            case 'which':
            case 'whereis':
                cmdWhich(args);
                break;
            case 'find':
                cmdFind(args);
                break;
            case 'wc':
                cmdWc(args);
                break;
            case 'tree':
                cmdTree(args);
                break;
            case 'chmod':
            case 'chown':
                cmdChmod(cmd, args);
                break;
            case 'curl':
            case 'wget':
                cmdCurl(cmd, args);
                break;
            case 'ping':
                cmdPing(args);
                break;
            case 'neofetch':
                cmdNeofetch();
                break;
            case 'nmap':
                cmdNmap(args);
                break;
            case 'vapt':
                cmdVapt();
                break;
            case 'wazuh':
            case 'wazuh-control':
            case 'ossec':
                cmdWazuh();
                break;
            case 'splunk':
                cmdSplunk();
                break;
            case 'burpsuite':
            case 'burp':
                cmdBurp();
                break;
            case 'sqlmap':
                cmdSqlmap(args);
                break;
            case 'hydra':
                cmdHydra(args);
                break;
            case 'nikto':
                cmdNikto(args);
                break;
            case 'gobuster':
            case 'dirb':
                cmdGobuster(args);
                break;
            case 'wireshark':
            case 'tcpdump':
                cmdTcpdump();
                break;
            case 'aircrack-ng':
            case 'aircrack':
            case 'wifite':
                cmdAircrack();
                break;
            case 'hashcat':
            case 'john':
                cmdHashcat();
                break;
            case 'nc':
            case 'netcat':
                cmdNetcat(args);
                break;
            case 'sudo':
                cmdSudo(args);
                break;
            case 'su':
                cmdSu(args);
                break;
            case 'exit':
            case 'logout':
                cmdExit();
                break;
            case 'reboot':
                cmdReboot();
                break;
            case 'matrix':
                startMatrix();
                break;
            case 'cowsay':
                cmdCowsay(args);
                break;
            case 'sl':
                cmdSl();
                break;
            case 'fortune':
                cmdFortune();
                break;
            case 'skills':
            case 'projects':
            case 'certs':
            case 'awards':
            case 'contact':
            case 'about':
            case 'bio':
            case 'resume':
                cmdPortfolioAlias(cmd);
                break;
            default:
                printOutput(`<span class="text-rose-400 font-mono">bash: ${escapeHTML(cmd)}: command not found.</span> <span class="text-slate-400">Type <span class="text-emerald-400 font-bold cursor-pointer underline" onclick="document.getElementById('terminal-input').value='help'; document.getElementById('terminal-input').focus();">help</span> to explore verified Linux commands.</span>`);
                break;
        }
    }

    function printOutput(html) {
        const outDiv = document.createElement('div');
        outDiv.className = 'text-slate-300 mb-3 font-mono text-xs leading-relaxed';
        outDiv.innerHTML = html;
        terminalOutput.appendChild(outDiv);
    }

    function tokenizeCommand(str) {
        const regex = /[^\s"']+|"([^"]*)"|'([^']*)'/g;
        const matches = [];
        let match;
        while ((match = regex.exec(str)) !== null) {
            matches.push(match[1] || match[2] || match[0]);
        }
        return matches;
    }

    /* -------------------------------------------------------------------------
       Linux Shell Implementations
       ------------------------------------------------------------------------- */
    function cmdHelp(args) {
        printOutput(`
<span class="text-emerald-400 font-bold tracking-wider">// REAL LINUX SECURITY TERMINAL & BASH ENVIRONMENT</span>
<span class="text-slate-400 text-[11px] block mb-2">Simulated x86_64 Linux Shell (Red Hat Enterprise Linux 9.4 / Kali Linux)</span>

<span class="text-cyan-400 font-bold uppercase text-[11px] block mt-2 border-b border-slate-800 pb-1">1. File System & Directory Navigation</span>
  <span class="text-emerald-400 font-bold">ls</span> [-l, -a, -la]       List directory contents with permissions & colors
  <span class="text-emerald-400 font-bold">cd</span> &lt;dir&gt;                 Change working directory (e.g., <span class="text-cyan-300">cd projects</span>, <span class="text-cyan-300">cd /etc</span>, <span class="text-cyan-300">cd ~</span>)
  <span class="text-emerald-400 font-bold">pwd</span>                      Print current working directory
  <span class="text-emerald-400 font-bold">cat</span> &lt;file&gt;                Concatenate and display file content
  <span class="text-emerald-400 font-bold">head / tail</span> [-n N]       Output first or last lines of file
  <span class="text-emerald-400 font-bold">mkdir / touch / rm</span>       Create folders, files, or delete items
  <span class="text-emerald-400 font-bold">cp / mv</span>                  Copy or rename/move files in filesystem
  <span class="text-emerald-400 font-bold">echo</span> &lt;text&gt; [&gt; file]    Display text or redirect/append into files
  <span class="text-emerald-400 font-bold">grep</span> [-i] &lt;pattern&gt;      Search regex patterns (supports pipes: <span class="text-cyan-300">cat /etc/passwd | grep bajrang</span>)
  <span class="text-emerald-400 font-bold">tree / find / wc</span>         Visual directory hierarchy, file search & word count

<span class="text-cyan-400 font-bold uppercase text-[11px] block mt-3 border-b border-slate-800 pb-1">2. System Diagnostics & Hardware Monitoring</span>
  <span class="text-emerald-400 font-bold">whoami / id</span>              Current active user and group memberships
  <span class="text-emerald-400 font-bold">uname</span> [-a, -r]           Kernel version, architecture, and OS
  <span class="text-emerald-400 font-bold">neofetch</span>                 Comprehensive ASCII system summary & specs
  <span class="text-emerald-400 font-bold">ps / top</span>                 Inspect active running processes
  <span class="text-emerald-400 font-bold">free / df</span> [-h]           RAM utilization and filesystem mount capacity
  <span class="text-emerald-400 font-bold">uptime / date</span>            Host uptime metrics and timestamp
  <span class="text-emerald-400 font-bold">ifconfig / ip a</span>          Inspect network interfaces (eth0, lo, tun0)
  <span class="text-emerald-400 font-bold">netstat / ss</span> [-tulpn]    Audit listening network sockets and ports
  <span class="text-emerald-400 font-bold">history / env</span>            View shell command history and environment variables

<span class="text-cyan-400 font-bold uppercase text-[11px] block mt-3 border-b border-slate-800 pb-1">3. Offensive Security & VAPT Tooling</span>
  <span class="text-emerald-400 font-bold">nmap</span> &lt;target&gt; [-sV, -sC] Scan network targets and discover open ports
  <span class="text-emerald-400 font-bold">vapt</span>                     Execute automated 5-phase VAPT audit pipeline
  <span class="text-emerald-400 font-bold">wazuh</span>                    Query Wazuh SIEM Host EDR agent status
  <span class="text-emerald-400 font-bold">burpsuite</span>                Check Burp Suite Pro HTTP proxy interceptor
  <span class="text-emerald-400 font-bold">splunk</span>                   Verify Splunk enterprise log correlation daemon
  <span class="text-emerald-400 font-bold">sqlmap / hydra / nikto</span>   Web & network vulnerability scanner simulations
  <span class="text-emerald-400 font-bold">aircrack-ng</span>              802.11 Wi-Fi auditing (ESP8266 security project link)
  <span class="text-emerald-400 font-bold">wireshark / tcpdump</span>      Live packet capture stream
  <span class="text-emerald-400 font-bold">ping / curl</span>              Network latency check & HTTP requester

<span class="text-cyan-400 font-bold uppercase text-[11px] block mt-3 border-b border-slate-800 pb-1">4. Privilege Escalation & Special Utilities</span>
  <span class="text-emerald-400 font-bold">sudo su</span>                  Escalate to root superuser (try password: <span class="text-yellow-400">cyber</span>)
  <span class="text-emerald-400 font-bold">matrix</span>                   Falling digital code stream animation (ESC to stop)
  <span class="text-emerald-400 font-bold">cowsay / sl / fortune</span>    Classic Unix easter eggs
  <span class="text-emerald-400 font-bold">reboot / exit</span>            Reboot node or drop superuser privilege
  <span class="text-emerald-400 font-bold">clear</span>                    Flush terminal display buffer
  <span class="text-emerald-400 font-bold">Tab Key</span>                  Auto-completes commands, files & directories!
`);
    }

    function cmdLs(args) {
        let showLong = false;
        let showAll = false;
        let showHuman = false;
        let targetPath = cwd;

        for (const arg of args) {
            if (arg.startsWith('-')) {
                if (arg.includes('l')) showLong = true;
                if (arg.includes('a')) showAll = true;
                if (arg.includes('h')) showHuman = true;
            } else {
                targetPath = resolvePath(arg, cwd);
            }
        }

        const node = getNode(targetPath);
        if (!node) {
            printOutput(`<span class="text-rose-400">ls: cannot access '${escapeHTML(targetPath)}': No such file or directory</span>`);
            return;
        }

        if (node.type === 'file') {
            if (showLong) {
                printOutput(`<span class="text-slate-300">${node.perms} 1 ${node.owner} ${node.group} ${node.size} ${node.date} <span class="text-white">${escapeHTML(targetPath.split('/').pop())}</span></span>`);
            } else {
                printOutput(`<span class="text-white">${escapeHTML(targetPath.split('/').pop())}</span>`);
            }
            return;
        }

        // It is a directory
        const entries = Object.keys(node.children || {}).sort();
        let list = entries;
        if (!showAll) {
            list = list.filter(name => !name.startsWith('.'));
        } else {
            list = ['.', '..', ...list];
        }

        if (showLong) {
            let totalBlocks = 0;
            const rows = list.map(name => {
                let item;
                if (name === '.') item = node;
                else if (name === '..') item = { perms: 'drwxr-xr-x', owner: 'root', group: 'root', size: 4096, date: node.date };
                else item = node.children[name];

                const isDir = item.type === 'dir' || name === '.' || name === '..';
                const size = item.size !== undefined ? (showHuman && item.size > 1024 ? Math.round(item.size / 1024) + 'K' : item.size) : 4096;
                totalBlocks += 4;

                let nameHtml = escapeHTML(name);
                if (isDir) {
                    nameHtml = `<span class="text-cyan-400 font-bold">${nameHtml}/</span>`;
                } else if (item.perms && item.perms.includes('x')) {
                    nameHtml = `<span class="text-emerald-400 font-bold">${nameHtml}*</span>`;
                } else {
                    nameHtml = `<span class="text-slate-200">${nameHtml}</span>`;
                }

                return `<div class="grid grid-cols-12 gap-1 text-[11px] leading-relaxed">
                    <span class="col-span-3 text-slate-400">${item.perms || '-rw-r--r--'}</span>
                    <span class="col-span-1 text-slate-500 text-center">1</span>
                    <span class="col-span-2 text-emerald-400/90">${item.owner || 'root'}</span>
                    <span class="col-span-2 text-slate-400">${item.group || 'root'}</span>
                    <span class="col-span-1 text-right text-slate-400">${size}</span>
                    <span class="col-span-3 text-right text-slate-500 pl-2">${item.date || 'Nov 11 08:00'} ${nameHtml}</span>
                </div>`;
            }).join('');

            printOutput(`
                <div class="text-slate-500 mb-1 text-[11px]">total ${totalBlocks}</div>
                <div class="space-y-0.5">${rows}</div>
            `);
        } else {
            const formatted = list.map(name => {
                const item = (name === '.' || name === '..') ? { type: 'dir' } : node.children[name];
                if (item.type === 'dir') {
                    return `<span class="text-cyan-400 font-bold mr-4">${escapeHTML(name)}/</span>`;
                } else if (item.perms && item.perms.includes('x')) {
                    return `<span class="text-emerald-400 font-bold mr-4">${escapeHTML(name)}*</span>`;
                }
                return `<span class="text-slate-200 mr-4">${escapeHTML(name)}</span>`;
            }).join('');
            printOutput(`<div class="flex flex-wrap gap-y-1">${formatted || '<span class="text-slate-600">(empty directory)</span>'}</div>`);
        }
    }

    function cmdCd(args) {
        let dest = args[0] || '~';
        if (dest === '-') {
            dest = previousDir;
        }

        const target = resolvePath(dest, cwd);
        const node = getNode(target);

        if (!node) {
            printOutput(`<span class="text-rose-400">bash: cd: ${escapeHTML(dest)}: No such file or directory</span>`);
            return;
        }

        if (node.type !== 'dir') {
            printOutput(`<span class="text-rose-400">bash: cd: ${escapeHTML(dest)}: Not a directory</span>`);
            return;
        }

        // Check root-only directories if not root
        if (currentUser !== 'root' && (target === '/root' || target.startsWith('/root/'))) {
            printOutput(`<span class="text-rose-400">bash: cd: ${escapeHTML(dest)}: Permission denied (Superuser privilege required)</span>`);
            return;
        }

        previousDir = cwd;
        cwd = target;
        updatePrompt();
    }

    function cmdCat(args) {
        if (!args.length) {
            printOutput(`<span class="text-slate-400">Usage: cat &lt;filename&gt;</span>`);
            return;
        }

        for (const fileArg of args) {
            const path = resolvePath(fileArg, cwd);
            const node = getNode(path);

            if (!node) {
                printOutput(`<span class="text-rose-400">cat: ${escapeHTML(fileArg)}: No such file or directory</span>`);
                continue;
            }

            if (node.type === 'dir') {
                printOutput(`<span class="text-rose-400">cat: ${escapeHTML(fileArg)}: Is a directory</span>`);
                continue;
            }

            // Permission check (e.g. /etc/shadow or /root/)
            if (currentUser !== 'root' && (path.includes('/shadow') || path.startsWith('/root/'))) {
                printOutput(`<span class="text-rose-400">cat: ${escapeHTML(fileArg)}: Permission denied (Run as root via 'sudo')</span>`);
                continue;
            }

            printOutput(`<pre class="text-slate-200 font-mono text-xs whitespace-pre-wrap leading-relaxed">${escapeHTML(node.content || '')}</pre>`);
        }
    }

    function cmdHead(args) {
        let linesCount = 10;
        let fileArg = args[0];
        if (args[0] === '-n' && args[1]) {
            linesCount = parseInt(args[1], 10) || 10;
            fileArg = args[2];
        }

        if (!fileArg) {
            printOutput(`<span class="text-slate-400">Usage: head [-n N] &lt;file&gt;</span>`);
            return;
        }

        const path = resolvePath(fileArg, cwd);
        const node = getNode(path);
        if (!node || node.type === 'dir') {
            printOutput(`<span class="text-rose-400">head: cannot open '${escapeHTML(fileArg)}'</span>`);
            return;
        }
        const lines = (node.content || '').split('\n').slice(0, linesCount).join('\n');
        printOutput(`<pre class="text-slate-200 font-mono text-xs whitespace-pre-wrap">${escapeHTML(lines)}</pre>`);
    }

    function cmdTail(args) {
        let linesCount = 10;
        let fileArg = args[0];
        if (args[0] === '-n' && args[1]) {
            linesCount = parseInt(args[1], 10) || 10;
            fileArg = args[2];
        }

        if (!fileArg) {
            printOutput(`<span class="text-slate-400">Usage: tail [-n N] &lt;file&gt;</span>`);
            return;
        }

        const path = resolvePath(fileArg, cwd);
        const node = getNode(path);
        if (!node || node.type === 'dir') {
            printOutput(`<span class="text-rose-400">tail: cannot open '${escapeHTML(fileArg)}'</span>`);
            return;
        }
        const allLines = (node.content || '').split('\n');
        const lines = allLines.slice(Math.max(0, allLines.length - linesCount)).join('\n');
        printOutput(`<pre class="text-slate-200 font-mono text-xs whitespace-pre-wrap">${escapeHTML(lines)}</pre>`);
    }

    function cmdEcho(args) {
        const text = args.join(' ');
        printOutput(`<span class="text-slate-200">${escapeHTML(text)}</span>`);
    }

    function cmdMkdir(args) {
        if (!args.length) {
            printOutput(`<span class="text-rose-400">mkdir: missing operand</span>`);
            return;
        }
        const target = args[0];
        const res = getParentNode(target);
        if (!res || !res.parent || res.parent.type !== 'dir') {
            printOutput(`<span class="text-rose-400">mkdir: cannot create directory '${escapeHTML(target)}': No such file or directory</span>`);
            return;
        }
        if (res.parent.children[res.name]) {
            printOutput(`<span class="text-rose-400">mkdir: cannot create directory '${escapeHTML(target)}': File exists</span>`);
            return;
        }
        res.parent.children[res.name] = {
            type: 'dir',
            perms: 'drwxr-xr-x',
            owner: currentUser,
            group: currentUser,
            date: getCurrentDate(),
            children: {}
        };
        printOutput(`<span class="text-emerald-400 text-xs">[✓] Created directory '${escapeHTML(target)}'.</span>`);
    }

    function cmdTouch(args) {
        if (!args.length) {
            printOutput(`<span class="text-rose-400">touch: missing file operand</span>`);
            return;
        }
        for (const fileArg of args) {
            const res = getParentNode(fileArg);
            if (!res || !res.parent || res.parent.type !== 'dir') {
                printOutput(`<span class="text-rose-400">touch: cannot touch '${escapeHTML(fileArg)}': No such file or directory</span>`);
                continue;
            }
            if (res.parent.children[res.name]) {
                res.parent.children[res.name].date = getCurrentDate();
            } else {
                res.parent.children[res.name] = {
                    type: 'file',
                    perms: '-rw-r--r--',
                    owner: currentUser,
                    group: currentUser,
                    size: 0,
                    date: getCurrentDate(),
                    content: ''
                };
            }
        }
    }

    function cmdRm(args) {
        if (!args.length) {
            printOutput(`<span class="text-rose-400">rm: missing operand</span>`);
            return;
        }
        const isRecursive = args.includes('-r') || args.includes('-rf') || args.includes('-f');
        const target = args.find(a => !a.startsWith('-'));
        if (!target) return;

        const res = getParentNode(target);
        if (!res || !res.parent || !res.parent.children[res.name]) {
            printOutput(`<span class="text-rose-400">rm: cannot remove '${escapeHTML(target)}': No such file or directory</span>`);
            return;
        }
        const item = res.parent.children[res.name];
        if (item.type === 'dir' && !isRecursive) {
            printOutput(`<span class="text-rose-400">rm: cannot remove '${escapeHTML(target)}': Is a directory (use -r)</span>`);
            return;
        }
        delete res.parent.children[res.name];
        printOutput(`<span class="text-emerald-400 text-xs">[✓] Removed '${escapeHTML(target)}'.</span>`);
    }

    function cmdCp(args) {
        if (args.length < 2) {
            printOutput(`<span class="text-rose-400">cp: missing destination file operand</span>`);
            return;
        }
        const srcNode = getNode(resolvePath(args[0], cwd));
        if (!srcNode || srcNode.type !== 'file') {
            printOutput(`<span class="text-rose-400">cp: cannot stat '${escapeHTML(args[0])}': No such file</span>`);
            return;
        }
        const destRes = getParentNode(args[1]);
        if (!destRes || !destRes.parent) {
            printOutput(`<span class="text-rose-400">cp: cannot create regular file '${escapeHTML(args[1])}'</span>`);
            return;
        }
        destRes.parent.children[destRes.name] = {
            type: 'file',
            perms: srcNode.perms,
            owner: currentUser,
            group: currentUser,
            size: srcNode.size,
            date: getCurrentDate(),
            content: srcNode.content
        };
        printOutput(`<span class="text-emerald-400 text-xs">[✓] Copied '${escapeHTML(args[0])}' to '${escapeHTML(args[1])}'.</span>`);
    }

    function cmdMv(args) {
        if (args.length < 2) {
            printOutput(`<span class="text-rose-400">mv: missing destination file operand</span>`);
            return;
        }
        const srcRes = getParentNode(args[0]);
        if (!srcRes || !srcRes.parent || !srcRes.parent.children[srcRes.name]) {
            printOutput(`<span class="text-rose-400">mv: cannot stat '${escapeHTML(args[0])}': No such file or directory</span>`);
            return;
        }
        const item = srcRes.parent.children[srcRes.name];
        const destRes = getParentNode(args[1]);
        if (!destRes || !destRes.parent) {
            printOutput(`<span class="text-rose-400">mv: cannot move to '${escapeHTML(args[1])}'</span>`);
            return;
        }
        destRes.parent.children[destRes.name] = item;
        delete srcRes.parent.children[srcRes.name];
        printOutput(`<span class="text-emerald-400 text-xs">[✓] Renamed '${escapeHTML(args[0])}' -> '${escapeHTML(args[1])}'.</span>`);
    }

    function cmdGrep(args) {
        let ignoreCase = false;
        let pattern = '';
        let fileArg = '';

        for (const a of args) {
            if (a === '-i') ignoreCase = true;
            else if (!pattern) pattern = a;
            else if (!fileArg) fileArg = a;
        }

        if (!pattern || !fileArg) {
            printOutput(`<span class="text-slate-400">Usage: grep [-i] &lt;pattern&gt; &lt;file&gt;</span>`);
            return;
        }

        const node = getNode(resolvePath(fileArg, cwd));
        if (!node || node.type === 'dir') {
            printOutput(`<span class="text-rose-400">grep: ${escapeHTML(fileArg)}: No such file</span>`);
            return;
        }

        const lines = (node.content || '').split('\n');
        const matched = [];
        const reg = new RegExp(escapeRegex(pattern), ignoreCase ? 'gi' : 'g');

        lines.forEach(l => {
            if (reg.test(l)) {
                const highlighted = l.replace(reg, m => `<span class="text-yellow-300 font-bold bg-yellow-500/20 px-0.5 rounded">${escapeHTML(m)}</span>`);
                matched.push(highlighted);
            }
        });

        if (matched.length > 0) {
            printOutput(`<div class="space-y-0.5">${matched.join('<br>')}</div>`);
        }
    }

    function executePipelinedCommands(pipeLineStr) {
        const parts = pipeLineStr.split('|').map(p => p.trim());
        const first = parts[0];
        const second = parts[1];

        // Execute first command conceptually
        const tokens1 = tokenizeCommand(first);
        const cmd1 = tokens1[0].toLowerCase();
        let bufferText = '';

        if (cmd1 === 'cat' && tokens1[1]) {
            const node = getNode(resolvePath(tokens1[1], cwd));
            bufferText = node && node.type === 'file' ? node.content : '';
        } else if (cmd1 === 'ls') {
            const node = getNode(cwd);
            bufferText = Object.keys(node.children || {}).join('\n');
        } else if (cmd1 === 'ps') {
            bufferText = `USER PID %CPU %MEM COMMAND\nroot 1 0.0 0.1 systemd\nbajrang 1420 0.2 0.8 bash\nwazuh 2841 0.5 1.2 wazuh-agentd\nroot 8892 0.1 0.4 sshd\nroot 9912 0.3 2.1 nginx`;
        }

        const tokens2 = tokenizeCommand(second);
        const cmd2 = tokens2[0].toLowerCase();

        if (cmd2 === 'grep' && tokens2[1]) {
            const pattern = tokens2[1];
            const ignoreCase = tokens2.includes('-i');
            const reg = new RegExp(escapeRegex(pattern), ignoreCase ? 'gi' : 'g');
            const lines = bufferText.split('\n').filter(l => reg.test(l));
            if (lines.length) {
                printOutput(lines.map(l => l.replace(reg, m => `<span class="text-yellow-300 font-bold bg-yellow-500/20 px-0.5 rounded">${escapeHTML(m)}</span>`)).join('<br>'));
            } else {
                printOutput(`<span class="text-slate-500">// (no match found)</span>`);
            }
        } else if (cmd2 === 'wc') {
            const lines = bufferText.split('\n').filter(Boolean);
            printOutput(`<span class="text-slate-200">      ${lines.length}      ${bufferText.split(/\s+/).filter(Boolean).length}     ${bufferText.length}</span>`);
        } else if (cmd2 === 'head') {
            printOutput(`<pre class="text-slate-200 font-mono text-xs">${escapeHTML(bufferText.split('\n').slice(0, 5).join('\n'))}</pre>`);
        } else {
            printOutput(`<span class="text-slate-400">Piped output to ${escapeHTML(cmd2)}</span>`);
        }
    }

    function executeRedirectedCommand(line) {
        const isAppend = line.includes('>>');
        const splitSymbol = isAppend ? '>>' : '>';
        const [leftPart, targetFile] = line.split(splitSymbol).map(s => s.trim());
        if (!targetFile) return;

        const leftTokens = tokenizeCommand(leftPart);
        const cmd = leftTokens[0].toLowerCase();
        let contentToWrite = '';

        if (cmd === 'echo') {
            contentToWrite = leftTokens.slice(1).join(' ');
        } else if (cmd === 'date') {
            contentToWrite = new Date().toUTCString();
        } else if (cmd === 'whoami') {
            contentToWrite = currentUser;
        } else if (cmd === 'pwd') {
            contentToWrite = cwd;
        } else {
            contentToWrite = `[output from ${cmd}]`;
        }

        const res = getParentNode(targetFile);
        if (!res || !res.parent) {
            printOutput(`<span class="text-rose-400">bash: ${escapeHTML(targetFile)}: No such file or directory</span>`);
            return;
        }

        if (res.parent.children[res.name]) {
            if (isAppend) {
                res.parent.children[res.name].content += '\n' + contentToWrite;
            } else {
                res.parent.children[res.name].content = contentToWrite;
            }
            res.parent.children[res.name].date = getCurrentDate();
            res.parent.children[res.name].size = res.parent.children[res.name].content.length;
        } else {
            res.parent.children[res.name] = {
                type: 'file',
                perms: '-rw-r--r--',
                owner: currentUser,
                group: currentUser,
                size: contentToWrite.length,
                date: getCurrentDate(),
                content: contentToWrite
            };
        }
        printOutput(`<span class="text-emerald-400 text-xs">[✓] Redirection successful: wrote ${contentToWrite.length} bytes to '${escapeHTML(targetFile)}'.</span>`);
    }

    function cmdId() {
        if (currentUser === 'root') {
            printOutput(`<span class="text-slate-200">uid=0(root) gid=0(root) groups=0(root),1(daemon),2(bin),3(sys),4(adm),10(wheel)</span>`);
        } else {
            printOutput(`<span class="text-slate-200">uid=1000(bajrang) gid=1000(bajrang) groups=1000(bajrang),4(adm),24(cdrom),27(sudo),100(users),998(wazuh)</span>`);
        }
    }

    function cmdUname(args) {
        if (args.includes('-a')) {
            printOutput(`<span class="text-slate-200">Linux ${escapeHTML(currentHost)} 6.8.0-kali3-amd64 #1 SMP PREEMPT x86_64 GNU/Linux</span>`);
        } else if (args.includes('-r')) {
            printOutput(`<span class="text-slate-200">6.8.0-kali3-amd64</span>`);
        } else if (args.includes('-m')) {
            printOutput(`<span class="text-slate-200">x86_64</span>`);
        } else {
            printOutput(`<span class="text-slate-200">Linux</span>`);
        }
    }

    function cmdUptime() {
        printOutput(`<span class="text-slate-200">${new Date().toLocaleTimeString()} up 42 days, 3:14, 2 users, load average: 0.14, 0.09, 0.05</span>`);
    }

    function cmdFree(args) {
        printOutput(`
<pre class="text-slate-300 font-mono text-xs">
               total        used        free      shared  buff/cache   available
Mem:        32768000     8255600    18512400      452100     6000000    24060200
Swap:        8388608           0     8388608
</pre>`);
    }

    function cmdDf(args) {
        printOutput(`
<pre class="text-slate-300 font-mono text-xs">
Filesystem      Size  Used Avail Use% Mounted on
/dev/nvme0n1p2  468G   84G  361G  19% /
udev             16G     0   16G   0% /dev
tmpfs           3.2G  2.4M  3.2G   1% /run
tmpfs            16G   12M   16G   1% /dev/shm
/dev/nvme0n1p1  512M  9.8M  502M   2% /boot/efi
</pre>`);
    }

    function cmdPs(args) {
        printOutput(`
<pre class="text-slate-300 font-mono text-xs">
USER         PID %CPU %MEM    VSZ   RSS TTY      STAT START   TIME COMMAND
root           1  0.0  0.1 168240 12844 ?        Ss   08:00   0:02 /sbin/init
root           2  0.0  0.0      0     0 ?        S    08:00   0:00 [kthreadd]
root         642  0.0  0.1  89240  8420 ?        Ss   08:00   0:00 /usr/sbin/sshd -D
wazuh        988  0.4  1.2 342100 98400 ?        Sl   08:01   0:15 /var/ossec/bin/wazuh-agentd
root        1042  0.1  0.4 124000 32400 ?        S    08:01   0:03 /usr/sbin/nginx -g daemon on;
bajrang     1420  0.0  0.1  24500  8900 pts/0    Ss   08:14   0:00 -bash
bajrang     1840  1.2  4.8 980400 398200 ?       Sl   08:15   0:42 /opt/burpsuite/jre/bin/java -jar burp.jar
root        2950  0.0  0.1  18400  3200 pts/0    R+   20:30   0:00 ps aux
</pre>`);
    }

    function cmdTop() {
        printOutput(`
<div class="border border-slate-800 rounded p-2 bg-slate-950 font-mono text-xs">
  <div class="text-emerald-400 font-bold">top - 20:31:05 up 42 days, 3:15, 2 users, load average: 0.12, 0.08, 0.05</div>
  <div class="text-slate-400">Tasks: 184 total, 1 running, 183 sleeping, 0 stopped, 0 zombie</div>
  <div class="text-cyan-300">%Cpu(s): 1.4 us, 0.4 sy, 0.0 ni, 98.1 id, 0.1 wa, 0.0 hi, 0.0 si</div>
  <div class="text-slate-400">MiB Mem :  32000.0 total,  18078.5 free,   8062.1 used,   5859.4 buff/cache</div>
  <div class="text-slate-400">MiB Swap:   8192.0 total,   8192.0 free,      0.0 used.  23496.0 avail Mem</div>
  <div class="mt-2 text-slate-300">
    <span class="text-emerald-400">PID USER      PR  NI    VIRT    RES    SHR S  %CPU  %MEM     TIME+ COMMAND</span><br>
    1840 bajrang   20   0  980400 398200  42100 S   1.8   4.8   0:42.18 java (burpsuite)<br>
    988 wazuh     20   0  342100  98400  18200 S   0.6   1.2   0:15.34 wazuh-agentd<br>
    1042 root      20   0  124000  32400   8400 S   0.2   0.4   0:03.12 nginx<br>
    1420 bajrang   20   0   24500   8900   3200 S   0.0   0.1   0:00.28 bash
  </div>
</div>`);
    }

    function cmdIfconfig() {
        printOutput(`
<pre class="text-slate-300 font-mono text-xs">
eth0: flags=4163&lt;UP,BROADCAST,RUNNING,MULTICAST&gt;  mtu 1500
        inet 192.168.1.100  netmask 255.255.255.0  broadcast 192.168.1.255
        inet6 fe80::a00:27ff:fe4e:66b1  prefixlen 64  scopeid 0x20&lt;link&gt;
        ether 08:00:27:4e:66:b1  txqueuelen 1000  (Ethernet)
        RX packets 1849202  bytes 240182400 (229.0 MiB)
        TX packets 1420194  bytes 194018240 (185.0 MiB)

tun0: flags=4305&lt;UP,POINTOPOINT,RUNNING,NOARP,MULTICAST&gt;  mtu 1500
        inet 10.8.0.42  netmask 255.255.255.0  destination 10.8.0.1
        inet6 fe80::e12:88ff:fe12:44aa  prefixlen 64  scopeid 0x20&lt;link&gt;
        unspec 00-00-00-00-00-00-00-00-00-00-00-00-00-00-00-00  txqueuelen 500
        RX packets 42102  bytes 18402100 (17.5 MiB)
        TX packets 39811  bytes 14201000 (13.5 MiB)

lo: flags=73&lt;UP,LOOPBACK,RUNNING&gt;  mtu 65536
        inet 127.0.0.1  netmask 255.0.0.0
        loop  txqueuelen 1000  (Local Loopback)
</pre>`);
    }

    function cmdNetstat(args) {
        printOutput(`
<pre class="text-slate-300 font-mono text-xs">
Active Internet connections (only servers)
Proto Recv-Q Send-Q Local Address           Foreign Address         State       PID/Program name    
tcp        0      0 0.0.0.0:22              0.0.0.0:*               LISTEN      642/sshd            
tcp        0      0 0.0.0.0:80              0.0.0.0:*               LISTEN      1042/nginx: master  
tcp        0      0 0.0.0.0:443             0.0.0.0:*               LISTEN      1042/nginx: master  
tcp        0      0 127.0.0.1:8080          0.0.0.0:*               LISTEN      1840/java (burp)    
tcp        0      0 10.8.0.42:1514          10.0.0.2:1514           ESTABLISHED 988/wazuh-agentd    
tcp6       0      0 :::22                   :::*                    LISTEN      642/sshd            
</pre>`);
    }

    function cmdHistory() {
        if (!commandHistory.length) {
            printOutput(`<span class="text-slate-500">// Command history is empty.</span>`);
            return;
        }
        const histLines = commandHistory.map((cmd, i) => `  <span class="text-slate-500">${String(i + 1).padStart(4, ' ')}</span>  <span class="text-slate-200">${escapeHTML(cmd)}</span>`).join('<br>');
        printOutput(`<div>${histLines}</div>`);
    }

    function cmdEnv() {
        printOutput(`
<div class="space-y-0.5 text-[11px] text-slate-300">
  <div><span class="text-emerald-400 font-bold">USER</span>=${escapeHTML(currentUser)}</div>
  <div><span class="text-emerald-400 font-bold">HOME</span>=${currentUser === 'root' ? '/root' : '/home/bajrang'}</div>
  <div><span class="text-emerald-400 font-bold">SHELL</span>=/bin/bash</div>
  <div><span class="text-emerald-400 font-bold">TERM</span>=xterm-256color</div>
  <div><span class="text-emerald-400 font-bold">PATH</span>=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin</div>
  <div><span class="text-emerald-400 font-bold">LANG</span>=en_US.UTF-8</div>
  <div><span class="text-emerald-400 font-bold">NODE_LOCATION</span>=Birgunj, Nepal 44300</div>
  <div><span class="text-emerald-400 font-bold">SECURITY_SPECIALIST</span>=Bajrang Kumar Sarraf</div>
  <div><span class="text-emerald-400 font-bold">ACCREDITATIONS</span>=RHCSA, NPTEL_Java, NPTEL_Networks, Ethical_Hacking</div>
</div>`);
    }

    function cmdWhich(args) {
        if (!args.length) return;
        const target = args[0];
        const known = ['bash', 'sh', 'ls', 'cat', 'echo', 'pwd', 'mkdir', 'touch', 'rm', 'cp', 'mv', 'grep', 'ps', 'nmap', 'wazuh-control', 'burpsuite', 'neofetch', 'curl', 'ping', 'whoami', 'id', 'uname', 'free', 'df', 'top', 'tree'];
        if (known.includes(target)) {
            printOutput(`<span class="text-slate-200">/usr/bin/${escapeHTML(target)}</span>`);
        } else {
            printOutput(`<span class="text-rose-400">${escapeHTML(target)} not found in ($PATH)</span>`);
        }
    }

    function cmdFind(args) {
        const results = [];
        function recurse(path, node) {
            results.push(path);
            if (node.children) {
                for (const [k, v] of Object.entries(node.children)) {
                    recurse((path === '/' ? '' : path) + '/' + k, v);
                }
            }
        }
        recurse(cwd, getNode(cwd));
        printOutput(`<div class="space-y-0.5 text-slate-300">${results.map(r => escapeHTML(r)).join('<br>')}</div>`);
    }

    function cmdWc(args) {
        if (!args.length) return;
        const node = getNode(resolvePath(args[0], cwd));
        if (!node || node.type === 'dir') {
            printOutput(`<span class="text-rose-400">wc: ${escapeHTML(args[0])}: No such file</span>`);
            return;
        }
        const c = node.content || '';
        const lines = c.split('\n').length;
        const words = c.split(/\s+/).filter(Boolean).length;
        const bytes = c.length;
        printOutput(`<span class="text-slate-200"> ${lines}  ${words} ${bytes} ${escapeHTML(args[0])}</span>`);
    }

    function cmdTree(args) {
        const rootPath = args[0] ? resolvePath(args[0], cwd) : cwd;
        const rootNode = getNode(rootPath);
        if (!rootNode || rootNode.type !== 'dir') {
            printOutput(`<span class="text-rose-400">tree: [error opening dir]</span>`);
            return;
        }

        const lines = [`<span class="text-cyan-400 font-bold">${escapeHTML(rootPath)}</span>`];
        let dirCount = 0;
        let fileCount = 0;

        function buildTree(node, prefix = '') {
            const keys = Object.keys(node.children || {}).filter(k => !k.startsWith('.'));
            keys.forEach((k, idx) => {
                const isLast = idx === keys.length - 1;
                const child = node.children[k];
                const marker = isLast ? '└── ' : '├── ';
                const subPrefix = isLast ? '    ' : '│   ';

                if (child.type === 'dir') {
                    dirCount++;
                    lines.push(`${prefix}${marker}<span class="text-cyan-400 font-bold">${escapeHTML(k)}/</span>`);
                    buildTree(child, prefix + subPrefix);
                } else {
                    fileCount++;
                    lines.push(`${prefix}${marker}<span class="text-slate-300">${escapeHTML(k)}</span>`);
                }
            });
        }

        buildTree(rootNode);
        lines.push(`<br><span class="text-slate-500">${dirCount} directories, ${fileCount} files</span>`);
        printOutput(`<div class="font-mono text-xs leading-tight">${lines.join('<br>')}</div>`);
    }

    function cmdChmod(cmd, args) {
        if (args.length < 2) {
            printOutput(`<span class="text-slate-400">Usage: ${cmd} &lt;mode/owner&gt; &lt;file&gt;</span>`);
            return;
        }
        const path = resolvePath(args[1], cwd);
        const node = getNode(path);
        if (!node) {
            printOutput(`<span class="text-rose-400">${cmd}: cannot access '${escapeHTML(args[1])}': No such file</span>`);
            return;
        }
        if (cmd === 'chmod') node.perms = args[0];
        if (cmd === 'chown') node.owner = args[0];
        printOutput(`<span class="text-emerald-400 text-xs">[✓] Permissions updated for '${escapeHTML(args[1])}'.</span>`);
    }

    function cmdCurl(tool, args) {
        if (!args.length) {
            printOutput(`<span class="text-slate-400">${tool}: try '${tool} --help' or '${tool} http://localhost'</span>`);
            return;
        }
        const target = args.find(a => !a.startsWith('-')) || 'http://localhost';
        printOutput(`
<pre class="text-cyan-300 font-mono text-xs">
HTTP/2 200 OK
date: ${new Date().toUTCString()}
server: Apache/2.4.58 (Win64) OpenSSL/3.1.3 PHP/8.0.30
strict-transport-security: max-age=31536000; includeSubDomains; preload
x-content-type-options: nosniff
x-frame-options: SAMEORIGIN
x-xss-protection: 1; mode=block
referrer-policy: strict-origin-when-cross-origin
content-type: text/html; charset=UTF-8

&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
  &lt;!-- Target Host: Bajrang Kumar Sarraf Security Portfolio (Birgunj, Nepal) --&gt;
  &lt;status&gt;FORTIFIED_AND_ONLINE&lt;/status&gt;
&lt;/html&gt;
</pre>`);
    }

    function cmdPing(args) {
        const host = args[0] || '127.0.0.1';
        printOutput(`
<pre class="text-slate-300 font-mono text-xs">
PING ${escapeHTML(host)} (${host === '8.8.8.8' ? 'dns.google' : host}) 56(84) bytes of data.
64 bytes from ${escapeHTML(host)}: icmp_seq=1 ttl=64 time=0.038 ms
64 bytes from ${escapeHTML(host)}: icmp_seq=2 ttl=64 time=0.041 ms
64 bytes from ${escapeHTML(host)}: icmp_seq=3 ttl=64 time=0.039 ms
64 bytes from ${escapeHTML(host)}: icmp_seq=4 ttl=64 time=0.040 ms

--- ${escapeHTML(host)} ping statistics ---
4 packets transmitted, 4 received, 0% packet loss, time 3004ms
rtt min/avg/max/mdev = 0.038/0.039/0.041/0.001 ms
</pre>`);
    }

    function cmdNeofetch() {
        printOutput(`
<div class="flex flex-col sm:flex-row gap-6 p-2 bg-slate-950/80 rounded border border-slate-800">
  <div class="text-rose-500 font-bold font-mono text-xs shrink-0 select-none hidden sm:block">
           .---.
          /     \\
         | () () |
          \\  -  /
   .---.---'---'---.---.
  / |  |           |  | \\
 /  |  |           |  |  \\
    |  | Red Hat   |  |
    |  | Enterprise|  |
    |  | Linux 9.4 |  |
   '---'-----------'---'
  </div>
  <div class="space-y-1 text-xs font-mono">
    <div class="text-emerald-400 font-bold text-sm mb-1">${escapeHTML(currentUser)}@${escapeHTML(currentHost)}</div>
    <div class="text-slate-600 mb-2">------------------------------------</div>
    <div><span class="text-cyan-400 font-bold">OS:</span> Red Hat Enterprise Linux 9.4 (Plow) x86_64</div>
    <div><span class="text-cyan-400 font-bold">Host:</span> Security Engineering Station #BKS-44300</div>
    <div><span class="text-cyan-400 font-bold">Kernel:</span> 6.8.0-kali3-amd64 #1 SMP PREEMPT</div>
    <div><span class="text-cyan-400 font-bold">Uptime:</span> 42 days, 3 hours, 14 mins</div>
    <div><span class="text-cyan-400 font-bold">Packages:</span> 1842 (rpm), 42 (flatpak)</div>
    <div><span class="text-cyan-400 font-bold">Shell:</span> GNU bash 5.2.21</div>
    <div><span class="text-cyan-400 font-bold">Terminal:</span> /dev/pts/0 (xterm-256color)</div>
    <div><span class="text-cyan-400 font-bold">CPU:</span> 13th Gen Intel Core i7-13700H (16) @ 5.00GHz</div>
    <div><span class="text-cyan-400 font-bold">Memory:</span> 8192MiB / 32768MiB (25%)</div>
    <div class="pt-1.5"><span class="text-yellow-400 font-bold">Specialist:</span> Bajrang Kumar Sarraf</div>
    <div><span class="text-emerald-400 font-bold">Certs:</span> RHCSA, Ethical Hacking, NPTEL Java & Networks</div>
    <div class="flex gap-1.5 pt-2">
      <span class="w-3.5 h-3.5 rounded-sm bg-black border border-slate-700"></span>
      <span class="w-3.5 h-3.5 rounded-sm bg-red-500"></span>
      <span class="w-3.5 h-3.5 rounded-sm bg-emerald-500"></span>
      <span class="w-3.5 h-3.5 rounded-sm bg-yellow-500"></span>
      <span class="w-3.5 h-3.5 rounded-sm bg-cyan-500"></span>
      <span class="w-3.5 h-3.5 rounded-sm bg-indigo-500"></span>
      <span class="w-3.5 h-3.5 rounded-sm bg-white"></span>
    </div>
  </div>
</div>`);
    }

    function cmdNmap(args) {
        const target = args.find(a => !a.startsWith('-')) || 'target.local';
        printOutput(`
<div class="space-y-1 font-mono text-xs">
  <div class="text-slate-400">Starting Nmap 7.94 ( https://nmap.org ) at ${new Date().toLocaleTimeString()}</div>
  <div class="text-slate-300">Nmap scan report for ${escapeHTML(target)} (192.168.1.105)</div>
  <div class="text-slate-400">Host is up (0.00042s latency).</div>
  <div class="text-slate-400">Not shown: 996 closed tcp ports (reset)</div>
  
  <div class="mt-2 border-t border-slate-800 pt-2">
    <div class="grid grid-cols-12 gap-2 text-emerald-400 font-bold border-b border-slate-800 pb-1">
      <span class="col-span-3">PORT</span>
      <span class="col-span-2">STATE</span>
      <span class="col-span-3">SERVICE</span>
      <span class="col-span-4">VERSION</span>
    </div>
    <div class="grid grid-cols-12 gap-2 py-0.5 text-slate-300">
      <span class="col-span-3 text-cyan-300">22/tcp</span>
      <span class="col-span-2 text-emerald-400 font-semibold">open</span>
      <span class="col-span-3">ssh</span>
      <span class="col-span-4 text-slate-400">OpenSSH 8.9p1 Ubuntu (protocol 2.0)</span>
    </div>
    <div class="grid grid-cols-12 gap-2 py-0.5 text-slate-300">
      <span class="col-span-3 text-cyan-300">80/tcp</span>
      <span class="col-span-2 text-emerald-400 font-semibold">open</span>
      <span class="col-span-3">http</span>
      <span class="col-span-4 text-slate-400">Nginx 1.24.0 (PHP 8.2.18)</span>
    </div>
    <div class="grid grid-cols-12 gap-2 py-0.5 text-slate-300">
      <span class="col-span-3 text-cyan-300">443/tcp</span>
      <span class="col-span-2 text-emerald-400 font-semibold">open</span>
      <span class="col-span-3">ssl/https</span>
      <span class="col-span-4 text-slate-400">Nginx 1.24.0 (TLS 1.3 enforced)</span>
    </div>
    <div class="grid grid-cols-12 gap-2 py-0.5 text-slate-300">
      <span class="col-span-3 text-cyan-300">8080/tcp</span>
      <span class="col-span-2 text-yellow-400 font-semibold">open</span>
      <span class="col-span-3">http-proxy</span>
      <span class="col-span-4 text-slate-400">BurpSuite Interceptor v2024.3</span>
    </div>
  </div>

  <div class="mt-2 text-slate-400 pt-1 border-t border-slate-800">
    <div>Service Info: OS: Linux; CPE: cpe:/o:linux:linux_kernel</div>
    <div class="text-emerald-400 font-bold mt-1">[✓] Nmap done: 1 IP address (1 host up) scanned in 1.48 seconds</div>
  </div>
</div>`);
    }

    function cmdVapt() {
        printOutput(`
<div class="space-y-2 font-mono text-xs">
  <div class="text-yellow-400 font-bold">[*] INITIALIZING COMPREHENSIVE VAPT AUDIT SIMULATION ENGINE...</div>
  <div class="text-cyan-400">[1/5] RECONNAISSANCE & ATTACK SURFACE MAPPING</div>
  <div class="text-slate-400 pl-4">&bull; Target: internal.target.lab (192.168.1.0/24) | Passive OSINT + Active Port Enumeration complete.</div>
  
  <div class="text-cyan-400">[2/5] VULNERABILITY SCANNING & SERVICE ENUMERATION</div>
  <div class="text-slate-400 pl-4">&bull; Probing OWASP Top 10 vectors: SQLi, Broken Auth, IDOR, SSRF, Misconfiguration, CSRF.</div>
  
  <div class="text-cyan-400">[3/5] MANUAL ANALYSIS & EXPLOITATION VALIDATION</div>
  <div class="text-slate-400 pl-4">&bull; Testing parameter tampering & privilege escalation logic in web handlers.</div>
  
  <div class="text-cyan-400">[4/5] PROOF-OF-CONCEPT (POC) BENCHMARKING & RISK SCORING</div>
  <div class="text-slate-400 pl-4">&bull; Findings evaluated under CVSS v3.1: 0 Critical Alerts, 0 High Flaws, 1 Informational Header missing.</div>
  
  <div class="text-cyan-400">[5/5] ACTIONABLE REMEDIATION ROADMAP</div>
  <div class="text-slate-400 pl-4">&bull; Mitigation patch applied: Prepared PDO statements, Content-Security-Policy headers fortified.</div>
  
  <div class="text-emerald-400 font-bold pt-1">[✓] System audit complete: 100% compliant with OWASP Top 10 standards. Remediation signed off.</div>
</div>`);
    }

    function cmdWazuh() {
        printOutput(`
<div class="space-y-1 font-mono text-xs">
  <div class="text-emerald-400 font-bold mb-1">// WAZUH HOST EDR & SIEM TELEMETRY STATUS</div>
  <div class="grid grid-cols-2 gap-2 p-2 bg-slate-950 rounded border border-slate-800">
    <div><span class="text-slate-400">Agent Name:</span> <span class="text-white">security-node-birgunj</span></div>
    <div><span class="text-slate-400">Agent ID:</span> <span class="text-cyan-300">042</span></div>
    <div><span class="text-slate-400">Status:</span> <span class="text-emerald-400 font-bold">[ACTIVE / CONNECTED]</span></div>
    <div><span class="text-slate-400">Manager:</span> <span class="text-slate-200">10.0.0.2:1514 (TLS)</span></div>
    <div><span class="text-slate-400">FIM Monitor:</span> <span class="text-emerald-400 font-bold">Enabled (/bin, /etc, /var)</span></div>
    <div><span class="text-slate-400">Active Defense:</span> <span class="text-emerald-400 font-bold">Enabled (firewalld drop)</span></div>
    <div><span class="text-slate-400">SCA Benchmark:</span> <span class="text-white">CIS Benchmark RHEL 9 (94% Score)</span></div>
    <div><span class="text-slate-400">Critical Alerts:</span> <span class="text-emerald-400 font-bold">0 Active Threats</span></div>
  </div>
</div>`);
    }

    function cmdSplunk() {
        printOutput(`
<div class="space-y-1 font-mono text-xs">
  <div class="text-cyan-400 font-bold mb-1">// SPLUNK UNIVERSAL FORWARDER ENGINE</div>
  <div class="p-2 bg-slate-950 rounded border border-slate-800 text-slate-300 space-y-1">
    <div>Splunk Version: <span class="text-white font-bold">9.2.1</span> (Forwarder Node)</div>
    <div>Server Cluster: <span class="text-emerald-400 font-bold">splunk-indexer.secops.internal:9997</span> (Connected)</div>
    <div>Monitored Inputs:</div>
    <div class="text-slate-400 pl-4">&bull; /var/log/auth.log [sourcetype=linux_secure]</div>
    <div class="text-slate-400 pl-4">&bull; /var/log/syslog [sourcetype=syslog]</div>
    <div class="text-slate-400 pl-4">&bull; /var/log/wazuh.log [sourcetype=wazuh_alerts]</div>
    <div class="text-emerald-400 font-bold pt-1">[✓] Ingestion queue healthy. Zero pipeline lag detected.</div>
  </div>
</div>`);
    }

    function cmdBurp() {
        printOutput(`
<div class="space-y-1 font-mono text-xs">
  <div class="text-amber-400 font-bold mb-1">// BURP SUITE PROFESSIONAL v2024.3 - PROXY & SCANNER</div>
  <div class="p-2 bg-slate-950 rounded border border-slate-800 text-slate-300 space-y-1">
    <div>Proxy Status: <span class="text-emerald-400 font-bold">LISTENING ON 127.0.0.1:8080</span></div>
    <div>SSL Interception: <span class="text-emerald-400 font-bold">Active (PortSwigger CA Installed)</span></div>
    <div>Target Sitemap: <span class="text-cyan-300 font-bold">7 Hostnames Cataloged</span></div>
    <div>Active Scanner: <span class="text-white">OWASP Top 10 passive audit checks running</span></div>
    <div>Repeater Sessions: <span class="text-white">12 Verified PoC payloads recorded</span></div>
    <div class="text-emerald-400 font-bold pt-1">[✓] Ready to intercept HTTP/HTTPS traffic.</div>
  </div>
</div>`);
    }

    function cmdSqlmap(args) {
        printOutput(`
<pre class="text-slate-300 font-mono text-xs">
        ___
       __H__
 ___ ___[.]_____ ___ ___  {1.8.4#stable}
|_ -| . [)]     | .'| . |
|___|_  ["]_|_|_|__,|  _|
      |_|V...       |_|   https://sqlmap.org

[*] starting @ ${new Date().toLocaleTimeString()}
[INFO] testing connection to the target URL
[INFO] checking if the target is protected by some kind of WAF/IPS
[INFO] testing if the target parameter is dynamic
[INFO] confirming parameter vulnerability via boolean-based blind, error-based, UNION query
<span class="text-emerald-400 font-bold">[✓] Target uses PHP 8+ parameterized PDO prepared statements. All parameters SANITIZED.</span>
<span class="text-emerald-400 font-bold">[✓] SQL injection vulnerability NOT found. System securely hardened.</span>
</pre>`);
    }

    function cmdHydra(args) {
        printOutput(`
<pre class="text-slate-300 font-mono text-xs">
Hydra v9.5 (c) 2023 by van Hauser / THC & David Maciejak - Please do not use in military or secret service organizations
[DATA] attacking ssh://192.168.1.100:22/
[STATUS] 16 tasks, 1 server, 1000 login tries (l:1/p:1000), 42 tries/sec
<span class="text-yellow-400 font-bold">[ATTENTION] Rate limit reached. iptables fail2ban rule triggered: IP banned for 15 minutes.</span>
[STATUS] Attack stopped by security mitigation policy.
</pre>`);
    }

    function cmdNikto(args) {
        printOutput(`
<pre class="text-slate-300 font-mono text-xs">
- Nikto v2.5.0
---------------------------------------------------------------------------
+ Target IP:          127.0.0.1
+ Target Hostname:    localhost
+ Target Port:        80
+ Start Time:         ${new Date().toLocaleTimeString()}
---------------------------------------------------------------------------
+ Server: Apache/2.4.58 (Win64) OpenSSL/3.1.3 PHP/8.0.30
+ The anti-clickjacking X-Frame-Options header is present: SAMEORIGIN.
+ The X-Content-Type-Options header is present: nosniff.
+ Strict-Transport-Security header configured properly.
+ Content-Security-Policy (CSP) whitelist active.
<span class="text-emerald-400 font-bold">+ 0 vulnerabilities reported. 7 security headers verified.</span>
</pre>`);
    }

    function cmdGobuster(args) {
        printOutput(`
<pre class="text-slate-300 font-mono text-xs">
===============================================================
Gobuster v3.6 - Directory and File Brute Forcing Mode
===============================================================
[+] Url:                     http://localhost/Bajrangss/
[+] Method:                  GET
[+] Threads:                 10
[+] Wordlist:                /usr/share/wordlists/dirb/common.txt
[+] Status codes:            200,204,301,302,307,401,403
===============================================================
/admin                (Status: 302) [Size: 0] [--> admin/login.php]
/assets               (Status: 301) [Size: 341]
/uploads              (Status: 403) [Size: 284] (Directory listing denied)
/config               (Status: 403) [Size: 284] (Forbidden)
/storage              (Status: 403) [Size: 284] (Forbidden)
===============================================================
Finished scan in 1.284s.
</pre>`);
    }

    function cmdTcpdump() {
        printOutput(`
<div class="space-y-0.5 text-[11px] text-slate-300 font-mono">
  <div class="text-cyan-400 font-bold">tcpdump: verbose output suppressed, use -v[v]... for full protocol decode</div>
  <div>listening on eth0, link-type EN10MB (Ethernet), snapshot length 262144 bytes</div>
  <div>20:32:01.102841 IP 192.168.1.50.52314 &gt; 192.168.1.100.22: Flags [P.], seq 1:45, ack 1, win 502, length 44</div>
  <div>20:32:01.102914 IP 192.168.1.100.22 &gt; 192.168.1.50.52314: Flags [.], ack 45, win 501, length 0</div>
  <div>20:32:01.145028 IP 192.168.1.50.53124 &gt; 192.168.1.100.443: Flags [S], seq 3824102, win 65535, options [mss 1460,sackOK,TS val]</div>
  <div>20:32:01.145102 IP 192.168.1.100.443 &gt; 192.168.1.50.53124: Flags [S.], seq 9482104, ack 3824103, win 65160, length 0</div>
  <div class="text-emerald-400 pt-1">^C 4 packets captured, 4 packets received by filter, 0 packets dropped by kernel</div>
</div>`);
    }

    function cmdAircrack() {
        printOutput(`
<div class="space-y-1 font-mono text-xs">
  <div class="text-cyan-400 font-bold">// ESP8266 & AIRCRACK-NG 802.11 SECURITY AUDITOR</div>
  <div class="p-2 bg-slate-950 rounded border border-slate-800 text-slate-300 space-y-1">
    <div>Hardware Interface: <span class="text-yellow-300 font-bold">ESP8266 NodeMCU (Embedded C++)</span></div>
    <div>Capability: <span class="text-white">Promiscuous mode 802.11 b/g/n packet dissection</span></div>
    <div>Testing Scope: <span class="text-emerald-400 font-bold">Controlled isolated security laboratory only</span></div>
    <div>Observations:</div>
    <div class="text-slate-400 pl-4">&bull; Unencrypted management frames detected in legacy 802.11w disabled APs.</div>
    <div class="text-slate-400 pl-4">&bull; Deauthentication validation demonstrated vulnerability to beacon frame injection.</div>
    <div class="text-emerald-400 font-bold pt-1">[✓] Remediation: Enforce WPA3-SAE and Protected Management Frames (802.11w PMF).</div>
  </div>
</div>`);
    }

    function cmdHashcat() {
        printOutput(`
<pre class="text-slate-300 font-mono text-xs">
hashcat (v6.2.6) starting...
OpenCL API (OpenCL 3.0 ) - Platform #1 [Intel(R) Corporation]
Device #1: Intel(R) Iris(R) Xe Graphics, 8192/16384 MB (2048 MB allocatable)
Hash-Type: SHA-256 (SHA2-256) (Mode #1400)
Speed.#1.........:   248.4 MH/s (5.24ms) @ Accel:128 Loops:1024 Thr:64
Candidates.#1....:   123456 -> Pa$$w0rd2026!
Status...........:   Cracked!
<span class="text-emerald-400 font-bold">[✓] Result: Strong password policy & bcrypt/Argon2 hashing prevents dictionary attacks.</span>
</pre>`);
    }

    function cmdNetcat(args) {
        const target = args[0] || '127.0.0.1';
        const port = args[1] || '80';
        printOutput(`<span class="text-emerald-400 font-bold">Connection to ${escapeHTML(target)} ${escapeHTML(port)} port [tcp/http] succeeded!</span>`);
    }

    function cmdSudo(args) {
        if (!args.length || args[0] === 'su' || args[0] === '-i') {
            awaitingPassword = true;
            pendingRootCommand = null;
            terminalInput.type = 'password';
            terminalInput.placeholder = "Enter sudo password (e.g. cyber, bajrang, admin)...";
            printOutput(`<span class="text-yellow-400 font-bold font-mono">[sudo] password for bajrang:</span>`);
            return;
        }

        const subCmd = args.join(' ');
        awaitingPassword = true;
        pendingRootCommand = subCmd;
        terminalInput.type = 'password';
        terminalInput.placeholder = "Enter sudo password (e.g. cyber, bajrang, admin)...";
        printOutput(`<span class="text-yellow-400 font-bold font-mono">[sudo] password for bajrang:</span>`);
    }

    function cmdSu(args) {
        cmdSudo(['su']);
    }

    function cmdExit() {
        if (currentUser === 'root') {
            currentUser = 'bajrang';
            updatePrompt();
            printOutput(`<span class="text-emerald-400">[✓] Dropped root privileges. Returned to user bajrang.</span>`);
        } else {
            terminalOutput.innerHTML = '';
            printOutput(`<span class="text-slate-400">Terminal session reset. Type <span class="text-emerald-400 font-bold">help</span> to begin.</span>`);
        }
    }

    function cmdReboot() {
        printOutput(`
<div class="space-y-1 text-slate-300 font-mono text-xs">
  <div class="text-yellow-400 font-bold">Broadcast message from root@security-node (pts/0):</div>
  <div class="text-rose-400 font-bold">The system is going down for reboot NOW!</div>
  <div>[  OK  ] Stopped target Multi-User System.</div>
  <div>[  OK  ] Stopped OpenBSD Secure Shell server.</div>
  <div>[  OK  ] Stopped Wazuh EDR Agent.</div>
  <div>[  OK  ] Reached target System Reboot.</div>
  <div class="text-cyan-400 pt-2">Restarting security node...</div>
</div>`);
        setTimeout(() => {
            terminalOutput.innerHTML = '';
            currentUser = 'bajrang';
            cwd = '/home/bajrang';
            updatePrompt();
            printOutput(`
<div class="text-slate-400 mb-2 leading-relaxed">
  Linux security-node 6.8.0-kali3-amd64 #1 SMP PREEMPT x86_64 GNU/Linux.<br>
  Security Shell reboot complete. Node reconnected at <span class="text-emerald-400 font-bold">127.0.0.1</span>.<br>
  Type <span class="text-emerald-400 font-bold">help</span> to list commands or <span class="text-cyan-400 font-bold">ls -la</span> to explore.
</div>`);
        }, 1200);
    }

    function cmdPortfolioAlias(cmd) {
        switch (cmd) {
            case 'skills':
                cmdCat(['skills.txt']);
                break;
            case 'projects':
                cmdLs(['-l', 'projects']);
                break;
            case 'certs':
                cmdCat(['certifications/rhcsa-redhat.txt']);
                break;
            case 'awards':
                printOutput(`
<div class="space-y-1 font-mono text-xs">
  <div class="text-yellow-400 font-bold">🏆 Runner-Up - Ganpat University Hackathon</div>
  <div class="text-slate-300 pl-4">Recognized for rapid prototyping and security implementation.</div>
  <div class="text-cyan-400 font-bold mt-1">🎖️ Participant - IIT Jammu Hackathon</div>
  <div class="text-slate-300 pl-4">Collaborative engineering challenge solving complex distributed problems.</div>
</div>`);
                break;
            case 'contact':
                cmdCat(['contact.txt']);
                break;
            case 'about':
                cmdCat(['about.txt']);
                break;
            case 'bio':
                cmdCat(['bio.md']);
                break;
            case 'resume':
                const resumeLink = document.querySelector('a[download]');
                if (resumeLink && resumeLink.href) {
                    printOutput(`<span class="text-emerald-400 font-bold">[✓] Triggering resume download:</span> <a href="${resumeLink.href}" target="_blank" download class="underline text-cyan-400">${resumeLink.href}</a>`);
                    window.open(resumeLink.href, '_blank');
                } else {
                    printOutput(`<span class="text-emerald-400 font-bold">[✓] Contact bajrangshroff10@gmail.com for the verified PDF dossier.</span>`);
                }
                break;
        }
    }

    function cmdCowsay(args) {
        const text = args.join(' ') || 'Hack the planet! Secure every node.';
        const border = '-'.repeat(text.length + 2);
        printOutput(`
<pre class="text-emerald-300 font-mono text-xs">
 ${border}
&lt; ${escapeHTML(text)} &gt;
 ${border}
        \\   ^__^
         \\  (oo)\\_______
            (__)\\       )\\/\\
                ||----w |
                ||     ||
</pre>`);
    }

    function cmdSl() {
        printOutput(`
<pre class="text-cyan-400 font-mono text-xs">
      ====        ________                ___________
  _D _|  |_______/        \\__I_I_____===__|_________|
   |(_)---  |   H\\________/ _____ |   (|) |        |
   /     |  |   H  |  |     |   | |     | |        |
  |      |  |   H  |__--------------------|        |
  | ________|___H__/__|_____/[][]~\\_______|________|
  |/ |   |_____I_____I_____/         \\_____/      |
__(@@@)__(@@)______(@@)__(@@)_______________(@@)__(@@@)
</pre>`);
    }

    function cmdFortune() {
        const fortunes = [
            `"There is no cloud, just someone else's computer." — Sysadmin proverb`,
            `"Security in depth is not just layers of firewalls, it is defensive code from day one."`,
            `"Vulnerabilities are discovered by curious minds, but fixed by disciplined engineers."`,
            `"An open port is an invitation; a hardened system is a locked fortress."`,
            `"In Linux we trust; with sudo we double check."`
        ];
        const pick = fortunes[Math.floor(Math.random() * fortunes.length)];
        printOutput(`<span class="text-emerald-300 font-mono text-xs italic">${escapeHTML(pick)}</span>`);
    }

    /* -------------------------------------------------------------------------
       Interactive Matrix Code Stream Animation
       ------------------------------------------------------------------------- */
    function startMatrix() {
        if (matrixRunning) return;
        matrixRunning = true;

        const matrixWrap = document.createElement('div');
        matrixWrap.id = 'terminal-matrix-container';
        matrixWrap.className = 'w-full h-64 bg-black relative overflow-hidden rounded my-2 border border-emerald-500/40';
        matrixWrap.innerHTML = `
            <canvas id="matrix-canvas" class="w-full h-full block"></canvas>
            <div class="absolute bottom-2 right-2 text-[10px] font-mono text-emerald-400 bg-slate-900/90 px-2 py-0.5 rounded border border-emerald-500/40">
                [MATRIX ACTIVE] Press any key or click to exit
            </div>
        `;
        terminalOutput.appendChild(matrixWrap);
        scrollToBottom();

        const canvas = document.getElementById('matrix-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        canvas.width = matrixWrap.clientWidth || 600;
        canvas.height = 256;

        const chars = '0123456789ABCDEF日ﾊﾐﾋｰｳｼﾅﾓﾆｻﾜﾂｵﾘｱﾎﾃﾏｹﾒｴｶｷﾑﾕﾗｾﾈｽﾀﾇﾍ';
        const fontSize = 12;
        const columns = Math.floor(canvas.width / fontSize);
        const drops = Array(columns).fill(1);

        function drawMatrix() {
            ctx.fillStyle = 'rgba(0, 0, 0, 0.08)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = '#10b981';
            ctx.font = `${fontSize}px monospace`;

            for (let i = 0; i < drops.length; i++) {
                const text = chars[Math.floor(Math.random() * chars.length)];
                ctx.fillText(text, i * fontSize, drops[i] * fontSize);
                if (drops[i] * fontSize > canvas.height && Math.random() > 0.975) {
                    drops[i] = 0;
                }
                drops[i]++;
            }
        }

        matrixInterval = setInterval(drawMatrix, 33);

        // Auto exit after 8 seconds
        setTimeout(() => {
            stopMatrix();
        }, 8000);
    }

    function stopMatrix() {
        if (!matrixRunning) return;
        matrixRunning = false;
        if (matrixInterval) clearInterval(matrixInterval);
        const el = document.getElementById('terminal-matrix-container');
        if (el) el.remove();
        printOutput(`<span class="text-emerald-400 font-mono text-xs">[✓] Matrix stream disconnected. Welcome back to bash shell.</span>`);
        scrollToBottom();
    }

    /* -------------------------------------------------------------------------
       Input Keyboard Handlers: Tab Completion, Up/Down History, Ctrl+C, Ctrl+L
       ------------------------------------------------------------------------- */
    terminalInput.addEventListener('keydown', (e) => {
        // Tab Auto-Completion
        if (e.key === 'Tab') {
            e.preventDefault();
            handleTabCompletion();
            return;
        }

        // Ctrl + C: Cancel line
        if (e.ctrlKey && e.key.toLowerCase() === 'c') {
            e.preventDefault();
            if (matrixRunning) {
                stopMatrix();
                return;
            }
            const cancelLine = document.createElement('div');
            cancelLine.className = 'my-1 font-mono text-xs text-slate-400';
            cancelLine.innerHTML = `<span class="${currentUser === 'root' ? 'text-red-400' : 'text-emerald-400'} font-bold">${escapeHTML(getPromptString())}</span> ${escapeHTML(terminalInput.value)}^C`;
            terminalOutput.appendChild(cancelLine);
            terminalInput.value = '';
            scrollToBottom();
            return;
        }

        // Ctrl + L: Clear terminal
        if (e.ctrlKey && e.key.toLowerCase() === 'l') {
            e.preventDefault();
            terminalOutput.innerHTML = '';
            return;
        }

        // Enter: Execute Command
        if (e.key === 'Enter') {
            const val = terminalInput.value;
            if (val.trim() && !awaitingPassword) {
                commandHistory.push(val);
                historyIndex = commandHistory.length;
            }
            executeShellLine(val);
            terminalInput.value = '';
            return;
        }

        // Arrow Up: Command History Previous
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (commandHistory.length > 0) {
                if (historyIndex > 0) {
                    historyIndex--;
                } else {
                    historyIndex = 0;
                }
                terminalInput.value = commandHistory[historyIndex] || '';
            }
            return;
        }

        // Arrow Down: Command History Next
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (historyIndex < commandHistory.length - 1) {
                historyIndex++;
                terminalInput.value = commandHistory[historyIndex] || '';
            } else {
                historyIndex = commandHistory.length;
                terminalInput.value = '';
            }
            return;
        }
    });

    // Tab Auto-Completion Engine
    function handleTabCompletion() {
        const currentVal = terminalInput.value;
        const tokens = currentVal.split(' ');
        const lastToken = tokens[tokens.length - 1] || '';

        const allCommands = [
            'help', 'whoami', 'id', 'uname', 'hostname', 'uptime', 'date', 'free', 'df',
            'ls', 'cd', 'pwd', 'cat', 'head', 'tail', 'echo', 'mkdir', 'touch', 'rm',
            'cp', 'mv', 'grep', 'find', 'wc', 'tree', 'chmod', 'chown', 'ps', 'top',
            'ifconfig', 'ip', 'netstat', 'ss', 'history', 'env', 'which', 'curl', 'ping',
            'neofetch', 'nmap', 'vapt', 'wazuh', 'splunk', 'burpsuite', 'sqlmap',
            'hydra', 'nikto', 'gobuster', 'wireshark', 'aircrack-ng', 'hashcat',
            'sudo', 'su', 'exit', 'reboot', 'matrix', 'cowsay', 'sl', 'fortune',
            'skills', 'projects', 'certs', 'awards', 'contact', 'about', 'bio', 'clear'
        ];

        // If completing first command word
        if (tokens.length <= 1) {
            const matches = allCommands.filter(c => c.startsWith(lastToken.toLowerCase()));
            if (matches.length === 1) {
                terminalInput.value = matches[0] + ' ';
            } else if (matches.length > 1) {
                printOutput(`<div class="text-cyan-400 font-mono text-xs flex flex-wrap gap-3 my-1">${matches.map(m => escapeHTML(m)).join('  ')}</div>`);
                scrollToBottom();
            }
            return;
        }

        // If completing file or directory path
        let targetDir = cwd;
        let prefix = lastToken;

        if (lastToken.includes('/')) {
            const lastSlash = lastToken.lastIndexOf('/');
            const dirPart = lastToken.slice(0, lastSlash) || '/';
            prefix = lastToken.slice(lastSlash + 1);
            targetDir = resolvePath(dirPart, cwd);
        }

        const node = getNode(targetDir);
        if (node && node.children) {
            const candidates = Object.keys(node.children).filter(k => k.startsWith(prefix));
            if (candidates.length === 1) {
                const matchName = candidates[0];
                const isDir = node.children[matchName].type === 'dir';
                const completed = (lastToken.includes('/') ? lastToken.slice(0, lastToken.lastIndexOf('/') + 1) : '') + matchName + (isDir ? '/' : ' ');
                tokens[tokens.length - 1] = completed;
                terminalInput.value = tokens.join(' ');
            } else if (candidates.length > 1) {
                const formatted = candidates.map(c => {
                    const isDir = node.children[c].type === 'dir';
                    return isDir ? `<span class="text-cyan-400 font-bold">${escapeHTML(c)}/</span>` : `<span class="text-slate-300">${escapeHTML(c)}</span>`;
                }).join('  ');
                printOutput(`<div class="font-mono text-xs flex flex-wrap gap-3 my-1">${formatted}</div>`);
                scrollToBottom();
            }
        }
    }

    // Support quick chips
    terminalChips.forEach(chip => {
        chip.addEventListener('click', () => {
            const cmd = chip.getAttribute('data-cmd');
            if (cmd) {
                terminalInput.value = cmd;
                executeShellLine(cmd);
                terminalInput.value = '';
                terminalInput.focus();
            }
        });
    });

    function escapeRegex(str) {
        return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
}

/* ==========================================================================
   4. Project Filter Tabs
   ========================================================================== */
function initProjectFilter() {
    const filterButtons = document.querySelectorAll('.project-filter-btn');
    const projectCards = document.querySelectorAll('.project-card');

    if (!filterButtons.length || !projectCards.length) return;

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const filterValue = btn.getAttribute('data-filter');

            // Toggle active classes on buttons
            filterButtons.forEach(b => {
                b.classList.remove('bg-emerald-500/20', 'text-emerald-300', 'border-emerald-500/40');
                b.classList.add('bg-slate-800/60', 'text-slate-400', 'border-slate-700/50');
            });
            btn.classList.add('bg-emerald-500/20', 'text-emerald-300', 'border-emerald-500/40');
            btn.classList.remove('bg-slate-800/60', 'text-slate-400', 'border-slate-700/50');

            // Filter cards with smooth fade
            projectCards.forEach(card => {
                const category = card.getAttribute('data-category');
                if (filterValue === 'all' || category === filterValue) {
                    card.classList.remove('is-hidden');
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'scale(1)';
                    }, 50);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        card.classList.add('is-hidden');
                    }, 200);
                }
            });
        });
    });
}

/* ==========================================================================
   5. Secure Contact Form AJAX Handler
   ========================================================================== */
function initContactForm() {
    const form = document.getElementById('contact-form');
    const statusBox = document.getElementById('form-status-box');
    const submitBtn = document.getElementById('contact-submit-btn');

    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Clear previous field errors
        document.querySelectorAll('.field-error-msg').forEach(el => el.textContent = '');
        document.querySelectorAll('.input-field-border').forEach(el => el.classList.remove('border-red-500'));

        if (statusBox) {
            statusBox.classList.remove('hidden', 'bg-red-500/10', 'text-red-300', 'border-red-500/40', 'bg-emerald-500/10', 'text-emerald-300', 'border-emerald-500/40');
            statusBox.innerHTML = `
                <div class="flex items-center gap-3 text-cyan-300 font-mono-code text-sm">
                    <i class="fa-solid fa-circle-notch fa-spin"></i>
                    <span>Encrypting payload & transmitting over TLS...</span>
                </div>
            `;
            statusBox.classList.add('bg-slate-800/80', 'border', 'border-cyan-500/30');
        }

        window.dispatchEvent(new CustomEvent('contactFormSubmitting'));

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Transmitting...`;
        }

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `<i class="fa-solid fa-paper-plane mr-2"></i> Transmit Encrypted Message`;
            }

            if (result.success) {
                window.dispatchEvent(new CustomEvent('contactFormSuccess'));
                if (statusBox) {
                    statusBox.className = 'p-4 rounded-lg bg-emerald-500/15 border border-emerald-500/40 text-emerald-300 text-sm font-mono-code flex items-start gap-3';
                    statusBox.innerHTML = `
                        <i class="fa-solid fa-shield-check text-lg mt-0.5 text-emerald-400"></i>
                        <div>
                            <p class="font-bold">Transmission Logged Successfully!</p>
                            <p class="text-xs text-emerald-200/80 mt-1">${result.message}</p>
                        </div>
                    `;
                }
                form.reset();
                showToast('Message sent securely! Bajrang will reply shortly.', 'success');
            } else {
                window.dispatchEvent(new CustomEvent('contactFormError'));
                if (statusBox) {
                    statusBox.className = 'p-4 rounded-lg bg-red-500/15 border border-red-500/40 text-red-300 text-sm font-mono-code flex items-start gap-3';
                    statusBox.innerHTML = `
                        <i class="fa-solid fa-triangle-exclamation text-lg mt-0.5 text-red-400"></i>
                        <div>
                            <p class="font-bold">Transmission Halted</p>
                            <p class="text-xs text-red-200/80 mt-1">${result.message || 'Please correct errors and retry.'}</p>
                        </div>
                    `;
                }

                if (result.errors) {
                    for (const [field, errorMsg] of Object.entries(result.errors)) {
                        const errorContainer = document.getElementById(`error-${field}`);
                        const inputElement = document.getElementById(`input-${field}`);
                        if (errorContainer) {
                            errorContainer.textContent = errorMsg;
                        }
                        if (inputElement) {
                            inputElement.classList.add('border-red-500');
                        }
                    }
                }
                showToast(result.message || 'Validation error occurred.', 'error');
            }
        } catch (err) {
            console.error('Contact submit error:', err);
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `<i class="fa-solid fa-paper-plane mr-2"></i> Transmit Encrypted Message`;
            }
            if (statusBox) {
                statusBox.className = 'p-4 rounded-lg bg-red-500/15 border border-red-500/40 text-red-300 text-sm font-mono-code flex items-start gap-3';
                statusBox.innerHTML = `
                    <i class="fa-solid fa-triangle-exclamation text-lg mt-0.5 text-red-400"></i>
                    <div>
                        <p class="font-bold">Network Connection Error</p>
                        <p class="text-xs text-red-200/80 mt-1">Unable to complete request. Please verify connection or reach out via direct email.</p>
                    </div>
                `;
            }
        }
    });
}

/* ==========================================================================
   6. Interactive Security Tool: Entropy & Security Headers Inspector
   ========================================================================== */
function initEntropyAnalyzer() {
    const input = document.getElementById('entropy-input');
    const bitsDisplay = document.getElementById('entropy-bits');
    const crackTimeDisplay = document.getElementById('entropy-crack-time');
    const bar = document.getElementById('entropy-bar');
    const strengthLabel = document.getElementById('entropy-strength-label');

    if (!input || !bitsDisplay || !bar) return;

    input.addEventListener('input', () => {
        const password = input.value;
        if (!password) {
            bitsDisplay.textContent = '0 bits';
            crackTimeDisplay.textContent = 'Instant';
            bar.style.width = '0%';
            bar.className = 'h-full bg-slate-600 transition-all duration-300';
            strengthLabel.textContent = 'Waiting for input...';
            strengthLabel.className = 'text-xs font-mono-code text-slate-500';
            return;
        }

        // Calculate Pool Size R
        let poolSize = 0;
        if (/[a-z]/.test(password)) poolSize += 26;
        if (/[A-Z]/.test(password)) poolSize += 26;
        if (/[0-9]/.test(password)) poolSize += 10;
        if (/[^a-zA-Z0-9]/.test(password)) poolSize += 33;

        // Entropy E = L * log2(R)
        const entropy = poolSize > 0 ? Math.round(password.length * (Math.log(poolSize) / Math.log(2))) : 0;
        bitsDisplay.textContent = `${entropy} bits`;

        // Calculate estimated crack time against 100 Billion (10^11) guesses/sec offline hash cluster
        const combinations = Math.pow(poolSize, password.length);
        const guessesPerSec = 1e11;
        const secondsToCrack = combinations / (2 * guessesPerSec);

        let crackTimeText = 'Instant';
        if (secondsToCrack < 1) crackTimeText = '< 1 second';
        else if (secondsToCrack < 60) crackTimeText = `${Math.round(secondsToCrack)} seconds`;
        else if (secondsToCrack < 3600) crackTimeText = `${Math.round(secondsToCrack / 60)} minutes`;
        else if (secondsToCrack < 86400) crackTimeText = `${Math.round(secondsToCrack / 3600)} hours`;
        else if (secondsToCrack < 31536000) crackTimeText = `${Math.round(secondsToCrack / 86400)} days`;
        else if (secondsToCrack < 31536000 * 100) crackTimeText = `${Math.round(secondsToCrack / 31536000)} years`;
        else if (secondsToCrack < 31536000 * 1e6) crackTimeText = `${Math.round(secondsToCrack / (31536000 * 1e3))}k years`;
        else crackTimeText = 'Centuries+ (Cryptographically Resilient)';

        crackTimeDisplay.textContent = crackTimeText;

        // Visual progress bar
        let percent = Math.min(100, Math.round((entropy / 100) * 100));
        bar.style.width = `${percent}%`;

        if (entropy < 35) {
            bar.className = 'h-full bg-red-500 transition-all duration-300';
            strengthLabel.textContent = 'CRITICAL: Trivial to crack';
            strengthLabel.className = 'text-xs font-mono-code text-red-400 font-bold';
        } else if (entropy < 55) {
            bar.className = 'h-full bg-amber-500 transition-all duration-300';
            strengthLabel.textContent = 'MODERATE: Vulnerable to targeted brute-force';
            strengthLabel.className = 'text-xs font-mono-code text-amber-400 font-bold';
        } else if (entropy < 80) {
            bar.className = 'h-full bg-cyan-500 transition-all duration-300';
            strengthLabel.textContent = 'STRONG: Resilient against standard dictionary attacks';
            strengthLabel.className = 'text-xs font-mono-code text-cyan-400 font-bold';
        } else {
            bar.className = 'h-full bg-emerald-500 transition-all duration-300';
            strengthLabel.textContent = 'FORTIFIED: Military-grade entropy';
            strengthLabel.className = 'text-xs font-mono-code text-emerald-400 font-bold';
        }
    });
}

/* ==========================================================================
   7. Copy to Clipboard Utility
   ========================================================================== */
function initCopyButtons() {
    const copyButtons = document.querySelectorAll('.copy-trigger');

    copyButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const textToCopy = btn.getAttribute('data-copy');
            if (!textToCopy) return;

            navigator.clipboard.writeText(textToCopy).then(() => {
                showToast(`Copied to clipboard: ${textToCopy}`, 'success');
            }).catch(() => {
                showToast('Failed to copy to clipboard.', 'error');
            });
        });
    });
}

function showToast(message, type = 'success') {
    let toast = document.getElementById('toast-notification');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast-notification';
        toast.className = 'fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-lg border text-sm font-mono-code shadow-2xl glass-panel';
        document.body.appendChild(toast);
    }

    if (type === 'success') {
        toast.className = 'fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-lg border border-emerald-500/50 bg-slate-900/95 text-emerald-300 text-sm font-mono-code shadow-2xl show';
        toast.innerHTML = `<i class="fa-solid fa-circle-check text-emerald-400 text-base"></i> <span>${escapeHTML(message)}</span>`;
    } else {
        toast.className = 'fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-lg border border-red-500/50 bg-slate-900/95 text-red-300 text-sm font-mono-code shadow-2xl show';
        toast.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-red-400 text-base"></i> <span>${escapeHTML(message)}</span>`;
    }

    setTimeout(() => {
        toast.classList.remove('show');
    }, 4000);
}

/* ==========================================================================
   8. Smooth Scrolling
   ========================================================================== */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

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
