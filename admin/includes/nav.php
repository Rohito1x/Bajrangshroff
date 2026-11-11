<?php
/**
 * Sleek Tactical Admin Sidebar Navigation
 */

declare(strict_types=1);

$currentPage = basename($_SERVER['PHP_SELF'] ?? '');
$adminUser = Auth::user();

// Fetch unread messages count for the badge
$unreadCount = 0;
try {
    $pdo = Database::getConnection();
    $unreadCount = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'unread'")->fetchColumn();
} catch (Throwable) {
    $unreadCount = 0;
}
?>

<!-- Mobile Sidebar Overlay -->
<div id="admin-sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 lg:hidden hidden backdrop-blur-sm"></div>

<!-- Sidebar Container -->
<aside id="admin-sidebar" class="fixed top-0 left-0 bottom-0 z-50 w-64 bg-[#090d17] border-r border-slate-800 flex flex-col justify-between transition-transform duration-300 lg:translate-x-0 -translate-x-full">
    <div>
        <!-- Brand Header -->
        <div class="h-18 px-6 border-b border-slate-800 flex items-center justify-between">
            <a href="index.php" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded bg-slate-900 border border-emerald-500/50 flex items-center justify-center text-emerald-400 font-mono font-bold text-sm shadow-[0_0_12px_rgba(16,185,129,0.3)]">
                    &gt;_
                </div>
                <div class="flex flex-col">
                    <span class="font-mono text-sm font-bold text-white tracking-wider">SEC_CMS</span>
                    <span class="text-[10px] font-mono text-emerald-400">ADMIN // DASHBOARD</span>
                </div>
            </a>
            <button id="admin-sidebar-toggle" class="lg:hidden text-slate-400 hover:text-white p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="p-4 space-y-1.5 overflow-y-auto">
            <div class="text-[10px] font-mono uppercase tracking-wider text-slate-500 px-3 py-2">Core Content</div>
            
            <a href="index.php" class="admin-nav-item <?= $currentPage === 'index.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                <span>Metrics Dashboard</span>
            </a>

            <a href="profile.php" class="admin-nav-item <?= $currentPage === 'profile.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-user-shield w-4 text-center"></i>
                <span>Profile & Bio</span>
            </a>

            <a href="projects.php" class="admin-nav-item <?= $currentPage === 'projects.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-folder-tree w-4 text-center"></i>
                <span>Projects Portfolio</span>
            </a>

            <a href="skills.php" class="admin-nav-item <?= $currentPage === 'skills.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-microchip w-4 text-center"></i>
                <span>Skills Matrix</span>
            </a>

            <a href="experiences.php" class="admin-nav-item <?= $currentPage === 'experiences.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-briefcase w-4 text-center"></i>
                <span>Experience Timeline</span>
            </a>

            <a href="certifications.php" class="admin-nav-item <?= $currentPage === 'certifications.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-award w-4 text-center"></i>
                <span>Certifications</span>
            </a>

            <div class="text-[10px] font-mono uppercase tracking-wider text-slate-500 px-3 pt-4 py-2">System & Visuals</div>

            <a href="animations.php" class="admin-nav-item <?= $currentPage === 'animations.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-wand-magic-sparkles w-4 text-center text-cyan-400"></i>
                <span>Animation Controller</span>
            </a>

            <a href="messages.php" class="admin-nav-item flex items-center justify-between <?= $currentPage === 'messages.php' ? 'active' : '' ?>">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-envelope w-4 text-center"></i>
                    <span>Inquiries Inbox</span>
                </div>
                <?php if ($unreadCount > 0): ?>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 text-[10px] font-bold">
                        <?= $unreadCount ?>
                    </span>
                <?php endif; ?>
            </a>
        </nav>
    </div>

    <!-- Footer User Session Info & Live Site Link -->
    <div class="p-4 border-t border-slate-800 space-y-3">
        <a href="../index.php" target="_blank" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 hover:border-emerald-500/50 text-slate-300 hover:text-emerald-400 text-xs font-mono transition-colors">
            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            <span>View Public Site</span>
        </a>

        <div class="flex items-center justify-between px-2 pt-1 font-mono text-xs">
            <div class="flex items-center gap-2 overflow-hidden">
                <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                <span class="text-slate-300 truncate text-[11px]"><?= htmlspecialchars($adminUser['username'] ?? 'Admin') ?></span>
            </div>
            <a href="logout.php" class="text-slate-500 hover:text-rose-400 text-xs transition-colors" title="Sign Out">
                <i class="fa-solid fa-power-off"></i>
            </a>
        </div>
    </div>
</aside>
