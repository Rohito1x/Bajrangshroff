<?php
/**
 * Flash Alert Notification Helper for Admin CMS
 */

declare(strict_types=1);

function set_flash(string $type, string $message): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['cms_flash'] = [
        'type' => $type, // 'success', 'danger', 'warning', 'info'
        'message' => $message
    ];
}

function render_alerts(): string {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['cms_flash'])) {
        return '';
    }

    $flash = $_SESSION['cms_flash'];
    unset($_SESSION['cms_flash']);

    $type = $flash['type'] ?? 'info';
    $message = htmlspecialchars($flash['message'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');

    $colors = [
        'success' => 'bg-emerald-500/15 border-emerald-500/50 text-emerald-300',
        'danger' => 'bg-rose-500/15 border-rose-500/50 text-rose-300',
        'warning' => 'bg-amber-500/15 border-amber-500/50 text-amber-300',
        'info' => 'bg-cyan-500/15 border-cyan-500/50 text-cyan-300'
    ];

    $icons = [
        'success' => 'fa-circle-check',
        'danger' => 'fa-triangle-exclamation',
        'warning' => 'fa-circle-exclamation',
        'info' => 'fa-circle-info'
    ];

    $colorClass = $colors[$type] ?? $colors['info'];
    $icon = $icons[$type] ?? $icons['info'];

    return sprintf(
        '<div class="p-4 mb-6 rounded-xl border %s flex items-center justify-between text-xs font-mono shadow-lg transition-all" role="alert">
            <div class="flex items-center gap-3">
                <i class="fa-solid %s text-base"></i>
                <span>%s</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-white p-1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>',
        $colorClass,
        $icon,
        $message
    );
}
