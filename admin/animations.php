<?php
/**
 * Animation Controller & Interactive Visuals Engine
 * Bajrang Kumar Sarraf - Cybersecurity Specialist Portfolio
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/auth_guard.php';

$pdo = Database::getConnection();

// Fetch Current Animation Settings
$anim = $pdo->query("SELECT * FROM animation_settings WHERE id = 1 LIMIT 1")->fetch() ?: [];

// Handle Save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!CSRF::verify($csrfToken)) {
        set_flash('danger', 'Security validation failed (CSRF mismatch).');
        header('Location: animations.php');
        exit;
    }

    $enableParticles = isset($_POST['enable_particles']) ? 1 : 0;
    $particleCount = max(10, min(200, (int)($_POST['particle_node_count'] ?? 60)));
    $particleColor = trim((string)($_POST['particle_color'] ?? '#10b981'));
    if (!preg_match('/^#[a-fA-F0-9]{6}$/', $particleColor)) {
        $particleColor = '#10b981';
    }

    $enableGlitch = isset($_POST['enable_glitch_effect']) ? 1 : 0;
    $enableCardTilt = isset($_POST['enable_card_tilt']) ? 1 : 0;
    $typingSpeed = max(20, min(300, (int)($_POST['typing_speed_ms'] ?? 80)));

    $terminalCustom = trim((string)($_POST['terminal_custom_commands'] ?? ''));
    // Validate JSON structure
    if (!empty($terminalCustom)) {
        $decoded = json_decode($terminalCustom, true);
        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            set_flash('danger', 'Invalid JSON syntax in custom terminal commands.');
            header('Location: animations.php');
            exit;
        }
        $terminalCustom = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE animation_settings SET 
                enable_particles = :enable_particles,
                particle_node_count = :node_count,
                particle_color = :color,
                enable_glitch_effect = :glitch,
                enable_card_tilt = :tilt,
                typing_speed_ms = :speed,
                terminal_custom_commands = :terminal
            WHERE id = 1
        ");

        $stmt->execute([
            'enable_particles' => $enableParticles,
            'node_count' => $particleCount,
            'color' => $particleColor,
            'glitch' => $enableGlitch,
            'tilt' => $enableCardTilt,
            'speed' => $typingSpeed,
            'terminal' => $terminalCustom
        ]);

        set_flash('success', 'Visual animation controller parameters synchronized successfully.');
        header('Location: animations.php');
        exit;
    } catch (Throwable $e) {
        set_flash('danger', 'Database update error: ' . $e->getMessage());
        header('Location: animations.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animation Controller & Visuals Engine | SEC_CMS</title>
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
                    <span class="text-cyan-400 font-bold">ANIMATION_CONTROLLER_ENGINE</span>
                </div>
            </div>
            <a href="../index.php" target="_blank" class="text-xs font-mono text-emerald-400 hover:text-emerald-300">
                <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Preview Live Site
            </a>
        </header>

        <!-- Page Body -->
        <main class="p-6 lg:p-8 space-y-8 flex-grow">
            
            <?= render_alerts() ?>

            <div class="border-b border-slate-800 pb-4">
                <h1 class="text-2xl font-bold font-mono text-white">Visual Effects & Animation Controller</h1>
                <p class="text-xs font-mono text-slate-400 mt-1">Configure hardware-accelerated canvas particles, typing speed, 3D card tilt, and terminal commands.</p>
            </div>

            <form action="animations.php" method="POST" class="space-y-8 max-w-4xl">
                <?= CSRF::field() ?>

                <!-- Group 1: Particle Matrix Canvas -->
                <div class="admin-card p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                                <i class="fa-solid fa-circle-nodes text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold font-mono text-white">HTML5 Cyber Particle Matrix</h3>
                                <p class="text-xs text-slate-400 font-mono">Dynamic node mesh connecting with laser vectors behind hero</p>
                            </div>
                        </div>
                        <!-- Toggle Switch -->
                        <label class="switch-container">
                            <input type="checkbox" name="enable_particles" value="1" <?= !empty($anim['enable_particles']) ? 'checked' : '' ?>>
                            <span class="switch-slider"></span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                        <!-- Node Count Slider -->
                        <div>
                            <div class="flex justify-between items-center text-xs font-mono mb-2">
                                <label for="particle_node_count" class="text-slate-300">Particle Endpoint Count</label>
                                <span id="node-count-val" class="text-emerald-400 font-bold"><?= (int)($anim['particle_node_count'] ?? 60) ?></span>
                            </div>
                            <input type="range" id="particle_node_count" name="particle_node_count" min="15" max="150" step="5" value="<?= (int)($anim['particle_node_count'] ?? 60) ?>" data-sync-target="node-count-val" class="w-full accent-emerald-500 cursor-pointer">
                            <span class="text-[10px] text-slate-500 font-mono block mt-1">Lower values reduce GPU consumption on ultra-low-power devices</span>
                        </div>

                        <!-- Particle Color Picker -->
                        <div>
                            <label for="particle_color" class="block text-xs font-mono text-slate-300 mb-2">Laser & Node Theme Hex Color</label>
                            <div class="flex items-center gap-3">
                                <input type="color" id="particle_color_picker" value="<?= htmlspecialchars($anim['particle_color'] ?? '#10b981') ?>" onchange="document.getElementById('particle_color').value = this.value" class="w-10 h-10 rounded bg-slate-900 border border-slate-700 cursor-pointer p-0.5">
                                <input type="text" id="particle_color" name="particle_color" value="<?= htmlspecialchars($anim['particle_color'] ?? '#10b981') ?>" onchange="document.getElementById('particle_color_picker').value = this.value" class="admin-input font-mono text-xs w-36 uppercase">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Group 2: Headline Typing & Glitch -->
                <div class="admin-card p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
                                <i class="fa-solid fa-keyboard text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold font-mono text-white">Headline Typing & Micro-Glitch</h3>
                                <p class="text-xs text-slate-400 font-mono">Dynamic role cycler and dual-channel chromatic aberration</p>
                            </div>
                        </div>
                        <label class="switch-container">
                            <input type="checkbox" name="enable_glitch_effect" value="1" <?= !empty($anim['enable_glitch_effect']) ? 'checked' : '' ?>>
                            <span class="switch-slider"></span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                        <!-- Typing Speed Slider -->
                        <div>
                            <div class="flex justify-between items-center text-xs font-mono mb-2">
                                <label for="typing_speed_ms" class="text-slate-300">Typing Stroke Delay</label>
                                <span id="speed-val" class="text-cyan-400 font-bold"><?= (int)($anim['typing_speed_ms'] ?? 80) ?> ms</span>
                            </div>
                            <input type="range" id="typing_speed_ms" name="typing_speed_ms" min="30" max="200" step="5" value="<?= (int)($anim['typing_speed_ms'] ?? 80) ?>" data-sync-target="speed-val" class="w-full accent-cyan-500 cursor-pointer">
                            <span class="text-[10px] text-slate-500 font-mono block mt-1">Lower ms = faster typing; Higher ms = deliberate terminal pacing</span>
                        </div>

                        <!-- 3D Card Tilt Switch -->
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-900 border border-slate-800">
                            <div>
                                <span class="text-xs font-mono font-bold text-white block">3D Card Perspective Tilt</span>
                                <span class="text-[10px] text-slate-500 font-mono">Enables mouse-tracking tilt on project cards</span>
                            </div>
                            <label class="switch-container">
                                <input type="checkbox" name="enable_card_tilt" value="1" <?= !empty($anim['enable_card_tilt']) ? 'checked' : '' ?>>
                                <span class="switch-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Group 3: Interactive Terminal CLI Commands & Outputs -->
                <div class="admin-card p-6 sm:p-8 space-y-4">
                    <div class="flex items-center gap-3 border-b border-slate-800 pb-4">
                        <div class="w-10 h-10 rounded-lg bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400">
                            <i class="fa-solid fa-terminal text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold font-mono text-white">Custom Terminal Commands & Responses</h3>
                            <p class="text-xs text-slate-400 font-mono">JSON dictionary mapping terminal commands to console outputs</p>
                        </div>
                    </div>

                    <p class="text-xs text-slate-400 font-sans leading-relaxed">
                        Define custom easter-egg commands and outputs for the interactive terminal on the public portfolio. Enter as valid JSON key-value pairs:
                    </p>

                    <div>
                        <textarea id="terminal_custom_commands" name="terminal_custom_commands" rows="10" class="admin-input font-mono text-xs leading-relaxed text-emerald-300 bg-slate-950"><?= htmlspecialchars($anim['terminal_custom_commands'] ?? '{}') ?></textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-8 py-3 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-mono font-bold text-xs uppercase tracking-wider transition-all shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:shadow-[0_0_25px_rgba(16,185,129,0.5)]">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Save & Apply Animation Parameters
                    </button>
                </div>

            </form>

        </main>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>
