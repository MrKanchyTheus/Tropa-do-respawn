<?php
/**
 * Barra Lateral Gamer (Sidebar)
 * Fiel ao mockup original do Wiki Hub da Tropa do Respawn.
 */
$categoriaFiltro = $_GET['categoria'] ?? '';
?>
<aside class="w-full md:w-64 shrink-0 flex flex-col gap-6">

    <!-- Card de Navegação da Wiki -->
    <div class="bg-dark-900 border border-purple-900/40 rounded-2xl p-5 shadow-lg shadow-black/40">
        <h4 class="text-xs font-rajdhani font-bold text-gray-400 uppercase tracking-wider mb-4 flex items-center gap-2">
            <i class="fa-solid fa-compass text-purple-400"></i> NAVEGAÇÃO
        </h4>
        <nav class="flex flex-col gap-1.5">
            <a href="index.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all <?= empty($categoriaFiltro) && basename($_SERVER['PHP_SELF']) === 'index.php' ? 'bg-purple-900/40 text-purple-300 border border-purple-500/40' : 'text-gray-300 hover:text-white hover:bg-dark-800' ?>">
                <i class="fa-solid fa-house w-4 text-purple-400"></i> Início
            </a>
            <a href="jogos.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all <?= basename($_SERVER['PHP_SELF']) === 'jogos.php' ? 'bg-purple-900/40 text-purple-300 border border-purple-500/40' : 'text-gray-300 hover:text-white hover:bg-dark-800' ?>">
                <i class="fa-solid fa-gamepad w-4 text-purple-400"></i> Jogos
            </a>
            <a href="wiki.php?categoria=Guias" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all <?= $categoriaFiltro === 'Guias' ? 'bg-purple-900/40 text-purple-300 border border-purple-500/40' : 'text-gray-300 hover:text-white hover:bg-dark-800' ?>">
                <i class="fa-solid fa-book-bookmark w-4 text-purple-400"></i> Guias
            </a>
            <a href="wiki.php?categoria=Tutoriais" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all <?= $categoriaFiltro === 'Tutoriais' ? 'bg-purple-900/40 text-purple-300 border border-purple-500/40' : 'text-gray-300 hover:text-white hover:bg-dark-800' ?>">
                <i class="fa-solid fa-graduation-cap w-4 text-purple-400"></i> Tutoriais
            </a>
            <a href="wiki.php?categoria=Listas" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all <?= $categoriaFiltro === 'Listas' ? 'bg-purple-900/40 text-purple-300 border border-purple-500/40' : 'text-gray-300 hover:text-white hover:bg-dark-800' ?>">
                <i class="fa-solid fa-list-ol w-4 text-purple-400"></i> Listas (Tier Lists)
            </a>
            <a href="comunidade.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all <?= basename($_SERVER['PHP_SELF']) === 'comunidade.php' ? 'bg-purple-900/40 text-purple-300 border border-purple-500/40' : 'text-gray-300 hover:text-white hover:bg-dark-800' ?>">
                <i class="fa-solid fa-users w-4 text-purple-400"></i> Comunidade
            </a>
            <a href="wiki.php?categoria=Regras" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all <?= $categoriaFiltro === 'Regras' ? 'bg-purple-900/40 text-purple-300 border border-purple-500/40' : 'text-gray-300 hover:text-white hover:bg-dark-800' ?>">
                <i class="fa-solid fa-shield-halved w-4 text-purple-400"></i> Regras
            </a>
        </nav>
    </div>

    <!-- Card Brand Tropa do Respawn -->
    <div class="relative overflow-hidden bg-gradient-to-br from-purple-950/40 to-dark-900 border border-purple-500/30 rounded-2xl p-6 text-center shadow-lg">
        <div class="w-14 h-14 mx-auto rounded-2xl bg-purple-900/40 border border-purple-400/30 flex items-center justify-center text-purple-300 text-2xl mb-3 shadow-[0_0_20px_rgba(168,85,247,0.3)]">
            <i class="fa-solid fa-crown"></i>
        </div>
        <h5 class="text-sm font-rajdhani font-black text-white uppercase tracking-wider">
            TROPA DO RESPAWN
        </h5>
        <p class="text-xs text-purple-400 mt-1 font-medium">
            Juntos somos mais fortes!
        </p>
    </div>

    <!-- Card Citação Gamer -->
    <div class="p-5 rounded-2xl bg-dark-900/60 border border-purple-900/30 text-center">
        <p class="text-xs italic text-purple-300/90 font-rajdhani font-semibold text-base tracking-wide">
            "Conhecimento também é uma arma."
        </p>
        <span class="block text-[11px] text-gray-500 mt-2 font-mono">
            — Tropa do Respawn
        </span>
    </div>

</aside>
