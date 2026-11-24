-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: bajrang_portfolio
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'bajrang_admin','bajrangshroff10@gmail.com','$2y$12$MNMggJSgLlLGNZ5lH5dGO.uaUmhGy0jFzIKnBkeW88uEp2UYZwX7O',0,NULL,'2026-11-11 15:52:45');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `animation_settings`
--

DROP TABLE IF EXISTS `animation_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `animation_settings` (
  `id` int(11) NOT NULL,
  `enable_particles` tinyint(4) DEFAULT 1,
  `particle_node_count` int(11) DEFAULT 60,
  `particle_color` varchar(20) DEFAULT '#10b981',
  `enable_glitch_effect` tinyint(4) DEFAULT 1,
  `enable_card_tilt` tinyint(4) DEFAULT 1,
  `typing_speed_ms` int(11) DEFAULT 80,
  `terminal_custom_commands` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `animation_settings`
--

LOCK TABLES `animation_settings` WRITE;
/*!40000 ALTER TABLE `animation_settings` DISABLE KEYS */;
INSERT INTO `animation_settings` VALUES (1,1,60,'#10b981',1,1,80,'{\n    \"whoami\": \"Bajrang Kumar Sarraf\\nRoles: Cybersecurity Trainer | Junior Penetration Tester | VAPT Analyst | Web Developer\\nLocation: Birgunj, Nepal 44300\",\n    \"vapt\": \"VAPT Scan Engine: Ready\\nMethodology: Recon -> Scanning -> Manual PoC -> CVSS v3.1 Remediation\\nFocus: OWASP Top 10, Network Perimeters, Active Directory\",\n    \"skills\": \"1. Security: VAPT, OWASP Top 10, Burp Suite, Nmap, Wazuh, Splunk\\n2. Systems: Linux (RHCSA), Windows Server, Active Directory\\n3. Dev: PHP 8+ OOP, Java, C++, JavaScript, Tailwind\",\n    \"contact\": \"Email: bajrangshroff10@gmail.com | Phone: +977 9816266554 | Location: Birgunj, Nepal\"\n}');
/*!40000 ALTER TABLE `animation_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `certifications`
--

DROP TABLE IF EXISTS `certifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `certifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `issuer` varchar(100) NOT NULL,
  `issue_date` varchar(50) NOT NULL,
  `credential_url` varchar(255) DEFAULT NULL,
  `badge_image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `certifications`
--

LOCK TABLES `certifications` WRITE;
/*!40000 ALTER TABLE `certifications` DISABLE KEYS */;
INSERT INTO `certifications` VALUES (1,'Red Hat Certified System Administrator (RHCSA)','IT Security College of Computer Science / Red Hat','Certified','https://www.redhat.com','assets/img/profile-cyber.svg'),(2,'Ethical Hacking Training','Broadway Infosys','Certified',NULL,'assets/img/profile-cyber.svg'),(3,'Programming in Java (NPTEL Certified)','IIT Kharagpur / NPTEL','Elite Certified',NULL,'assets/img/profile-cyber.svg'),(4,'Computer Networks and Communication (NPTEL Certified)','IIT Kharagpur / NPTEL','Elite Certified',NULL,'assets/img/profile-cyber.svg');
/*!40000 ALTER TABLE `certifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `status` varchar(20) DEFAULT 'unread',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (1,'Rohit Soni','bajangshroff10@gmail.com','Audit my website [VAPT & Web Penetration Testing]','hey i want to audit my website for security testing','::1','read','2026-11-11 16:13:47');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `experiences`
--

DROP TABLE IF EXISTS `experiences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `experiences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company` varchar(150) NOT NULL,
  `role` varchar(150) NOT NULL,
  `duration` varchar(100) NOT NULL,
  `bullet_points` text NOT NULL,
  `display_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `experiences`
--

