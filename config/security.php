<?php
/**
 * Security, Authentication Guards & Upload Sanitizer Core
 * Profile: Bajrang Kumar Sarraf - Cybersecurity Specialist & Web Developer
 */

declare(strict_types=1);

require_once __DIR__ . '/database.php';

// Ensure secure session is initialized
if (session_status() === PHP_SESSION_NONE) {
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    session_set_cookie_params([
        'lifetime' => 28800, // 8 hours
        'path' => '/',
        'domain' => '',
        'secure' => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

/**
 * CSRF Protection Subsystem
 */
class CSRF {
    public static function getToken(): string {
        if (empty($_SESSION['cms_csrf_token']) || empty($_SESSION['cms_csrf_time']) || (time() - $_SESSION['cms_csrf_time'] > 7200)) {
            $_SESSION['cms_csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['cms_csrf_time'] = time();
        }
        return $_SESSION['cms_csrf_token'];
    }

    public static function verify(?string $token): bool {
        if (empty($token) || empty($_SESSION['cms_csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['cms_csrf_token'], $token);
    }

    public static function field(): string {
        $token = self::getToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}

/**
 * Authentication & Hardened Session Manager
 */
class Auth {
    private const MAX_FAILED_ATTEMPTS = 5;
    private const LOCKOUT_DURATION_SECONDS = 900; // 15 minutes

    /**
     * Check if administrator is logged in and session integrity is intact
     */
    public static function check(): bool {
        if (empty($_SESSION['admin_auth']) || empty($_SESSION['admin_id'])) {
            return false;
        }

        // Session hijacking mitigation: compare IP and User-Agent fingerprint
        $currentFingerprint = md5(($_SERVER['REMOTE_ADDR'] ?? '') . ($_SERVER['HTTP_USER_AGENT'] ?? ''));
        if (($_SESSION['admin_fingerprint'] ?? '') !== $currentFingerprint) {
            self::logout();
            return false;
        }

        return true;
    }

    /**
     * Get logged-in user ID
     */
    public static function id(): ?int {
        return self::check() ? (int)$_SESSION['admin_id'] : null;
    }

    /**
     * Retrieve full admin record
     */
    public static function user(): ?array {
        if (!self::check()) {
            return null;
        }

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT id, username, email, created_at FROM admins WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $_SESSION['admin_id']]);
            return $stmt->fetch() ?: null;
        } catch (Throwable $e) {
            error_log('Auth::user error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Attempt login with rate limiting & brute-force account locking
     */
    public static function login(string $username, string $password): array {
        $username = trim($username);
        if (empty($username) || empty($password)) {
            return ['success' => false, 'error' => 'Please provide both username and password.'];
        }

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username LIMIT 1");
            $stmt->execute(['username' => $username]);
            $admin = $stmt->fetch();

            if (!$admin) {
                // Timing attack resistance: run dummy password_verify
                password_verify($password, '$2y$12$DUMMY_HASH_PREVENTS_TIMING_ATTACK_01234567890123456789012');
                return ['success' => false, 'error' => 'Invalid username or password.'];
            }

            // Check if account is locked
            if (!empty($admin['locked_until'])) {
                $lockedUntil = strtotime($admin['locked_until']);
                if ($lockedUntil > time()) {
                    $remainingMinutes = ceil(($lockedUntil - time()) / 60);
                    return [
                        'success' => false, 
                        'error' => "Account locked due to consecutive failed attempts. Try again in {$remainingMinutes} minute(s)."
                    ];
                } else {
                    // Lockout period expired: reset counter
                    $stmt = $pdo->prepare("UPDATE admins SET failed_attempts = 0, locked_until = NULL WHERE id = :id");
                    $stmt->execute(['id' => $admin['id']]);
                    $admin['failed_attempts'] = 0;
                }
            }

            // Verify password
            if (password_verify($password, $admin['password_hash'])) {
                // Successful Login: reset failed attempts
                $stmt = $pdo->prepare("UPDATE admins SET failed_attempts = 0, locked_until = NULL WHERE id = :id");
                $stmt->execute(['id' => $admin['id']]);

                // Regenerate session ID to prevent session fixation attacks
                if (!headers_sent()) {
                    session_regenerate_id(true);
                }

                $_SESSION['admin_auth'] = true;
                $_SESSION['admin_id'] = (int)$admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['admin_fingerprint'] = md5(($_SERVER['REMOTE_ADDR'] ?? '') . ($_SERVER['HTTP_USER_AGENT'] ?? ''));
                $_SESSION['admin_login_time'] = time();

                return ['success' => true];
            } else {
                // Failed password: increment counter
                $newAttempts = (int)$admin['failed_attempts'] + 1;
                $lockedUntil = null;

                if ($newAttempts >= self::MAX_FAILED_ATTEMPTS) {
                    $lockedUntil = date('Y-m-d H:i:s', time() + self::LOCKOUT_DURATION_SECONDS);
                }

                $stmt = $pdo->prepare("UPDATE admins SET failed_attempts = :attempts, locked_until = :locked WHERE id = :id");
                $stmt->execute([
                    'attempts' => $newAttempts,
                    'locked' => $lockedUntil,
                    'id' => $admin['id']
                ]);

                if ($lockedUntil !== null) {
                    return [
                        'success' => false,
                        'error' => 'Maximum login attempts exceeded. Account is locked for 15 minutes.'
                    ];
                }

                $remaining = self::MAX_FAILED_ATTEMPTS - $newAttempts;
                return [
                    'success' => false,
                    'error' => "Invalid credentials. {$remaining} attempt(s) remaining before account lockout."
                ];
            }
        } catch (Throwable $e) {
            error_log('Login exception: ' . $e->getMessage());
            return ['success' => false, 'error' => 'An authentication error occurred. Please try again.'];
        }
    }

    /**
     * Terminate session completely
     */
    public static function logout(): void {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        @session_destroy();
    }
}

/**
 * Hardened File Upload Utility
 * Defends against arbitrary file uploads, MIME spoofing, path traversal, and malicious extensions
 */
class FileUploader {
    private const ALLOWED_IMAGE_TYPES = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml'
    ];

    private const ALLOWED_DOC_TYPES = [
        'pdf' => 'application/pdf'
    ];

    /**
     * Process and validate file upload
     * 
     * @param array $file $_FILES item
     * @param string $category Subdirectory: 'profile', 'projects', or 'documents'
     * @param bool $allowPdf Whether PDF uploads are permitted
     * @param int $maxBytes Maximum filesize in bytes (default 8MB)
     * @return string Relative path to uploaded file (e.g., 'uploads/projects/xyz.webp')
     */
    public static function upload(array $file, string $category, bool $allowPdf = false, int $maxBytes = 8388608): string {
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new RuntimeException('Invalid upload parameter.');
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file was uploaded.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Uploaded file exceeds maximum allowable size.');
            default:
                throw new RuntimeException('An error occurred during file upload.');
        }

        if ($file['size'] > $maxBytes) {
            throw new RuntimeException('File exceeds size limit (' . round($maxBytes / 1048576, 1) . 'MB).');
        }

        // Verify Extension against strict whitelist
        $fileName = $file['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedMimes = self::ALLOWED_IMAGE_TYPES;
        if ($allowPdf) {
            $allowedMimes = array_merge($allowedMimes, self::ALLOWED_DOC_TYPES);
        }

        if (!array_key_exists($ext, $allowedMimes)) {
            throw new RuntimeException('Disallowed file extension (.' . htmlspecialchars($ext) . ').');
        }

        // Verify True MIME-type via finfo
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        // Check against known extensions
        $expectedMime = $allowedMimes[$ext];
        if ($mime !== $expectedMime && !($ext === 'svg' && str_contains($mime, 'xml'))) {
            throw new RuntimeException('File content does not match extension (detected: ' . htmlspecialchars($mime) . ').');
        }

        // If raster image, verify real image geometry to prevent polyglot payloads
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            $imgInfo = @getimagesize($file['tmp_name']);
            if ($imgInfo === false) {
                throw new RuntimeException('Uploaded file is corrupted or not a valid image.');
            }
        }

        // Additional sanitization if SVG
        if ($ext === 'svg') {
            $svgContent = (string)file_get_contents($file['tmp_name']);
            // Check for malicious script tags or onload handlers in SVG
            if (preg_match('/<script|javascript:|on\w+=/i', $svgContent)) {
                throw new RuntimeException('SVG file contains disallowed script attributes.');
            }
        }

        // Prepare destination directory
        $baseUploadDir = __DIR__ . '/../uploads/' . trim($category, '/');
        if (!is_dir($baseUploadDir)) {
            @mkdir($baseUploadDir, 0755, true);
        }

        // Generate cryptographically unique filename preventing overwriting & path traversal
        $newFilename = sprintf(
            '%s_%s.%s',
            date('Ymd_His'),
            bin2hex(random_bytes(8)),
            $ext
        );

        $targetPath = $baseUploadDir . '/' . $newFilename;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new RuntimeException('Failed to move uploaded file to target storage.');
        }

        // Set safe file permissions (non-executable)
        @chmod($targetPath, 0644);

        return 'uploads/' . trim($category, '/') . '/' . $newFilename;
    }
}

/**
 * Sanitization & Escaping Helper
 */
function sanitize_text(?string $str): string {
    return htmlspecialchars(trim((string)$str), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}
