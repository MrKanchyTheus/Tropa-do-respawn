<?php
/**
 * Barra de Navegação Superior (Header)
 * Inspirada exatamente no design dos mockups originais da Tropa do Respawn.
 */
require_once __DIR__ . '/auth.php';
$usuarioAtual = usuarioLogado();
$paginaAtiva = basename($_SERVER['PHP_SELF'], '.php');
?>
<header class="sticky top-0 z-50 bg-dark-950/90 backdrop-blur-md border-b border-purple-900/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 gap-4">
            
            <!-- Logo e Nome -->
            <a href="index.php" class="flex items-center gap-3 shrink-0 group">
                <div class="relative w-11 h-11 rounded-xl overflow-hidden border border-purple-500/40 p-0.5 group-hover:border-purple-400 shadow-[0_0_15px_rgba(168,85,247,0.3)] transition-all">
                    <img src="assets/img/logo.jpeg" alt="Tropa do Respawn" class="w-full h-full object-cover rounded-lg">
                </div>
                <div>
                    <span class="block text-xs font-rajdhani font-semibold tracking-wider text-purple-400 uppercase">Comunidade Gamer</span>
                    <span class="text-xl sm:text-2xl font-rajdhani font-black tracking-wider text-white uppercase group-hover:text-purple-300 transition-colors">
                        Tropa do <span class="text-purple-500">Respawn</span>
                    </span>
                </div>
            </a>

            <!-- Menu Desktop -->
            <nav class="hidden md:flex items-center gap-1 lg:gap-2">
                <a href="index.php" class="px-3.5 py-2 rounded-xl text-sm font-semibold tracking-wide transition-all <?= $paginaAtiva === 'index' ? 'text-purple-400 bg-purple-950/50 border border-purple-500/30' : 'text-gray-300 hover:text-white hover:bg-dark-850' ?>">
                    <i class="fa-solid fa-house mr-1.5 text-xs text-purple-400"></i> INÍCIO
                </a>
                <a href="wiki.php" class="px-3.5 py-2 rounded-xl text-sm font-semibold tracking-wide transition-all <?= $paginaAtiva === 'wiki' || $paginaAtiva === 'guia' ? 'text-purple-400 bg-purple-950/50 border border-purple-500/30' : 'text-gray-300 hover:text-white hover:bg-dark-850' ?>">
                    <i class="fa-solid fa-book-open mr-1.5 text-xs text-purple-400"></i> WIKI
                </a>
                <a href="jogos.php" class="px-3.5 py-2 rounded-xl text-sm font-semibold tracking-wide transition-all <?= $paginaAtiva === 'jogos' ? 'text-purple-400 bg-purple-950/50 border border-purple-500/30' : 'text-gray-300 hover:text-white hover:bg-dark-850' ?>">
                    <i class="fa-solid fa-gamepad mr-1.5 text-xs text-purple-400"></i> JOGOS
                </a>
                <a href="comunidade.php" class="px-3.5 py-2 rounded-xl text-sm font-semibold tracking-wide transition-all <?= $paginaAtiva === 'comunidade' || $paginaAtiva === 'forum_detalhe' || $paginaAtiva === 'forum_novo' ? 'text-purple-400 bg-purple-950/50 border border-purple-500/30' : 'text-gray-300 hover:text-white hover:bg-dark-850' ?>">
                    <i class="fa-solid fa-users mr-1.5 text-xs text-purple-400"></i> COMUNIDADE
                </a>
                <a href="historico.php" class="px-3.5 py-2 rounded-xl text-sm font-semibold tracking-wide transition-all <?= $paginaAtiva === 'historico' ? 'text-purple-400 bg-purple-950/50 border border-purple-500/30' : 'text-gray-300 hover:text-white hover:bg-dark-850' ?>" title="Auditoria e Histórico de Alterações">
                    <i class="fa-solid fa-clock-rotate-left mr-1.5 text-xs text-purple-400"></i> HISTÓRICO
                </a>
                <a href="suporte.php" class="px-3.5 py-2 rounded-xl text-sm font-semibold tracking-wide transition-all <?= $paginaAtiva === 'suporte' ? 'text-purple-400 bg-purple-950/50 border border-purple-500/30' : 'text-gray-300 hover:text-white hover:bg-dark-850' ?>">
                    <i class="fa-solid fa-headset mr-1.5 text-xs text-purple-400"></i> SUPORTE
                </a>
            </nav>

            <!-- Campo de Busca Desktop -->
            <div class="hidden xl:block relative w-64">
                <form action="wiki.php" method="GET">
                    <div class="relative">
                        <input type="text" name="busca" placeholder="Buscar na Tropa..." value="<?= isset($_GET['busca']) ? e($_GET['busca']) : '' ?>" class="w-full pl-10 pr-4 py-2 bg-dark-900 border border-purple-900/40 rounded-xl text-xs text-gray-200 placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-xs text-gray-400"></i>
                    </div>
                </form>
            </div>

            <!-- Ações do Usuário (Desktop) -->
            <div class="hidden sm:flex items-center gap-3">
                <?php if ($usuarioAtual): ?>
                    <!-- Notificação -->
                    <button type="button" class="relative p-2.5 rounded-xl bg-dark-900 border border-purple-900/40 text-gray-400 hover:text-purple-400 hover:border-purple-500/50 transition-all" title="Notificações">
                        <i class="fa-regular fa-bell text-sm"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-purple-500 rounded-full animate-ping"></span>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-purple-500 rounded-full"></span>
                    </button>

                    <!-- Botão de Criar Fórum Rápido -->
                    <a href="forum_novo.php" class="hidden lg:flex items-center gap-2 px-3.5 py-2 rounded-xl bg-purple-600/20 border border-purple-500/40 text-purple-300 hover:bg-purple-600/30 text-xs font-semibold tracking-wide transition-all">
                        <i class="fa-solid fa-plus text-xs"></i> Criar Fórum
                    </a>

                    <!-- Dropdown do Usuário Logado -->
                    <div class="relative group">
                        <button type="button" class="flex items-center gap-2.5 p-1.5 pr-3 rounded-xl bg-dark-900 border border-purple-900/40 hover:border-purple-500/50 transition-all">
                            <img src="<?= e($usuarioAtual['avatar']) ?>" alt="Avatar" class="w-8 h-8 rounded-lg object-cover border border-purple-500/40">
                            <div class="text-left hidden lg:block">
                                <span class="block text-xs font-bold text-white"><?= e($usuarioAtual['username']) ?></span>
                                <span class="block text-[10px] font-mono text-purple-400">Nv. <?= $usuarioAtual['nivel'] ?> • <?= $usuarioAtual['xp'] ?> XP</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 group-hover:text-purple-400 transition-transform"></i>
                        </button>

                        <!-- Menu Suspenso -->
                        <div class="absolute right-0 mt-2 w-56 bg-dark-900 border border-purple-500/40 rounded-xl shadow-[0_10px_25px_rgba(0,0,0,0.7)] py-2 hidden group-hover:block animate-in fade-in z-50">
                            <div class="px-4 py-2 border-b border-purple-900/30">
                                <p class="text-xs text-gray-400">Logado como</p>
                                <p class="text-sm font-bold text-white truncate"><?= e($usuarioAtual['nome_completo']) ?></p>
                                <p class="text-[11px] font-mono text-purple-400"><?= $usuarioAtual['idade'] ?> anos <?= $usuarioAtual['pode_criar_forum'] ? '• Apto a criar fóruns' : '• Idade mín. não atingida' ?></p>
                            </div>
                            <a href="perfil.php" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-gray-300 hover:text-white hover:bg-purple-950/40">
                                <i class="fa-solid fa-id-badge text-purple-400 w-4"></i> Meu Perfil Gamer
                            </a>
                            <a href="forum_novo.php" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-gray-300 hover:text-white hover:bg-purple-950/40">
                                <i class="fa-solid fa-plus-circle text-purple-400 w-4"></i> Criar Novo Fórum
                            </a>
                            <a href="historico.php?usuario=<?= urlencode($usuarioAtual['username']) ?>" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-gray-300 hover:text-white hover:bg-purple-950/40">
                                <i class="fa-solid fa-list-check text-purple-400 w-4"></i> Meu Histórico de Ações
                            </a>
                            <div class="border-t border-purple-900/30 my-1"></div>
                            <a href="actions/auth_logout.php" data-confirm-modal="Deseja realmente desconectar da sua conta gamer?" data-confirm-title="Encerrar Sessão" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-red-400 hover:bg-red-950/30">
                                <i class="fa-solid fa-arrow-right-from-bracket text-red-400 w-4"></i> Sair da Conta
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Visitante -->
                    <a href="login.php" class="px-4 py-2 rounded-xl text-xs font-semibold tracking-wide text-gray-200 hover:text-white hover:bg-dark-850 border border-purple-900/40 transition-all">
                        ENTRAR
                    </a>
                    <a href="registro.php" class="px-4 py-2 rounded-xl text-xs font-semibold tracking-wide text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 shadow-[0_0_15px_rgba(168,85,247,0.35)] transition-all">
                        <i class="fa-solid fa-user-plus mr-1"></i> CRIAR CONTA
                    </a>
                <?php endif; ?>
            </div>

            <!-- Botão Menu Mobile -->
            <button id="btn-mobile-menu" type="button" class="md:hidden p-2 rounded-xl bg-dark-900 border border-purple-900/40 text-gray-300 hover:text-purple-400">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
        </div>
    </div>

    <!-- Drawer Mobile -->
    <div id="mobile-drawer" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden md:hidden">
        <div class="fixed top-0 right-0 w-3/4 max-w-xs h-full bg-dark-900 border-l border-purple-900/40 p-6 flex flex-col justify-between overflow-y-auto">
            <div>
                <div class="flex items-center justify-between pb-6 border-b border-purple-900/30">
                    <div class="flex items-center gap-2">
                        <img src="assets/img/logo.jpeg" alt="Logo" class="w-8 h-8 rounded-lg object-cover">
                        <span class="font-rajdhani font-bold text-white text-lg">TROPA DO RESPAWN</span>
                    </div>
                    <button id="btn-close-mobile" type="button" class="text-gray-400 hover:text-white">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <!-- Links Mobile -->
                <nav class="flex flex-col gap-2 mt-6">
                    <a href="index.php" class="px-4 py-3 rounded-xl text-sm font-semibold <?= $paginaAtiva === 'index' ? 'text-purple-400 bg-purple-950/40 border border-purple-500/30' : 'text-gray-300 hover:bg-dark-800' ?>">
                        <i class="fa-solid fa-house mr-2 text-purple-400"></i> Início
                    </a>
                    <a href="wiki.php" class="px-4 py-3 rounded-xl text-sm font-semibold <?= $paginaAtiva === 'wiki' ? 'text-purple-400 bg-purple-950/40 border border-purple-500/30' : 'text-gray-300 hover:bg-dark-800' ?>">
                        <i class="fa-solid fa-book-open mr-2 text-purple-400"></i> Wiki & Guias
                    </a>
                    <a href="jogos.php" class="px-4 py-3 rounded-xl text-sm font-semibold <?= $paginaAtiva === 'jogos' ? 'text-purple-400 bg-purple-950/40 border border-purple-500/30' : 'text-gray-300 hover:bg-dark-800' ?>">
                        <i class="fa-solid fa-gamepad mr-2 text-purple-400"></i> Jogos
                    </a>
                    <a href="comunidade.php" class="px-4 py-3 rounded-xl text-sm font-semibold <?= $paginaAtiva === 'comunidade' ? 'text-purple-400 bg-purple-950/40 border border-purple-500/30' : 'text-gray-300 hover:bg-dark-800' ?>">
                        <i class="fa-solid fa-users mr-2 text-purple-400"></i> Comunidade & Fóruns
                    </a>
                    <a href="historico.php" class="px-4 py-3 rounded-xl text-sm font-semibold <?= $paginaAtiva === 'historico' ? 'text-purple-400 bg-purple-950/40 border border-purple-500/30' : 'text-gray-300 hover:bg-dark-800' ?>">
                        <i class="fa-solid fa-clock-rotate-left mr-2 text-purple-400"></i> Histórico de Auditoria
                    </a>
                    <a href="suporte.php" class="px-4 py-3 rounded-xl text-sm font-semibold <?= $paginaAtiva === 'suporte' ? 'text-purple-400 bg-purple-950/40 border border-purple-500/30' : 'text-gray-300 hover:bg-dark-800' ?>">
                        <i class="fa-solid fa-headset mr-2 text-purple-400"></i> Suporte
                    </a>
                </nav>
            </div>

            <!-- Botões Inferiores Mobile -->
            <div class="pt-6 border-t border-purple-900/30 flex flex-col gap-3">
                <?php if ($usuarioAtual): ?>
                    <div class="flex items-center gap-3 p-3 bg-dark-800 rounded-xl border border-purple-900/30">
                        <img src="<?= e($usuarioAtual['avatar']) ?>" class="w-10 h-10 rounded-lg object-cover">
                        <div>
                            <p class="text-sm font-bold text-white"><?= e($usuarioAtual['username']) ?></p>
                            <p class="text-xs text-purple-400"><?= $usuarioAtual['idade'] ?> anos</p>
                        </div>
                    </div>
                    <a href="forum_novo.php" class="w-full py-2.5 rounded-xl bg-purple-600 text-center text-xs font-bold text-white shadow-lg shadow-purple-600/30">
                        + Criar Fórum
                    </a>
                    <a href="actions/auth_logout.php" data-confirm-modal="Deseja realmente sair?" class="w-full py-2.5 rounded-xl bg-red-900/30 border border-red-500/40 text-center text-xs font-bold text-red-300">
                        Desconectar
                    </a>
                <?php else: ?>
                    <a href="login.php" class="w-full py-2.5 rounded-xl bg-dark-800 border border-purple-900/40 text-center text-xs font-bold text-gray-200">
                        Entrar
                    </a>
                    <a href="registro.php" class="w-full py-2.5 rounded-xl bg-purple-600 text-center text-xs font-bold text-white shadow-lg shadow-purple-600/30">
                        Criar Conta
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