LOCK TABLES `experiences` WRITE;
/*!40000 ALTER TABLE `experiences` DISABLE KEYS */;
INSERT INTO `experiences` VALUES (1,'Centegic Technology Pvt. Ltd.','Cybersecurity Trainer / Junior VAPT Analyst','June 2026 – Sept 2026','[\"Led cybersecurity workshops, hands-on lab exercises, and threat modeling demos for technical cohorts.\",\"Conducted web application and network VAPT, identified critical OWASP flaws, and author actionable remediation guides.\",\"Prepared structured risk rating reports mapped to CVSS v3.1 standards for engineering teams.\"]',1),(2,'Parul Sevashram Hospital','IT Support Engineer','June 2024 – June 2025','[\"Managed enterprise hospital network infrastructure, switch configurations, VLAN segmentation, and server administration.\",\"Administered Windows Server environments, Active Directory domain services, user accounts, and security policies.\",\"Provided rapid troubleshooting and maintenance across critical medical telemetry systems with 99.9% uptime.\"]',2);
/*!40000 ALTER TABLE `experiences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_settings`
--

DROP TABLE IF EXISTS `profile_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile_settings` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `tagline_roles` text NOT NULL,
  `bio` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `location` varchar(150) NOT NULL,
  `avatar_path` varchar(255) DEFAULT 'assets/img/profile-cyber.svg',
  `hero_bg_path` varchar(255) DEFAULT NULL,
  `resume_pdf_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile_settings`
--

LOCK TABLES `profile_settings` WRITE;
/*!40000 ALTER TABLE `profile_settings` DISABLE KEYS */;
INSERT INTO `profile_settings` VALUES (1,'Bajrang Kumar Sarraf','Cybersecurity Trainer, Junior Penetration Tester, VAPT Analyst, Full-Stack Web Developer, Security Researcher','Cybersecurity professional with hands-on experience in Vulnerability Assessment and Penetration Testing (VAPT), training, network administration, and web development. Experienced in analyzing web apps/networks, validating vulnerabilities with Proof-of-Concepts (PoCs), and drafting remediation reports. Strong background in Linux, Windows Server, Burp Suite, Nmap, Wazuh, and Splunk.','bajrangshroff10@gmail.com','+977 9816266554','Birgunj, Nepal 44300','uploads/profile/20261111_171515_afd7eecc03edbd7b.jpeg',NULL,NULL);
/*!40000 ALTER TABLE `profile_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `live_url` varchar(255) DEFAULT NULL,
  `github_url` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT 'assets/img/profile-cyber.svg',
  `display_order` int(11) DEFAULT 0,
  `is_featured` tinyint(4) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,'ESP8266 Wi-Fi Security Tool','esp8266-wifi-security-tool','Hardware & Security','Custom hardware security auditing device built with C++ and Arduino microcontrollers to demonstrate IEEE 802.11 Wi-Fi weaknesses in controlled lab environments.',NULL,'https://github.com/bajrang-sarraf','assets/img/profile-cyber.svg',1,1),(2,'News4Nepal','news4nepal','Web Development','High-performance, responsive news and media publishing web portal serving real-time journalism across Nepal with secure PHP architecture and parameterized queries.','https://news4nepal.com',NULL,'assets/img/profile-cyber.svg',2,1),(3,'VisionPoint Education Portal','visionpoint-education-portal','Web Development','Full-featured educational consultancy web portal supporting overseas education programs, counselor appointment booking, and student application intake.','https://visionpoint.edu.np',NULL,'assets/img/profile-cyber.svg',3,1),(4,'HakkiVeda Nepal','hakkiveda-nepal','E-Commerce','Herbal and wellness e-commerce platform featuring catalog exploration, cart management, and seamless order dispatch.',NULL,'https://github.com/bajrang-sarraf','assets/img/profile-cyber.svg',4,0),(5,'Nano Tech IT Solution','nano-tech-it-solution','E-Commerce','E-commerce platform for computer hardware, peripherals, and enterprise IT equipment with automated quotation generation.',NULL,'https://github.com/bajrang-sarraf','assets/img/profile-cyber.svg',5,0),(6,'Namaste Education Foundation','namaste-education-foundation','Corporate Consultancy','Corporate consultancy portal delivering global university matching and student inquiry processing.',NULL,'https://github.com/bajrang-sarraf','assets/img/profile-cyber.svg',6,0),(7,'Naya Kadam Nepal','naya-kadam-nepal','Non-Profit / NGO','Community outreach and social welfare web portal dedicated to humanitarian campaigns and volunteer mobilization.',NULL,'https://github.com/bajrang-sarraf','assets/img/profile-cyber.svg',7,0);
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `proficiency_pct` int(11) DEFAULT 85,
  `display_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skills`
--

LOCK TABLES `skills` WRITE;
/*!40000 ALTER TABLE `skills` DISABLE KEYS */;
INSERT INTO `skills` VALUES (1,'VAPT (Web & Network)','Offensive Security',90,1),(2,'OWASP Top 10 Auditing','Offensive Security',92,2),(3,'Burp Suite Professional','Offensive Security',88,3),(4,'Nmap & NSE Scripting','Offensive Security',90,4),(5,'Wazuh SIEM & EDR','Defensive Security',82,5),(6,'Splunk Enterprise Security','Defensive Security',80,6),(7,'Linux Administration (RHCSA)','Networking & Systems',90,7),(8,'Windows Server & Active Directory','Networking & Systems',84,8),(9,'TCP/IP, Routing & Switching','Networking & Systems',88,9),(10,'PHP 8+ OOP & Secure PDO','Web Engineering',92,10),(11,'Java (NPTEL Certified)','Web Engineering',80,11),(12,'C++ / Arduino Embedded Tools','Web Engineering',78,12),(13,'JavaScript & Tailwind CSS','Web Engineering',90,13);
/*!40000 ALTER TABLE `skills` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-24 22:36:10
