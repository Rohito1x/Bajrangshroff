<?php
/**
 * Admin Authentication Guard Middleware
 * Enforces authenticated admin session, validates session integrity, prevents unauthorized access
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/security.php';
require_once __DIR__ . '/alert_helper.php';

if (!Auth::check()) {
    $redirect = urlencode($_SERVER['REQUEST_URI'] ?? 'index.php');
    header('Location: login.php?redirect=' . $redirect);
    exit;
}

$currentAdmin = Auth::user();
