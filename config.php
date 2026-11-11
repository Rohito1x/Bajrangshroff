<?php
/**
 * Application Configuration & Security Core
 * Profile: Bajrang Kumar Sarraf - Cybersecurity Specialist & Web Developer
 * PHP 8+ Modern Architecture
 */

declare(strict_types=1);

// Error handling based on environment
define('APP_ENV', 'development'); // 'development' or 'production'

if (APP_ENV === 'development') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(0);
}

// Session configuration with security flags
if (session_status() === PHP_SESSION_NONE) {
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    
    session_set_cookie_params([
        'lifetime' => 86400, // 24 hours
        'path' => '/',
        'domain' => '',
        'secure' => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    
    session_start();
}

/**
 * Core Application Config
 */
class AppConfig {
    public const SITE_NAME = 'Bajrang Kumar Sarraf | Cybersecurity & Web Development';
    public const SITE_TITLE = 'Bajrang Kumar Sarraf — Cybersecurity Trainer & Penetration Tester';
    public const OWNER_NAME = 'Bajrang Kumar Sarraf';
    public const OWNER_EMAIL = 'bajrangshroff10@gmail.com';
    public const OWNER_PHONE = '+977 9816266554';
    public const OWNER_LOCATION = 'Birgunj, Nepal 44300';
    public const STORAGE_PATH = __DIR__ . '/storage';
    public const LOG_FILE = __DIR__ . '/storage/messages.json';
    public const RATE_LIMIT_FILE = __DIR__ . '/storage/rate_limits.json';

    /**
     * Determine dynamic base URL for asset loading
     */
    public static function baseUrl(): string {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $baseDir = rtrim($scriptDir, '/');
        // Handle subdirectories if called from handlers/
        if (str_ends_with($baseDir, '/handlers')) {
            $baseDir = substr($baseDir, 0, -9);
        }
        return $protocol . $host . $baseDir;
    }
}

/**
 * Security & Validation Helper
 */
class PortfolioSecurity {
    /**
     * Send hardened HTTP security headers
     */
    public static function applySecurityHeaders(): void {
        if (headers_sent()) {
            return;
        }

        // Prevent MIME type sniffing
        header('X-Content-Type-Options: nosniff');
        // Clickjacking defense
        header('X-Frame-Options: SAMEORIGIN');
        // XSS Filter
        header('X-XSS-Protection: 1; mode=block');
        // Referrer policy
        header('Referrer-Policy: strict-origin-when-cross-origin');
        // Permissions policy
        header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
        
        // Content Security Policy (allows necessary CDNs for modern UI: Tailwind, FontAwesome, Google Fonts, Highlight.js)
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net",
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com data:",
            "img-src 'self' data: https: blob:",
            "connect-src 'self'",
            "frame-ancestors 'self'"
        ];
        header('Content-Security-Policy: ' . implode('; ', $csp));
    }

    /**
     * Generate or fetch current CSRF token
     */
    public static function getCsrfToken(): string {
        if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_token_time']) || (time() - $_SESSION['csrf_token_time'] > 7200)) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify CSRF token using constant-time comparison
     */
    public static function verifyCsrfToken(?string $token): bool {
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Sanitize string input to prevent XSS
     */
    public static function sanitize(string $input): string {
        return htmlspecialchars(trim($input), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Client IP address detector with proxy safety check
     */
    public static function getClientIp(): string {
        $headers = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        return '127.0.0.1';
    }

    /**
     * File-based Rate Limiter to stop spam bots
     */
    public static function checkRateLimit(string $action, int $maxAttempts = 5, int $decaySeconds = 300): bool {
        $ip = self::getClientIp();
        $rateLimitFile = AppConfig::RATE_LIMIT_FILE;
        $now = time();

        if (!is_dir(AppConfig::STORAGE_PATH)) {
            @mkdir(AppConfig::STORAGE_PATH, 0755, true);
        }

        $records = [];
        if (file_exists($rateLimitFile)) {
            $content = @file_get_contents($rateLimitFile);
            $records = json_decode($content ?: '[]', true) ?: [];
        }

        $key = md5($ip . '_' . $action);

        // Clean expired records
        foreach ($records as $k => $data) {
            if ($now - $data['timestamp'] > $decaySeconds) {
                unset($records[$k]);
            }
        }

        if (isset($records[$key])) {
            if ($records[$key]['attempts'] >= $maxAttempts) {
                return false; // Rate limit exceeded
            }
            $records[$key]['attempts']++;
        } else {
            $records[$key] = [
                'ip' => $ip,
                'action' => $action,
                'attempts' => 1,
                'timestamp' => $now
            ];
        }

        @file_put_contents($rateLimitFile, json_encode($records, JSON_PRETTY_PRINT), LOCK_EX);
        return true;
    }
}

// Global convenience helper
function csrf_field(): string {
    $token = PortfolioSecurity::getCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

function e(string $string): string {
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}
