<?php
/**
 * Experience Timeline Management Controller
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
        header('Location: experiences.php');
        exit;
    }

    $delId = (int)($_POST['id'] ?? 0);
    if ($delId > 0) {
        $stmt = $pdo->prepare("DELETE FROM experiences WHERE id = :id");
        $stmt->execute(['id' => $delId]);
        set_flash('success', 'Timeline milestone removed.');
    }
    header('Location: experiences.php');
    exit;
}

// Handle Create / Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && in_array($_POST['action'], ['create', 'update'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!CSRF::verify($csrfToken)) {
        set_flash('danger', 'Security validation failed (CSRF mismatch).');
        header('Location: experiences.php');
        exit;
    }

    $company = trim((string)($_POST['company'] ?? ''));
    $role = trim((string)($_POST['role'] ?? ''));
    $duration = trim((string)($_POST['duration'] ?? ''));
    $displayOrder = (int)($_POST['display_order'] ?? 0);

    // Convert newline-separated bullet points to JSON array
    $rawBullets = trim((string)($_POST['bullet_points'] ?? ''));
    $bulletArray = array_values(array_filter(array_map('trim', explode("\n", $rawBullets))));
    $bulletJson = json_encode($bulletArray, JSON_UNESCAPED_SLASHES);

    try {
        if ($_POST['action'] === 'create') {
            $stmt = $pdo->prepare("INSERT INTO experiences (company, role, duration, bullet_points, display_order) VALUES (:company, :role, :duration, :bullet_points, :display_order)");
            $stmt->execute([
                'company' => $company,
                'role' => $role,
                'duration' => $duration,
                'bullet_points' => $bulletJson,
                'display_order' => $displayOrder
            ]);
            set_flash('success', 'New career milestone added to timeline.');
        } else {
            $id = (int)($_POST['id'] ?? 0);
            $stmt = $pdo->prepare("UPDATE experiences SET company = :company, role = :role, duration = :duration, bullet_points = :bullet_points, display_order = :display_order WHERE id = :id");
            $stmt->execute([
                'company' => $company,
                'role' => $role,
                'duration' => $duration,
                'bullet_points' => $bulletJson,
                'display_order' => $displayOrder,
                'id' => $id
            ]);
            set_flash('success', 'Experience milestone updated successfully.');
        }
    } catch (Throwable $e) {
        set_flash('danger', 'Database error: ' . $e->getMessage());
    }

    header('Location: experiences.php');
    exit;
}

$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$expToEdit = null;
if ($editId) {
    $stmt = $pdo->prepare("SELECT * FROM experiences WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $editId]);
    $expToEdit = $stmt->fetch();
}

$experiences = $pdo->query("SELECT * FROM experiences ORDER BY display_order ASC, id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Experience Timeline | SEC_CMS</title>
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
                    <span class="text-emerald-400 font-bold">EXPERIENCE_TIMELINE_MANAGER</span>
                </div>
            </div>
            <a href="../index.php#experience" target="_blank" class="text-xs font-mono text-cyan-400 hover:text-cyan-300">
                <i class="fa-solid fa-eye mr-1"></i> Preview Timeline
            </a>
        </header>

        <!-- Page Body -->
        <main class="p-6 lg:p-8 space-y-8 flex-grow">
            
            <?= render_alerts() ?>

            <div class="border-b border-slate-800 pb-4">
                <h1 class="text-2xl font-bold font-mono text-white">Career Experience & Timeline Milestones</h1>
                <p class="text-xs font-mono text-slate-400 mt-1">Manage professional positions, enterprise network administration, and VAPT instruction history.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Form -->
                <div class="lg:col-span-5 admin-card p-6">
                    <h3 class="text-sm font-mono uppercase tracking-wider text-emerald-400 font-bold border-b border-slate-800 pb-3 mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-pen-nib"></i>
                        <span><?= $expToEdit ? 'Modify Experience Milestone' : 'Add New Career Milestone' ?></span>
                    </h3>

                    <form action="experiences.php" method="POST" class="space-y-5">
                        <?= CSRF::field() ?>
                        <input type="hidden" name="action" value="<?= $expToEdit ? 'update' : 'create' ?>">
                        <?php if ($expToEdit): ?>
                            <input type="hidden" name="id" value="<?= $expToEdit['id'] ?>">
                        <?php endif; ?>

                        <div>
                            <label for="company" class="block text-xs font-mono text-slate-300 mb-2">Company / Enterprise Institution</label>
                            <input type="text" id="company" name="company" required value="<?= htmlspecialchars($expToEdit['company'] ?? '') ?>" placeholder="e.g. Centegic Technology Pvt. Ltd." class="admin-input font-mono text-xs">
                        </div>

                        <div>
                            <label for="role" class="block text-xs font-mono text-slate-300 mb-2">Role Title / Designation</label>
                            <input type="text" id="role" name="role" required value="<?= htmlspecialchars($expToEdit['role'] ?? '') ?>" placeholder="e.g. Cybersecurity Trainer / Junior VAPT Analyst" class="admin-input font-mono text-xs">
                        </div>

                        <div>
                            <label for="duration" class="block text-xs font-mono text-slate-300 mb-2">Duration Period</label>
                            <input type="text" id="duration" name="duration" required value="<?= htmlspecialchars($expToEdit['duration'] ?? '') ?>" placeholder="e.g. June 2026 – Sept 2026" class="admin-input font-mono text-xs">
                        </div>

                        <div>
                            <label for="bullet_points" class="block text-xs font-mono text-slate-300 mb-1">Key Deliverables & Responsibilities (1 per line)</label>
                            <span class="text-[10px] text-slate-500 font-mono block mb-2">Each line will become a bullet point in the timeline</span>
                            <?php 
                            $bulletText = '';
                            if ($expToEdit) {
                                $arr = json_decode($expToEdit['bullet_points'] ?: '[]', true) ?: [];
                                $bulletText = implode("\n", $arr);
                            }
                            ?>
                            <textarea id="bullet_points" name="bullet_points" rows="5" required class="admin-input font-sans text-xs leading-relaxed" placeholder="Conducted web & network VAPT...&#10;Prepared risk rating reports..."><?= htmlspecialchars($bulletText) ?></textarea>
                        </div>

                        <div>
                            <label for="display_order" class="block text-xs font-mono text-slate-300 mb-2">Display Sort Order</label>
                            <input type="number" id="display_order" name="display_order" value="<?= (int)($expToEdit['display_order'] ?? 0) ?>" class="admin-input font-mono text-xs">
                        </div>

                        <div class="pt-2 flex items-center justify-between">
                            <?php if ($expToEdit): ?>
                                <a href="experiences.php" class="text-xs font-mono text-slate-400 hover:text-white">Cancel Edit</a>
                            <?php else: ?>
                                <span></span>
                            <?php endif; ?>
                            <button type="submit" class="px-5 py-2.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-mono font-bold text-xs uppercase tracking-wider transition-all">
                                <i class="fa-solid fa-floppy-disk mr-1"></i> <?= $expToEdit ? 'Save Changes' : 'Add Milestone' ?>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right: Timeline List -->
                <div class="lg:col-span-7 space-y-4">
                    <div class="admin-card p-5 border-b border-slate-800 flex items-center justify-between">
                        <span class="font-mono text-xs uppercase tracking-wider text-slate-400">// CHRONOLOGICAL RECORD (<?= count($experiences) ?> Total)</span>
                    </div>

                    <?php foreach ($experiences as $exp): 
                        $bullets = json_decode($exp['bullet_points'] ?: '[]', true) ?: [];
                    ?>
                        <div class="admin-card p-6 relative">
                            <div class="flex items-start justify-between gap-4 border-b border-slate-800 pb-3 mb-3">
                                <div>
                                    <h4 class="text-base font-bold font-mono text-white"><?= htmlspecialchars($exp['role']) ?></h4>
                                    <p class="text-xs font-mono text-emerald-400"><?= htmlspecialchars($exp['company']) ?> &bull; <?= htmlspecialchars($exp['duration']) ?></p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="experiences.php?edit=<?= $exp['id'] ?>" class="p-1.5 rounded bg-slate-800 text-slate-300 hover:text-white" title="Edit">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <form action="experiences.php" method="POST" class="inline confirm-delete-form">
                                        <?= CSRF::field() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $exp['id'] ?>">
                                        <button type="submit" class="p-1.5 rounded bg-rose-500/10 text-rose-400 hover:bg-rose-500/20" title="Delete">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <ul class="space-y-1.5 text-xs text-slate-300 font-sans">
                                <?php foreach ($bullets as $b): ?>
                                    <li class="flex items-start gap-2">
                                        <i class="fa-solid fa-chevron-right text-emerald-400 text-[10px] mt-1 shrink-0"></i>
                                        <span><?= htmlspecialchars($b) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>

        </main>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>
