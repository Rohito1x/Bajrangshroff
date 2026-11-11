<?php
/**
 * Global Footer Component
 * Bajrang Kumar Sarraf - Cybersecurity Specialist & Full-Stack Web Developer
 */

declare(strict_types=1);

$baseUrl = AppConfig::baseUrl();
$profile = !empty($profile) ? $profile : ($data['profile'] ?? []);
?>
    <!-- Global Footer -->
    <footer class="mt-auto border-t border-slate-800 bg-[#060a10] text-slate-400 font-mono text-xs">
        <!-- Top Footer Strip -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
                
                <!-- Col 1: Identity & Bio Summary -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded bg-slate-900 border border-emerald-500/50 flex items-center justify-center text-emerald-400 font-bold">
                            &gt;_
                        </div>
                        <span class="text-base font-bold text-white tracking-tight">Bajrang Kumar Sarraf</span>
                    </div>
                    <p class="text-slate-400 text-xs leading-relaxed max-w-lg">
                        Cybersecurity Trainer, Junior Penetration Tester, VAPT Analyst, and Web Developer based in Birgunj, Nepal. Passionate about safeguarding architectures through offensive reconnaissance and robust defense.
                    </p>
                    <div class="flex items-center gap-2 pt-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-emerald-400 text-[11px] font-semibold">PGP Encrypted &bull; OWASP Compliant &bull; RHCSA Hardened</span>
                    </div>
                </div>

                <!-- Col 2: Quick Jump Links -->
                <div>
                    <h4 class="text-slate-200 font-bold uppercase tracking-wider text-xs mb-3 text-emerald-400">// Quick Navigation</h4>
                    <ul class="space-y-2">
                        <li><a href="#about" class="hover:text-emerald-400 transition-colors">&gt; About Profile</a></li>
                        <li><a href="#skills" class="hover:text-emerald-400 transition-colors">&gt; Technical Proficiencies</a></li>
                        <li><a href="#methodology" class="hover:text-emerald-400 transition-colors">&gt; VAPT Methodology</a></li>
                        <li><a href="#experience" class="hover:text-emerald-400 transition-colors">&gt; Career Experience</a></li>
                        <li><a href="#projects" class="hover:text-emerald-400 transition-colors">&gt; Web & Security Builds</a></li>
                        <li><a href="#terminal" class="hover:text-cyan-400 transition-colors">&gt; Terminal Console</a></li>
                        <li><a href="#security-lab" class="hover:text-amber-400 transition-colors">&gt; Entropy & Sec Lab</a></li>
                    </ul>
                </div>

                <!-- Col 3: Direct Reach & Social Channels -->
                <div>
                    <h4 class="text-slate-200 font-bold uppercase tracking-wider text-xs mb-3 text-emerald-400">// Direct Transmission</h4>
                    <ul class="space-y-2 text-xs">
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-emerald-400 w-4"></i>
                            <span>Birgunj, Nepal 44300</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-phone text-emerald-400 w-4"></i>
                            <a href="tel:+9779816266554" class="hover:text-emerald-300">+977 9816266554</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-emerald-400 w-4"></i>
                            <a href="mailto:bajrangshroff10@gmail.com" class="hover:text-emerald-300">bajrangshroff10@gmail.com</a>
                        </li>
                    </ul>

                    <div class="flex items-center gap-3 mt-4 pt-2">
                        <?php foreach ($profile['social'] ?? [] as $soc): ?>
                            <a href="<?= e($soc['url']) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= e($soc['name']) ?>" class="w-8 h-8 rounded bg-slate-900 border border-slate-700 flex items-center justify-center text-slate-300 hover:text-emerald-400 hover:border-emerald-500/50 hover:scale-110 transition-all">
                                <i class="<?= e($soc['icon']) ?> text-sm"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <!-- Bottom Disclaimer & Copyright Bar -->
            <div class="pt-8 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-400">
                <div>
                    &copy; <?= date('Y') ?> <span class="text-slate-200 font-semibold">Bajrang Kumar Sarraf</span>. All rights reserved.
                </div>
                <div class="text-center sm:text-right text-slate-400">
                    Built with PHP 8+ OOP, Modern Tailwind CSS & Vanilla JS. Security-hardened with CSRF & CSP.
                </div>
                <div>
                    <a href="#" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-emerald-400 transition-colors">
                        <i class="fa-solid fa-arrow-up text-[10px]"></i> Top of Node
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Project Details & PoC Lightbox Modal -->
    <div id="project-lightbox-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#050810]/85 backdrop-blur-md" role="dialog" aria-modal="true" aria-labelledby="modal-project-title">
        <div id="project-lightbox-dialog" class="glass-panel w-full max-w-2xl rounded-2xl border border-emerald-500/40 p-6 sm:p-8 relative shadow-[0_0_50px_rgba(0,0,0,0.8),0_0_30px_rgba(16,185,129,0.2)] bg-[#0c1220]">
            <!-- Close Button -->
            <button id="modal-close-btn" type="button" aria-label="Close dialog" class="absolute top-5 right-5 w-8 h-8 rounded-lg bg-slate-900 border border-slate-700 text-slate-400 hover:text-emerald-400 hover:border-emerald-500/50 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <!-- Modal Header -->
            <div class="mb-4 pr-10">
                <span id="modal-project-category" class="cyber-badge badge-emerald font-mono text-[10px] uppercase mb-2 inline-block">Security Architecture</span>
                <h3 id="modal-project-title" class="text-2xl font-bold text-white tracking-tight">Project Title</h3>
            </div>

            <!-- Modal Image Preview -->
            <div id="modal-project-img-wrap" class="mb-4 rounded-xl overflow-hidden border border-slate-700/80 bg-slate-950 max-h-56 sm:max-h-64 flex items-center justify-center relative hidden">
                <img id="modal-project-img" src="" alt="Project Screenshot" class="w-full h-full max-h-56 sm:max-h-64 object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-[#0c1220]/80 via-transparent to-transparent pointer-events-none"></div>
            </div>

            <!-- Modal Body -->
            <div class="space-y-4 text-slate-300 text-sm">
                <p id="modal-project-description" class="leading-relaxed font-sans text-xs sm:text-sm">
                    Detailed architectural overview and threat model remediation details.
                </p>

                <div>
                    <h4 class="text-xs font-mono uppercase tracking-wider text-emerald-400 mb-2 font-bold">// Security Highlights & Deliverables</h4>
                    <ul id="modal-project-highlights" class="space-y-2 text-xs"></ul>
                </div>

                <div>
                    <h4 class="text-xs font-mono uppercase tracking-wider text-slate-400 mb-2 font-bold">// Stack & Protocols</h4>
                    <div id="modal-project-tech" class="flex flex-wrap gap-1.5 font-mono"></div>
                </div>
            </div>

            <!-- Modal Footer Links -->
            <div class="mt-6 pt-5 border-t border-slate-800 flex items-center justify-between font-mono text-xs">
                <span class="text-slate-500 text-[11px]"><i class="fa-solid fa-shield-halved text-emerald-400 mr-1"></i>Verified Deployment</span>
                <a id="modal-project-link" href="#" target="_blank" rel="noopener noreferrer" class="cyber-btn cyber-btn-primary text-xs py-2 px-4">
                    <span>Visit Live Target</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- GSAP (GreenSock) Core & ScrollTrigger CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <!-- Global Client Scripts -->
    <script src="<?= e($baseUrl) ?>/assets/js/main.js"></script>
    <script src="<?= e($baseUrl) ?>/assets/js/animations.js"></script>
</body>
</html>
