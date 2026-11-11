<?php
/**
 * Profile & Portfolio Data Store
 * Bajrang Kumar Sarraf - Cybersecurity Specialist & Full-Stack Web Developer
 */

declare(strict_types=1);

return [
    'profile' => [
        'name' => 'Bajrang Kumar Sarraf',
        'title' => 'Cybersecurity Trainer & VAPT Analyst',
        'taglines' => [
            'Cybersecurity Trainer',
            'Junior Penetration Tester',
            'VAPT Analyst',
            'Full-Stack Web Developer',
            'Security Researcher'
        ],
        'location' => 'Birgunj, Nepal 44300',
        'phone' => '+977 9816266554',
        'email' => 'bajrangshroff10@gmail.com',
        'status' => 'Available for Security Audits & Engineering Roles',
        'status_type' => 'available', // available | busy
        'summary' => 'Cybersecurity professional with hands-on experience in Vulnerability Assessment and Penetration Testing (VAPT), training, network administration, and web development. Experienced in analyzing web apps/networks, validating vulnerabilities with Proof-of-Concepts (PoCs), and drafting remediation reports. Strong background in Linux, Windows Server, Burp Suite, Nmap, Wazuh, and Splunk.',
        'philosophy' => 'Security is not an afterthought or a static checklist; it is an ongoing mindset. By combining offensive adversarial thinking with defensive engineering and clean code, I build systems resilient against modern threats while breaking down real-world vulnerabilities before threat actors can exploit them.',
        'social' => [
            [
                'name' => 'LinkedIn',
                'url' => 'https://linkedin.com/in/bajrang-kumar-sarraf',
                'icon' => 'fa-brands fa-linkedin-in',
                'color' => '#0A66C2'
            ],
            [
                'name' => 'GitHub',
                'url' => 'https://github.com/bajrang-sarraf',
                'icon' => 'fa-brands fa-github',
                'color' => '#f0f6fc'
            ],
            [
                'name' => 'Email',
                'url' => 'mailto:bajrangshroff10@gmail.com',
                'icon' => 'fa-solid fa-envelope',
                'color' => '#10b981'
            ],
            [
                'name' => 'WhatsApp',
                'url' => 'https://wa.me/9779816266554',
                'icon' => 'fa-brands fa-whatsapp',
                'color' => '#25D366'
            ]
        ],
        'quick_stats' => [
            [
                'metric' => 'VAPT & Audits',
                'value' => '15+',
                'detail' => 'Web & network assessments conducted',
                'icon' => 'fa-solid fa-shield-virus'
            ],
            [
                'metric' => 'Web Solutions',
                'value' => '7+',
                'detail' => 'Production websites & platforms built',
                'icon' => 'fa-solid fa-code'
            ],
            [
                'metric' => 'Certifications & Awards',
                'value' => '5+',
                'detail' => 'RHCSA, NPTEL, Hackathon runner-up',
                'icon' => 'fa-solid fa-award'
            ],
            [
                'metric' => 'PoC Validation',
                'value' => '100%',
                'detail' => 'Reproducible evidence & remediation',
                'icon' => 'fa-solid fa-bug-slash'
            ]
        ]
    ],

    'skills_categories' => [
        [
            'id' => 'security',
            'name' => 'Offensive & Defensive Security',
            'subtitle' => 'Penetration Testing, Vulnerability Research, SIEM Monitoring & Threat Modeling',
            'icon' => 'fa-solid fa-shield-halved',
            'accent' => 'emerald',
            'skills' => [
                [
                    'name' => 'VAPT (Web & Network)',
                    'level' => 90,
                    'badge' => 'Core Expert',
                    'icon' => 'fa-solid fa-crosshairs',
                    'desc' => 'Comprehensive vulnerability assessments, network scanning, and black-box/gray-box penetration testing.'
                ],
                [
                    'name' => 'Web App Security (OWASP Top 10)',
                    'level' => 92,
                    'badge' => 'Advanced',
                    'icon' => 'fa-solid fa-globe',
                    'desc' => 'In-depth identification & remediation of SQLi, XSS, CSRF, IDOR/BOLA, SSRF, and authentication flaws.'
                ],
                [
                    'name' => 'Burp Suite Professional',
                    'level' => 88,
                    'badge' => 'Tooling',
                    'icon' => 'fa-solid fa-spider',
                    'desc' => 'HTTP traffic interception, repeater analysis, intruder fuzzing, collaborator audits, and custom extensions.'
                ],
                [
                    'name' => 'Nmap Network Scanning & NSE',
                    'level' => 90,
                    'badge' => 'Recon',
                    'icon' => 'fa-solid fa-network-wired',
                    'desc' => 'Port scanning, service fingerprinting, OS detection, and custom Lua NSE script automation.'
                ],
                [
                    'name' => 'Wazuh SIEM & EDR',
                    'level' => 82,
                    'badge' => 'Defensive',
                    'icon' => 'fa-solid fa-server',
                    'desc' => 'Log analysis, file integrity monitoring (FIM), host intrusion detection, and regulatory compliance auditing.'
                ],
                [
                    'name' => 'Splunk Enterprise Security',
                    'level' => 80,
                    'badge' => 'SIEM',
                    'icon' => 'fa-solid fa-chart-line',
                    'desc' => 'Data ingestion, SPL queries, security dashboards, correlation searches, and incident investigation.'
                ],
                [
                    'name' => 'PoC Validation & Exploit Research',
                    'level' => 86,
                    'badge' => 'Research',
                    'icon' => 'fa-solid fa-flask-vial',
                    'desc' => 'Crafting reliable, non-destructive proof-of-concept exploits to validate impact and author remediation guidance.'
                ],
                [
                    'name' => 'Wireshark & Packet Analysis',
                    'level' => 85,
                    'badge' => 'Network',
                    'icon' => 'fa-solid fa-wave-square',
                    'desc' => 'Deep packet inspection, protocol dissecting, packet stream tracking, and unencrypted credential discovery.'
                ]
            ]
        ],
        [
            'id' => 'systems',
            'name' => 'Systems & Networking',
            'subtitle' => 'Infrastructure Administration, Operating Systems, Hardening & Enterprise Protocols',
            'icon' => 'fa-solid fa-server',
            'accent' => 'cyan',
            'skills' => [
                [
                    'name' => 'Linux Administration (RHCSA Certified)',
                    'level' => 90,
                    'badge' => 'Certified',
                    'icon' => 'fa-brands fa-linux',
                    'desc' => 'Red Hat Enterprise Linux, Kali, Ubuntu; bash scripting, user/group permission models, systemd, and LVM.'
                ],
                [
                    'name' => 'Windows Server & Active Directory',
                    'level' => 84,
                    'badge' => 'Enterprise',
                    'icon' => 'fa-brands fa-windows',
                    'desc' => 'AD DS domain configuration, Group Policy Objects (GPOs), Kerberos/NTLM fundamentals, and DNS/DHCP servers.'
                ],
                [
                    'name' => 'TCP/IP Suite & Protocol Architecture',
                    'level' => 88,
                    'badge' => 'Networking',
                    'icon' => 'fa-solid fa-diagram-project',
                    'desc' => 'Deep mastery of IPv4/IPv6, subnetting (CIDR), ARP, ICMP, TCP 3-way handshakes, UDP, and flow control.'
                ],
                [
                    'name' => 'HTTP/HTTPS, TLS & SSL Ciphers',
                    'level' => 90,
                    'badge' => 'Web Protocol',
                    'icon' => 'fa-solid fa-lock',
                    'desc' => 'Certificates management, HSTS, secure cipher configuration, TLS handshake analysis, and HTTP headers hardening.'
                ],
                [
                    'name' => 'Switching, Routing & VLANs',
                    'level' => 82,
                    'badge' => 'Hardware',
                    'icon' => 'fa-solid fa-route',
                    'desc' => 'L2/L3 managed switches, 802.1Q trunking, inter-VLAN routing, ACLs, and enterprise network segmentation.'
                ],
                [
                    'name' => 'Firewall & Host Defense (iptables / UFW)',
                    'level' => 85,
                    'badge' => 'Defense',
                    'icon' => 'fa-solid fa-shield-cat',
                    'desc' => 'Stateful packet filtering, NAT, port forwarding, fail2ban brute-force protection, and perimeter security.'
                ]
            ]
        ],
        [
            'id' => 'development',
            'name' => 'Development & Cloud',
            'subtitle' => 'Secure Full-Stack Web Development, Scripting, Hardware Tools & Cloud Fundamentals',
            'icon' => 'fa-solid fa-code',
            'accent' => 'indigo',
            'skills' => [
                [
                    'name' => 'PHP 8+ (OOP, MVC & Secure Coding)',
                    'level' => 92,
                    'badge' => 'Expert',
                    'icon' => 'fa-brands fa-php',
                    'desc' => 'Modern PHP 8 with typed properties, attributes, PDO prepared statements, robust sanitization, and session security.'
                ],
                [
                    'name' => 'Java (Core & OOP, NPTEL Certified)',
                    'level' => 80,
                    'badge' => 'Certified',
                    'icon' => 'fa-brands fa-java',
                    'desc' => 'Object-oriented software architecture, collections, multithreading, and algorithmic problem-solving.'
                ],
                [
                    'name' => 'C++ / Arduino (Hardware Security)',
                    'level' => 78,
                    'badge' => 'IoT / Embedded',
                    'icon' => 'fa-solid fa-microchip',
                    'desc' => 'Microcontroller programming for wireless network auditing, frame analysis, and ESP8266 security tools.'
                ],
                [
                    'name' => 'JavaScript & Vanilla DOM',
                    'level' => 88,
                    'badge' => 'Frontend',
                    'icon' => 'fa-brands fa-js',
                    'desc' => 'ES6+, Asynchronous Fetch API, client-side input validation, dynamic UI rendering, and event-driven widgets.'
                ],
                [
                    'name' => 'HTML5, CSS3 & Tailwind CSS',
                    'level' => 90,
                    'badge' => 'UI/UX',
                    'icon' => 'fa-brands fa-css3-alt',
                    'desc' => 'Mobile-first responsive design, modern dark-mode layouts, accessible markup, and smooth CSS transitions.'
                ],
                [
                    'name' => 'MySQL / MariaDB Security',
                    'level' => 86,
                    'badge' => 'Database',
                    'icon' => 'fa-solid fa-database',
                    'desc' => 'Relational database schema modeling, indexing, parameterized queries, and least-privilege user grants.'
                ],
                [
                    'name' => 'AWS Cloud Fundamentals',
                    'level' => 75,
                    'badge' => 'Cloud',
                    'icon' => 'fa-brands fa-aws',
                    'desc' => 'EC2 virtual server provisioning, S3 bucket policy hardening, IAM roles, Security Groups, and VPC subnets.'
                ]
            ]
        ]
    ],

    'vapt_methodology' => [
        [
            'phase' => '01',
            'name' => 'Reconnaissance & Scope',
            'desc' => 'Passive & active OSINT reconnaissance, target surface mapping, subdomain enumeration, and technology fingerprinting.',
            'tools' => ['Nmap', 'Amass', 'Whois', 'Wappalyzer']
        ],
        [
            'phase' => '02',
            'name' => 'Vulnerability Scanning',
            'desc' => 'Automated & manual endpoint fuzzing, port scanning, configuration auditing, and dependency check against known CVEs.',
            'tools' => ['Burp Suite', 'Nikto', 'Nmap NSE', 'Nuclei']
        ],
        [
            'phase' => '03',
            'name' => 'Manual Analysis & Exploitation',
            'desc' => 'Rigorous manual validation of business logic flaws, parameter tampering, privilege escalation, and bypass techniques.',
            'tools' => ['Burp Repeater', 'Postman', 'Custom Python/Bash']
        ],
        [
            'phase' => '04',
            'name' => 'PoC Validation & Risk Rating',
            'desc' => 'Crafting harmless Proof-of-Concepts (PoCs) to verify tangible exploitability; assigning CVSS v3.1 severity scores.',
            'tools' => ['CVSS Calculator', 'PoC Screenshots', 'Logs']
        ],
        [
            'phase' => '05',
            'name' => 'Remediation & Re-testing',
            'desc' => 'Authoring comprehensive remediation roadmaps with exact code fixes; conducting re-tests after client patches.',
            'tools' => ['Technical Reports', 'Executive Summary']
        ]
    ],

    'experience' => [
        [
            'role' => 'Cybersecurity Trainer / Junior VAPT Analyst',
            'company' => 'Centegic Technology Pvt. Ltd.',
            'period' => 'June 2026 – Sept 2026',
            'location' => 'Nepal',
            'type' => 'Full-time / Professional',
            'icon' => 'fa-solid fa-shield-virus',
            'highlights' => [
                'Led comprehensive hands-on cybersecurity workshops, lab exercises, and threat modeling demos for aspiring security practitioners.',
                'Conducted web application and network Vulnerability Assessment and Penetration Testing (VAPT), identifying critical flaws such as IDOR and SQLi.',
                'Prepared structured risk rating reports mapped to OWASP Top 10 guidelines and authored actionable remediation documentation for software engineers.',
                'Designed vulnerable lab environments in isolated networks for offensive simulation exercises and defensive blue-team hardening.'
            ],
            'tags' => ['VAPT', 'OWASP Top 10', 'Burp Suite', 'Threat Modeling', 'Technical Training', 'Remediation Guidance']
        ],
        [
            'role' => 'IT Support Engineer',
            'company' => 'Parul Sevashram Hospital',
            'period' => 'June 2024 – June 2025',
            'location' => 'Gujarat, India',
            'type' => 'Full-time / Infrastructure',
            'icon' => 'fa-solid fa-network-wired',
            'highlights' => [
                'Managed enterprise hospital network infrastructure, L2/L3 switch configurations, VLAN segmentation, and server administration.',
                'Administered Windows Server environments, Active Directory domain services, user accounts, and group security policies.',
                'Configured network protocols (TCP/IP, DHCP, DNS) and ensured 99.9% uptime for critical medical telemetry and hospital databases.',
                'Delivered rapid tier-2/3 technical troubleshooting, endpoint protection, and hardware perimeter maintenance across hospital wards.'
            ],
            'tags' => ['Network Infrastructure', 'Windows Server', 'Active Directory', 'Switching & Routing', 'Enterprise IT Support']
        ]
    ],

    'projects' => [
        [
            'id' => 'esp8266-wifi-tool',
            'title' => 'ESP8266 Wi-Fi Security Tool',
            'category' => 'security',
            'category_label' => 'Hardware & Security',
            'featured' => true,
            'summary' => 'Custom hardware security auditing device built with C++ and Arduino microcontrollers to demonstrate IEEE 802.11 Wi-Fi weaknesses in controlled lab environments.',
            'description' => 'Engineered a specialized hardware security auditing appliance utilizing the ESP8266 Wi-Fi SoC. Configured firmware to capture and analyze 802.11 management frames, simulate deauthentication vulnerabilities, detect rogue APs, and demonstrate the urgent need for 802.11w protected management frames in enterprise and campus networks.',
            'highlights' => [
                'Demonstrates 802.11 deauthentication frame vulnerabilities in isolated educational labs',
                'Packet sniffing and beacon frame analysis directly at the hardware layer',
                'Embedded web portal UI running on the ESP8266 chip for wireless control and metrics',
                'Authored complete lab documentation outlining mitigation strategies'
            ],
            'tech_stack' => ['C++', 'Arduino IDE', 'ESP8266 SoC', '802.11 Protocols', 'Hardware Security', 'Embedded Web Server'],
            'demo_url' => null,
            'github_url' => 'https://github.com/bajrang-sarraf',
            'badge' => 'Hardware / IoT Security'
        ],
        [
            'id' => 'news4nepal',
            'title' => 'News4Nepal',
            'category' => 'web',
            'category_label' => 'Web Platform',
            'featured' => true,
            'summary' => 'High-performance, responsive news and media publishing web portal serving real-time journalism across Nepal.',
            'description' => 'Architected and built a modern, resilient news platform engineered for fast loading speeds and heavy concurrent readers. Built with secure PHP backend architecture, dynamic category routing, optimized SQL indexing, and hardened against common web attack vectors (SQLi, CSRF, and clickjacking).',
            'highlights' => [
                'Dynamic content management system with rapid editorial workflows',
                'Strict input sanitization and parameterized queries for robust SQL injection prevention',
                'Mobile-first responsive design tailored for diverse network bandwidth conditions',
                'SEO schema optimization with OpenGraph metadata integration'
            ],
            'tech_stack' => ['PHP 8+', 'MySQL', 'JavaScript', 'Tailwind CSS', 'Responsive UI', 'SEO Schema'],
            'demo_url' => 'https://news4nepal.com',
            'github_url' => null,
            'badge' => 'Production Web App'
        ],
        [
            'id' => 'visionpoint',
            'title' => 'VisionPoint Education Portal',
            'category' => 'web',
            'category_label' => 'Education & Services',
            'featured' => true,
            'summary' => 'Full-featured educational consultancy web portal supporting overseas education programs and student application tracking.',
            'description' => 'Designed and developed a corporate education service portal for VisionPoint. Features comprehensive program catalogs, counselor appointment scheduling, secure document inquiry forms, and a responsive modern user interface.',
            'highlights' => [
                'Interactive abroad study course directory and destination filter',
                'Appointment booking pipeline with automated client email confirmations',
                'CSRF-protected contact and counseling intake workflows',
                'Accessible, fast-loading design with cross-device compatibility'
            ],
            'tech_stack' => ['PHP', 'MySQL', 'JavaScript', 'Tailwind CSS', 'AJAX Form Processing'],
            'demo_url' => 'https://visionpoint.edu.np',
            'github_url' => null,
            'badge' => 'Education Portal'
        ],
        [
            'id' => 'hakkiveda',
            'title' => 'HakkiVeda Nepal',
            'category' => 'ecommerce',
            'category_label' => 'E-Commerce',
            'featured' => false,
            'summary' => 'Herbal and wellness e-commerce platform featuring catalog exploration, cart management, and seamless order dispatch.',
            'description' => 'Built a complete e-commerce solution for organic healthcare and herbal products in Nepal. Engineered cart state management, safe session isolation, categorized inventory browsing, and client order tracking.',
            'highlights' => [
                'Session-hardened cart management preventing state tampering',
                'Product variant selection, search filtering, and inventory availability check',
                'Secure checkout flow with order verification and notification delivery',
                'Optimized asset delivery and lightweight vanilla JavaScript modules'
            ],
            'tech_stack' => ['PHP 8', 'MySQL', 'Vanilla JS', 'Tailwind CSS', 'Payment Flow'],
            'demo_url' => null,
            'github_url' => 'https://github.com/bajrang-sarraf',
            'badge' => 'E-Commerce'
        ],
        [
            'id' => 'nanotech',
            'title' => 'Nano Tech IT Solution',
            'category' => 'ecommerce',
            'category_label' => 'E-Commerce & Tech',
            'featured' => false,
            'summary' => 'E-commerce platform for computer hardware, peripherals, and enterprise IT equipment with automated quotation generation.',
            'description' => 'Engineered a tech hardware catalog and sales web portal. Provides real-time component specification comparisons, filtered searches by price and brand, and instant business quotation requests.',
            'highlights' => [
                'Dynamic hardware specification comparison engine',
                'AJAX-driven product search with instant live results',
                'Quotation generation module for corporate and enterprise procurement',
                'Database schema indexing for rapid multi-attribute queries'
            ],
            'tech_stack' => ['PHP', 'MySQL', 'JavaScript', 'CSS3', 'REST-style Endpoints'],
            'demo_url' => null,
            'github_url' => 'https://github.com/bajrang-sarraf',
            'badge' => 'Tech Hardware Portal'
        ],
        [
            'id' => 'namaste-education',
            'title' => 'Namaste Education Foundation',
            'category' => 'corporate',
            'category_label' => 'Corporate Consultancy',
            'featured' => false,
            'summary' => 'Corporate consultancy portal delivering global university matching and student inquiry processing.',
            'description' => 'Created an elegant web presence for an international educational consultancy. The portal streamlines student discovery of test preparation classes, university deadlines, and visa guidance.',
            'highlights' => [
                'Clean corporate brand identity with modern typography and interactive layouts',
                'Validated intake forms with anti-spam rate limiting',
                'Interactive guides for IELTS/TOEFL and country requirements',
                'Lighthouse score optimization for performance and SEO'
            ],
            'tech_stack' => ['PHP 8', 'HTML5/Tailwind', 'JavaScript', 'SEO Architecture'],
            'demo_url' => null,
            'github_url' => 'https://github.com/bajrang-sarraf',
            'badge' => 'Corporate Portal'
        ],
        [
            'id' => 'naya-kadam',
            'title' => 'Naya Kadam Nepal',
            'category' => 'nonprofit',
            'category_label' => 'Non-Profit / NGO',
            'featured' => false,
            'summary' => 'Community outreach and social welfare web portal dedicated to humanitarian campaigns and volunteer mobilization.',
            'description' => 'Developed a non-profit website to highlight community projects, outreach programs, and social initiatives. Emphasizes donor transparency, campaign milestones, and volunteer onboarding.',
            'highlights' => [
                'Engaging visual storytelling highlighting humanitarian impact and milestones',
                'Volunteer registration and event participation management',
                'Accessible contrast and navigation meeting WCAG guidelines',
                'Lightweight footprint ensuring accessibility in rural areas with low-speed internet'
            ],
            'tech_stack' => ['PHP', 'Responsive CSS', 'Vanilla JavaScript', 'Accessibility WCAG'],
            'demo_url' => null,
            'github_url' => 'https://github.com/bajrang-sarraf',
            'badge' => 'Community / NGO'
        ]
    ],

    'bug_bounty_research' => [
        'overview' => 'Active participant in private bug bounty programs and authorized responsible disclosure initiatives. Passionate about uncovering high-impact vulnerabilities, reproducing findings through disciplined Proof-of-Concepts (PoCs), and assisting engineering teams in implementing secure code fixes.',
        'stats' => [
            ['label' => 'Bounty Programs', 'val' => 'Private Invitee'],
            ['label' => 'Primary Focus', 'val' => 'OWASP Top 10 / Logic'],
            ['label' => 'Validation Rate', 'val' => 'Verified PoCs'],
            ['label' => 'Disclosure Ethics', 'val' => '100% Responsible']
        ],
        'vulnerabilities_found' => [
            [
                'type' => 'Broken Object Level Authorization (BOLA / IDOR)',
                'severity' => 'High',
                'cvss' => '8.1',
                'badge_color' => 'red',
                'summary' => 'Identified missing user-tenant validation in API endpoint allowing unauthorized access to cross-account sensitive profile data.',
                'impact' => 'Privilege escalation & data confidentiality breach across multitenant instances.',
                'fix' => 'Implemented server-side session identity mapping and UUID authorization checks.'
            ],
            [
                'type' => 'Stored & Reflected Cross-Site Scripting (XSS)',
                'severity' => 'Medium / High',
                'cvss' => '7.4',
                'badge_color' => 'orange',
                'summary' => 'Discovered unescaped input reflection inside rendered user feedback forms and profile display templates.',
                'impact' => 'Session hijacking potential, DOM manipulation, and malicious redirection.',
                'fix' => 'Enforced context-aware output encoding (htmlspecialchars with ENT_QUOTES) and Content Security Policy (CSP).'
            ],
            [
                'type' => 'Flawed Business Logic & Input Validation Bypasses',
                'severity' => 'Medium',
                'cvss' => '6.5',
                'badge_color' => 'amber',
                'summary' => 'Found client-side validation reliance during checkout and parameter tampering on quantity/pricing fields.',
                'impact' => 'Discrepancies in billing and unauthorized status transitions.',
                'fix' => 'Re-computed all price and status mutations strictly within the server domain with cryptographic verification.'
            ]
        ]
    ],

    'education' => [
        [
            'degree' => 'B.Sc. in Information Technology',
            'specialization' => 'Specialization in Cyber Security and Forensics',
            'institution' => 'Parul University',
            'period' => 'Graduated',
            'location' => 'Gujarat, India',
            'icon' => 'fa-solid fa-graduation-cap',
            'desc' => 'Comprehensive coursework in Applied Cryptography, Ethical Hacking, Network Security, Digital Forensics & Incident Response, Database Security, and Malware Analysis.'
        ]
    ],

    'certifications' => [
        [
            'title' => 'Red Hat Certified System Administrator (RHCSA)',
            'issuer' => 'IT Security College of Computer Science / Red Hat',
            'icon' => 'fa-brands fa-redhat',
            'category' => 'System Administration',
            'badge' => 'Certified',
            'color' => '#EE0000',
            'desc' => 'Validates core system administration competencies across Red Hat Enterprise Linux environments, including storage configuration, user management, SELinux enforcement, and service deployment.'
        ],
        [
            'title' => 'Ethical Hacking Training',
            'issuer' => 'Broadway Infosys',
            'icon' => 'fa-solid fa-user-secret',
            'category' => 'Offensive Security',
            'badge' => 'Certified',
            'color' => '#10b981',
            'desc' => 'In-depth training on penetration testing methodologies, footprinting, network scanning, system exploitation, web application attacks, and wireless auditing.'
        ],
        [
            'title' => 'Programming in Java (NPTEL Certified)',
            'issuer' => 'IIT Kharagpur / NPTEL',
            'icon' => 'fa-brands fa-java',
            'category' => 'Software Engineering',
            'badge' => 'Elite Certified',
            'color' => '#F89820',
            'desc' => 'Rigorous certification covering core Java, Object-Oriented Design, data structures, exception handling, and concurrent programming.'
        ],
        [
            'title' => 'Computer Networks and Communication (NPTEL Certified)',
            'issuer' => 'IIT Kharagpur / NPTEL',
            'icon' => 'fa-solid fa-network-wired',
            'category' => 'Networking',
            'badge' => 'Elite Certified',
            'color' => '#06b6d4',
            'desc' => 'In-depth theoretical and practical study of OSI reference model, TCP/IP protocol stack, network layer routing algorithms, congestion control, and data link protocols.'
        ]
    ],

    'awards_hackathons' => [
        [
            'title' => 'Runner-Up – Ganpat University Hackathon',
            'issuer' => 'Ganpat University',
            'icon' => 'fa-solid fa-trophy',
            'badge' => '2nd Place',
            'color' => '#fbbf24',
            'desc' => 'Awarded 2nd position among competing engineering teams for developing an innovative technology solution under tight deadline constraints.'
        ],
        [
            'title' => 'Participant – IIT Jammu Hackathon',
            'issuer' => 'Indian Institute of Technology (IIT) Jammu',
            'icon' => 'fa-solid fa-medal',
            'badge' => 'National Participant',
            'color' => '#6366f1',
            'desc' => 'Competed at national level hackathon addressing complex algorithmic and real-world system challenges.'
        ]
    ],

    'languages' => [
        ['name' => 'English', 'level' => 'Professional Working Proficiency', 'flag' => '🇬🇧', 'percent' => 90],
        ['name' => 'Nepali', 'level' => 'Native / Bilingual', 'flag' => '🇳🇵', 'percent' => 100],
        ['name' => 'Hindi', 'level' => 'Fluent Proficiency', 'flag' => '🇮🇳', 'percent' => 95],
        ['name' => 'Bhojpuri', 'level' => 'Native / Fluent', 'flag' => '🇳🇵', 'percent' => 95]
    ],

    'terminal_commands' => [
        'help' => 'Available commands: whoami, bio, skills, vapt, projects, exp, certs, awards, contact, clear, matrix, ping',
        'whoami' => "Bajrang Kumar Sarraf\nRoles: Cybersecurity Trainer | Junior Penetration Tester | VAPT Analyst | Web Developer\nLocation: Birgunj, Nepal 44300\nStatus: Available for security audits and full-stack development",
        'bio' => "Cybersecurity professional with hands-on experience in Vulnerability Assessment and Penetration Testing (VAPT), training, network administration, and web development. Specializing in offensive assessment and defensive hardening.",
        'skills' => "1. Offensive & Defensive: VAPT, OWASP Top 10, Burp Suite, Nmap, Wazuh, Splunk, PoC Validation\n2. Systems: Linux (RHCSA), Windows Server, Active Directory, TCP/IP, Switching, Firewalls\n3. Dev: PHP 8+, Java, C++, JavaScript, Node.js, Tailwind CSS, MySQL, AWS",
        'vapt' => "VAPT Scan Engine: Ready\nMethodology: Recon -> Surface Mapping -> Vulnerability Scanning -> PoC Exploitation -> Remediation\nSpecialty: OWASP Top 10, BOLA/IDOR, XSS, CSRF, Network Perimeter Audits",
        'projects' => "1. ESP8266 Wi-Fi Security Tool [Hardware Security / C++]\n2. News4Nepal [Production News Portal - news4nepal.com]\n3. VisionPoint [Education Portal - visionpoint.edu.np]\n4. HakkiVeda Nepal [E-Commerce Platform]\n5. Nano Tech IT Solution [Tech Hardware E-Commerce]\n6. Namaste Education Foundation [Consultancy Portal]\n7. Naya Kadam Nepal [Non-profit Web Portal]",
        'exp' => "1. Centegic Technology Pvt. Ltd. | Cybersecurity Trainer / Junior VAPT Analyst (June 2026 – Sept 2026)\n2. Parul Sevashram Hospital | IT Support Engineer (June 2024 – June 2025)",
        'certs' => "1. RHCSA (Red Hat Certified System Administrator)\n2. Ethical Hacking Training (Broadway Infosys)\n3. Programming in Java (NPTEL - IIT Kharagpur)\n4. Computer Networks and Communication (NPTEL - IIT Kharagpur)",
        'awards' => "1. Runner-Up @ Ganpat University Hackathon\n2. Participant @ IIT Jammu Hackathon",
        'contact' => "Email: bajrangshroff10@gmail.com\nPhone: +977 9816266554\nLocation: Birgunj, Nepal 44300\nWhatsApp: https://wa.me/9779816266554"
    ]
];
