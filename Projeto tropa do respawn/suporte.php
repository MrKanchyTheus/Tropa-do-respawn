<?php
/**
 * Página de Suporte - Tropa do Respawn
 * Fiel ao mockup original do design: Central de atendimento, canais e formulário.
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Suporte da Tropa do Respawn | Central de Ajuda Gamer';
$usuarioAtual = usuarioLogado();

require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/nav.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">

    <!-- HERO DO SUPORTE -->
    <div class="relative overflow-hidden bg-gradient-to-r from-purple-950 via-dark-900 to-indigo-950/80 border border-purple-500/40 rounded-3xl p-6 sm:p-10 shadow-[0_0_35px_rgba(168,85,247,0.2)] mb-10">
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            
            <div class="max-w-2xl text-center md:text-left">
                <span class="inline-block px-3 py-1 rounded-full bg-purple-900/60 border border-purple-400/30 text-purple-300 text-xs font-mono uppercase font-semibold mb-3">
                    PRECISOU DE AJUDA?
                </span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-rajdhani font-black text-white uppercase tracking-wider leading-none">
                    SUPORTE DA <br>
                    <span class="text-purple-400">TROPA DO RESPAWN</span>
                </h1>
                <p class="mt-4 text-sm sm:text-base text-gray-300 leading-relaxed">
                    Aqui você encontra todas as formas de entrar em contato com a nossa equipe de suporte. Estamos sempre prontos para te ajudar!
                </p>
            </div>

            <!-- Mascote com Coroa e Frase Gamer -->
            <div class="shrink-0 flex flex-col items-center">
                <div class="w-28 h-28 rounded-3xl overflow-hidden border-2 border-purple-500/60 shadow-[0_0_25px_rgba(168,85,247,0.4)]">
                    <img src="assets/img/mascote.jpeg" alt="Mascote" class="w-full h-full object-cover">
                </div>
                <span class="mt-2 text-xs font-rajdhani font-bold text-purple-300 uppercase tracking-widest">
                    JUNTOS SOMOS MAIS FORTES!
                </span>
            </div>

        </div>
    </div>

    <!-- GRID PRINCIPAL DE SUPORTE (2 COLUNAS) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- COLUNA ESQUERDA: AUTOATENDIMENTO E DÚVIDAS -->
        <div class="lg:col-span-7 space-y-6">
            
            <div class="bg-dark-900 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-lg">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fa-solid fa-comments text-purple-400 text-xl"></i>
                    <h2 class="text-xl font-rajdhani font-bold text-white uppercase tracking-wider">
                        TIRE SUAS DÚVIDAS
                    </h2>
                </div>
                <p class="text-xs text-gray-400 leading-relaxed mb-6">
                    Antes de entrar em contato, verifique se sua dúvida já não está em nossas perguntas frequentes ou na nossa Wiki. Isso pode te ajudar mais rápido!
                </p>

                <!-- Grid de 4 Cards de Dúvidas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    
                    <!-- Card 1: Perguntas Frequentes -->
                    <button type="button" onclick="alert('1. Como subir de nível? Participe dos fóruns e avalie tópicos.<br>2. Como criar fóruns? Crie sua conta e comprove a idade mínima de 14 anos.<br>3. Posso editar meus tópicos? Sim, através do seu perfil.', 'Perguntas Frequentes')" class="text-left group p-4 rounded-2xl bg-dark-950 border border-purple-900/30 hover:border-purple-500/50 transition-all flex items-start justify-between">
                        <div>
                            <div class="w-8 h-8 rounded-lg bg-purple-950/70 border border-purple-500/30 flex items-center justify-center text-purple-400 mb-2">
                                <i class="fa-solid fa-circle-question"></i>
                            </div>
                            <h4 class="text-sm font-rajdhani font-bold text-white uppercase">PERGUNTAS FREQUENTES</h4>
                            <p class="text-[11px] text-gray-400 mt-0.5">Veja as dúvidas mais comuns e respostas.</p>
                        </div>
                        <i class="fa-solid fa-chevron-right text-xs text-gray-500 group-hover:text-purple-400 mt-2 transition-all"></i>
                    </button>

                    <!-- Card 2: Wiki do Respawn -->
                    <a href="wiki.php" class="group p-4 rounded-2xl bg-dark-950 border border-purple-900/30 hover:border-purple-500/50 transition-all flex items-start justify-between">
                        <div>
                            <div class="w-8 h-8 rounded-lg bg-purple-950/70 border border-purple-500/30 flex items-center justify-center text-purple-400 mb-2">
                                <i class="fa-solid fa-book-open"></i>
                            </div>
                            <h4 class="text-sm font-rajdhani font-bold text-white uppercase">WIKI DO RESPAWN</h4>
                            <p class="text-[11px] text-gray-400 mt-0.5">Tutoriais, guias e listas completas.</p>
                        </div>
                        <i class="fa-solid fa-chevron-right text-xs text-gray-500 group-hover:text-purple-400 mt-2 transition-all"></i>
                    </a>

                    <!-- Card 3: Guias e Tutoriais -->
                    <a href="wiki.php?categoria=Tutoriais" class="group p-4 rounded-2xl bg-dark-950 border border-purple-900/30 hover:border-purple-500/50 transition-all flex items-start justify-between">
                        <div>
                            <div class="w-8 h-8 rounded-lg bg-purple-950/70 border border-purple-500/30 flex items-center justify-center text-purple-400 mb-2">
                                <i class="fa-solid fa-gamepad"></i>
                            </div>
                            <h4 class="text-sm font-rajdhani font-bold text-white uppercase">GUIAS E TUTORIAIS</h4>
                            <p class="text-[11px] text-gray-400 mt-0.5">Aprenda mais sobre nossos jogos.</p>
                        </div>
                        <i class="fa-solid fa-chevron-right text-xs text-gray-500 group-hover:text-purple-400 mt-2 transition-all"></i>
                    </a>

                    <!-- Card 4: Comunidade -->
                    <a href="comunidade.php" class="group p-4 rounded-2xl bg-dark-950 border border-purple-900/30 hover:border-purple-500/50 transition-all flex items-start justify-between">
                        <div>
                            <div class="w-8 h-8 rounded-lg bg-purple-950/70 border border-purple-500/30 flex items-center justify-center text-purple-400 mb-2">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <h4 class="text-sm font-rajdhani font-bold text-white uppercase">COMUNIDADE</h4>
                            <p class="text-[11px] text-gray-400 mt-0.5">Tire dúvidas com outros membros da tropa.</p>
                        </div>
                        <i class="fa-solid fa-chevron-right text-xs text-gray-500 group-hover:text-purple-400 mt-2 transition-all"></i>
                    </a>

                </div>

                <!-- Barra: Não encontrou o que precisa? -->
                <div class="mt-6 p-4 rounded-2xl bg-purple-950/30 border border-purple-500/30 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fa-regular fa-lightbulb text-purple-400 text-lg"></i>
                        <div>
                            <span class="block text-xs font-rajdhani font-bold text-white uppercase">NÃO ENCONTROU O QUE PRECISA?</span>
                            <span class="block text-[11px] text-gray-400">Preencha o formulário abaixo que nossa equipe responderá o quanto antes!</span>
                        </div>
                    </div>
                    <a href="#formulario" class="px-3 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-500 text-white text-xs font-semibold shrink-0">
                        Ir ao form <i class="fa-solid fa-arrow-down text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- FORMULÁRIO DE SUPORTE -->
            <div id="formulario" class="bg-dark-900 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-lg">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fa-solid fa-envelope-open-text text-purple-400 text-xl"></i>
                    <h3 class="text-xl font-rajdhani font-bold text-white uppercase tracking-wider">
                        ENVIAR MENSAGEM AO SUPORTE
                    </h3>
                </div>
                <p class="text-xs text-gray-400 mb-6">
                    Preencha os campos abaixo com detalhes da sua solicitação ou relatório de bug.
                </p>

                <form action="actions/suporte_enviar.php" method="POST" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 uppercase mb-1">Seu Nome</label>
                            <input type="text" name="nome" required value="<?= e($usuarioAtual['nome_completo'] ?? '') ?>" placeholder="Como prefere ser chamado" class="w-full px-4 py-2.5 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white focus:outline-none focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 uppercase mb-1">Seu E-mail</label>
                            <input type="email" name="email" required value="<?= e($usuarioAtual['email'] ?? '') ?>" placeholder="seuemail@exemplo.com" class="w-full px-4 py-2.5 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white focus:outline-none focus:border-purple-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-1">Categoria do Chamado</label>
                        <select name="categoria" class="w-full px-4 py-2.5 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white focus:outline-none focus:border-purple-500">
                            <option value="Dúvida sobre Fóruns ou Wiki">Dúvida sobre Fóruns ou Wiki</option>
                            <option value="Problema com Conta / Login">Problema com Conta / Login</option>
                            <option value="Reportar Bug no Site">Reportar Bug no Site</option>
                            <option value="Sugestão para a Comunidade">Sugestão para a Comunidade</option>
                            <option value="Outro Assunto">Outro Assunto</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-1">Mensagem ou Detalhes</label>
                        <textarea name="mensagem" rows="4" required placeholder="Descreva sua solicitação com o máximo de informações possível..." class="w-full px-4 py-2.5 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white focus:outline-none focus:border-purple-500"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl font-rajdhani font-bold uppercase tracking-wider text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 shadow-[0_0_20px_rgba(168,85,247,0.4)] transition-all">
                        ENVIAR SOLICITAÇÃO <i class="fa-solid fa-paper-plane text-xs ml-1"></i>
                    </button>
                </form>
            </div>

        </div>

        <!-- COLUNA DIREITA: CANAIS DE CONTATO E HORÁRIO -->
        <div class="lg:col-span-5 space-y-6">
            
            <div class="bg-dark-900 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-lg">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fa-solid fa-headset text-purple-400 text-xl"></i>
                    <h3 class="text-xl font-rajdhani font-bold text-white uppercase tracking-wider">
                        CANAIS DE CONTATO
                    </h3>
                </div>
                <p class="text-xs text-gray-400 mb-6">
                    Escolha o canal que preferir e fale com a gente!
                </p>

                <div class="space-y-3.5">
                    
                    <!-- DISCORD -->
                    <div class="p-4 rounded-2xl bg-dark-950 border border-purple-900/30 flex items-center justify-between">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-indigo-950/80 border border-indigo-500/40 flex items-center justify-center text-indigo-400 text-lg">
                                <i class="fa-brands fa-discord"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-rajdhani font-bold text-white uppercase">DISCORD</h4>
                                <p class="text-[11px] text-gray-400">Fale diretamente no nosso servidor.</p>
                            </div>
                        </div>
                        <a href="https://discord.com" target="_blank" class="px-3.5 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-500 text-white font-rajdhani font-bold text-xs uppercase tracking-wider transition-all">
                            ENTRAR <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    <!-- E-MAIL -->
                    <div class="p-4 rounded-2xl bg-dark-950 border border-purple-900/30 flex items-center justify-between">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-purple-950/80 border border-purple-500/40 flex items-center justify-center text-purple-400 text-lg">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-rajdhani font-bold text-white uppercase">E-MAIL</h4>
                                <p class="text-[11px] text-gray-400">suporte@tropadorespawn.com</p>
                            </div>
                        </div>
                        <a href="mailto:suporte@tropadorespawn.com" class="px-3.5 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-500 text-white font-rajdhani font-bold text-xs uppercase tracking-wider transition-all">
                            ENVIAR <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    <!-- FORMULÁRIO -->
                    <div class="p-4 rounded-2xl bg-dark-950 border border-purple-900/30 flex items-center justify-between">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-purple-950/80 border border-purple-500/40 flex items-center justify-center text-purple-400 text-lg">
                                <i class="fa-solid fa-file-signature"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-rajdhani font-bold text-white uppercase">FORMULÁRIO</h4>
                                <p class="text-[11px] text-gray-400">Atendimento registrado e rastreável.</p>
                            </div>
                        </div>
                        <a href="#formulario" class="px-3.5 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-500 text-white font-rajdhani font-bold text-xs uppercase tracking-wider transition-all">
                            ACESSAR <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    <!-- WHATSAPP -->
                    <div class="p-4 rounded-2xl bg-dark-950 border border-purple-900/30 flex items-center justify-between">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-emerald-950/80 border border-emerald-500/40 flex items-center justify-center text-emerald-400 text-lg">
                                <i class="fa-brands fa-whatsapp"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-rajdhani font-bold text-white uppercase">WHATSAPP</h4>
                                <p class="text-[11px] text-gray-400">Canal rápido para a tropa.</p>
                            </div>
                        </div>
                        <a href="https://whatsapp.com" target="_blank" class="px-3.5 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-500 text-white font-rajdhani font-bold text-xs uppercase tracking-wider transition-all">
                            CHAMAR <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                </div>

                <!-- CARD HORÁRIO DE ATENDIMENTO -->
                <div class="mt-6 p-4 rounded-2xl bg-dark-950/70 border border-purple-900/40 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fa-regular fa-clock text-purple-400 text-lg"></i>
                        <div>
                            <span class="block text-xs font-rajdhani font-bold text-white uppercase">HORÁRIO DE ATENDIMENTO</span>
                            <span class="block text-[11px] text-gray-400">Segunda a Sexta • 10h às 22h (UTC -3)</span>
                        </div>
                    </div>
                    <span class="text-[10px] font-mono text-purple-400 font-bold">ESTAMOS AQUI POR VOCÊ!</span>
                </div>

            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
