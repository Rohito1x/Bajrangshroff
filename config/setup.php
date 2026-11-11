<?php
/**
 * Database Migration & Default Seed Script
 * Profile: Bajrang Kumar Sarraf - Cybersecurity Specialist & Web Developer
 */

declare(strict_types=1);

require_once __DIR__ . '/database.php';

try {
    $pdo = Database::getConnection();

    // 1. Create Admins Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            failed_attempts INT DEFAULT 0,
            locked_until DATETIME NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");

    // 2. Create Profile Settings Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS profile_settings (
            id INTEGER PRIMARY KEY,
            full_name VARCHAR(100) NOT NULL,
            tagline_roles TEXT NOT NULL,
            bio TEXT NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone VARCHAR(50) NOT NULL,
            location VARCHAR(150) NOT NULL,
            avatar_path VARCHAR(255) DEFAULT 'assets/img/profile-cyber.svg',
            hero_bg_path VARCHAR(255) NULL,
            resume_pdf_path VARCHAR(255) NULL
        );
    ");

    // 3. Create Animation Settings Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS animation_settings (
            id INTEGER PRIMARY KEY,
            enable_particles TINYINT DEFAULT 1,
            particle_node_count INT DEFAULT 60,
            particle_color VARCHAR(20) DEFAULT '#10b981',
            enable_glitch_effect TINYINT DEFAULT 1,
            enable_card_tilt TINYINT DEFAULT 1,
            typing_speed_ms INT DEFAULT 80,
            terminal_custom_commands TEXT NULL
        );
    ");

    // 4. Create Projects Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS projects (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(150) NOT NULL,
            slug VARCHAR(150) NOT NULL UNIQUE,
            category VARCHAR(50) NOT NULL,
            description TEXT NOT NULL,
            live_url VARCHAR(255) NULL,
            github_url VARCHAR(255) NULL,
            image_path VARCHAR(255) DEFAULT 'assets/img/profile-cyber.svg',
            display_order INT DEFAULT 0,
            is_featured TINYINT DEFAULT 1
        );
    ");

    // 5. Create Skills Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS skills (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL,
            category VARCHAR(50) NOT NULL,
            proficiency_pct INT DEFAULT 85,
            display_order INT DEFAULT 0
        );
    ");

    // 6. Create Experiences Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS experiences (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            company VARCHAR(150) NOT NULL,
            role VARCHAR(150) NOT NULL,
            duration VARCHAR(100) NOT NULL,
            bullet_points TEXT NOT NULL,
            display_order INT DEFAULT 0
        );
    ");

    // 7. Create Certifications Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS certifications (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(150) NOT NULL,
            issuer VARCHAR(100) NOT NULL,
            issue_date VARCHAR(50) NOT NULL,
            credential_url VARCHAR(255) NULL,
            badge_image_path VARCHAR(255) NULL
        );
    ");

    // 8. Create Contact Messages Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS contact_messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            subject VARCHAR(150) NOT NULL,
            message TEXT NOT NULL,
            ip_address VARCHAR(45) NOT NULL,
            status VARCHAR(20) DEFAULT 'unread',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");

    // =========================================================================
    // SEED INITIAL DATA IF TABLES ARE EMPTY
    // =========================================================================

    // Seed Admin Account
    $checkAdmin = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
    if ((int)$checkAdmin === 0) {
        $defaultUser = 'bajrang_admin';
        $defaultEmail = 'bajrangshroff10@gmail.com';
        $defaultPass = 'Cyber@Bajrang2026!';
        $hash = password_hash($defaultPass, PASSWORD_BCRYPT, ['cost' => 12]);

        $stmt = $pdo->prepare("INSERT INTO admins (username, email, password_hash, created_at) VALUES (?, ?, ?, datetime('now'))");
        $stmt->execute([$defaultUser, $defaultEmail, $hash]);
    }

    // Seed Profile Settings
    $checkProfile = $pdo->query("SELECT COUNT(*) FROM profile_settings")->fetchColumn();
    if ((int)$checkProfile === 0) {
        $taglines = 'Cybersecurity Trainer, Junior Penetration Tester, VAPT Analyst, Full-Stack Web Developer, Security Researcher';
        $bio = 'Cybersecurity professional with hands-on experience in Vulnerability Assessment and Penetration Testing (VAPT), training, network administration, and web development. Experienced in analyzing web apps/networks, validating vulnerabilities with Proof-of-Concepts (PoCs), and drafting remediation reports. Strong background in Linux, Windows Server, Burp Suite, Nmap, Wazuh, and Splunk.';

        $stmt = $pdo->prepare("
            INSERT INTO profile_settings (id, full_name, tagline_roles, bio, email, phone, location, avatar_path, hero_bg_path, resume_pdf_path)
            VALUES (1, ?, ?, ?, ?, ?, ?, 'assets/img/profile-cyber.svg', NULL, NULL)
        ");
        $stmt->execute([
            'Bajrang Kumar Sarraf',
            $taglines,
            $bio,
            'bajrangshroff10@gmail.com',
            '+977 9816266554',
            'Birgunj, Nepal 44300'
        ]);
    }

    // Seed Animation Settings
    $checkAnim = $pdo->query("SELECT COUNT(*) FROM animation_settings")->fetchColumn();
    if ((int)$checkAnim === 0) {
        $terminalCmds = json_encode([
            'whoami' => "Bajrang Kumar Sarraf\nRoles: Cybersecurity Trainer | Junior Penetration Tester | VAPT Analyst | Web Developer\nLocation: Birgunj, Nepal 44300",
            'vapt' => "VAPT Scan Engine: Ready\nMethodology: Recon -> Scanning -> Manual PoC -> CVSS v3.1 Remediation\nFocus: OWASP Top 10, Network Perimeters, Active Directory",
            'skills' => "1. Security: VAPT, OWASP Top 10, Burp Suite, Nmap, Wazuh, Splunk\n2. Systems: Linux (RHCSA), Windows Server, Active Directory\n3. Dev: PHP 8+ OOP, Java, C++, JavaScript, Tailwind",
            'contact' => "Email: bajrangshroff10@gmail.com | Phone: +977 9816266554 | Location: Birgunj, Nepal"
        ], JSON_PRETTY_PRINT);

        $stmt = $pdo->prepare("
            INSERT INTO animation_settings (id, enable_particles, particle_node_count, particle_color, enable_glitch_effect, enable_card_tilt, typing_speed_ms, terminal_custom_commands)
            VALUES (1, 1, 60, '#10b981', 1, 1, 80, ?)
        ");
        $stmt->execute([$terminalCmds]);
    }

    // Seed Projects
    $checkProjects = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
    if ((int)$checkProjects === 0) {
        $projects = [
            [
                'title' => 'ESP8266 Wi-Fi Security Tool',
                'slug' => 'esp8266-wifi-security-tool',
                'category' => 'Hardware & Security',
                'description' => 'Custom hardware security auditing device built with C++ and Arduino microcontrollers to demonstrate IEEE 802.11 Wi-Fi weaknesses in controlled lab environments.',
                'live_url' => null,
                'github_url' => 'https://github.com/bajrang-sarraf',
                'image_path' => 'assets/img/profile-cyber.svg',
                'display_order' => 1,
                'is_featured' => 1
            ],
            [
                'title' => 'News4Nepal',
                'slug' => 'news4nepal',
                'category' => 'Web Development',
                'description' => 'High-performance, responsive news and media publishing web portal serving real-time journalism across Nepal with secure PHP architecture and parameterized queries.',
                'live_url' => 'https://news4nepal.com',
                'github_url' => null,
                'image_path' => 'assets/img/profile-cyber.svg',
                'display_order' => 2,
                'is_featured' => 1
            ],
            [
                'title' => 'VisionPoint Education Portal',
                'slug' => 'visionpoint-education-portal',
                'category' => 'Web Development',
                'description' => 'Full-featured educational consultancy web portal supporting overseas education programs, counselor appointment booking, and student application intake.',
                'live_url' => 'https://visionpoint.edu.np',
                'github_url' => null,
                'image_path' => 'assets/img/profile-cyber.svg',
                'display_order' => 3,
                'is_featured' => 1
            ],
            [
                'title' => 'HakkiVeda Nepal',
                'slug' => 'hakkiveda-nepal',
                'category' => 'E-Commerce',
                'description' => 'Herbal and wellness e-commerce platform featuring catalog exploration, cart management, and seamless order dispatch.',
                'live_url' => null,
                'github_url' => 'https://github.com/bajrang-sarraf',
                'image_path' => 'assets/img/profile-cyber.svg',
                'display_order' => 4,
                'is_featured' => 0
            ],
            [
                'title' => 'Nano Tech IT Solution',
                'slug' => 'nano-tech-it-solution',
                'category' => 'E-Commerce',
                'description' => 'E-commerce platform for computer hardware, peripherals, and enterprise IT equipment with automated quotation generation.',
                'live_url' => null,
                'github_url' => 'https://github.com/bajrang-sarraf',
                'image_path' => 'assets/img/profile-cyber.svg',
                'display_order' => 5,
                'is_featured' => 0
            ],
            [
                'title' => 'Namaste Education Foundation',
                'slug' => 'namaste-education-foundation',
                'category' => 'Corporate Consultancy',
                'description' => 'Corporate consultancy portal delivering global university matching and student inquiry processing.',
                'live_url' => null,
                'github_url' => 'https://github.com/bajrang-sarraf',
                'image_path' => 'assets/img/profile-cyber.svg',
                'display_order' => 6,
                'is_featured' => 0
            ],
            [
                'title' => 'Naya Kadam Nepal',
                'slug' => 'naya-kadam-nepal',
                'category' => 'Non-Profit / NGO',
                'description' => 'Community outreach and social welfare web portal dedicated to humanitarian campaigns and volunteer mobilization.',
                'live_url' => null,
                'github_url' => 'https://github.com/bajrang-sarraf',
                'image_path' => 'assets/img/profile-cyber.svg',
                'display_order' => 7,
                'is_featured' => 0
            ]
        ];

        $stmt = $pdo->prepare("
            INSERT INTO projects (title, slug, category, description, live_url, github_url, image_path, display_order, is_featured)
            VALUES (:title, :slug, :category, :description, :live_url, :github_url, :image_path, :display_order, :is_featured)
        ");
        foreach ($projects as $p) {
            $stmt->execute($p);
        }
    }

    // Seed Skills
    $checkSkills = $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();
    if ((int)$checkSkills === 0) {
        $skills = [
            ['name' => 'VAPT (Web & Network)', 'category' => 'Offensive Security', 'proficiency_pct' => 90, 'display_order' => 1],
            ['name' => 'OWASP Top 10 Auditing', 'category' => 'Offensive Security', 'proficiency_pct' => 92, 'display_order' => 2],
            ['name' => 'Burp Suite Professional', 'category' => 'Offensive Security', 'proficiency_pct' => 88, 'display_order' => 3],
            ['name' => 'Nmap & NSE Scripting', 'category' => 'Offensive Security', 'proficiency_pct' => 90, 'display_order' => 4],
            ['name' => 'Wazuh SIEM & EDR', 'category' => 'Defensive Security', 'proficiency_pct' => 82, 'display_order' => 5],
            ['name' => 'Splunk Enterprise Security', 'category' => 'Defensive Security', 'proficiency_pct' => 80, 'display_order' => 6],
            ['name' => 'Linux Administration (RHCSA)', 'category' => 'Networking & Systems', 'proficiency_pct' => 90, 'display_order' => 7],
            ['name' => 'Windows Server & Active Directory', 'category' => 'Networking & Systems', 'proficiency_pct' => 84, 'display_order' => 8],
            ['name' => 'TCP/IP, Routing & Switching', 'category' => 'Networking & Systems', 'proficiency_pct' => 88, 'display_order' => 9],
            ['name' => 'PHP 8+ OOP & Secure PDO', 'category' => 'Web Engineering', 'proficiency_pct' => 92, 'display_order' => 10],
            ['name' => 'Java (NPTEL Certified)', 'category' => 'Web Engineering', 'proficiency_pct' => 80, 'display_order' => 11],
            ['name' => 'C++ / Arduino Embedded Tools', 'category' => 'Web Engineering', 'proficiency_pct' => 78, 'display_order' => 12],
            ['name' => 'JavaScript & Tailwind CSS', 'category' => 'Web Engineering', 'proficiency_pct' => 90, 'display_order' => 13]
        ];

        $stmt = $pdo->prepare("INSERT INTO skills (name, category, proficiency_pct, display_order) VALUES (:name, :category, :proficiency_pct, :display_order)");
        foreach ($skills as $s) {
            $stmt->execute($s);
        }
    }

    // Seed Experiences
    $checkExp = $pdo->query("SELECT COUNT(*) FROM experiences")->fetchColumn();
    if ((int)$checkExp === 0) {
        $experiences = [
            [
                'company' => 'Centegic Technology Pvt. Ltd.',
                'role' => 'Cybersecurity Trainer / Junior VAPT Analyst',
                'duration' => 'June 2026 – Sept 2026',
                'bullet_points' => json_encode([
                    'Led cybersecurity workshops, hands-on lab exercises, and threat modeling demos for technical cohorts.',
                    'Conducted web application and network VAPT, identified critical OWASP flaws, and author actionable remediation guides.',
                    'Prepared structured risk rating reports mapped to CVSS v3.1 standards for engineering teams.'
                ]),
                'display_order' => 1
            ],
            [
                'company' => 'Parul Sevashram Hospital',
                'role' => 'IT Support Engineer',
                'duration' => 'June 2024 – June 2025',
                'bullet_points' => json_encode([
                    'Managed enterprise hospital network infrastructure, switch configurations, VLAN segmentation, and server administration.',
                    'Administered Windows Server environments, Active Directory domain services, user accounts, and security policies.',
                    'Provided rapid troubleshooting and maintenance across critical medical telemetry systems with 99.9% uptime.'
                ]),
                'display_order' => 2
            ]
        ];

        $stmt = $pdo->prepare("INSERT INTO experiences (company, role, duration, bullet_points, display_order) VALUES (:company, :role, :duration, :bullet_points, :display_order)");
        foreach ($experiences as $exp) {
            $stmt->execute($exp);
        }
    }

    // Seed Certifications
    $checkCerts = $pdo->query("SELECT COUNT(*) FROM certifications")->fetchColumn();
    if ((int)$checkCerts === 0) {
        $certs = [
            [
                'title' => 'Red Hat Certified System Administrator (RHCSA)',
                'issuer' => 'IT Security College of Computer Science / Red Hat',
                'issue_date' => 'Certified',
                'credential_url' => 'https://www.redhat.com',
                'badge_image_path' => 'assets/img/profile-cyber.svg'
            ],
            [
                'title' => 'Ethical Hacking Training',
                'issuer' => 'Broadway Infosys',
                'issue_date' => 'Certified',
                'credential_url' => null,
                'badge_image_path' => 'assets/img/profile-cyber.svg'
            ],
            [
                'title' => 'Programming in Java (NPTEL Certified)',
                'issuer' => 'IIT Kharagpur / NPTEL',
                'issue_date' => 'Elite Certified',
                'credential_url' => null,
                'badge_image_path' => 'assets/img/profile-cyber.svg'
            ],
            [
                'title' => 'Computer Networks and Communication (NPTEL Certified)',
                'issuer' => 'IIT Kharagpur / NPTEL',
                'issue_date' => 'Elite Certified',
                'credential_url' => null,
                'badge_image_path' => 'assets/img/profile-cyber.svg'
            ]
        ];

        $stmt = $pdo->prepare("INSERT INTO certifications (title, issuer, issue_date, credential_url, badge_image_path) VALUES (:title, :issuer, :issue_date, :credential_url, :badge_image_path)");
        foreach ($certs as $c) {
            $stmt->execute($c);
        }
    }

    $isCli = (php_sapi_name() === 'cli');

    if ($isCli) {
        echo "[SUCCESS] Portfolio Database & CMS schema setup completed successfully.\n";
        echo "[INFO] Default Admin Credentials:\n";
        echo "       Username: bajrang_admin\n";
        echo "       Password: Cyber@Bajrang2026!\n";
        echo "       Database: " . Database::SQLITE_FILE . "\n";
    } else {
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html><head><title>CMS Setup Complete</title><style>body{background:#080c14;color:#f8fafc;font-family:monospace;padding:40px;line-height:1.6}.box{max-width:600px;margin:auto;background:#0d1322;padding:30px;border-radius:10px;border:1px solid #10b981;box-shadow:0 0 20px rgba(16,185,129,0.2)}a{color:#10b981;text-decoration:none;font-weight:bold}</style></head><body>';
        echo '<div class="box">';
        echo '<h2 style="color:#00ff9d;">[+] CMS Setup Initialized Successfully</h2>';
        echo '<p>All 8 database tables have been verified and seeded with portfolio data.</p>';
        echo '<hr style="border-color:#1e293b;margin:20px 0;">';
        echo '<p><strong>Default Administrator Account:</strong></p>';
        echo '<p>Username: <code style="color:#22d3ee;">bajrang_admin</code><br>Password: <code style="color:#fbbf24;">Cyber@Bajrang2026!</code></p>';
        echo '<div style="margin-top:30px;"><a href="../admin/login.php">&gt; Proceed to Admin Login &rarr;</a></div>';
        echo '</div></body></html>';
    }

} catch (Throwable $e) {
    if (php_sapi_name() === 'cli') {
        fwrite(STDERR, "[ERROR] Migration failed: " . $e->getMessage() . "\n");
        exit(1);
    } else {
        die("<h1>Migration Error</h1><p>" . htmlspecialchars($e->getMessage()) . "</p>");
    }
}
