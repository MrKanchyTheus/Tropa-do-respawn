<?php
/**
 * Criação de Novo Fórum - Tropa do Respawn
 * Implementa estritamente a Regra de Negócio CORE (Narrativa 2):
 * - Conta logada obrigatória
 * - Idade mínima validada (IDADE_MINIMA_FORUM)
 * - Retorno de avisos em MODAL e TOAST
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Criar Fórum | Tropa do Respawn';
$usuario = usuarioLogado();
$pdo = obterConexao();

// Busca lista de jogos para o select
$jogos = $pdo->query("SELECT id, nome FROM jogos ORDER BY nome ASC")->fetchAll();

require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/nav.php';
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-1">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs font-mono text-gray-400 mb-6">
        <a href="index.php" class="hover:text-purple-400">Início</a>
        <span>/</span>
        <a href="comunidade.php" class="hover:text-purple-400">Comunidade</a>
        <span>/</span>
        <span class="text-purple-400">Criar Novo Fórum</span>
    </nav>

    <?php if (!$usuario): ?>
        <!-- BLOQUEIO 1: NÃO LOGADO -->
        <div class="bg-dark-900 border border-purple-900/50 rounded-3xl p-8 sm:p-12 text-center shadow-2xl">
            <div class="w-20 h-20 mx-auto rounded-3xl bg-purple-950/70 border-2 border-purple-500/50 flex items-center justify-center text-purple-400 text-3xl mb-6 shadow-[0_0_30px_rgba(168,85,247,0.3)]">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h1 class="text-3xl font-rajdhani font-black text-white uppercase tracking-wider">
                ACESSO RESTRITO A MEMBROS
            </h1>
            <p class="text-sm text-gray-300 max-w-md mx-auto mt-3 leading-relaxed">
                Para criar tópicos na Tropa do Respawn, é necessário estar conectado à sua conta gamer. Junte-se à nossa comunidade!
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="login.php" class="px-6 py-3 rounded-xl font-rajdhani font-bold text-sm tracking-wider uppercase text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 shadow-lg shadow-purple-600/40 transition-all">
                    FAZER LOGIN
                </a>
                <a href="registro.php" class="px-6 py-3 rounded-xl font-rajdhani font-bold text-sm tracking-wider uppercase text-gray-200 hover:text-white bg-dark-950 border border-purple-900/50 hover:border-purple-400 transition-all">
                    CRIAR CONTA GRÁTIS
                </a>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                TropaModal.alert('Requisito da Comunidade', 'Você precisa estar logado com sua conta para criar fóruns na Tropa do Respawn.');
            });
        </script>

    <?php elseif (!$usuario['pode_criar_forum']): ?>
        <!-- BLOQUEIO 2: IDADE MÍNIMA NÃO ATINGIDA (NARRATIVA 2) -->
        <div class="bg-dark-900 border border-amber-500/50 rounded-3xl p-8 sm:p-12 text-center shadow-2xl">
            <div class="w-20 h-20 mx-auto rounded-3xl bg-amber-950/70 border-2 border-amber-500/50 flex items-center justify-center text-amber-400 text-3xl mb-6 shadow-[0_0_30px_rgba(245,158,11,0.3)]">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <span class="inline-block px-3 py-1 rounded-full bg-amber-950/80 border border-amber-500/40 text-amber-300 text-xs font-mono uppercase font-bold mb-3">
                REGRA DE IDADE MÍNIMA DA COMUNIDADE
            </span>
            <h1 class="text-3xl font-rajdhani font-black text-white uppercase tracking-wider">
                VOCÊ NÃO POSSUI A IDADE MÍNIMA REQUERIDA
            </h1>
            <p class="text-sm text-gray-300 max-w-lg mx-auto mt-3 leading-relaxed">
                Olá <strong class="text-white"><?= e($usuario['username']) ?></strong>, seu cadastro indica que você possui <strong class="text-amber-400"><?= $usuario['idade'] ?> anos</strong>.
                De acordo com os requisitos de conformidade e segurança da Tropa do Respawn, a idade mínima para publicar e moderar fóruns próprios é de <strong class="text-white"><?= IDADE_MINIMA_FORUM ?> anos</strong>.
            </p>
            <div class="mt-4 p-4 rounded-2xl bg-dark-950 border border-amber-900/40 text-xs text-gray-400 max-w-md mx-auto">
                <p>Você continua livre para explorar todos os guias da Wiki, ler os tópicos e acompanhar as estratégias!</p>
            </div>
            <div class="mt-8">
                <a href="comunidade.php" class="px-6 py-3 rounded-xl font-rajdhani font-bold text-sm tracking-wider uppercase text-white bg-purple-600 hover:bg-purple-500 shadow-lg shadow-purple-600/40 transition-all inline-flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Voltar aos Fóruns
                </a>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                TropaModal.alert('Requisito de Idade Mínima', 'Você possui <?= $usuario['idade'] ?> anos. A idade mínima necessária para criar fóruns na plataforma é de <?= IDADE_MINIMA_FORUM ?> anos.');
            });
        </script>

    <?php else: ?>
        <!-- FORMULÁRIO HABILITADO (USUÁRIO LOGADO E IDADE >= 14) -->
        <div class="bg-dark-900 border border-purple-900/50 rounded-3xl p-6 sm:p-10 shadow-[0_0_35px_rgba(168,85,247,0.2)]">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-purple-900/30 mb-8">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-rajdhani font-black text-white uppercase tracking-wider">
                        CRIAR NOVO TÓPICO NO FÓRUM
                    </h1>
                    <p class="text-xs text-gray-400 mt-1">
                        Inicie uma nova discussão, compartilhe uma build ou tire dúvidas com a comunidade.
                    </p>
                </div>
                
                <!-- Badge de Validação de Idade -->
                <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-green-950/60 border border-green-500/40 text-green-300 text-xs font-mono shrink-0">
                    <i class="fa-solid fa-circle-check text-green-400"></i>
                    <span>Idade verificada (<?= $usuario['idade'] ?> anos • Apto)</span>
                </div>
            </div>

            <form action="actions/forum_criar.php" method="POST" class="space-y-6">
                
                <!-- Título -->
                <div>
                    <label for="titulo" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                        Título do Fórum <span class="text-purple-400">*</span>
                    </label>
                    <input type="text" id="titulo" name="titulo" required minlength="5" placeholder="Ex: Qual o melhor combo de ataque para o modo Ranqueado?" class="w-full px-4 py-3 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all">
                </div>

                <!-- Categoria e Jogo -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="categoria" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                            Categoria <span class="text-purple-400">*</span>
                        </label>
                        <select id="categoria" name="categoria" required class="w-full px-4 py-3 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white focus:outline-none focus:border-purple-500">
                            <option value="Combos">Combos & Técnicas</option>
                            <option value="Dicas">Dicas & Estratégias</option>
                            <option value="Dúvidas">Dúvidas & Ajuda</option>
                            <option value="Builds">Builds & Composições</option>
                            <option value="Geral">Discussão Geral</option>
                        </select>
                    </div>

                    <div>
                        <label for="jogo_id" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                            Jogo Relacionado (Opcional)
                        </label>
                        <select id="jogo_id" name="jogo_id" class="w-full px-4 py-3 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white focus:outline-none focus:border-purple-500">
                            <option value="">Nenhum / Geral</option>
                            <?php foreach ($jogos as $j): ?>
                                <option value="<?= $j['id'] ?>"><?= e($j['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Conteúdo -->
                <div>
                    <label for="conteudo" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                        Conteúdo da Mensagem <span class="text-purple-400">*</span>
                    </label>
                    <textarea id="conteudo" name="conteudo" rows="7" required placeholder="Descreva sua tática, sua dúvida ou compartilhe sua análise detalhada com os jogadores da tropa..." class="w-full px-4 py-3 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all"></textarea>
                </div>

                <!-- Tags -->
                <div>
                    <label for="tags" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                        Tags / Palavras-chave (Separadas por espaço)
                    </label>
                    <input type="text" id="tags" name="tags" placeholder="#Combos #JJS #Meta #Estrategia" class="w-full px-4 py-3 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-all">
                </div>

                <!-- Botões de Ação -->
                <div class="flex items-center justify-between pt-4 border-t border-purple-900/30">
                    <a href="comunidade.php" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-gray-400 hover:text-white hover:bg-dark-850 transition-all">
                        Cancelar
                    </a>
                    <button type="submit" class="px-7 py-3 rounded-xl font-rajdhani font-bold text-sm uppercase tracking-wider text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 shadow-[0_0_20px_rgba(168,85,247,0.4)] transition-all flex items-center gap-2">
                        PUBLICAR FÓRUM <span class="text-xs font-mono text-purple-200">(+50 XP)</span> <i class="fa-solid fa-paper-plane text-xs"></i>
                    </button>
                </div>

            </form>

        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
