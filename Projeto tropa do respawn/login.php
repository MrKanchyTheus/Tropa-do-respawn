<?php
/**
 * Página de Login - Tropa do Respawn
 * Fiel ao mockup original do design: "FAÇA LOGIN E FAÇA PARTE DA TROPA!"
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

// Se já estiver logado, redireciona para a wiki
if (estaLogado()) {
    header('Location: wiki.php');
    exit;
}

$pageTitle = 'Entrar na Sua Conta | Tropa do Respawn';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/nav.php';
?>

<div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        
        <!-- COLUNA ESQUERDA: APRESENTAÇÃO E BENEFÍCIOS -->
        <div class="lg:col-span-6 flex flex-col justify-between">
            <div>
                <h1 class="text-4xl sm:text-5xl font-black font-rajdhani uppercase tracking-tight text-white leading-tight">
                    FAÇA LOGIN E <br>
                    FAÇA PARTE DA <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-purple-300 to-indigo-400 neon-text-purple">
                        TROPA!
                    </span>
                </h1>

                <p class="mt-4 text-gray-300 text-sm leading-relaxed max-w-md">
                    Entre na sua conta e tenha acesso a dicas, guias exclusivos, seu perfil, conquistas e muito mais!
                </p>

                <!-- Lista de Benefícios com Ícones -->
                <div class="mt-8 space-y-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-purple-950/60 border border-purple-500/40 flex items-center justify-center text-purple-400 shrink-0">
                            <i class="fa-solid fa-crosshairs text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-rajdhani font-bold text-white uppercase">Dicas e estratégias</h4>
                            <p class="text-xs text-gray-400">Melhore nos seus jogos favoritos</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-purple-950/60 border border-purple-500/40 flex items-center justify-center text-purple-400 shrink-0">
                            <i class="fa-solid fa-users text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-rajdhani font-bold text-white uppercase">Comunidade ativa</h4>
                            <p class="text-xs text-gray-400">Converse, crie fóruns e troque ideias</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-purple-950/60 border border-purple-500/40 flex items-center justify-center text-purple-400 shrink-0">
                            <i class="fa-solid fa-book-open text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-rajdhani font-bold text-white uppercase">Guias e Wiki</h4>
                            <p class="text-xs text-gray-400">Tudo o que você precisa saber</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-purple-950/60 border border-purple-500/40 flex items-center justify-center text-purple-400 shrink-0">
                            <i class="fa-solid fa-trophy text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-rajdhani font-bold text-white uppercase">Conquistas e perfil</h4>
                            <p class="text-xs text-gray-400">Acompanhe seu progresso e suba de nível</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 50.000 Jogadores -->
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

        <!-- COLUNA DIREITA: FORMULÁRIO DE LOGIN -->
        <div class="lg:col-span-6">
            <div class="bg-dark-900 border border-purple-900/50 rounded-3xl p-6 sm:p-8 shadow-[0_0_35px_rgba(168,85,247,0.2)]">
                
                <div class="text-center mb-6">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-purple-950/70 border border-purple-500/40 flex items-center justify-center text-purple-400 text-xl mb-3">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    </div>
                    <h2 class="text-2xl font-rajdhani font-black text-white uppercase tracking-wider">
                        ENTRAR NA SUA CONTA
                    </h2>
                    <p class="text-xs text-gray-400 mt-1">
                        Bem-vindo de volta, guerreiro! 👋
                    </p>
                </div>

                <form action="actions/auth_login.php" method="POST" class="space-y-4">
                    
                    <!-- Campo E-mail / Usuário -->
                    <div>
                        <label for="login" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                            E-mail ou nome de usuário
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                                <i class="fa-regular fa-user text-sm"></i>
                            </div>
                            <input type="text" id="login" name="login" required placeholder="Digite seu e-mail ou usuário" class="w-full pl-10 pr-4 py-3 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all">
                        </div>
                    </div>

                    <!-- Campo Senha -->
                    <div>
                        <label for="senha" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                            Senha
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                                <i class="fa-solid fa-lock text-sm"></i>
                            </div>
                            <input type="password" id="senha" name="senha" required placeholder="Digite sua senha" class="w-full pl-10 pr-11 py-3 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all">
                            <button type="button" data-toggle-password="senha" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-500 hover:text-purple-400 transition-colors" title="Mostrar/ocultar senha">
                                <i class="fa-regular fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Lembrar e Esqueci Senha -->
                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-gray-400 hover:text-gray-300">
                            <input type="checkbox" name="lembrar" class="w-4 h-4 rounded bg-dark-950 border-purple-900/50 text-purple-600 focus:ring-purple-500">
                            <span>Lembrar de mim</span>
                        </label>
                        <a href="javascript:void(0)" onclick="alert('Para recuperar sua senha, entre em contato com nosso Suporte ou use o canal do Discord da Tropa!', 'Recuperação de Acesso')" class="text-purple-400 hover:text-purple-300 font-medium">
                            Esqueceu sua senha?
                        </a>
                    </div>

                    <!-- Botão de Envio -->
                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-sm font-rajdhani font-bold tracking-wider uppercase text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 shadow-[0_0_20px_rgba(168,85,247,0.4)] transition-all flex items-center justify-center gap-2">
                        ENTRAR <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>

                </form>

                <!-- Divisor Social -->
                <div class="relative my-6 text-center">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-purple-900/30"></div>
                    </div>
                    <span class="relative px-3 bg-dark-900 text-xs text-gray-500 uppercase font-mono">
                        ou entre com
                    </span>
                </div>

                <!-- Botões Sociais -->
                <div class="grid grid-cols-3 gap-3">
                    <button type="button" onclick="alert('Login integrado com o Google em breve!', 'OAuth Social')" class="flex items-center justify-center gap-2 py-2.5 px-3 bg-dark-950 border border-purple-900/30 hover:border-purple-500/50 rounded-xl text-xs text-gray-300 hover:text-white transition-all">
                        <i class="fa-brands fa-google text-red-400"></i> Google
                    </button>
                    <button type="button" onclick="alert('Login integrado com o Discord em breve!', 'OAuth Social')" class="flex items-center justify-center gap-2 py-2.5 px-3 bg-dark-950 border border-purple-900/30 hover:border-purple-500/50 rounded-xl text-xs text-gray-300 hover:text-white transition-all">
                        <i class="fa-brands fa-discord text-indigo-400"></i> Discord
                    </button>
                    <button type="button" onclick="alert('Login integrado com Xbox em breve!', 'OAuth Social')" class="flex items-center justify-center gap-2 py-2.5 px-3 bg-dark-950 border border-purple-900/30 hover:border-purple-500/50 rounded-xl text-xs text-gray-300 hover:text-white transition-all">
                        <i class="fa-brands fa-xbox text-green-400"></i> Xbox
                    </button>
                </div>

                <!-- Link para Cadastro -->
                <div class="mt-6 text-center text-xs text-gray-400">
                    Ainda não tem uma conta? 
                    <a href="registro.php" class="text-purple-400 hover:text-purple-300 font-bold underline ml-1">
                        Criar conta
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
