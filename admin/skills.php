<?php
/**
 * Skills Matrix Management Controller
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
        header('Location: skills.php');
        exit;
    }

    $delId = (int)($_POST['id'] ?? 0);
    if ($delId > 0) {
        $stmt = $pdo->prepare("DELETE FROM skills WHERE id = :id");
        $stmt->execute(['id' => $delId]);
        set_flash('success', 'Skill entry deleted.');
    }
    header('Location: skills.php');
    exit;
}

// Handle Create / Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && in_array($_POST['action'], ['create', 'update'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!CSRF::verify($csrfToken)) {
        set_flash('danger', 'Security validation failed (CSRF mismatch).');
        header('Location: skills.php');
        exit;
    }

    $name = trim((string)($_POST['name'] ?? ''));
    $category = trim((string)($_POST['category'] ?? 'Offensive Security'));
    $proficiency = max(1, min(100, (int)($_POST['proficiency_pct'] ?? 85)));
    $displayOrder = (int)($_POST['display_order'] ?? 0);

    try {
        if ($_POST['action'] === 'create') {
            $stmt = $pdo->prepare("INSERT INTO skills (name, category, proficiency_pct, display_order) VALUES (:name, :category, :proficiency, :order)");
            $stmt->execute([
                'name' => $name,
                'category' => $category,
                'proficiency' => $proficiency,
                'order' => $displayOrder
            ]);
            set_flash('success', 'New technical proficiency added.');
        } else {
            $id = (int)($_POST['id'] ?? 0);
            $stmt = $pdo->prepare("UPDATE skills SET name = :name, category = :category, proficiency_pct = :proficiency, display_order = :order WHERE id = :id");
            $stmt->execute([
                'name' => $name,
                'category' => $category,
                'proficiency' => $proficiency,
                'order' => $displayOrder,
                'id' => $id
            ]);
            set_flash('success', 'Skill rating updated successfully.');
        }
    } catch (Throwable $e) {
        set_flash('danger', 'Database error: ' . $e->getMessage());
    }

    header('Location: skills.php');
    exit;
}

$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$skillToEdit = null;
if ($editId) {
    $stmt = $pdo->prepare("SELECT * FROM skills WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $editId]);
    $skillToEdit = $stmt->fetch();
}

// Fetch all skills ordered by category & display_order
$skills = $pdo->query("SELECT * FROM skills ORDER BY category ASC, display_order ASC, id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Skills Matrix | SEC_CMS</title>
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
                    <span class="text-emerald-400 font-bold">SKILLS_MATRIX_MANAGER</span>
                </div>
            </div>
            <a href="../index.php#skills" target="_blank" class="text-xs font-mono text-cyan-400 hover:text-cyan-300">
                <i class="fa-solid fa-eye mr-1"></i> Preview Skills
            </a>
        </header>

        <!-- Page Body -->
        <main class="p-6 lg:p-8 space-y-8 flex-grow">
            
            <?= render_alerts() ?>

            <div class="border-b border-slate-800 pb-4">
                <h1 class="text-2xl font-bold font-mono text-white">Technical Arsenal & Proficiencies</h1>
                <p class="text-xs font-mono text-slate-400 mt-1">Manage offensive security tools, systems administration, and web engineering ratings.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Form for Add / Edit -->
                <div class="lg:col-span-5 admin-card p-6">
                    <h3 class="text-sm font-mono uppercase tracking-wider text-emerald-400 font-bold border-b border-slate-800 pb-3 mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-plus-minus"></i>
                        <span><?= $skillToEdit ? 'Modify Skill Rating' : 'Add New Skill Competency' ?></span>
                    </h3>

                    <form action="skills.php" method="POST" class="space-y-5">
                        <?= CSRF::field() ?>
                        <input type="hidden" name="action" value="<?= $skillToEdit ? 'update' : 'create' ?>">
                        <?php if ($skillToEdit): ?>
                            <input type="hidden" name="id" value="<?= $skillToEdit['id'] ?>">
                        <?php endif; ?>

                        <div>
                            <label for="name" class="block text-xs font-mono text-slate-300 mb-2">Skill / Tool Name</label>
                            <input type="text" id="name" name="name" required value="<?= htmlspecialchars($skillToEdit['name'] ?? '') ?>" placeholder="e.g. Burp Suite Professional" class="admin-input font-mono text-xs">
                        </div>

                        <div>
                            <label for="category" class="block text-xs font-mono text-slate-300 mb-2">Classification Category</label>
                            <select id="category" name="category" class="admin-input font-mono text-xs">
                                <?php 
                                $categories = ['Offensive Security', 'Defensive Security', 'Networking & Systems', 'Web Engineering', 'Cloud & IoT'];
                                foreach ($categories as $cat): 
                                ?>
                                    <option value="<?= $cat ?>" <?= ($skillToEdit['category'] ?? '') === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <div class="flex justify-between items-center text-xs font-mono mb-2">
                                <label for="proficiency_pct" class="text-slate-300">Proficiency Rating (Percentage)</label>
                                <span id="prof-val" class="text-emerald-400 font-bold"><?= (int)($skillToEdit['proficiency_pct'] ?? 85) ?>%</span>
                            </div>
                            <input type="range" id="proficiency_pct" name="proficiency_pct" min="10" max="100" value="<?= (int)($skillToEdit['proficiency_pct'] ?? 85) ?>" data-sync-target="prof-val" class="w-full accent-emerald-500 cursor-pointer">
                        </div>

                        <div>
                            <label for="display_order" class="block text-xs font-mono text-slate-300 mb-2">Display Sort Order</label>
                            <input type="number" id="display_order" name="display_order" value="<?= (int)($skillToEdit['display_order'] ?? 0) ?>" class="admin-input font-mono text-xs">
                        </div>

                        <div class="pt-2 flex items-center justify-between">
                            <?php if ($skillToEdit): ?>
                                <a href="skills.php" class="text-xs font-mono text-slate-400 hover:text-white">Cancel Edit</a>
                            <?php else: ?>
                                <span></span>
                            <?php endif; ?>
                            <button type="submit" class="px-5 py-2.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-mono font-bold text-xs uppercase tracking-wider transition-all">
                                <i class="fa-solid fa-floppy-disk mr-1"></i> <?= $skillToEdit ? 'Save Changes' : 'Add Skill' ?>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right: Skills Table -->
                <div class="lg:col-span-7 admin-card overflow-hidden">
                    <div class="p-5 border-b border-slate-800 flex items-center justify-between">
                        <span class="font-mono text-xs uppercase tracking-wider text-slate-400">// CURRENT ARSENAL (<?= count($skills) ?> Total)</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="admin-table font-mono text-xs">
                            <thead>
                                <tr>
                                    <th>Skill Name</th>
                                    <th>Category</th>
                                    <th>Proficiency</th>
                                    <th>Order</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($skills as $s): ?>
                                    <tr>
                                        <td class="font-bold text-white"><?= htmlspecialchars($s['name']) ?></td>
                                        <td>
                                            <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300 text-[10px] border border-slate-700">
                                                <?= htmlspecialchars($s['category']) ?>
                                            </span>
                                        </td>
                                        <td class="w-36">
                                            <div class="flex items-center gap-2">
                                                <div class="flex-grow bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                                    <div class="bg-gradient-to-r from-emerald-500 to-cyan-500 h-1.5 rounded-full" style="width: <?= (int)$s['proficiency_pct'] ?>%"></div>
                                                </div>
                                                <span class="text-emerald-400 font-bold text-[11px]"><?= (int)$s['proficiency_pct'] ?>%</span>
                                            </div>
                                        </td>
                                        <td><?= (int)$s['display_order'] ?></td>
                                        <td class="text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="skills.php?edit=<?= $s['id'] ?>" class="p-1.5 rounded bg-slate-800 text-slate-300 hover:text-white" title="Edit">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <form action="skills.php" method="POST" class="inline confirm-delete-form">
                                                    <?= CSRF::field() ?>
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?= $s['id'] ?>">
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
