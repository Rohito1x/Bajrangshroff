# Bajrang Kumar Sarraf — Cybersecurity Specialist & Full-Stack Web Developer Portfolio + Admin CMS

A modern, responsive, high-performance, and security-hardened personal portfolio and custom Content Management System (CMS) built with **Object-Oriented PHP 8+**, **SQLite3/MySQL via PDO**, **Tailwind CSS**, **GSAP + ScrollTrigger**, and **Vanilla JavaScript (ES6+)**.

---

## 🛡️ Target Profile & Background
- **Name:** Bajrang Kumar Sarraf
- **Role / Tagline:** Cybersecurity Trainer | Junior Penetration Tester | VAPT Analyst | Web Developer
- **Location:** Birgunj, Nepal 44300
- **Phone:** +977 9816266554
- **Email:** bajrangshroff10@gmail.com
- **Core Credentials:**
  - **RHCSA** (Red Hat Certified System Administrator) – IT Security College of Computer Science
  - **B.Sc. in Information Technology** (Specialization: Cyber Security & Forensics) – Parul University
  - **Ethical Hacking Training** – Broadway Infosys
  - **NPTEL Certified:** Programming in Java & Computer Networks (IIT Kharagpur)
  - **Hackathons:** Runner-Up at Ganpat University Hackathon; Participant at IIT Jammu Hackathon

---

## 🔐 Admin Portal & CMS
The portfolio includes a password-protected, modern dark-themed custom Admin Portal in `/admin/` enabling the site owner to manage content and visual effects dynamically without touching code:

- **Metrics Dashboard:** Real-time metrics overview (projects, skills, milestones, certifications, contact inquiries).
- **Profile & Media Management:** Update legal name, bio narrative, dynamic typing roles, contact records, upload profile avatars, and update resume (PDF).
- **Projects Portfolio CRUD:** Add, edit, sort, and delete project case studies with image screenshots, categorization, and live URLs.
- **Skills Matrix Management:** Adjust technical proficiency ratings ($1-100\%$), categories, and display orders.
- **Experience Timeline Management:** Edit companies, roles, dates, and JSON-backed bullet points.
- **Certifications Management:** Catalog credentials with issuing institutions and badge images.
- **Animation Controller:** Real-time controls to toggle HTML5 particle canvas on/off, adjust particle node count ($15-150$), change laser hex colors, adjust typing speed ($20-300\text{ms}$), toggle 3D card tilt, and customize terminal easter-egg commands!
- **Inquiries Inbox:** Review contact submissions, mark as read/unread, inspect sender IPs and timestamps, and reply via mail/WhatsApp.

### 🔑 Default Administrator Credentials
- **Login URL:** `http://localhost/Bajrangss/admin/login.php`
- **Username:** `bajrang_admin`
- **Password:** `Cyber@Bajrang2026!`
- **Security Protections:** Rate-limiting, 15-minute brute-force account locking after 5 failed attempts, session hijacking mitigation via IP/User-Agent fingerprinting, CSRF validation tokens, and timing-safe password verification (`PASSWORD_BCRYPT` cost 12).

---

## 🚀 Key Features & Architectural Highlights

1. **Cyberpunk Minimalist & Modern UI/UX:**
   - Dark obsidian background (`#080c14`) with subtle cyber-mesh grid and scanline overlays.
   - Neon emerald (`#10b981`), cyber cyan (`#06b6d4`), and alert crimson (`#f43f5e`) accents.
   - Glassmorphism panels with backdrop blur and animated beacon indicators.
   - Fully responsive across desktop, tablet, and mobile screens.

2. **Interactive Hacker Terminal / CLI Widget:**
   - Real interactive shell with prompt `bajrang@security-node:~$`.
   - Supports commands: `help`, `whoami`, `bio`, `skills`, `vapt`, `projects`, `exp`, `certs`, `awards`, `contact`, `ping`, `clear`, `sudo`, `easteregg` + any custom commands added via the CMS!
   - Quick-run chips for touch/mobile devices and command history navigation (Up/Down arrow keys).

3. **Dynamic Visual Effects (GSAP + HTML5 Canvas):**
   - **Particle Network Canvas:** Interactive node network with cursor proximity repulsion and automated off-screen pause via `IntersectionObserver`.
   - **3D Card Perspective Tilt:** Smooth mouse-tracking card tilts with radial holographic shines.
   - **Scanning Laser Reveal:** Horizontal neon laser bar animation expanding underneath section headings.
   - **Tactical Stat Counters:** Achievement numbers smoothly counting up from zero on scroll.
   - **Scroll-Synchronized Timeline Path:** SVG line drawing down through career milestones.

