/**
 * Admin CMS Interactive JavaScript
 * Bajrang Kumar Sarraf - Cybersecurity Specialist Portfolio
 */

'use strict';

document.addEventListener('DOMContentLoaded', () => {
    initDropzones();
    initRangeDisplay();
    initMobileSidebar();
    initDeleteConfirmations();
});

// 1. Drag & Drop Live Image Preview
function initDropzones() {
    const dropzones = document.querySelectorAll('.dropzone-box');

    dropzones.forEach(zone => {
        const input = zone.querySelector('input[type="file"]');
        const previewImg = zone.querySelector('.preview-thumbnail');
        const placeholder = zone.querySelector('.dropzone-placeholder');

        if (!input) return;

        zone.addEventListener('click', (e) => {
            if (e.target !== input) {
                input.click();
            }
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            zone.addEventListener(eventName, (e) => {
                e.preventDefault();
                zone.classList.add('dragover');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            zone.addEventListener(eventName, (e) => {
                e.preventDefault();
                zone.classList.remove('dragover');
            });
        });

        zone.addEventListener('drop', (e) => {
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                input.files = e.dataTransfer.files;
                handlePreview(input.files[0], previewImg, placeholder);
            }
        });

        input.addEventListener('change', () => {
            if (input.files && input.files[0]) {
                handlePreview(input.files[0], previewImg, placeholder);
            }
        });
    });

    function handlePreview(file, imgElement, placeholderElement) {
        if (!file || !file.type.startsWith('image/')) return;

        const reader = new FileReader();
        reader.onload = (e) => {
            if (imgElement) {
                imgElement.src = e.target.result;
                imgElement.classList.remove('hidden');
            }
            if (placeholderElement) {
                placeholderElement.classList.add('hidden');
            }
        };
        reader.readAsDataURL(file);
    }
}

// 2. Range Input Live Value Sync (for Animation Settings)
function initRangeDisplay() {
    const rangeInputs = document.querySelectorAll('input[type="range"][data-sync-target]');
    rangeInputs.forEach(range => {
        const targetId = range.getAttribute('data-sync-target');
        const targetSpan = document.getElementById(targetId);
        if (targetSpan) {
            range.addEventListener('input', () => {
                targetSpan.textContent = range.value;
            });
        }
    });
}

// 3. Mobile Sidebar Toggle
function initMobileSidebar() {
    const toggleBtn = document.getElementById('admin-sidebar-toggle');
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('admin-sidebar-overlay');

    if (!toggleBtn || !sidebar) return;

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        if (overlay) overlay.classList.toggle('hidden');
    });

    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    }
}

// 4. Secure Delete Confirmation
function initDeleteConfirmations() {
    document.querySelectorAll('.confirm-delete-form').forEach(form => {
        form.addEventListener('submit', (e) => {
            const confirmed = confirm('CRITICAL: Are you sure you want to delete this record? This action cannot be undone.');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });
}
