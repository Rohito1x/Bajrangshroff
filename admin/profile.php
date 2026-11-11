<?php
/**
 * Profile & Bio Management Controller
 * Bajrang Kumar Sarraf - Cybersecurity Specialist Portfolio
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/auth_guard.php';

$pdo = Database::getConnection();

// Fetch Profile Settings
$profile = $pdo->query("SELECT * FROM profile_settings WHERE id = 1 LIMIT 1")->fetch() ?: [];

$error = '';
$success = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!CSRF::verify($csrfToken)) {
        set_flash('danger', 'Security validation failed (CSRF token mismatch).');
        header('Location: profile.php');
        exit;
    }

    $action = $_POST['action'] ?? 'update_profile';

    if ($action === 'update_profile') {
        $fullName = trim((string)($_POST['full_name'] ?? ''));
        $taglineRoles = trim((string)($_POST['tagline_roles'] ?? ''));
        $bio = trim((string)($_POST['bio'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $phone = trim((string)($_POST['phone'] ?? ''));
        $location = trim((string)($_POST['location'] ?? ''));

        $avatarPath = $profile['avatar_path'] ?? 'assets/img/profile-cyber.svg';
        $resumePath = $profile['resume_pdf_path'] ?? null;

        // Process Avatar Upload if provided
        if (!empty($_FILES['avatar']['name'])) {
            try {
                $avatarPath = FileUploader::upload($_FILES['avatar'], 'profile', false);
            } catch (Throwable $e) {
                set_flash('danger', 'Avatar upload failed: ' . $e->getMessage());
                header('Location: profile.php');
                exit;
            }
        }

        // Process Resume PDF Upload if provided
        if (!empty($_FILES['resume_pdf']['name'])) {
            try {
                $resumePath = FileUploader::upload($_FILES['resume_pdf'], 'documents', true);
            } catch (Throwable $e) {
                set_flash('danger', 'Resume upload failed: ' . $e->getMessage());
                header('Location: profile.php');
                exit;
            }
        }

        try {
            $stmt = $pdo->prepare("
                UPDATE profile_settings SET 
                    full_name = :full_name,
                    tagline_roles = :tagline_roles,
                    bio = :bio,
                    email = :email,
                    phone = :phone,
                    location = :location,
                    avatar_path = :avatar_path,
                    resume_pdf_path = :resume_pdf_path
                WHERE id = 1
            ");

            $stmt->execute([
                'full_name' => $fullName,
                'tagline_roles' => $taglineRoles,
                'bio' => $bio,
                'email' => $email,
                'phone' => $phone,
                'location' => $location,
                'avatar_path' => $avatarPath,
                'resume_pdf_path' => $resumePath
            ]);

            set_flash('success', 'Profile and identity settings updated successfully.');
            header('Location: profile.php');
            exit;
        } catch (Throwable $e) {
            set_flash('danger', 'Database error: ' . $e->getMessage());
            header('Location: profile.php');
            exit;
        }
    } elseif ($action === 'change_password') {
        $currentPass = (string)($_POST['current_password'] ?? '');
        $newPass = (string)($_POST['new_password'] ?? '');
        $confirmPass = (string)($_POST['confirm_password'] ?? '');

        if (empty($currentPass) || empty($newPass)) {
            set_flash('danger', 'All password fields are required.');
            header('Location: profile.php');
            exit;
        }

        if ($newPass !== $confirmPass) {
            set_flash('danger', 'New passwords do not match.');
            header('Location: profile.php');
            exit;
        }

        if (strlen($newPass) < 8) {
            set_flash('danger', 'New password must be at least 8 characters long.');
            header('Location: profile.php');
            exit;
        }

        // Verify current password
        $stmt = $pdo->prepare("SELECT password_hash FROM admins WHERE id = :id");
        $stmt->execute(['id' => Auth::id()]);
        $existingHash = (string)$stmt->fetchColumn();

        if (!password_verify($currentPass, $existingHash)) {
            set_flash('danger', 'Current password is incorrect.');
            header('Location: profile.php');
            exit;
        }

        // Update password
        $newHash = password_hash($newPass, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $pdo->prepare("UPDATE admins SET password_hash = :hash WHERE id = :id");
        $stmt->execute(['hash' => $newHash, 'id' => Auth::id()]);

        set_flash('success', 'Security passphrase updated successfully. Session credentials refreshed.');
        header('Location: profile.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Profile & Bio | SEC_CMS</title>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="bg-[#080c14] text-slate-100 flex min-h-screen">

    <!-- Navigation Sidebar -->
    <?php require_once __DIR__ . '/includes/nav.php'; ?>

    <!-- Main Content Area -->
    <div class="lg:pl-64 flex-grow flex flex-col min-w-0">
        
        <!-- Top Navbar -->
        <header class="h-18 px-6 lg:px-8 border-b border-slate-800 bg-[#090d17]/80 backdrop-blur-md sticky top-0 z-30 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button id="admin-sidebar-toggle" class="lg:hidden text-slate-400 hover:text-white p-2 rounded-lg bg-slate-900 border border-slate-800">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="flex items-center gap-2 font-mono text-xs">
                    <span class="text-slate-500">// CMS:</span>
                    <span class="text-emerald-400 font-bold">IDENTITY_&_PROFILE_SETTINGS</span>
                </div>
            </div>
            <a href="../index.php#about" target="_blank" class="text-xs font-mono text-cyan-400 hover:text-cyan-300">
                <i class="fa-solid fa-eye mr-1"></i> Preview About Section
            </a>
        </header>

        <!-- Page Body -->
        <main class="p-6 lg:p-8 space-y-8 flex-grow">
            
            <?= render_alerts() ?>

            <div class="border-b border-slate-800 pb-4 mb-6">
                <h1 class="text-2xl font-bold font-mono text-white">Profile, Dossier & Media Management</h1>
                <p class="text-xs font-mono text-slate-400 mt-1">Configure candidate credentials, bio, dynamic typing roles, and resume files.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Main Profile Form -->
                <div class="lg:col-span-8 admin-card p-6 sm:p-8">
                    <h3 class="text-sm font-mono uppercase tracking-wider text-emerald-400 font-bold border-b border-slate-800 pb-3 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-address-card"></i>
                        <span>Core Identity Parameters</span>
                    </h3>

                    <form action="profile.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <?= CSRF::field() ?>
                        <input type="hidden" name="action" value="update_profile">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="full_name" class="block text-xs font-mono text-slate-300 mb-2">Full Legal / Professional Name</label>
                                <input type="text" id="full_name" name="full_name" required value="<?= htmlspecialchars($profile['full_name'] ?? '') ?>" class="admin-input font-mono text-xs">
                            </div>
                            <div>
                                <label for="location" class="block text-xs font-mono text-slate-300 mb-2">Location Coordinates</label>
                                <input type="text" id="location" name="location" required value="<?= htmlspecialchars($profile['location'] ?? '') ?>" class="admin-input font-mono text-xs">
                            </div>
                        </div>

                        <!-- Tagline Roles for Typing Animation -->
                        <div>
                            <label for="tagline_roles" class="block text-xs font-mono text-slate-300 mb-1">
                                Dynamic Typing Roles (Comma-Separated for Hero Typing Animation)
                            </label>
                            <span class="text-[11px] text-slate-500 font-mono block mb-2">Controls the animated typing scrambler in the hero section</span>
                            <textarea id="tagline_roles" name="tagline_roles" rows="2" required class="admin-input font-mono text-xs"><?= htmlspecialchars($profile['tagline_roles'] ?? '') ?></textarea>
                        </div>

                        <!-- Bio Narrative -->
                        <div>
                            <label for="bio" class="block text-xs font-mono text-slate-300 mb-2">Professional Philosophy & Bio Narrative</label>
                            <textarea id="bio" name="bio" rows="5" required class="admin-input font-sans text-xs leading-relaxed"><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
                        </div>

                        <!-- Contact Channels -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="email" class="block text-xs font-mono text-slate-300 mb-2">Primary Public Email</label>
                                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($profile['email'] ?? '') ?>" class="admin-input font-mono text-xs">
                            </div>
                            <div>
                                <label for="phone" class="block text-xs font-mono text-slate-300 mb-2">Primary Phone / WhatsApp</label>
                                <input type="text" id="phone" name="phone" required value="<?= htmlspecialchars($profile['phone'] ?? '') ?>" class="admin-input font-mono text-xs">
                            </div>
                        </div>

                        <!-- Uploads Section -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-800">
                            <!-- Avatar Upload -->
                            <div>
                                <label class="block text-xs font-mono text-slate-300 mb-2">Profile Avatar / Shield Icon</label>
                                <div class="dropzone-box">
                                    <input type="file" name="avatar" accept=".jpg,.jpeg,.png,.webp,.svg" class="hidden">
                                    <img src="../<?= htmlspecialchars($profile['avatar_path'] ?? 'assets/img/profile-cyber.svg') ?>" alt="Current Avatar" class="preview-thumbnail mx-auto w-24 h-24 rounded-xl object-contain border border-slate-700 p-1 mb-2">
                                    <span class="text-xs font-mono text-slate-400 block">Click or Drop new image to change</span>
                                    <span class="text-[10px] text-slate-500 font-mono">JPG, PNG, WEBP, SVG (Max 8MB)</span>
                                </div>
                            </div>

                            <!-- Resume PDF Upload -->
                            <div>
                                <label class="block text-xs font-mono text-slate-300 mb-2">Curriculum Vitae (PDF Document)</label>
                                <div class="dropzone-box">
                                    <input type="file" name="resume_pdf" accept=".pdf" class="hidden">
                                    <div class="my-3 text-cyan-400 text-3xl">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </div>
                                    <span class="text-xs font-mono text-slate-400 block">Upload Updated Resume (PDF)</span>
                                    <?php if (!empty($profile['resume_pdf_path'])): ?>
                                        <a href="../<?= htmlspecialchars($profile['resume_pdf_path']) ?>" target="_blank" class="text-[11px] text-emerald-400 hover:underline font-mono block mt-1">
                                            <i class="fa-solid fa-download mr-1"></i> Download Existing CV
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-800 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-mono font-bold text-xs uppercase tracking-wider transition-all shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                                <i class="fa-solid fa-floppy-disk mr-2"></i> Save Profile Modifications
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right: Password & Security Credentials -->
                <div class="lg:col-span-4 admin-card p-6 sm:p-8 space-y-6">
                    <h3 class="text-sm font-mono uppercase tracking-wider text-rose-400 font-bold border-b border-slate-800 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Security Passphrase</span>
                    </h3>

                    <form action="profile.php" method="POST" class="space-y-4">
                        <?= CSRF::field() ?>
                        <input type="hidden" name="action" value="change_password">

                        <div>
                            <label for="current_password" class="block text-xs font-mono text-slate-300 mb-2">Current Passphrase</label>
                            <input type="password" id="current_password" name="current_password" required placeholder="••••••••••••" class="admin-input font-mono text-xs">
                        </div>

                        <div>
                            <label for="new_password" class="block text-xs font-mono text-slate-300 mb-2">New Passphrase</label>
                            <input type="password" id="new_password" name="new_password" required placeholder="Min. 8 characters" class="admin-input font-mono text-xs">
                        </div>

                        <div>
                            <label for="confirm_password" class="block text-xs font-mono text-slate-300 mb-2">Confirm New Passphrase</label>
                            <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm match" class="admin-input font-mono text-xs">
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-rose-500/20 border border-rose-500/40 hover:bg-rose-500/30 text-rose-300 font-mono font-bold text-xs uppercase transition-all">
                                <i class="fa-solid fa-key mr-2"></i> Rotate Passphrase
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </main>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>