4. **Defensive & Offensive Security Matrix:**
   - **VAPT & Web Security:** OWASP Top 10, Burp Suite Pro, Nmap & NSE scripting, PoC validation.
   - **Systems & Infrastructure:** Linux (RHCSA), Windows Server Active Directory, TCP/IP, Switching/Routing.
   - **Software Engineering:** PHP 8+ OOP, Secure PDO, Java, C++ (Arduino/ESP8266), Tailwind CSS, MySQL.

5. **Military-Grade Contact & Upload Security:**
   - **Session-Based CSRF Protection:** Cryptographically secure random tokens with constant-time verification.
   - **Anti-Spam Honeypot:** Invisible trap input neutralizing automated spam bots.
   - **File-based & DB Rate Limiting:** Throttles submissions per client IP.
   - **Upload Hardening:** Restricts extensions, inspects MIME-types via `finfo`, checks image geometry, renames to random hashes, and prevents script execution via `uploads/.htaccess`.

---

## 📂 Project Structure

```text
/Bajrangss
├── index.php                 # Main entry point (dynamically hydrated from DB)
├── config.php                # Site configuration, credentials, CSRF & security headers
├── .htaccess                 # Apache server security directives
├── README.md                 # Complete documentation
├── config/
│   ├── database.php          # PDO SQLite/MySQL singleton connection
│   ├── security.php          # Auth guards, CSRF engine, upload sanitizer
│   └── setup.php             # Self-executing DB migration & seed populator
├── uploads/                  # Uploaded media with execution prevention (.htaccess)
│   ├── profile/
│   ├── projects/
│   └── documents/
├── admin/
│   ├── index.php             # Metrics dashboard
│   ├── login.php             # Hardened authentication gateway
│   ├── logout.php            # Session terminator
│   ├── profile.php           # Profile, bio & resume manager
│   ├── projects.php          # Project portfolio CRUD
│   ├── skills.php            # Skills matrix manager
│   ├── experiences.php       # Timeline history manager
│   ├── certifications.php    # Certifications manager
│   ├── animations.php        # Animation controller & visual engine
│   ├── messages.php          # Contact inbox
│   └── includes/
│       ├── auth_guard.php    # Middleware enforcing authenticated session
│       ├── nav.php           # Sleek tactical admin sidebar
│       └── alert_helper.php  # Flash notification renderer
├── handlers/
│   └── contact_process.php   # Contact processor (logs to DB and file)
├── includes/
│   ├── header.php            # SEO meta, schemas, security headers, navbar
│   ├── footer.php            # Scripts, copyright, lightbox modal
│   └── data.php              # Fallback array-driven store
├── assets/
│   ├── css/
│   │   ├── style.css         # Dark cyberpunk styling
│   │   ├── animations.css    # Glitch, scanning laser, 3D tilt styles
│   │   └── admin.css         # Tactical CMS dashboard theme
│   ├── js/
│   │   ├── main.js           # Core client scripts & terminal
│   │   ├── animations.js     # GSAP ScrollTrigger & canvas particle engine
│   │   └── admin.js          # Drag-and-drop previews & sync sliders
│   └── img/
│       ├── favicon.svg       # Terminal SVG favicon
│       └── profile-cyber.svg # Cyber HUD profile emblem
└── storage/
    ├── .htaccess             # Strictly denies direct HTTP access
    ├── portfolio.sqlite      # SQLite database
    └── messages.json         # Encrypted contact log
```

---

## 💻 Running Locally

### Option 1: Via XAMPP / Apache
1. Place this folder in `c:\xampp\htdocs\Bajrangss`.
2. Start the Apache module in the **XAMPP Control Panel**.
3. Open your browser and navigate to:
   - **Public Portfolio:** `http://localhost/Bajrangss/`
   - **Admin CMS Portal:** `http://localhost/Bajrangss/admin/`

### Option 2: Via PHP Built-in Server
Open PowerShell in this directory and execute:
```powershell
php -S localhost:8000
```
Then visit:
- **Public Portfolio:** `http://localhost:8000`
- **Admin CMS Portal:** `http://localhost:8000/admin/`
