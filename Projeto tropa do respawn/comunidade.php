<?php
/**
 * Hub da Comunidade e Fóruns - Tropa do Respawn
 * Requisitos:
 * - Listagem de fóruns
 * - Filtros por categoria e busca
 * - Exibição de médias de avaliação e total de comentários
 * - Botão de criação com verificação de login e idade mínima
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Comunidade Gamer & Fóruns | Tropa do Respawn';
$usuarioAtual = usuarioLogado();
$pdo = obterConexao();

// Filtros e Ordenação
$busca = trim($_GET['busca'] ?? '');
$categoriaFiltro = trim($_GET['categoria'] ?? '');
$ordem = trim($_GET['ordem'] ?? 'recentes'); // recentes, avaliados, comentados

$sql = "
    SELECT f.*, u.username as autor_nome, u.avatar as autor_avatar, u.nivel as autor_nivel, j.nome as jogo_nome
    FROM foruns f
    JOIN usuarios u ON f.usuario_id = u.id
    LEFT JOIN jogos j ON f.jogo_id = j.id
    WHERE 1=1
";
$params = [];

if (!empty($busca)) {
    $sql .= " AND (f.titulo LIKE ? OR f.conteudo LIKE ? OR f.tags LIKE ?)";
    $like = "%{$busca}%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if (!empty($categoriaFiltro)) {
    $sql .= " AND f.categoria = ?";
    $params[] = $categoriaFiltro;
}

if ($ordem === 'avaliados') {
    $sql .= " ORDER BY f.media_avaliacao DESC, f.total_avaliacoes DESC";
} elseif ($ordem === 'comentados') {
    $sql .= " ORDER BY f.total_comentarios DESC";
} else {
    $sql .= " ORDER BY f.criado_em DESC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$foruns = $stmt->fetchAll();

// Contadores gerais
$totalTopicos = count($foruns);

require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/nav.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">

    <!-- Topo da Comunidade -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-purple-900/40 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-950/70 border border-purple-500/40 text-purple-300 text-xs font-mono uppercase font-semibold mb-2">
                <i class="fa-solid fa-users text-purple-400"></i> Fórum Aberto
            </div>
            <h1 class="text-3xl sm:text-4xl font-rajdhani font-black text-white uppercase tracking-wider">
                FÓRUNS DA COMUNIDADE
            </h1>
            <p class="text-xs sm:text-sm text-gray-400 mt-1">
                Compartilhe combos, tire dúvidas, avalie estratégias e fortaleça a tropa!
            </p>
        </div>

        <!-- Botão Criar Fórum -->
        <div>
            <a href="forum_novo.php" class="inline-flex items-center gap-2.5 px-6 py-3 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-rajdhani font-bold text-sm uppercase tracking-wider shadow-[0_0_20px_rgba(168,85,247,0.4)] transition-all">
                <i class="fa-solid fa-plus text-sm"></i> CRIAR NOVO FÓRUM
            </a>
        </div>
    </div>

    <!-- Barra de Filtros e Busca -->
    <div class="bg-dark-900 border border-purple-900/40 rounded-2xl p-4 sm:p-5 shadow-lg mb-8">
        <form action="comunidade.php" method="GET" class="flex flex-col md:flex-row items-center gap-4">
            
            <!-- Campo de Busca -->
            <div class="relative flex-1 w-full">
                <input type="text" name="busca" value="<?= e($busca) ?>" placeholder="Buscar nos fóruns (ex: combos JJS, Buster ranqueada...)" class="w-full pl-10 pr-4 py-2.5 bg-dark-950 border border-purple-900/40 rounded-xl text-xs text-white placeholder-gray-500 focus:outline-none focus:border-purple-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-xs text-purple-400"></i>
            </div>

            <!-- Filtro de Categoria -->
            <div class="w-full md:w-48">
                <select name="categoria" class="w-full px-3 py-2.5 bg-dark-950 border border-purple-900/40 rounded-xl text-xs text-white focus:outline-none focus:border-purple-500">
                    <option value="">Todas as Categorias</option>
                    <option value="Combos" <?= $categoriaFiltro === 'Combos' ? 'selected' : '' ?>>Combos & Golpes</option>
                    <option value="Dicas" <?= $categoriaFiltro === 'Dicas' ? 'selected' : '' ?>>Dicas Estratégicas</option>
                    <option value="Dúvidas" <?= $categoriaFiltro === 'Dúvidas' ? 'selected' : '' ?>>Dúvidas & Ajuda</option>
                    <option value="Builds" <?= $categoriaFiltro === 'Builds' ? 'selected' : '' ?>>Builds & Decks</option>
                    <option value="Geral" <?= $categoriaFiltro === 'Geral' ? 'selected' : '' ?>>Discussão Geral</option>
                </select>
            </div>

            <!-- Filtro de Ordenação -->
            <div class="w-full md:w-48">
                <select name="ordem" class="w-full px-3 py-2.5 bg-dark-950 border border-purple-900/40 rounded-xl text-xs text-white focus:outline-none focus:border-purple-500">
                    <option value="recentes" <?= $ordem === 'recentes' ? 'selected' : '' ?>>Mais Recentes</option>
                    <option value="avaliados" <?= $ordem === 'avaliados' ? 'selected' : '' ?>>Melhor Avaliados ★</option>
                    <option value="comentados" <?= $ordem === 'comentados' ? 'selected' : '' ?>>Mais Comentados</option>
                </select>
            </div>

            <!-- Botão Filtrar -->
            <button type="submit" class="w-full md:w-auto px-5 py-2.5 bg-purple-600 hover:bg-purple-500 text-white rounded-xl text-xs font-semibold uppercase tracking-wider transition-all shrink-0">
                Filtrar
            </button>

            <?php if (!empty($busca) || !empty($categoriaFiltro) || $ordem !== 'recentes'): ?>
                <a href="comunidade.php" class="text-xs text-purple-400 hover:underline shrink-0">Limpar</a>
            <?php endif; ?>

        </form>
    </div>

    <!-- Lista de Fóruns -->
    <?php if (empty($foruns)): ?>
        <div class="p-12 bg-dark-900 border border-purple-900/30 rounded-3xl text-center">
            <i class="fa-solid fa-comments text-5xl text-purple-900 mb-3"></i>
            <h3 class="text-lg font-rajdhani font-bold text-white uppercase">Nenhum tópico encontrado</h3>
            <p class="text-xs text-gray-400 mt-1">Seja o primeiro a inaugurar essa discussão na tropa!</p>
            <a href="forum_novo.php" class="inline-block mt-4 px-5 py-2.5 bg-purple-600 text-xs font-bold text-white uppercase rounded-xl">Criar Novo Fórum</a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($foruns as $f): ?>
                <div class="group bg-dark-900 border border-purple-900/40 hover:border-purple-500/60 rounded-3xl p-5 sm:p-6 gamer-card shadow-lg transition-all">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        
                        <!-- Conteúdo Esquerdo do Fórum -->
                        <div class="flex items-start gap-4 flex-1 min-w-0">
                            <!-- Avatar do Autor -->
                            <img src="<?= e($f['autor_avatar']) ?>" alt="Avatar" class="w-11 h-11 rounded-2xl object-cover border border-purple-500/40 shrink-0">
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 rounded bg-purple-950/80 border border-purple-500/30 text-[10px] font-mono text-purple-300 uppercase font-semibold">
                                        <?= e($f['categoria']) ?>
                                    </span>
                                    <?php if (!empty($f['jogo_nome'])): ?>
                                        <span class="text-[11px] font-medium text-purple-400 flex items-center gap-1">
                                            <i class="fa-solid fa-gamepad text-[10px]"></i> <?= e($f['jogo_nome']) ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="text-[11px] text-gray-500 font-mono">• por <?= e($f['autor_nome']) ?> (Nv. <?= $f['autor_nivel'] ?>)</span>
                                    <span class="text-[11px] text-gray-500 font-mono">• <?= formatarTempoRelativo($f['criado_em']) ?></span>
                                </div>

                                <a href="forum_detalhe.php?id=<?= $f['id'] ?>" class="block">
                                    <h3 class="text-base sm:text-lg font-rajdhani font-bold text-white group-hover:text-purple-300 transition-colors line-clamp-2">
                                        <?= e($f['titulo']) ?>
                                    </h3>
                                </a>

                                <p class="text-xs text-gray-400 mt-1 line-clamp-2 leading-relaxed">
                                    <?= e($f['conteudo']) ?>
                                </p>

                                <?php if (!empty($f['tags'])): ?>
                                    <div class="mt-2.5 flex flex-wrap gap-1.5">
                                        <?php 
                                        $tagsList = explode(' ', $f['tags']);
                                        foreach ($tagsList as $tag): 
                                            if (trim($tag) === '') continue;
                                        ?>
                                            <span class="text-[10px] font-mono text-purple-400 bg-purple-950/50 px-2 py-0.5 rounded border border-purple-900/30">
                                                <?= e($tag) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Indicadores Direitos (Avaliação, Comentários e Botão) -->
                        <div class="shrink-0 flex sm:flex-col items-center sm:items-end justify-between border-t sm:border-t-0 pt-3 sm:pt-0 border-purple-900/30 gap-3">
                            
                            <!-- Média de Estrelas -->
                            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-dark-950 border border-purple-900/40" title="Média de avaliação dos usuários">
                                <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                                <span class="text-xs font-mono font-bold text-white"><?= number_format($f['media_avaliacao'], 1) ?></span>
                                <span class="text-[10px] font-mono text-gray-500">(<?= $f['total_avaliacoes'] ?>)</span>
                            </div>

                            <!-- Total de Comentários -->
                            <div class="flex items-center gap-1.5 text-xs font-mono text-gray-400">
                                <i class="fa-regular fa-comment text-purple-400"></i>
                                <span><?= $f['total_comentarios'] ?> respostas</span>
                            </div>

                            <!-- Botão Acessar -->
                            <a href="forum_detalhe.php?id=<?= $f['id'] ?>" class="px-4 py-1.5 rounded-xl bg-purple-600/30 hover:bg-purple-600 border border-purple-500/40 text-purple-300 hover:text-white text-xs font-semibold tracking-wide transition-all flex items-center gap-1">
                                Ver Tópico <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>

                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
