<?php
/**
 * Página Inicial (Início) - Tropa do Respawn
 * Fiel ao design original: "DICAS QUE TE LEVAM MAIS LONGE"
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Tropa do Respawn | Dicas Que Te Levam Mais Longe';
$usuarioAtual = usuarioLogado();

$pdo = obterConexao();

// Busca jogos e contadores para os destaques
$totalJogos = (int) $pdo->query("SELECT COUNT(*) FROM jogos")->fetchColumn();
$totalArtigos = (int) $pdo->query("SELECT COUNT(*) FROM artigos_wiki")->fetchColumn();
$totalForuns = (int) $pdo->query("SELECT COUNT(*) FROM foruns")->fetchColumn();

require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="flex-1 pb-16">

    <!-- HERO SECTION -->
    <section class="relative overflow-hidden pt-8 pb-16 lg:py-20 border-b border-purple-900/30">
        <!-- Luz de fundo roxa ambiente -->
        <div class="absolute top-1/4 left-1/3 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-purple-700/20 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute top-1/3 right-1/4 w-80 h-80 bg-indigo-600/15 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Coluna de Texto Hero -->
                <div class="lg:col-span-7 flex flex-col items-start">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-purple-950/70 border border-purple-500/40 text-purple-300 text-xs font-semibold tracking-wide mb-6 shadow-[0_0_15px_rgba(168,85,247,0.25)]">
                        <span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>
                        A Maior Wiki de Dicas & Estratégias Gamers
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black font-rajdhani uppercase tracking-tight text-white leading-none">
                        DICAS QUE TE <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-purple-300 to-indigo-400 neon-text-purple">
                            LEVAM MAIS LONGE
                        </span>
                    </h1>

                    <p class="mt-6 text-gray-300 text-base sm:text-lg max-w-xl font-normal leading-relaxed">
                        Guias, estratégias, tier lists e muito mais dos melhores jogos. Tudo em um só lugar com uma comunidade pronta para te ajudar a vencer!
                    </p>

                    <!-- Botões de Ação Hero -->
                    <div class="mt-8 flex flex-wrap items-center gap-4">
                        <?php if (!$usuarioAtual): ?>
                            <a href="registro.php" class="px-7 py-3.5 rounded-xl font-rajdhani font-bold text-sm tracking-wider uppercase text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 shadow-[0_0_25px_rgba(168,85,247,0.45)] transition-all flex items-center gap-2">
                                <i class="fa-solid fa-user-plus text-sm"></i> CRIE SUA CONTA
                            </a>
                            <a href="login.php" class="px-7 py-3.5 rounded-xl font-rajdhani font-bold text-sm tracking-wider uppercase text-gray-200 hover:text-white bg-dark-900 border border-purple-500/30 hover:border-purple-400 transition-all flex items-center gap-2">
                                <i class="fa-solid fa-arrow-right-to-bracket text-sm text-purple-400"></i> ENTRAR NO SITE
                            </a>
                        <?php else: ?>
                            <a href="wiki.php" class="px-7 py-3.5 rounded-xl font-rajdhani font-bold text-sm tracking-wider uppercase text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 shadow-[0_0_25px_rgba(168,85,247,0.45)] transition-all flex items-center gap-2">
                                <i class="fa-solid fa-book-open text-sm"></i> EXPLORAR A WIKI
                            </a>
                            <a href="forum_novo.php" class="px-7 py-3.5 rounded-xl font-rajdhani font-bold text-sm tracking-wider uppercase text-gray-200 hover:text-white bg-dark-900 border border-purple-500/30 hover:border-purple-400 transition-all flex items-center gap-2">
                                <i class="fa-solid fa-plus text-sm text-purple-400"></i> CRIAR NOVO FÓRUM
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Métricas Rápidas -->
                    <div class="mt-10 pt-8 border-t border-purple-900/30 grid grid-cols-3 gap-6 font-mono text-center sm:text-left">
                        <div>
                            <span class="block text-2xl font-bold font-rajdhani text-white"><?= $totalArtigos ?>+</span>
                            <span class="text-xs text-purple-400">Guias & Dicas</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-bold font-rajdhani text-white"><?= $totalForuns ?>+</span>
                            <span class="text-xs text-purple-400">Tópicos Ativos</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-bold font-rajdhani text-white">50k+</span>
                            <span class="text-xs text-purple-400">Membros da Tropa</span>
                        </div>
                    </div>
                </div>

                <!-- Coluna de Imagem / Mascote Hero -->
                <div class="lg:col-span-5 flex justify-center relative">
                    <div class="relative w-80 sm:w-96 rounded-3xl overflow-hidden border-2 border-purple-500/40 shadow-[0_0_50px_rgba(168,85,247,0.4)] group">
                        <img src="assets/img/mascote.jpeg" alt="Gamer Tropa do Respawn" class="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark-950 via-transparent to-transparent"></div>
                        
                        <!-- Badge flutuante no canto -->
                        <div class="absolute bottom-4 left-4 right-4 p-3.5 bg-dark-950/85 backdrop-blur-md rounded-2xl border border-purple-500/30 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-900/50 flex items-center justify-center text-purple-400">
                                    <i class="fa-solid fa-bolt-lightning text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-rajdhani font-bold text-white uppercase">Tropa do Respawn</p>
                                    <p class="text-[11px] text-purple-300">Conhecimento também é uma arma</p>
                                </div>
                            </div>
                            <span class="text-xs font-mono text-purple-400 font-bold">#1 WIKI</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SEÇÃO: CATEGORIAS DE JOGOS -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-gamepad text-purple-400 text-xl"></i>
                <h2 class="text-2xl font-rajdhani font-black text-white uppercase tracking-wider">
                    CATEGORIAS DE JOGOS
                </h2>
            </div>
            <a href="jogos.php" class="text-xs font-rajdhani font-bold uppercase text-purple-400 hover:text-purple-300 tracking-wider flex items-center gap-1.5 transition-colors">
                VER TODOS <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            
            <!-- Card FPS -->
            <a href="jogos.php?categoria=FPS" class="group bg-dark-900 border border-purple-900/40 hover:border-purple-500/60 rounded-2xl p-4 text-center gamer-card shadow-lg flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl bg-purple-950/60 border border-purple-500/30 flex items-center justify-center text-purple-400 group-hover:text-white group-hover:bg-purple-600 transition-all mb-3 shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                    <i class="fa-solid fa-crosshairs text-2xl"></i>
                </div>
                <h3 class="text-sm font-rajdhani font-bold text-white uppercase tracking-wider">FPS</h3>
                <span class="text-[11px] text-purple-400 font-medium mt-1 group-hover:underline flex items-center gap-1">
                    Ver dicas <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </span>
            </a>

            <!-- Card MOBA -->
            <a href="jogos.php?categoria=MOBA" class="group bg-dark-900 border border-purple-900/40 hover:border-purple-500/60 rounded-2xl p-4 text-center gamer-card shadow-lg flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl bg-purple-950/60 border border-purple-500/30 flex items-center justify-center text-purple-400 group-hover:text-white group-hover:bg-purple-600 transition-all mb-3 shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                    <i class="fa-solid fa-shield-halved text-2xl"></i>
                </div>
                <h3 class="text-sm font-rajdhani font-bold text-white uppercase tracking-wider">MOBA</h3>
                <span class="text-[11px] text-purple-400 font-medium mt-1 group-hover:underline flex items-center gap-1">
                    Ver dicas <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </span>
            </a>

            <!-- Card BATTLE ROYALE -->
            <a href="jogos.php?categoria=Battle Royale" class="group bg-dark-900 border border-purple-900/40 hover:border-purple-500/60 rounded-2xl p-4 text-center gamer-card shadow-lg flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl bg-purple-950/60 border border-purple-500/30 flex items-center justify-center text-purple-400 group-hover:text-white group-hover:bg-purple-600 transition-all mb-3 shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                    <i class="fa-solid fa-parachute-box text-2xl"></i>
                </div>
                <h3 class="text-sm font-rajdhani font-bold text-white uppercase tracking-wider">BATTLE ROYALE</h3>
                <span class="text-[11px] text-purple-400 font-medium mt-1 group-hover:underline flex items-center gap-1">
                    Ver dicas <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </span>
            </a>

            <!-- Card SANDBOX -->
            <a href="jogos.php?categoria=Sandbox" class="group bg-dark-900 border border-purple-900/40 hover:border-purple-500/60 rounded-2xl p-4 text-center gamer-card shadow-lg flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl bg-purple-950/60 border border-purple-500/30 flex items-center justify-center text-purple-400 group-hover:text-white group-hover:bg-purple-600 transition-all mb-3 shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                    <i class="fa-solid fa-cubes text-2xl"></i>
                </div>
                <h3 class="text-sm font-rajdhani font-bold text-white uppercase tracking-wider">SANDBOX</h3>
                <span class="text-[11px] text-purple-400 font-medium mt-1 group-hover:underline flex items-center gap-1">
                    Ver dicas <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </span>
            </a>

            <!-- Card ESPORTS -->
            <a href="jogos.php?categoria=Esports" class="group bg-dark-900 border border-purple-900/40 hover:border-purple-500/60 rounded-2xl p-4 text-center gamer-card shadow-lg flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl bg-purple-950/60 border border-purple-500/30 flex items-center justify-center text-purple-400 group-hover:text-white group-hover:bg-purple-600 transition-all mb-3 shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                    <i class="fa-solid fa-trophy text-2xl"></i>
                </div>
                <h3 class="text-sm font-rajdhani font-bold text-white uppercase tracking-wider">ESPORTS</h3>
                <span class="text-[11px] text-purple-400 font-medium mt-1 group-hover:underline flex items-center gap-1">
                    Ver dicas <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </span>
            </a>

            <!-- Card RPG -->
            <a href="jogos.php?categoria=RPG" class="group bg-dark-900 border border-purple-900/40 hover:border-purple-500/60 rounded-2xl p-4 text-center gamer-card shadow-lg flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl bg-purple-950/60 border border-purple-500/30 flex items-center justify-center text-purple-400 group-hover:text-white group-hover:bg-purple-600 transition-all mb-3 shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                    <i class="fa-solid fa-dragon text-2xl"></i>
                </div>
                <h3 class="text-sm font-rajdhani font-bold text-white uppercase tracking-wider">RPG</h3>
                <span class="text-[11px] text-purple-400 font-medium mt-1 group-hover:underline flex items-center gap-1">
                    Ver dicas <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </span>
            </a>

        </div>
    </section>

    <!-- SEÇÃO: CARDS PROMOCIONAIS (CRIE SEU PERFIL & SUPORTE DA RESPAWN) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Card Crie Seu Perfil -->
            <div class="relative overflow-hidden bg-gradient-to-r from-dark-900 to-purple-950/40 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-2 text-purple-400 text-xs font-rajdhani font-bold uppercase mb-2">
                        <i class="fa-solid fa-user-circle"></i> CRIE SEU PERFIL
                    </div>
                    <h3 class="text-xl sm:text-2xl font-rajdhani font-bold text-white">
                        Monte seu perfil, acompanhe suas conquistas e participe da comunidade!
                    </h3>
                    <div class="mt-4">
                        <?php if ($usuarioAtual): ?>
                            <a href="perfil.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-rajdhani font-bold text-xs uppercase tracking-wider shadow-[0_0_15px_rgba(168,85,247,0.35)] transition-all">
                                VER MEU PERFIL <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        <?php else: ?>
                            <a href="registro.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-rajdhani font-bold text-xs uppercase tracking-wider shadow-[0_0_15px_rgba(168,85,247,0.35)] transition-all">
                                CRIAR PERFIL <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Insígnia Visual Gamer -->
                <div class="shrink-0 flex flex-col items-center justify-center p-4 rounded-2xl bg-dark-950/80 border border-purple-500/30 text-center w-36">
                    <div class="w-12 h-12 rounded-full bg-purple-900/60 border border-purple-400 flex items-center justify-center text-purple-300 text-xl mb-1 shadow-[0_0_15px_rgba(168,85,247,0.4)]">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                    <span class="text-xs font-mono font-bold text-white">Nível 23</span>
                    <span class="text-[10px] font-mono text-purple-400">XP 4250/5000</span>
                    <div class="flex gap-1 mt-2 text-amber-400 text-[10px]">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>
            </div>

            <!-- Card Suporte da Respawn -->
            <div class="relative overflow-hidden bg-gradient-to-r from-dark-900 to-indigo-950/40 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-2 text-indigo-400 text-xs font-rajdhani font-bold uppercase mb-2">
                        <i class="fa-solid fa-headset"></i> SUPORTE DA RESPAWN
                    </div>
                    <h3 class="text-xl sm:text-2xl font-rajdhani font-bold text-white">
                        Está com problemas ou precisa de ajuda? Nossa equipe está aqui para você!
                    </h3>
                    <div class="mt-4">
                        <a href="suporte.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-rajdhani font-bold text-xs uppercase tracking-wider shadow-[0_0_15px_rgba(168,85,247,0.35)] transition-all">
                            ABRIR CHAMADO <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Ícone Gamer Headset -->
                <div class="shrink-0 flex flex-col items-center justify-center p-4 rounded-2xl bg-dark-950/80 border border-purple-500/30 text-center w-36">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-950/60 border border-indigo-500/30 flex items-center justify-center text-purple-400 text-3xl mb-1 shadow-[0_0_15px_rgba(99,102,241,0.3)]">
                        <i class="fa-solid fa-headphones-simple"></i>
                    </div>
                    <span class="text-[11px] font-rajdhani font-bold text-gray-300 uppercase">Atendimento</span>
                    <span class="text-[10px] font-mono text-purple-400">10h às 22h</span>
                </div>
            </div>

        </div>
    </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
