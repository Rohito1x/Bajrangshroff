<?php
/**
 * Hardened Admin Login Authentication Portal
 * Bajrang Kumar Sarraf - Cybersecurity Specialist Portfolio
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/includes/alert_helper.php';

// If already authenticated, redirect to dashboard
if (Auth::check()) {
    header('Location: index.php');
    exit;
}

$error = '';
$redirect = $_GET['redirect'] ?? 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. CSRF Token Verification
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!CSRF::verify($csrfToken)) {
        $error = 'Security validation failed (Invalid CSRF Token). Please reload and try again.';
    } else {
        // 2. Process Credentials
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        $result = Auth::login($username, $password);

        if ($result['success']) {
            // Validate redirect URL against open redirection attacks
            $safeRedirect = 'index.php';
            if (!empty($redirect) && !str_starts_with($redirect, 'http://') && !str_starts_with($redirect, 'https://') && !str_starts_with($redirect, '//')) {
                $safeRedirect = $redirect;
            }
            header('Location: ' . $safeRedirect);
            exit;
        } else {
            $error = $result['error'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEC_CMS Authentication | Bajrang Kumar Sarraf</title>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="bg-[#080c14] text-slate-100 flex items-center justify-center min-h-screen p-4 selection:bg-emerald-500 selection:text-black">

    <div class="w-full max-w-md">
        
        <!-- Header Monogram -->
        <div class="text-center mb-8">
            <div class="inline-flex w-14 h-14 rounded-2xl bg-slate-900 border border-emerald-500/50 items-center justify-center text-emerald-400 font-mono font-bold text-xl shadow-[0_0_25px_rgba(16,185,129,0.3)] mb-4">
                &gt;_
            </div>
            <h1 class="text-2xl font-bold font-mono tracking-tight text-white">SEC_CMS GATEWAY</h1>
            <p class="text-xs font-mono text-slate-400 mt-1">Authorized Personnel Authentication Node</p>
        </div>

        <!-- Auth Card -->
        <div class="admin-card p-8 border border-slate-800 shadow-2xl relative">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-6">
                <span class="text-xs font-mono uppercase tracking-wider text-slate-400">// CREDENTIAL_CHALLENGE</span>
                <span class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 text-[10px] font-mono">TLS_STRICT</span>
            </div>

            <?php if (!empty($error)): ?>
                <div class="p-4 mb-6 rounded-lg bg-rose-500/15 border border-rose-500/40 text-rose-300 text-xs font-mono flex items-start gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-base mt-0.5"></i>
                    <div>
                        <strong class="block">Authentication Refused</strong>
                        <span><?= htmlspecialchars($error, ENT_QUOTES | ENT_HTML5, 'UTF-8') ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <form action="login.php<?= !empty($redirect) ? '?redirect=' . htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8') : '' ?>" method="POST" class="space-y-5" autocomplete="off">
                <?= CSRF::field() ?>

                <div>
                    <label for="username" class="block text-xs font-mono text-slate-300 mb-2">Username / Operator ID</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 text-xs">
                            <i class="fa-solid fa-user-shield"></i>
                        </span>
                        <input type="text" id="username" name="username" required autofocus placeholder="bajrang_admin" class="admin-input pl-10 font-mono text-xs">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-mono text-slate-300 mb-2">Security Passphrase</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 text-xs">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" id="password" name="password" required placeholder="••••••••••••" class="admin-input pl-10 font-mono text-xs">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 px-4 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-mono font-bold text-xs tracking-wider uppercase transition-all shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:shadow-[0_0_25px_rgba(16,185,129,0.5)] flex items-center justify-center gap-2">
                        <i class="fa-solid fa-fingerprint text-sm"></i>
                        <span>Authenticate Session</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-slate-800/80 text-center text-[11px] font-mono text-slate-500">
                Rate-limited. Consecutive failed attempts trigger a 15-minute lock.
            </div>
        </div>

        <!-- Back to Website -->
        <div class="text-center mt-6">
            <a href="../index.php" class="text-xs font-mono text-slate-400 hover:text-emerald-400 transition-colors">
                &larr; Return to Public Portfolio
            </a>
        </div>

    </div>

</body>
</html>
