<?php
/**
 * Página de Registro / Criar Conta - Tropa do Respawn
 * Fiel ao mockup original do design: "FAÇA PARTE DA TROPA!"
 * Inclui campo 'Data de Nascimento' para o cumprimento do requisito de idade mínima.
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

if (estaLogado()) {
    header('Location: wiki.php');
    exit;
}

$pageTitle = 'Criar Sua Conta | Tropa do Respawn';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/nav.php';
?>

<div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        
        <!-- COLUNA ESQUERDA: APRESENTAÇÃO E BENEFÍCIOS -->
        <div class="lg:col-span-5 flex flex-col justify-between">
            <div>
                <h1 class="text-4xl sm:text-5xl font-black font-rajdhani uppercase tracking-tight text-white leading-tight">
                    FAÇA PARTE DA <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-purple-300 to-indigo-400 neon-text-purple">
                        TROPA!
                    </span>
                </h1>

                <p class="mt-4 text-gray-300 text-sm leading-relaxed">
                    Crie sua conta e tenha acesso a dicas exclusivas, guias completas, chat da comunidade, fóruns de discussão e muito mais!
                </p>

                <!-- Benefícios -->
                <div class="mt-8 space-y-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-purple-950/60 border border-purple-500/40 flex items-center justify-center text-purple-400 shrink-0">
                            <i class="fa-solid fa-crosshairs text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-rajdhani font-bold text-white uppercase">Dicas e estratégias</h4>
                            <p class="text-xs text-gray-400">Melhore no seus jogos favoritos</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-purple-950/60 border border-purple-500/40 flex items-center justify-center text-purple-400 shrink-0">
                            <i class="fa-solid fa-users text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-rajdhani font-bold text-white uppercase">Comunidade ativa</h4>
                            <p class="text-xs text-gray-400">Converse e troque ideias nos fóruns</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-purple-950/60 border border-purple-500/40 flex items-center justify-center text-purple-400 shrink-0">
                            <i class="fa-solid fa-book-open text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-rajdhani font-bold text-white uppercase">Guias e Wiki</h4>
                            <p class="text-xs text-gray-400">Tudo que você precisa saber</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-purple-950/60 border border-purple-500/40 flex items-center justify-center text-purple-400 shrink-0">
                            <i class="fa-solid fa-trophy text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-rajdhani font-bold text-white uppercase">Conquistas e perfil</h4>
                            <p class="text-xs text-gray-400">Acompanhe seu progresso gamer</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 50k Jogadores -->
            <div class="mt-8 p-4 rounded-2xl bg-dark-900/80 border border-purple-500/30 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-600/30 border border-purple-500/40 flex items-center justify-center text-purple-300 text-xl shrink-0">
                    <i class="fa-solid fa-users-rays"></i>
                </div>
                <div>
                    <span class="block text-[11px] font-mono uppercase text-gray-400">Junte-se a mais de</span>
                    <span class="block text-base font-rajdhani font-bold text-purple-400">50.000 JOGADORES</span>
                    <span class="block text-[11px] text-gray-400">e faça parte dessa tropa!</span>
                </div>
            </div>
        </div>

        <!-- COLUNA DIREITA: FORMULÁRIO DE REGISTRO -->
        <div class="lg:col-span-7">
            <div class="bg-dark-900 border border-purple-900/50 rounded-3xl p-6 sm:p-8 shadow-[0_0_35px_rgba(168,85,247,0.2)]">
                
                <div class="text-center mb-6">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-purple-950/70 border border-purple-500/40 flex items-center justify-center text-purple-400 text-xl mb-3">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <h2 class="text-2xl font-rajdhani font-black text-white uppercase tracking-wider">
                        CRIAR SUA CONTA
                    </h2>
                    <p class="text-xs text-gray-400 mt-1">
                        É rápido, fácil e grátis! 🚀
                    </p>
                </div>

                <form action="actions/auth_register.php" method="POST" class="space-y-4">
                    
                    <!-- Linha: Usuário e Nome Completo -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="username" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                                Nome de usuário
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                                    <i class="fa-regular fa-user text-sm"></i>
                                </div>
                                <input type="text" id="username" name="username" required placeholder="Nick gamer (ex: CyberPro)" class="w-full pl-10 pr-3 py-2.5 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all">
                            </div>
                        </div>

                        <div>
                            <label for="nome_completo" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                                Nome completo
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                                    <i class="fa-regular fa-id-card text-sm"></i>
                                </div>
                                <input type="text" id="nome_completo" name="nome_completo" required placeholder="Seu nome e sobrenome" class="w-full pl-10 pr-3 py-2.5 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- E-mail -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                            E-mail
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                                <i class="fa-regular fa-envelope text-sm"></i>
                            </div>
                            <input type="email" id="email" name="email" required placeholder="seuemail@exemplo.com" class="w-full pl-10 pr-3 py-2.5 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all">
                        </div>
                    </div>

                    <!-- Senha e Confirmar Senha -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="reg_senha" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                                Senha
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                                    <i class="fa-solid fa-lock text-sm"></i>
                                </div>
                                <input type="password" id="reg_senha" name="senha" required minlength="6" placeholder="Mínimo 6 dígitos" class="w-full pl-10 pr-10 py-2.5 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all">
                                <button type="button" data-toggle-password="reg_senha" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-purple-400">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="confirmar_senha" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                                Confirmar senha
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                                    <i class="fa-solid fa-shield-halved text-sm"></i>
                                </div>
                                <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6" placeholder="Repita sua senha" class="w-full pl-10 pr-10 py-2.5 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all">
                                <button type="button" data-toggle-password="confirmar_senha" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-purple-400">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Data de Nascimento (Requisito da Regra de Negócio de Idade Mínima) -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="data_nascimento" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                Data de nascimento
                            </label>
                            <span class="text-[11px] font-mono text-purple-400" title="Necessário ter idade mínima para publicar tópicos na comunidade">
                                *Idade mínima para criar fóruns: <?= IDADE_MINIMA_FORUM ?> anos
                            </span>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                                <i class="fa-regular fa-calendar-days text-sm"></i>
                            </div>
                            <input type="date" id="data_nascimento" name="data_nascimento" required max="<?= date('Y-m-d') ?>" class="w-full pl-10 pr-3 py-2.5 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all">
                        </div>
                    </div>

                    <!-- Termos de Uso -->
                    <div class="pt-1">
                        <label class="flex items-start gap-2.5 cursor-pointer text-xs text-gray-400 hover:text-gray-300">
                            <input type="checkbox" name="termos" required class="mt-0.5 w-4 h-4 rounded bg-dark-950 border-purple-900/50 text-purple-600 focus:ring-purple-500">
                            <span>
                                Eu concordo com os 
                                <a href="javascript:void(0)" onclick="alert('Termos de Uso da Tropa do Respawn: Respeito mútuo, proibido spam, e conteúdo focado em jogos e cooperação.', 'Termos de Uso')" class="text-purple-400 hover:underline">Termos de Uso</a> 
                                e a 
                                <a href="javascript:void(0)" onclick="alert('Política de Privacidade: Seus dados estão seguros e nunca são vendidos a terceiros.', 'Política de Privacidade')" class="text-purple-400 hover:underline">Política de Privacidade</a>.
                            </span>
                        </label>
                    </div>

                    <!-- Botão de Criação -->
                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-sm font-rajdhani font-bold tracking-wider uppercase text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 shadow-[0_0_20px_rgba(168,85,247,0.4)] transition-all flex items-center justify-center gap-2">
                        CRIAR CONTA <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>

                </form>

                <!-- Divisor Social -->
                <div class="relative my-5 text-center">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-purple-900/30"></div>
                    </div>
                    <span class="relative px-3 bg-dark-900 text-xs text-gray-500 uppercase font-mono">
                        ou
                    </span>
                </div>

                <!-- Botões Sociais -->
                <div class="grid grid-cols-3 gap-3">
                    <button type="button" onclick="alert('Cadastro integrado com Google em breve!', 'OAuth Social')" class="flex items-center justify-center gap-2 py-2 px-3 bg-dark-950 border border-purple-900/30 hover:border-purple-500/50 rounded-xl text-xs text-gray-300 hover:text-white transition-all">
                        <i class="fa-brands fa-google text-red-400"></i> Google
                    </button>
                    <button type="button" onclick="alert('Cadastro integrado com Discord em breve!', 'OAuth Social')" class="flex items-center justify-center gap-2 py-2 px-3 bg-dark-950 border border-purple-900/30 hover:border-purple-500/50 rounded-xl text-xs text-gray-300 hover:text-white transition-all">
                        <i class="fa-brands fa-discord text-indigo-400"></i> Discord
                    </button>
                    <button type="button" onclick="alert('Cadastro integrado com Xbox em breve!', 'OAuth Social')" class="flex items-center justify-center gap-2 py-2 px-3 bg-dark-950 border border-purple-900/30 hover:border-purple-500/50 rounded-xl text-xs text-gray-300 hover:text-white transition-all">
                        <i class="fa-brands fa-xbox text-green-400"></i> Xbox
                    </button>
                </div>

                <!-- Link para Login -->
                <div class="mt-5 text-center text-xs text-gray-400">
                    Já tem uma conta? 
                    <a href="login.php" class="text-purple-400 hover:text-purple-300 font-bold underline ml-1">
                        Entrar
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
