<?php
/**
 * Projects CRUD & Screenshot Manager
 * Bajrang Kumar Sarraf - Cybersecurity Specialist Portfolio
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/auth_guard.php';

$pdo = Database::getConnection();

$action = $_GET['action'] ?? 'list';
$projectId = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Handle Project Deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!CSRF::verify($csrfToken)) {
        set_flash('danger', 'Security token expired. Please retry.');
        header('Location: projects.php');
        exit;
    }

    $delId = (int)($_POST['id'] ?? 0);
    if ($delId > 0) {
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = :id");
        $stmt->execute(['id' => $delId]);
        set_flash('success', 'Project removed successfully.');
    }
    header('Location: projects.php');
    exit;
}

// Handle Add / Edit Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && in_array($_POST['action'], ['create', 'update'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!CSRF::verify($csrfToken)) {
        set_flash('danger', 'Security validation failed (CSRF mismatch).');
        header('Location: projects.php');
        exit;
    }

    $title = trim((string)($_POST['title'] ?? ''));
    $slug = trim((string)($_POST['slug'] ?? ''));
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    }
    $category = trim((string)($_POST['category'] ?? 'Web Development'));
    $description = trim((string)($_POST['description'] ?? ''));
    $liveUrl = trim((string)($_POST['live_url'] ?? '')) ?: null;
    $githubUrl = trim((string)($_POST['github_url'] ?? '')) ?: null;
    $displayOrder = (int)($_POST['display_order'] ?? 0);
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;

    $imagePath = $_POST['existing_image'] ?? 'assets/img/profile-cyber.svg';

    // Handle Image Upload if provided
    if (!empty($_FILES['image']['name'])) {
        try {
            $imagePath = FileUploader::upload($_FILES['image'], 'projects', false);
        } catch (Throwable $e) {
            set_flash('danger', 'Project screenshot upload failed: ' . $e->getMessage());
            header('Location: projects.php');
            exit;
        }
    }

    try {
        if ($_POST['action'] === 'create') {
            $stmt = $pdo->prepare("
                INSERT INTO projects (title, slug, category, description, live_url, github_url, image_path, display_order, is_featured)
                VALUES (:title, :slug, :category, :description, :live_url, :github_url, :image_path, :display_order, :is_featured)
            ");
            $stmt->execute([
                'title' => $title,
                'slug' => $slug,
                'category' => $category,
                'description' => $description,
                'live_url' => $liveUrl,
                'github_url' => $githubUrl,
                'image_path' => $imagePath,
                'display_order' => $displayOrder,
                'is_featured' => $isFeatured
            ]);
            set_flash('success', 'New project added to portfolio successfully.');
        } else {
            $editId = (int)($_POST['id'] ?? 0);
            $stmt = $pdo->prepare("
                UPDATE projects SET 
                    title = :title,
                    slug = :slug,
                    category = :category,
                    description = :description,
                    live_url = :live_url,
                    github_url = :github_url,
                    image_path = :image_path,
                    display_order = :display_order,
                    is_featured = :is_featured
                WHERE id = :id
            ");
            $stmt->execute([
                'title' => $title,
                'slug' => $slug,
                'category' => $category,
                'description' => $description,
                'live_url' => $liveUrl,
                'github_url' => $githubUrl,
                'image_path' => $imagePath,
                'display_order' => $displayOrder,
                'is_featured' => $isFeatured,
                'id' => $editId
            ]);
            set_flash('success', 'Project details updated successfully.');
        }
    } catch (Throwable $e) {
        set_flash('danger', 'Database error: ' . $e->getMessage());
    }

    header('Location: projects.php');
    exit;
}

// Fetch single project for editing if requested
$projectToEdit = null;
if ($action === 'edit' && $projectId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $projectId]);
    $projectToEdit = $stmt->fetch();
}

// Fetch all projects for listing
$allProjects = $pdo->query("SELECT * FROM projects ORDER BY display_order ASC, id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects Portfolio CRUD | SEC_CMS</title>
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
                    <span class="text-emerald-400 font-bold">PROJECTS_PORTFOLIO_CRUD</span>
                </div>
            </div>
            <a href="../index.php#projects" target="_blank" class="text-xs font-mono text-cyan-400 hover:text-cyan-300">
                <i class="fa-solid fa-eye mr-1"></i> Preview Showcase
            </a>
        </header>

        <!-- Page Body -->
        <main class="p-6 lg:p-8 space-y-8 flex-grow">
            
            <?= render_alerts() ?>

            <!-- Action Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-4">
                <div>
                    <h1 class="text-2xl font-bold font-mono text-white">Project Case Studies & Hardware Tooling</h1>
                    <p class="text-xs font-mono text-slate-400 mt-1">Manage web platforms, hardware security instruments, and e-commerce portals.</p>
                </div>
                <div>
                    <?php if ($action === 'list'): ?>
                        <a href="projects.php?action=create" class="px-4 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-mono font-bold text-xs flex items-center gap-2 transition-all">
                            <i class="fa-solid fa-plus"></i>
                            <span>Create New Project</span>
                        </a>
                    <?php else: ?>
                        <a href="projects.php" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-mono text-xs flex items-center gap-2 transition-all">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Back to Project List</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($action === 'create' || ($action === 'edit' && $projectToEdit)): ?>
                <!-- CREATE / EDIT FORM -->
                <div class="admin-card p-6 sm:p-8 max-w-4xl">
                    <h3 class="text-sm font-mono uppercase tracking-wider text-emerald-400 font-bold border-b border-slate-800 pb-3 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-pen-nib"></i>
                        <span><?= $action === 'create' ? 'Register New Case Study' : 'Edit Project: ' . htmlspecialchars($projectToEdit['title']) ?></span>
                    </h3>

                    <form action="projects.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <?= CSRF::field() ?>
                        <input type="hidden" name="action" value="<?= $action === 'create' ? 'create' : 'update' ?>">
                        <?php if ($action === 'edit'): ?>
                            <input type="hidden" name="id" value="<?= $projectToEdit['id'] ?>">
                            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($projectToEdit['image_path']) ?>">
                        <?php endif; ?>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="title" class="block text-xs font-mono text-slate-300 mb-2">Project Title</label>
                                <input type="text" id="title" name="title" required value="<?= htmlspecialchars($projectToEdit['title'] ?? '') ?>" placeholder="e.g. ESP8266 Wi-Fi Security Tool" class="admin-input font-mono text-xs">
                            </div>
                            <div>
                                <label for="slug" class="block text-xs font-mono text-slate-300 mb-2">URL Slug (Unique)</label>
                                <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($projectToEdit['slug'] ?? '') ?>" placeholder="e.g. esp8266-wifi-tool (leave blank to auto-generate)" class="admin-input font-mono text-xs">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <div>
                                <label for="category" class="block text-xs font-mono text-slate-300 mb-2">Classification Category</label>
                                <select id="category" name="category" class="admin-input font-mono text-xs">
                                    <?php 
                                    $cats = ['Hardware & Security', 'Web Development', 'E-Commerce', 'Corporate Consultancy', 'Non-Profit / NGO'];
                                    foreach ($cats as $c): 
                                    ?>
                                        <option value="<?= $c ?>" <?= ($projectToEdit['category'] ?? '') === $c ? 'selected' : '' ?>><?= $c ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="display_order" class="block text-xs font-mono text-slate-300 mb-2">Display Sort Order</label>
                                <input type="number" id="display_order" name="display_order" value="<?= (int)($projectToEdit['display_order'] ?? 0) ?>" class="admin-input font-mono text-xs">
                            </div>
                            <div class="flex items-center pt-6">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="is_featured" value="1" <?= !empty($projectToEdit['is_featured']) ? 'checked' : '' ?> class="w-4 h-4 rounded border-slate-700 text-emerald-500 focus:ring-0">
                                    <span class="text-xs font-mono text-slate-300">Highlight as Featured</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-xs font-mono text-slate-300 mb-2">Comprehensive Architecture & Threat Mitigation Description</label>
                            <textarea id="description" name="description" rows="5" required class="admin-input font-sans text-xs leading-relaxed" placeholder="Describe architectural implementation, protocols audited, vulnerabilities mitigated..."><?= htmlspecialchars($projectToEdit['description'] ?? '') ?></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="live_url" class="block text-xs font-mono text-slate-300 mb-2">Live Production Target URL</label>
                                <input type="url" id="live_url" name="live_url" value="<?= htmlspecialchars($projectToEdit['live_url'] ?? '') ?>" placeholder="https://news4nepal.com" class="admin-input font-mono text-xs">
                            </div>
                            <div>
                                <label for="github_url" class="block text-xs font-mono text-slate-300 mb-2">GitHub / Lab Repository URL</label>
                                <input type="url" id="github_url" name="github_url" value="<?= htmlspecialchars($projectToEdit['github_url'] ?? '') ?>" placeholder="https://github.com/bajrang-sarraf" class="admin-input font-mono text-xs">
                            </div>
                        </div>

                        <!-- Image Upload Dropzone -->
                        <div>
                            <label class="block text-xs font-mono text-slate-300 mb-2">Project Screenshot / Technical Graphic</label>
                            <div class="dropzone-box">
                                <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.svg" class="hidden">
                                <?php if (!empty($projectToEdit['image_path'])): ?>
                                    <img src="../<?= htmlspecialchars($projectToEdit['image_path']) ?>" alt="Project Screenshot" class="preview-thumbnail mx-auto w-36 h-24 rounded-lg object-contain border border-slate-700 p-1 mb-2">
                                <?php endif; ?>
                                <span class="text-xs font-mono text-slate-400 block">Click or Drop new graphic to upload</span>
                                <span class="text-[10px] text-slate-500 font-mono">JPG, PNG, WEBP, SVG (Max 8MB)</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-800 flex justify-end gap-3">
                            <a href="projects.php" class="px-5 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 font-mono text-xs">Cancel</a>
                            <button type="submit" class="px-6 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-mono font-bold text-xs uppercase tracking-wider transition-all">
                                <i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Project Case Study
                            </button>
                        </div>
                    </form>
                </div>

            <?php else: ?>
                <!-- PROJECTS LIST TABLE -->
                <div class="admin-card overflow-hidden">
                    <div class="p-5 border-b border-slate-800 flex items-center justify-between">
                        <span class="font-mono text-xs uppercase tracking-wider text-slate-400">// REPOSITORY CATALOG (<?= count($allProjects) ?> Total)</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="admin-table font-mono text-xs">
                            <thead>
                                <tr>
                                    <th>Thumbnail</th>
                                    <th>Project Title</th>
                                    <th>Category</th>
                                    <th>Live Target</th>
                                    <th>Status</th>
                                    <th>Order</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allProjects as $proj): ?>
                                    <tr>
                                        <td class="w-16">
                                            <img src="../<?= htmlspecialchars($proj['image_path']) ?>" alt="Thumbnail" class="w-12 h-10 object-contain rounded bg-slate-900 border border-slate-800 p-0.5">
                                        </td>
                                        <td>
                                            <div class="font-bold text-white"><?= htmlspecialchars($proj['title']) ?></div>
                                            <span class="text-[10px] text-slate-500"><?= htmlspecialchars($proj['slug']) ?></span>
                                        </td>
                                        <td>
                                            <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300 text-[10px] border border-slate-700">
                                                <?= htmlspecialchars($proj['category']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($proj['live_url'])): ?>
                                                <a href="<?= htmlspecialchars($proj['live_url']) ?>" target="_blank" class="text-emerald-400 hover:underline">
                                                    <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Live
                                                </a>
                                            <?php elseif (!empty($proj['github_url'])): ?>
                                                <a href="<?= htmlspecialchars($proj['github_url']) ?>" target="_blank" class="text-cyan-400 hover:underline">
                                                    <i class="fa-brands fa-github mr-1"></i> Lab Code
                                                </a>
                                            <?php else: ?>
                                                <span class="text-slate-500 italic">Internal Lab</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($proj['is_featured'])): ?>
                                                <span class="px-2 py-0.5 rounded bg-amber-500/15 border border-amber-500/40 text-amber-300 text-[10px] font-bold">Featured</span>
                                            <?php else: ?>
                                                <span class="text-slate-500 text-[10px]">Standard</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= (int)$proj['display_order'] ?></td>
                                        <td class="text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="projects.php?action=edit&id=<?= $proj['id'] ?>" class="p-1.5 rounded bg-slate-800 text-slate-300 hover:text-white" title="Edit">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <form action="projects.php" method="POST" class="inline confirm-delete-form">
                                                    <?= CSRF::field() ?>
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?= $proj['id'] ?>">
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
            <?php endif; ?>

        </main>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>
