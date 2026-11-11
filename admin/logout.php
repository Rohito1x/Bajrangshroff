<?php
/**
 * Admin Session Termination Endpoint
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/security.php';

Auth::logout();

header('Location: login.php?msg=logged_out');
exit;
