<?php
/**
 * Visualização de Artigo / Guia da Wiki - Tropa do Respawn
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$slug = trim($_GET['slug'] ?? '');
if (empty($slug)) {
    header('Location: wiki.php');
    exit;
}

$pdo = obterConexao();

// Busca o artigo com dados do autor e do jogo
$stmt = $pdo->prepare("
    SELECT a.*, u.username as autor_nome, u.avatar as autor_avatar, u.nivel as autor_nivel, j.nome as jogo_nome, j.slug as jogo_slug
    FROM artigos_wiki a
    JOIN usuarios u ON a.usuario_id = u.id
    LEFT JOIN jogos j ON a.jogo_id = j.id
    WHERE a.slug = ?
    LIMIT 1
");
$stmt->execute([$slug]);
$artigo = $stmt->fetch();

if (!$artigo) {
    definirToast('error', 'O guia solicitado não foi encontrado na Wiki!');
    header('Location: wiki.php');
    exit;
}

// Incrementa visualização
$pdo->prepare("UPDATE artigos_wiki SET visualizacoes = visualizacoes + 1 WHERE id = ?")->execute([$artigo['id']]);

// Busca outros guias relacionados
$stmtRel = $pdo->prepare("SELECT * FROM artigos_wiki WHERE id != ? AND (categoria = ? OR jogo_id = ?) ORDER BY visualizacoes DESC LIMIT 3");
$stmtRel->execute([$artigo['id'], $artigo['categoria'], $artigo['jogo_id']]);
$relacionados = $stmtRel->fetchAll();

$pageTitle = e($artigo['titulo']) . ' | Wiki Tropa do Respawn';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/nav.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs font-mono text-gray-400 mb-6">
        <a href="index.php" class="hover:text-purple-400">Início</a>
        <span>/</span>
        <a href="wiki.php" class="hover:text-purple-400">Wiki</a>
        <span>/</span>
        <a href="wiki.php?categoria=<?= urlencode($artigo['categoria']) ?>" class="hover:text-purple-400"><?= e($artigo['categoria']) ?></a>
        <span>/</span>
        <span class="text-purple-400 truncate max-w-xs"><?= e($artigo['titulo']) ?></span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- CONTEÚDO PRINCIPAL DO ARTIGO -->
        <article class="lg:col-span-8 bg-dark-900 border border-purple-900/40 rounded-3xl p-6 sm:p-10 shadow-xl">
            
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="px-3 py-1 rounded-lg bg-purple-950/80 border border-purple-500/40 text-xs font-mono text-purple-300 font-bold uppercase">
                    <?= e($artigo['categoria']) ?>
                </span>
                <?php if (!empty($artigo['jogo_nome'])): ?>
                    <a href="wiki.php?jogo=<?= urlencode($artigo['jogo_slug']) ?>" class="px-3 py-1 rounded-lg bg-dark-950 border border-purple-900/40 hover:border-purple-500/50 text-xs font-medium text-purple-400 transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-gamepad text-[10px]"></i> <?= e($artigo['jogo_nome']) ?>
                    </a>
                <?php endif; ?>
                <span class="text-xs font-mono text-gray-500">
                    <i class="fa-regular fa-clock mr-1"></i> Leitura: <?= e($artigo['tempo_leitura']) ?>
                </span>
                <span class="text-xs font-mono text-gray-500">
                    <i class="fa-regular fa-eye mr-1"></i> <?= $artigo['visualizacoes'] + 1 ?> visualizações
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-rajdhani font-black text-white uppercase tracking-wide leading-tight">
                <?= e($artigo['titulo']) ?>
            </h1>

            <!-- Autor e Data -->
            <div class="flex items-center justify-between py-4 my-6 border-y border-purple-900/30">
                <div class="flex items-center gap-3">
                    <img src="<?= e($artigo['autor_avatar']) ?>" alt="Avatar" class="w-10 h-10 rounded-xl object-cover border border-purple-500/40">
                    <div>
                        <span class="block text-xs font-bold text-white"><?= e($artigo['autor_nome']) ?></span>
                        <span class="block text-[10px] font-mono text-purple-400">Gamer Nv. <?= $artigo['autor_nivel'] ?></span>
                    </div>
                </div>
                <div class="text-right text-xs font-mono text-gray-400">
                    Publicado <?= formatarTempoRelativo($artigo['criado_em']) ?>
                </div>
            </div>

            <!-- Resumo Destaque -->
            <div class="p-4 rounded-2xl bg-purple-950/30 border-l-4 border-purple-500 text-sm text-purple-200 leading-relaxed mb-8 italic">
                <?= nl2br(e($artigo['resumo'])) ?>
            </div>

            <!-- Corpo do Conteúdo -->
            <div class="text-gray-300 text-sm sm:text-base leading-relaxed space-y-4">
                <?= nl2br(e($artigo['conteudo'])) ?>
            </div>

            <!-- Rodapé do Guia com Ação para Fórum -->
            <div class="mt-10 pt-6 border-t border-purple-900/40 flex flex-col sm:flex-row items-center justify-between gap-4">
                <span class="text-xs text-gray-400">Gostou da dica? Participe da discussão com a comunidade!</span>
                <a href="comunidade.php" class="px-5 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-rajdhani font-bold text-xs uppercase tracking-wider shadow-lg shadow-purple-600/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-users text-xs"></i> Ver Fóruns de Discussão
                </a>
            </div>

        </article>

        <!-- LATERAL COM GUIAS RELACIONADOS -->
        <aside class="lg:col-span-4 space-y-6">
            
            <div class="bg-dark-900 border border-purple-900/40 rounded-3xl p-6 shadow-lg">
                <h3 class="text-sm font-rajdhani font-bold text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-fire text-purple-400"></i> GUIAS RELACIONADOS
                </h3>

                <div class="space-y-4">
                    <?php foreach ($relacionados as $rel): ?>
                        <a href="guia.php?slug=<?= urlencode($rel['slug']) ?>" class="group block p-3.5 rounded-2xl bg-dark-950 border border-purple-900/30 hover:border-purple-500/50 transition-all">
                            <span class="text-[10px] font-mono text-purple-400 font-bold uppercase">
                                <?= e($rel['categoria']) ?>
                            </span>
                            <h4 class="text-xs font-bold text-white group-hover:text-purple-300 transition-colors line-clamp-2 mt-1">
                                <?= e($rel['titulo']) ?>
                            </h4>
                            <span class="block text-[10px] text-gray-500 mt-2 font-mono">
                                <?= formatarTempoRelativo($rel['criado_em']) ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="mt-6 pt-4 border-t border-purple-900/30 text-center">
                    <a href="wiki.php" class="text-xs text-purple-400 hover:text-white font-semibold">
                        Explorar todos os artigos <i class="fa-solid fa-arrow-right text-[10px] ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Card Dica Rápida -->
            <div class="p-5 rounded-3xl bg-gradient-to-br from-purple-950/40 to-dark-900 border border-purple-500/30 text-center">
                <i class="fa-solid fa-shield-cat text-3xl text-purple-400 mb-2"></i>
                <h4 class="text-sm font-rajdhani font-bold text-white uppercase">Tem uma dica incrível?</h4>
                <p class="text-xs text-gray-400 mt-1">Crie um tópico no fórum para compartilhar estratégias com outros guerreiros da tropa!</p>
                <a href="forum_novo.php" class="mt-4 inline-block px-4 py-2 rounded-xl bg-purple-600/30 hover:bg-purple-600 border border-purple-500/40 text-purple-300 hover:text-white text-xs font-semibold transition-all">
                    + Criar Fórum
                </a>
            </div>

        </aside>

    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
