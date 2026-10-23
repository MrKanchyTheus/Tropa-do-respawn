<?php
/**
 * Rodapé Compartilhado (Footer)
 * Inclui redes sociais, slogan, termos e os scripts do sistema (Toasts, Modais, JS).
 */
?>
<footer class="mt-auto bg-dark-950 border-t border-purple-900/40 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            
            <!-- Logo e Slogan -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl overflow-hidden border border-purple-500/40 p-0.5 shadow-[0_0_15px_rgba(168,85,247,0.25)]">
                    <img src="assets/img/logo.jpeg" alt="Logo" class="w-full h-full object-cover rounded-lg">
                </div>
                <div>
                    <span class="block font-rajdhani font-black text-white text-lg tracking-wider">
                        TROPA DO <span class="text-purple-500">RESPAWN</span>
                    </span>
                    <span class="block text-xs text-purple-400 font-medium">
                        Mais que um site, uma comunidade!
                    </span>
                </div>
            </div>

            <!-- Links Rápidos -->
            <div class="flex flex-wrap items-center justify-center gap-6 text-xs text-gray-400 font-medium">
                <a href="wiki.php?categoria=Regras" class="hover:text-purple-400 transition-colors">Regras da Comunidade</a>
                <span class="text-purple-900">•</span>
                <a href="historico.php" class="hover:text-purple-400 transition-colors">Histórico de Auditoria</a>
                <span class="text-purple-900">•</span>
                <a href="suporte.php" class="hover:text-purple-400 transition-colors">Central de Suporte</a>
                <span class="text-purple-900">•</span>
                <a href="https://tropadorespwan.infinityfree.io/" target="_blank" class="hover:text-purple-400 transition-colors text-purple-400">Servidor Online</a>
            </div>

            <!-- Redes Sociais Gamer -->
            <div class="flex items-center gap-4 text-gray-400">
                <a href="https://discord.com" target="_blank" rel="noopener" class="w-9 h-9 rounded-xl bg-dark-900 border border-purple-900/40 flex items-center justify-center hover:text-white hover:border-purple-500 hover:shadow-[0_0_12px_rgba(168,85,247,0.4)] transition-all" title="Discord">
                    <i class="fa-brands fa-discord text-sm"></i>
                </a>
                <a href="https://youtube.com" target="_blank" rel="noopener" class="w-9 h-9 rounded-xl bg-dark-900 border border-purple-900/40 flex items-center justify-center hover:text-white hover:border-purple-500 hover:shadow-[0_0_12px_rgba(168,85,247,0.4)] transition-all" title="YouTube">
                    <i class="fa-brands fa-youtube text-sm"></i>
                </a>
                <a href="https://instagram.com" target="_blank" rel="noopener" class="w-9 h-9 rounded-xl bg-dark-900 border border-purple-900/40 flex items-center justify-center hover:text-white hover:border-purple-500 hover:shadow-[0_0_12px_rgba(168,85,247,0.4)] transition-all" title="Instagram">
                    <i class="fa-brands fa-instagram text-sm"></i>
                </a>
                <a href="https://x.com" target="_blank" rel="noopener" class="w-9 h-9 rounded-xl bg-dark-900 border border-purple-900/40 flex items-center justify-center hover:text-white hover:border-purple-500 hover:shadow-[0_0_12px_rgba(168,85,247,0.4)] transition-all" title="X (Twitter)">
                    <i class="fa-brands fa-x-twitter text-sm"></i>
                </a>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-purple-900/20 text-center text-xs text-gray-500 font-mono">
            &copy; <?= date('Y') ?> Tropa do Respawn. Todos os direitos reservados. Conhecimento também é uma arma.
        </div>
    </div>
</footer>

<!-- Inclusão de Modais e Toasts -->
<?php require_once __DIR__ . '/modal.php'; ?>
<?php require_once __DIR__ . '/toast.php'; ?>

<!-- Script Frontend Principal -->
<script src="assets/js/app.js"></script>
</body>
</html>
