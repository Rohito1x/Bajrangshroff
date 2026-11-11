<?php
/**
 * Inquiries Inbox & Message Manager
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
        header('Location: messages.php');
        exit;
    }

    $delId = (int)($_POST['id'] ?? 0);
    if ($delId > 0) {
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = :id");
        $stmt->execute(['id' => $delId]);
        set_flash('success', 'Transmission record purged.');
    }
    header('Location: messages.php');
    exit;
}

// Handle Status Toggle (Mark read / unread)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_status') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (CSRF::verify($csrfToken)) {
        $msgId = (int)($_POST['id'] ?? 0);
        $newStatus = ($_POST['status'] ?? 'read') === 'unread' ? 'unread' : 'read';
        $stmt = $pdo->prepare("UPDATE contact_messages SET status = :status WHERE id = :id");
        $stmt->execute(['status' => $newStatus, 'id' => $msgId]);
        set_flash('success', 'Transmission status updated to: ' . strtoupper($newStatus));
    }
    header('Location: messages.php');
    exit;
}

// Active Filter: 'all', 'unread', 'read'
$filter = $_GET['filter'] ?? 'all';
$viewId = isset($_GET['id']) ? (int)$_GET['id'] : null;

// If viewing a message, mark as read automatically
$activeMessage = null;
if ($viewId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $viewId]);
    $activeMessage = $stmt->fetch();

    if ($activeMessage && $activeMessage['status'] === 'unread') {
        $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'read' WHERE id = :id");
        $stmt->execute(['id' => $viewId]);
        $activeMessage['status'] = 'read';
    }
}

// Query filtered messages
$query = "SELECT * FROM contact_messages";
if ($filter === 'unread') {
    $query .= " WHERE status = 'unread'";
} elseif ($filter === 'read') {
    $query .= " WHERE status = 'read'";
}
$query .= " ORDER BY created_at DESC";

$messages = $pdo->query($query)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiries Inbox | SEC_CMS</title>
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
                    <span class="text-emerald-400 font-bold">TRANSMISSION_INBOX</span>
                </div>
            </div>
            <a href="../index.php#contact" target="_blank" class="text-xs font-mono text-cyan-400 hover:text-cyan-300">
                <i class="fa-solid fa-eye mr-1"></i> Preview Contact Form
            </a>
        </header>

        <!-- Page Body -->
        <main class="p-6 lg:p-8 space-y-8 flex-grow">
            
            <?= render_alerts() ?>

            <!-- Action Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-4">
                <div>
                    <h1 class="text-2xl font-bold font-mono text-white">Encrypted Transmissions & Inquiries</h1>
                    <p class="text-xs font-mono text-slate-400 mt-1">Review contact inquiries, client VAPT requests, and corporate training proposals.</p>
                </div>
                
                <!-- Filter Pills -->
                <div class="flex items-center gap-2 font-mono text-xs">
                    <a href="messages.php?filter=all" class="px-3 py-1.5 rounded-lg border <?= $filter === 'all' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : 'bg-slate-900 text-slate-400 border-slate-800' ?>">All</a>
                    <a href="messages.php?filter=unread" class="px-3 py-1.5 rounded-lg border <?= $filter === 'unread' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : 'bg-slate-900 text-slate-400 border-slate-800' ?>">Unread</a>
                    <a href="messages.php?filter=read" class="px-3 py-1.5 rounded-lg border <?= $filter === 'read' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : 'bg-slate-900 text-slate-400 border-slate-800' ?>">Read</a>
                </div>
            </div>

            <!-- Single Message Detail Card (If active) -->
            <?php if ($activeMessage): ?>
                <div class="admin-card p-6 sm:p-8 border border-emerald-500/40 max-w-4xl relative shadow-2xl">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-800 pb-4 mb-5 font-mono text-xs">
                        <div>
                            <span class="text-slate-500 block text-[10px] uppercase">// TRANSMISSION_ID: #<?= (int)$activeMessage['id'] ?></span>
                            <h2 class="text-lg font-bold text-white mt-1"><?= htmlspecialchars($activeMessage['subject']) ?></h2>
                            <p class="text-emerald-400 text-xs mt-0.5">
                                From: <strong class="text-white"><?= htmlspecialchars($activeMessage['name']) ?></strong> 
                                &lt;<a href="mailto:<?= htmlspecialchars($activeMessage['email']) ?>" class="underline"><?= htmlspecialchars($activeMessage['email']) ?></a>&gt;
                            </p>
                        </div>
                        <div class="text-right text-[11px] text-slate-400">
                            <div><?= htmlspecialchars($activeMessage['created_at']) ?></div>
                            <div class="text-[10px] text-slate-500 font-mono mt-1">IP: <?= htmlspecialchars($activeMessage['ip_address']) ?></div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="text-xs font-mono uppercase tracking-wider text-slate-400">// Message Body:</div>
                        <div class="p-4 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 font-sans text-sm leading-relaxed whitespace-pre-wrap">
                            <?= htmlspecialchars($activeMessage['message']) ?>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-800 flex flex-wrap items-center justify-between gap-3 font-mono text-xs">
                        <div class="flex items-center gap-2">
                            <a href="mailto:<?= htmlspecialchars($activeMessage['email']) ?>?subject=Re: <?= urlencode($activeMessage['subject']) ?>" class="px-4 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold transition-all flex items-center gap-2">
                                <i class="fa-solid fa-reply"></i>
                                <span>Reply via Mail</span>
                            </a>
                            <a href="https://wa.me/?text=Hello%20<?= urlencode($activeMessage['name']) ?>" target="_blank" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 transition-all flex items-center gap-2">
                                <i class="fa-brands fa-whatsapp text-emerald-400"></i>
                                <span>WhatsApp Contact</span>
                            </a>
                        </div>

                        <div class="flex items-center gap-2">
                            <form action="messages.php" method="POST" class="inline">
                                <?= CSRF::field() ?>
                                <input type="hidden" name="action" value="toggle_status">
                                <input type="hidden" name="id" value="<?= $activeMessage['id'] ?>">
                                <input type="hidden" name="status" value="unread">
                                <button type="submit" class="px-3 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300">
                                    Mark as Unread
                                </button>
                            </form>
                            <form action="messages.php" method="POST" class="inline confirm-delete-form">
                                <?= CSRF::field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $activeMessage['id'] ?>">
                                <button type="submit" class="px-3 py-2 rounded-lg bg-rose-500/10 border border-rose-500/30 text-rose-300 hover:bg-rose-500/20">
                                    <i class="fa-solid fa-trash mr-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Messages List Table -->
            <div class="admin-card overflow-hidden">
                <div class="p-5 border-b border-slate-800 flex items-center justify-between font-mono text-xs">
                    <span class="uppercase tracking-wider text-slate-400">// INQUIRIES REGISTER (<?= count($messages) ?> Total)</span>
                </div>

                <?php if (empty($messages)): ?>
                    <div class="text-center py-12 text-slate-500 font-mono text-xs">
                        <i class="fa-solid fa-folder-open text-3xl mb-2 block"></i>
                        No transmission records found matching this filter.
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="admin-table font-mono text-xs">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Sender</th>
                                    <th>Subject</th>
                                    <th>Origin IP</th>
                                    <th>Received</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $m): ?>
                                    <tr class="<?= $m['status'] === 'unread' ? 'bg-emerald-500/[0.03]' : '' ?>">
                                        <td>
                                            <?php if ($m['status'] === 'unread'): ?>
                                                <span class="px-2 py-0.5 rounded bg-emerald-500/20 border border-emerald-500/50 text-emerald-300 text-[10px] font-bold">UNREAD</span>
                                            <?php else: ?>
                                                <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-500 text-[10px]">READ</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="font-bold text-white"><?= htmlspecialchars($m['name']) ?></div>
                                            <div class="text-[11px] text-slate-400"><?= htmlspecialchars($m['email']) ?></div>
                                        </td>
                                        <td class="max-w-xs truncate text-slate-300">
                                            <?= htmlspecialchars($m['subject']) ?>
                                        </td>
                                        <td class="text-slate-500 text-[11px]"><?= htmlspecialchars($m['ip_address']) ?></td>
                                        <td class="text-slate-400 text-[11px]"><?= date('M j, Y H:i', strtotime($m['created_at'])) ?></td>
                                        <td class="text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="messages.php?id=<?= $m['id'] ?>" class="px-2.5 py-1 rounded bg-slate-800 text-cyan-400 hover:text-white" title="View Transmission">
                                                    Read
                                                </a>
                                                <form action="messages.php" method="POST" class="inline confirm-delete-form">
                                                    <?= CSRF::field() ?>
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?= $m['id'] ?>">
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
                <?php endif; ?>
            </div>

        </main>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>
