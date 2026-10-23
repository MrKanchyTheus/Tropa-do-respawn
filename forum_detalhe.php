<?php
/**
 * Visualização Completa de Tópico do Fórum - Tropa do Respawn
 * Implementa:
 * - Leitura do tópico
 * - Avaliação interativa de 1 a 5 estrelas
 * - Comentários em tempo real
 * - Modais e Toasts
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$forumId = (int) ($_GET['id'] ?? 0);
if ($forumId <= 0) {
    header('Location: comunidade.php');
    exit;
}

$pdo = obterConexao();
$usuarioAtual = usuarioLogado();

// Incrementa visualizações
$pdo->prepare("UPDATE foruns SET visualizacoes = visualizacoes + 1 WHERE id = ?")->execute([$forumId]);

// Busca dados do fórum
$stmt = $pdo->prepare("
    SELECT f.*, u.username as autor_nome, u.avatar as autor_avatar, u.nivel as autor_nivel, u.xp as autor_xp, j.nome as jogo_nome, j.slug as jogo_slug
    FROM foruns f
    JOIN usuarios u ON f.usuario_id = u.id
    LEFT JOIN jogos j ON f.jogo_id = j.id
    WHERE f.id = ?
");
$stmt->execute([$forumId]);
$forum = $stmt->fetch();

if (!$forum) {
    definirToast('error', 'O fórum solicitado não foi encontrado!');
    header('Location: comunidade.php');
    exit;
}

// Avaliação do usuário atual neste fórum (se logado)
$minhaNota = 0;
if ($usuarioAtual) {
    $stmtNota = $pdo->prepare("SELECT nota FROM forum_avaliacoes WHERE forum_id = ? AND usuario_id = ?");
    $stmtNota->execute([$forumId, $usuarioAtual['id']]);
    $minhaNota = (int) ($stmtNota->fetchColumn() ?: 0);
}

// Busca comentários do fórum
$stmtComent = $pdo->prepare("
    SELECT c.*, u.username as autor_nome, u.avatar as autor_avatar, u.nivel as autor_nivel
    FROM forum_comentarios c
    JOIN usuarios u ON c.usuario_id = u.id
    WHERE c.forum_id = ?
    ORDER BY c.criado_em ASC
");
$stmtComent->execute([$forumId]);
$comentarios = $stmtComent->fetchAll();

$pageTitle = e($forum['titulo']) . ' | Fórum Tropa do Respawn';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/nav.php';
?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs font-mono text-gray-400 mb-6">
        <a href="index.php" class="hover:text-purple-400">Início</a>
        <span>/</span>
        <a href="comunidade.php" class="hover:text-purple-400">Comunidade</a>
        <span>/</span>
        <span class="text-purple-400 truncate max-w-sm"><?= e($forum['titulo']) ?></span>
    </nav>

    <!-- CARD PRINCIPAL DO FÓRUM -->
    <article class="bg-dark-900 border border-purple-900/50 rounded-3xl p-6 sm:p-10 shadow-xl mb-8">
        
        <!-- Topo: Categoria, Jogo e Estatísticas -->
        <div class="flex flex-wrap items-center justify-between gap-3 pb-5 border-b border-purple-900/30">
            <div class="flex flex-wrap items-center gap-2">
                <span class="px-3 py-1 rounded-lg bg-purple-950/80 border border-purple-500/40 text-xs font-mono text-purple-300 font-bold uppercase">
                    <?= e($forum['categoria']) ?>
                </span>
                <?php if (!empty($forum['jogo_nome'])): ?>
                    <span class="px-3 py-1 rounded-lg bg-dark-950 border border-purple-900/40 text-xs font-medium text-purple-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-gamepad text-[10px]"></i> <?= e($forum['jogo_nome']) ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Visualizações e Tempo -->
            <div class="flex items-center gap-4 text-xs font-mono text-gray-500">
                <span><i class="fa-regular fa-eye mr-1"></i> <?= $forum['visualizacoes'] ?> visualizações</span>
                <span><i class="fa-regular fa-clock mr-1"></i> <?= formatarTempoRelativo($forum['criado_em']) ?></span>
            </div>
        </div>

        <!-- Título do Fórum -->
        <h1 class="text-2xl sm:text-3xl font-rajdhani font-black text-white uppercase tracking-wide leading-tight mt-6">
            <?= e($forum['titulo']) ?>
        </h1>

        <!-- Perfil do Autor -->
        <div class="flex items-center justify-between py-4 my-6 border-y border-purple-900/30">
            <div class="flex items-center gap-3.5">
                <img src="<?= e($forum['autor_avatar']) ?>" alt="Avatar" class="w-12 h-12 rounded-2xl object-cover border border-purple-500/40">
                <div>
                    <span class="block text-sm font-bold text-white"><?= e($forum['autor_nome']) ?></span>
                    <span class="block text-xs font-mono text-purple-400">Gamer Nível <?= $forum['autor_nivel'] ?> • <?= $forum['autor_xp'] ?> XP</span>
                </div>
            </div>

            <!-- Botão de Excluir (Autor ou Admin) -->
            <?php if ($usuarioAtual && ($usuarioAtual['id'] == $forum['usuario_id'] || ($usuarioAtual['tipo'] ?? '') === 'admin')): ?>
                <form action="actions/forum_excluir.php" method="POST">
                    <input type="hidden" name="forum_id" value="<?= $forum['id'] ?>">
                    <button type="submit" data-confirm-modal="Deseja realmente apagar este tópico do fórum? Esta ação não pode ser desfeita." data-confirm-title="Excluir Fórum" class="px-3 py-1.5 rounded-xl bg-red-950/60 border border-red-500/40 text-red-400 hover:text-white hover:bg-red-900/60 text-xs font-semibold transition-all">
                        <i class="fa-solid fa-trash-can mr-1"></i> Excluir Tópico
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Conteúdo do Post -->
        <div class="text-gray-200 text-sm sm:text-base leading-relaxed py-2 space-y-4">
            <?= nl2br(e($forum['conteudo'])) ?>
        </div>

        <!-- Tags -->
        <?php if (!empty($forum['tags'])): ?>
            <div class="mt-6 pt-4 border-t border-purple-900/30 flex flex-wrap gap-2">
                <?php 
                $tagsList = explode(' ', $forum['tags']);
                foreach ($tagsList as $tag): 
                    if (trim($tag) === '') continue;
                ?>
                    <span class="text-xs font-mono text-purple-400 bg-purple-950/50 px-2.5 py-1 rounded-lg border border-purple-900/40">
                        <?= e($tag) ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- SEÇÃO DE AVALIAÇÃO DE FÓRUM (Requisito: "avaliar fóruns") -->
        <div class="mt-8 pt-6 border-t border-purple-900/40 flex flex-col sm:flex-row sm:items-center justify-between gap-6 bg-dark-950/60 p-6 rounded-2xl border border-purple-900/30">
            <div>
                <span class="block text-xs font-rajdhani font-bold uppercase tracking-wider text-purple-400 mb-1">
                    AVALIAÇÃO DA COMUNIDADE
                </span>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1 text-amber-400 text-lg">
                        <?php 
                        $media = round((float) $forum['media_avaliacao']);
                        for ($i = 1; $i <= 5; $i++): 
                        ?>
                            <i class="fa-solid fa-star <?= $i <= $media ? 'text-amber-400' : 'text-gray-700' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="text-base font-rajdhani font-bold text-white"><?= number_format($forum['media_avaliacao'], 1) ?> / 5.0</span>
                    <span class="text-xs font-mono text-gray-500">(<?= $forum['total_avaliacoes'] ?> votos)</span>
                </div>
            </div>

            <!-- Interatividade de Voto (1 a 5 estrelas) -->
            <div>
                <?php if ($usuarioAtual): ?>
                    <form action="actions/forum_avaliar.php" method="POST" class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <input type="hidden" name="forum_id" value="<?= $forum['id'] ?>">
                        
                        <div class="star-rating-input flex items-center gap-1.5 cursor-pointer">
                            <input type="hidden" name="nota" value="<?= $minhaNota ?: 5 ?>">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <i class="fa-solid fa-star rating-star text-xl <?= $s <= $minhaNota ? 'text-purple-400' : 'text-gray-600' ?>" data-value="<?= $s ?>" title="<?= $s ?> estrelas"></i>
                            <?php endfor; ?>
                        </div>

                        <button type="submit" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-rajdhani font-bold text-xs uppercase tracking-wider shadow-md shadow-purple-600/30 transition-all">
                            <?= $minhaNota ? 'Atualizar Avaliação' : 'Avaliar Fórum' ?>
                        </button>
                    </form>
                    <?php if ($minhaNota > 0): ?>
                        <span class="block text-[11px] font-mono text-purple-400 mt-1">Sua nota atual: <?= $minhaNota ?> estrelas</span>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="login.php" class="text-xs text-purple-400 hover:underline flex items-center gap-1">
                        <i class="fa-solid fa-star text-amber-400"></i> Faça login para avaliar este fórum
                    </a>
                <?php endif; ?>
            </div>
        </div>

    </article>

    <!-- SEÇÃO DE COMENTÁRIOS (Requisito: "comentar em outros fóruns") -->
    <section id="comentarios" class="bg-dark-900 border border-purple-900/50 rounded-3xl p-6 sm:p-10 shadow-xl">
        
        <div class="flex items-center justify-between pb-4 border-b border-purple-900/30 mb-8">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-comments text-purple-400 text-xl"></i>
                <h2 class="text-xl font-rajdhani font-black text-white uppercase tracking-wider">
                    RESPOSTAS & COMENTÁRIOS (<?= count($comentarios) ?>)
                </h2>
            </div>
        </div>

        <!-- Formulário para Comentar -->
        <?php if ($usuarioAtual): ?>
            <form action="actions/forum_comentar.php" method="POST" class="mb-10">
                <input type="hidden" name="forum_id" value="<?= $forum['id'] ?>">
                
                <div class="flex items-start gap-3.5 mb-3">
                    <img src="<?= e($usuarioAtual['avatar']) ?>" class="w-10 h-10 rounded-xl object-cover border border-purple-500/40 shrink-0">
                    <div class="flex-1">
                        <textarea name="conteudo" rows="3" required placeholder="Escreva sua resposta, counter-estratégia ou feedback construtivo..." class="w-full px-4 py-3 bg-dark-950 border border-purple-900/40 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all"></textarea>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2.5 rounded-xl font-rajdhani font-bold text-xs uppercase tracking-wider text-white bg-purple-600 hover:bg-purple-500 shadow-[0_0_15px_rgba(168,85,247,0.4)] transition-all flex items-center gap-2">
                        PUBLICAR RESPOSTA <span class="font-mono text-purple-200">(+15 XP)</span> <i class="fa-solid fa-paper-plane text-[10px]"></i>
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="p-6 rounded-2xl bg-dark-950 border border-purple-900/40 text-center mb-8">
                <i class="fa-solid fa-user-lock text-3xl text-purple-500 mb-2"></i>
                <h4 class="text-sm font-rajdhani font-bold text-white uppercase">Deseja responder a este tópico?</h4>
                <p class="text-xs text-gray-400 mt-1">Conecte-se à sua conta gamer para participar das discussões da Tropa.</p>
                <div class="mt-4 flex justify-center gap-3">
                    <a href="login.php" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-xl text-xs font-bold uppercase tracking-wider">Entrar</a>
                    <a href="registro.php" class="px-4 py-2 bg-dark-900 border border-purple-900/50 text-gray-200 hover:text-white rounded-xl text-xs font-bold uppercase tracking-wider">Criar Conta</a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Listagem de Respostas -->
        <?php if (empty($comentarios)): ?>
            <div class="py-8 text-center text-gray-500 text-xs">
                Ainda não há comentários neste tópico. Seja o primeiro a responder!
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($comentarios as $com): ?>
                    <div class="p-5 rounded-2xl bg-dark-950 border border-purple-900/30 flex items-start gap-4">
                        <img src="<?= e($com['autor_avatar']) ?>" alt="Avatar" class="w-10 h-10 rounded-xl object-cover border border-purple-500/30 shrink-0">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-white"><?= e($com['autor_nome']) ?></span>
                                    <span class="text-[10px] font-mono text-purple-400 bg-purple-950/70 px-2 py-0.5 rounded border border-purple-900/30">
                                        Nv. <?= $com['autor_nivel'] ?>
                                    </span>
                                </div>
                                <span class="text-[11px] font-mono text-gray-500">
                                    <?= formatarTempoRelativo($com['criado_em']) ?>
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                                <?= nl2br(e($com['conteudo'])) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </section>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
