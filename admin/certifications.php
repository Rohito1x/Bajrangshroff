<?php
/**
 * Certifications & Credentials Management Controller
 * Bajrang Kumar Sarraf - Cybersecurity Specialist Portfolio
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/auth_guard.php';

$pdo = Database::getConnection();

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!CSRF::verify($csrfToken)) {
        set_flash('danger', 'Security validation failed.');
        header('Location: certifications.php');
        exit;
    }

    $delId = (int)($_POST['id'] ?? 0);
    if ($delId > 0) {
        $stmt = $pdo->prepare("DELETE FROM certifications WHERE id = :id");
        $stmt->execute(['id' => $delId]);
        set_flash('success', 'Certification record deleted.');
    }
    header('Location: certifications.php');
    exit;
}

// Handle Create / Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && in_array($_POST['action'], ['create', 'update'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!CSRF::verify($csrfToken)) {
        set_flash('danger', 'Security validation failed (CSRF mismatch).');
        header('Location: certifications.php');
        exit;
    }

    $title = trim((string)($_POST['title'] ?? ''));
    $issuer = trim((string)($_POST['issuer'] ?? ''));
    $issueDate = trim((string)($_POST['issue_date'] ?? 'Certified'));
    $credentialUrl = trim((string)($_POST['credential_url'] ?? '')) ?: null;

    $badgePath = $_POST['existing_badge'] ?? 'assets/img/profile-cyber.svg';

    if (!empty($_FILES['badge_image']['name'])) {
        try {
            $badgePath = FileUploader::upload($_FILES['badge_image'], 'profile', false);
        } catch (Throwable $e) {
            set_flash('danger', 'Badge image upload failed: ' . $e->getMessage());
            header('Location: certifications.php');
            exit;
        }
    }

    try {
        if ($_POST['action'] === 'create') {
            $stmt = $pdo->prepare("INSERT INTO certifications (title, issuer, issue_date, credential_url, badge_image_path) VALUES (:title, :issuer, :issue_date, :credential_url, :badge)");
            $stmt->execute([
                'title' => $title,
                'issuer' => $issuer,
                'issue_date' => $issueDate,
                'credential_url' => $credentialUrl,
                'badge' => $badgePath
            ]);
            set_flash('success', 'New certification credential cataloged.');
        } else {
            $id = (int)($_POST['id'] ?? 0);
            $stmt = $pdo->prepare("UPDATE certifications SET title = :title, issuer = :issuer, issue_date = :issue_date, credential_url = :credential_url, badge_image_path = :badge WHERE id = :id");
            $stmt->execute([
                'title' => $title,
                'issuer' => $issuer,
                'issue_date' => $issueDate,
                'credential_url' => $credentialUrl,
                'badge' => $badgePath,
                'id' => $id
            ]);
            set_flash('success', 'Certification details updated.');
        }
    } catch (Throwable $e) {
        set_flash('danger', 'Database error: ' . $e->getMessage());
    }

    header('Location: certifications.php');
    exit;
}

$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$certToEdit = null;
if ($editId) {
    $stmt = $pdo->prepare("SELECT * FROM certifications WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $editId]);
    $certToEdit = $stmt->fetch();
}

$certifications = $pdo->query("SELECT * FROM certifications ORDER BY id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Certifications | SEC_CMS</title>
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
                    <span class="text-emerald-400 font-bold">CERTIFICATIONS_CREDENTIALS</span>
                </div>
            </div>
            <a href="../index.php#certifications" target="_blank" class="text-xs font-mono text-cyan-400 hover:text-cyan-300">
                <i class="fa-solid fa-eye mr-1"></i> Preview Credentials
            </a>
        </header>

        <!-- Page Body -->
        <main class="p-6 lg:p-8 space-y-8 flex-grow">
            
            <?= render_alerts() ?>

            <div class="border-b border-slate-800 pb-4">
                <h1 class="text-2xl font-bold font-mono text-white">Certifications & Formal Accreditations</h1>
                <p class="text-xs font-mono text-slate-400 mt-1">Manage RHCSA, ethical hacking, and advanced academic certifications.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Form -->
                <div class="lg:col-span-5 admin-card p-6">
                    <h3 class="text-sm font-mono uppercase tracking-wider text-emerald-400 font-bold border-b border-slate-800 pb-3 mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-award"></i>
                        <span><?= $certToEdit ? 'Modify Certification' : 'Register New Certification' ?></span>
                    </h3>

                    <form action="certifications.php" method="POST" enctype="multipart/form-data" class="space-y-5">
                        <?= CSRF::field() ?>
                        <input type="hidden" name="action" value="<?= $certToEdit ? 'update' : 'create' ?>">
                        <?php if ($certToEdit): ?>
                            <input type="hidden" name="id" value="<?= $certToEdit['id'] ?>">
                            <input type="hidden" name="existing_badge" value="<?= htmlspecialchars($certToEdit['badge_image_path'] ?? '') ?>">
                        <?php endif; ?>

                        <div>
                            <label for="title" class="block text-xs font-mono text-slate-300 mb-2">Certification Title</label>
                            <input type="text" id="title" name="title" required value="<?= htmlspecialchars($certToEdit['title'] ?? '') ?>" placeholder="e.g. Red Hat Certified System Administrator (RHCSA)" class="admin-input font-mono text-xs">
                        </div>

                        <div>
                            <label for="issuer" class="block text-xs font-mono text-slate-300 mb-2">Issuing Organization</label>
                            <input type="text" id="issuer" name="issuer" required value="<?= htmlspecialchars($certToEdit['issuer'] ?? '') ?>" placeholder="e.g. Red Hat / IT Security College" class="admin-input font-mono text-xs">
                        </div>

                        <div>
                            <label for="issue_date" class="block text-xs font-mono text-slate-300 mb-2">Issue Date / Badge Label</label>
                            <input type="text" id="issue_date" name="issue_date" required value="<?= htmlspecialchars($certToEdit['issue_date'] ?? 'Certified') ?>" placeholder="e.g. Certified / Elite Certified" class="admin-input font-mono text-xs">
                        </div>

                        <div>
                            <label for="credential_url" class="block text-xs font-mono text-slate-300 mb-2">Online Verification Link (Optional)</label>
                            <input type="url" id="credential_url" name="credential_url" value="<?= htmlspecialchars($certToEdit['credential_url'] ?? '') ?>" placeholder="https://..." class="admin-input font-mono text-xs">
                        </div>

                        <div>
                            <label class="block text-xs font-mono text-slate-300 mb-2">Badge Graphic (Optional)</label>
                            <div class="dropzone-box">
                                <input type="file" name="badge_image" accept=".jpg,.jpeg,.png,.webp,.svg" class="hidden">
                                <?php if (!empty($certToEdit['badge_image_path'])): ?>
                                    <img src="../<?= htmlspecialchars($certToEdit['badge_image_path']) ?>" alt="Badge" class="preview-thumbnail mx-auto w-16 h-16 rounded object-contain mb-2">
                                <?php endif; ?>
                                <span class="text-xs font-mono text-slate-400 block">Click or Drop badge image</span>
                            </div>
                        </div>

                        <div class="pt-2 flex items-center justify-between">
                            <?php if ($certToEdit): ?>
                                <a href="certifications.php" class="text-xs font-mono text-slate-400 hover:text-white">Cancel Edit</a>
                            <?php else: ?>
                                <span></span>
                            <?php endif; ?>
                            <button type="submit" class="px-5 py-2.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-mono font-bold text-xs uppercase tracking-wider transition-all">
                                <i class="fa-solid fa-floppy-disk mr-1"></i> <?= $certToEdit ? 'Save Changes' : 'Catalog Cert' ?>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right: List -->
                <div class="lg:col-span-7 admin-card overflow-hidden">
                    <div class="p-5 border-b border-slate-800 flex items-center justify-between">
                        <span class="font-mono text-xs uppercase tracking-wider text-slate-400">// VERIFIED CREDENTIALS (<?= count($certifications) ?> Total)</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="admin-table font-mono text-xs">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Issuer</th>
                                    <th>Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($certifications as $c): ?>
                                    <tr>
                                        <td>
                                            <div class="font-bold text-white"><?= htmlspecialchars($c['title']) ?></div>
                                            <?php if (!empty($c['credential_url'])): ?>
                                                <a href="<?= htmlspecialchars($c['credential_url']) ?>" target="_blank" class="text-[11px] text-cyan-400 hover:underline">
                                                    <i class="fa-solid fa-link text-[10px]"></i> Verify
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-slate-300"><?= htmlspecialchars($c['issuer']) ?></td>
                                        <td>
                                            <span class="px-2 py-0.5 rounded bg-emerald-500/15 border border-emerald-500/40 text-emerald-300 text-[10px] font-bold">
                                                <?= htmlspecialchars($c['issue_date']) ?>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="certifications.php?edit=<?= $c['id'] ?>" class="p-1.5 rounded bg-slate-800 text-slate-300 hover:text-white" title="Edit">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <form action="certifications.php" method="POST" class="inline confirm-delete-form">
                                                    <?= CSRF::field() ?>
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                                    <button type="submit" class="p-1.5 rounded bg-rose-500/10 text-rose-400 hover:bg-rose-500/20" title="Delete">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </main>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>
