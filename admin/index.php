<?php
/**
 * Admin Dashboard & Metrics Overview
 * Bajrang Kumar Sarraf - Cybersecurity Specialist Portfolio
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/auth_guard.php';

$pdo = Database::getConnection();

// Fetch Metrics
$projectCount = (int)$pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$skillCount = (int)$pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();
$experienceCount = (int)$pdo->query("SELECT COUNT(*) FROM experiences")->fetchColumn();
$certCount = (int)$pdo->query("SELECT COUNT(*) FROM certifications")->fetchColumn();
$messageCount = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
$unreadCount = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'unread'")->fetchColumn();

// Fetch Animation Status
$anim = $pdo->query("SELECT * FROM animation_settings WHERE id = 1 LIMIT 1")->fetch() ?: [];

// Fetch Recent Messages
$recentMessages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Fetch Recent Projects
$recentProjects = $pdo->query("SELECT * FROM projects ORDER BY display_order ASC, id DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEC_CMS Metrics Dashboard | Bajrang Kumar Sarraf</title>
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
                    <span class="text-slate-500">// CONSOLE:</span>
                    <span class="text-emerald-400 font-bold">NODE_METRICS_OVERVIEW</span>
                </div>
            </div>

            <div class="flex items-center gap-4 font-mono text-xs">
                <span class="hidden sm:inline-flex items-center gap-1.5 text-slate-400">
                    <i class="fa-solid fa-server text-emerald-400 text-[10px]"></i>
                    <span>SQLite_Active</span>
                </span>
                <a href="logout.php" class="px-3 py-1.5 rounded-lg bg-rose-500/10 border border-rose-500/30 text-rose-300 hover:bg-rose-500/20 transition-colors">
                    <i class="fa-solid fa-power-off mr-1"></i> Sign Out
                </a>
            </div>
        </header>

        <!-- Page Body -->
        <main class="p-6 lg:p-8 space-y-8 flex-grow">
            
            <?= render_alerts() ?>

            <!-- Welcome Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800/80 pb-6">
                <div>
                    <h1 class="text-2xl font-bold font-mono text-white">System Command & Metrics Dashboard</h1>
                    <p class="text-xs font-mono text-slate-400 mt-1">
                        Operating under authenticated authority of <strong class="text-emerald-400"><?= htmlspecialchars($currentAdmin['username']) ?></strong> &bull; Birgunj, Nepal
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="animations.php" class="px-4 py-2 rounded-lg bg-cyan-500/10 border border-cyan-500/30 hover:border-cyan-400 text-cyan-300 font-mono text-xs flex items-center gap-2 transition-all">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>Animation Controller</span>
                    </a>
                    <a href="projects.php" class="px-4 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-mono font-bold text-xs flex items-center gap-2 transition-all">
                        <i class="fa-solid fa-plus"></i>
                        <span>Add Project</span>
                    </a>
                </div>
            </div>

            <!-- Metrics Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                
                <!-- Card 1: Projects -->
                <div class="admin-card p-5 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Published Projects</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-sm">
                            <i class="fa-solid fa-folder-tree"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-extrabold font-mono text-white"><?= $projectCount ?></div>
                    <div class="mt-2 text-[11px] font-mono text-slate-400 flex items-center justify-between">
                        <a href="projects.php" class="text-emerald-400 hover:underline">Manage All &rarr;</a>
                        <span class="text-slate-500">Live Case Studies</span>
                    </div>
                </div>

                <!-- Card 2: Inquiries -->
                <div class="admin-card p-5 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Total Inquiries</span>
                        <div class="w-8 h-8 rounded-lg bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400 text-sm">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold font-mono text-white"><?= $messageCount ?></span>
                        <?php if ($unreadCount > 0): ?>
                            <span class="text-xs font-mono text-rose-400 font-bold">(<?= $unreadCount ?> unread)</span>
                        <?php endif; ?>
                    </div>
                    <div class="mt-2 text-[11px] font-mono text-slate-400 flex items-center justify-between">
                        <a href="messages.php" class="text-cyan-400 hover:underline">Open Inbox &rarr;</a>
                        <span class="text-slate-500">Encrypted Store</span>
                    </div>
                </div>

                <!-- Card 3: Skills -->
                <div class="admin-card p-5 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Skills & Tooling</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400 text-sm">
                            <i class="fa-solid fa-microchip"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-extrabold font-mono text-white"><?= $skillCount ?></div>
                    <div class="mt-2 text-[11px] font-mono text-slate-400 flex items-center justify-between">
                        <a href="skills.php" class="text-indigo-400 hover:underline">Manage Skills &rarr;</a>
                        <span class="text-slate-500">Categorized</span>
                    </div>
                </div>

                <!-- Card 4: Animation Engine -->
                <div class="admin-card p-5 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Animation System</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400 text-sm">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                    </div>
                    <div class="text-sm font-bold font-mono text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full <?= !empty($anim['enable_particles']) ? 'bg-emerald-500' : 'bg-slate-600' ?>"></span>
                        <span><?= !empty($anim['enable_particles']) ? 'Particles Active' : 'Particles Off' ?></span>
                    </div>
                    <div class="mt-2 text-[11px] font-mono text-slate-400 flex items-center justify-between">
                        <a href="animations.php" class="text-amber-400 hover:underline">Configure Effects &rarr;</a>
                        <span>Speed: <?= (int)($anim['typing_speed_ms'] ?? 80) ?>ms</span>
                    </div>
                </div>

            </div>

            <!-- Main Sections Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left: Recent Inquiries Table -->
                <div class="lg:col-span-7 admin-card p-6">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-4">
                        <div class="flex items-center gap-2 font-mono text-xs">
                            <i class="fa-solid fa-inbox text-emerald-400"></i>
                            <span class="text-white font-bold uppercase tracking-wider">Recent Transmissions</span>
                        </div>
                        <a href="messages.php" class="text-xs font-mono text-slate-400 hover:text-emerald-400 transition-colors">View All &rarr;</a>
                    </div>

                    <?php if (empty($recentMessages)): ?>
                        <div class="text-center py-8 text-slate-500 font-mono text-xs">
                            <i class="fa-solid fa-comment-slash text-2xl mb-2 block"></i>
                            No contact inquiries recorded yet.
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="admin-table font-mono text-xs">
                                <thead>
                                    <tr>
                                        <th>Sender</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentMessages as $msg): ?>
                                        <tr>
                                            <td>
                                                <div class="font-bold text-white"><?= htmlspecialchars($msg['name']) ?></div>
                                                <div class="text-[11px] text-slate-400"><?= htmlspecialchars($msg['email']) ?></div>
                                            </td>
                                            <td class="truncate max-w-xs text-slate-300">
                                                <?= htmlspecialchars($msg['subject']) ?>
                                            </td>
                                            <td>
                                                <?php if ($msg['status'] === 'unread'): ?>
                                                    <span class="px-2 py-0.5 rounded bg-emerald-500/15 border border-emerald-500/40 text-emerald-300 text-[10px] font-bold">UNREAD</span>
                                                <?php else: ?>
                                                    <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-400 text-[10px]">READ</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="messages.php?id=<?= $msg['id'] ?>" class="text-cyan-400 hover:text-cyan-300">Read &rarr;</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Right: Quick Navigation Hub & Projects Preview -->
                <div class="lg:col-span-5 space-y-6">
                    
                    <!-- Quick Actions Hub -->
                    <div class="admin-card p-6">
                        <h3 class="font-mono text-xs uppercase tracking-wider text-slate-400 border-b border-slate-800 pb-3 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-compass text-cyan-400"></i>
                            <span>Admin Dispatch Shortcuts</span>
                        </h3>
                        <div class="grid grid-cols-2 gap-3 font-mono text-xs">
                            <a href="profile.php" class="p-3 rounded-lg bg-slate-900 border border-slate-800 hover:border-emerald-500/50 hover:bg-slate-900/80 transition-all flex flex-col gap-1">
                                <i class="fa-solid fa-id-badge text-emerald-400 text-base"></i>
                                <span class="text-white font-bold">Edit Profile & Bio</span>
                                <span class="text-[10px] text-slate-500">Avatar, resume, phone</span>
                            </a>
                            <a href="animations.php" class="p-3 rounded-lg bg-slate-900 border border-slate-800 hover:border-cyan-500/50 hover:bg-slate-900/80 transition-all flex flex-col gap-1">
                                <i class="fa-solid fa-sliders text-cyan-400 text-base"></i>
                                <span class="text-white font-bold">Animation Engine</span>
                                <span class="text-[10px] text-slate-500">Canvas, glitch, speed</span>
                            </a>
                            <a href="skills.php" class="p-3 rounded-lg bg-slate-900 border border-slate-800 hover:border-indigo-500/50 hover:bg-slate-900/80 transition-all flex flex-col gap-1">
                                <i class="fa-solid fa-layer-group text-indigo-400 text-base"></i>
                                <span class="text-white font-bold">Skills Matrix</span>
                                <span class="text-[10px] text-slate-500">Ratings, categories</span>
                            </a>
                            <a href="experiences.php" class="p-3 rounded-lg bg-slate-900 border border-slate-800 hover:border-amber-500/50 hover:bg-slate-900/80 transition-all flex flex-col gap-1">
                                <i class="fa-solid fa-clock-rotate-left text-amber-400 text-base"></i>
                                <span class="text-white font-bold">Timeline History</span>
                                <span class="text-[10px] text-slate-500">Companies & roles</span>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Projects Mini-List -->
                    <div class="admin-card p-6">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-4">
                            <span class="font-mono text-xs uppercase tracking-wider text-slate-400">// ACTIVE PROJECTS</span>
                            <a href="projects.php" class="text-xs font-mono text-emerald-400 hover:underline">View All &rarr;</a>
                        </div>
                        <div class="space-y-3 font-mono text-xs">
                            <?php foreach ($recentProjects as $p): ?>
                                <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-900/80 border border-slate-800/80">
                                    <div class="overflow-hidden">
                                        <div class="font-bold text-white truncate"><?= htmlspecialchars($p['title']) ?></div>
                                        <span class="text-[10px] text-emerald-400"><?= htmlspecialchars($p['category']) ?></span>
                                    </div>
                                    <a href="projects.php?action=edit&id=<?= $p['id'] ?>" class="text-slate-400 hover:text-white p-1" title="Edit Project">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>

            </div>

        </main>
    </div>

    <!-- Admin JS -->
    <script src="../assets/js/admin.js"></script>
</body>
</html>
