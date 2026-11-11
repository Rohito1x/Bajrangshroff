<?php
/**
 * Secure Contact Form Processor
 * Handles AJAX JSON and standard POST submissions with anti-spam & CSRF validation
 */

declare(strict_types=1);

require_once __DIR__ . '/../config.php';

// Apply security headers
PortfolioSecurity::applySecurityHeaders();

// Determine if request is AJAX
$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
    || (isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json'))
    || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

// Helper to send response
function respond(bool $success, string $message, array $errors = [], int $statusCode = 200, bool $isAjax = true): void {
    http_response_code($statusCode);
    
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => time()
        ]);
        exit;
    }

    // Standard POST fallback: redirect to index with session flash
    $_SESSION['flash_status'] = $success ? 'success' : 'error';
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_errors'] = $errors;
    header('Location: ' . AppConfig::baseUrl() . '/index.php#contact');
    exit;
}

// 1. Check HTTP Method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Method Not Allowed. Only POST requests are permitted.', [], 405, $isAjax);
}

// 2. Anti-Spam Honeypot Check
// If the hidden trap input is filled, it's an automated bot
if (!empty($_POST['website_hp'])) {
    // Artificial delay to deter bots, then return fake success
    usleep(500000);
    respond(true, 'Your transmission has been logged. Thank you.', [], 200, $isAjax);
}

// 3. Rate Limiting Check (Max 5 submissions per 5 minutes per IP)
if (!PortfolioSecurity::checkRateLimit('contact_submit', 5, 300)) {
    respond(false, 'Too many requests from your IP. Please wait a few minutes before trying again.', ['rate_limit' => 'Rate limit exceeded'], 429, $isAjax);
}

// 4. CSRF Token Verification
$csrfToken = $_POST['csrf_token'] ?? '';
if (!PortfolioSecurity::verifyCsrfToken($csrfToken)) {
    respond(false, 'Security token validation failed (CSRF). Please reload the page and try again.', ['csrf' => 'Invalid or expired CSRF token'], 403, $isAjax);
}

// 5. Input Sanitization & Collection
$name = trim((string)($_POST['name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$service = trim((string)($_POST['service'] ?? 'General Inquiry'));
$subject = trim((string)($_POST['subject'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

$errors = [];

// Name validation
if (empty($name)) {
    $errors['name'] = 'Full name is required.';
} elseif (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
    $errors['name'] = 'Name must be between 2 and 100 characters.';
}

// Email validation
if (empty($email)) {
    $errors['email'] = 'Email address is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please provide a valid email address.';
} elseif (mb_strlen($email) > 120) {
    $errors['email'] = 'Email address exceeds maximum length.';
}

// Subject validation
if (empty($subject)) {
    $errors['subject'] = 'Subject line is required.';
} elseif (mb_strlen($subject) < 3 || mb_strlen($subject) > 150) {
    $errors['subject'] = 'Subject must be between 3 and 150 characters.';
}

// Message validation
if (empty($message)) {
    $errors['message'] = 'Message body cannot be empty.';
} elseif (mb_strlen($message) < 10) {
    $errors['message'] = 'Message must be at least 10 characters long.';
} elseif (mb_strlen($message) > 4000) {
    $errors['message'] = 'Message exceeds maximum allowable length (4000 characters).';
}

if (!empty($errors)) {
    respond(false, 'Validation failed. Please correct the highlighted errors.', $errors, 422, $isAjax);
}

// 6. Secure Logging / Storage
$submissionRecord = [
    'id' => bin2hex(random_bytes(8)),
    'timestamp' => date('c'),
    'ip_address' => PortfolioSecurity::getClientIp(),
    'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', 0, 200),
    'name' => PortfolioSecurity::sanitize($name),
    'email' => filter_var($email, FILTER_SANITIZE_EMAIL),
    'service' => PortfolioSecurity::sanitize($service),
    'subject' => PortfolioSecurity::sanitize($subject),
    'message' => PortfolioSecurity::sanitize($message)
];

$storageDir = AppConfig::STORAGE_PATH;
if (!is_dir($storageDir)) {
    @mkdir($storageDir, 0755, true);
}

$logFile = AppConfig::LOG_FILE;
$existingData = [];
if (file_exists($logFile)) {
    $content = @file_get_contents($logFile);
    $existingData = json_decode($content ?: '[]', true) ?: [];
}

// Append new submission
$existingData[] = $submissionRecord;
@file_put_contents($logFile, json_encode($existingData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);

// Insert into Database if initialized
try {
    require_once __DIR__ . '/../config/database.php';
    if (Database::isInitialized()) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO contact_messages (name, email, subject, message, ip_address, status, created_at)
            VALUES (:name, :email, :subject, :message, :ip, 'unread', datetime('now'))
        ");
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'subject' => $subject . (!empty($service) ? " [{$service}]" : ''),
            'message' => $message,
            'ip' => PortfolioSecurity::getClientIp()
        ]);
    }
} catch (Throwable $e) {
    error_log('Database contact insert error: ' . $e->getMessage());
}

// 7. Optional Email Dispatch
// Using mail() safely with fallback so offline/local environments don't error out
$to = AppConfig::OWNER_EMAIL;
$mailSubject = "[Portfolio Inquiry] " . strip_tags($subject);
$mailBody = "New message received from your portfolio website:\n\n" .
    "Name: {$submissionRecord['name']}\n" .
    "Email: {$submissionRecord['email']}\n" .
    "Service Requested: {$submissionRecord['service']}\n" .
    "Subject: {$submissionRecord['subject']}\n" .
    "IP: {$submissionRecord['ip_address']}\n" .
    "Time: {$submissionRecord['timestamp']}\n\n" .
    "Message:\n{$submissionRecord['message']}\n";

$headers = [
    'From' => 'no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost'),
    'Reply-To' => $submissionRecord['email'],
    'X-Mailer' => 'PHP/' . phpversion()
];

// Attempt mail transmission without throwing unhandled warnings if SMTP is unconfigured
@mail($to, $mailSubject, $mailBody, $headers);

// Regenerate CSRF token after successful submission
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

respond(true, 'Message transmitted securely! Thank you, Bajrang will get back to you shortly.', [], 200, $isAjax);
