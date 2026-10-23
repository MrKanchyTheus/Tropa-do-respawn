<?php
/**
 * Hub Principal da Wiki - Tropa do Respawn
 * Fiel ao mockup original do design: 3 colunas, categorias e destaques de jogos.
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Wiki Tropa do Respawn | Guias, Tutoriais e Dicas';
$usuarioAtual = usuarioLogado();
$pdo = obterConexao();

// Filtros
$busca = trim($_GET['busca'] ?? '');
$categoriaFiltro = trim($_GET['categoria'] ?? '');
$jogoFiltro = trim($_GET['jogo'] ?? '');

// Consulta de artigos da wiki
$sql = "
    SELECT a.*, u.username as autor_nome, u.avatar as autor_avatar, j.nome as jogo_nome 
    FROM artigos_wiki a
    JOIN usuarios u ON a.usuario_id = u.id
    LEFT JOIN jogos j ON a.jogo_id = j.id
    WHERE 1=1
";
$params = [];

if (!empty($busca)) {
    $sql .= " AND (a.titulo LIKE ? OR a.resumo LIKE ? OR a.conteudo LIKE ?)";
    $like = "%{$busca}%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if (!empty($categoriaFiltro)) {
    $sql .= " AND a.categoria = ?";
    $params[] = $categoriaFiltro;
}

if (!empty($jogoFiltro)) {
    $sql .= " AND j.slug = ?";
    $params[] = $jogoFiltro;
}

$sql .= " ORDER BY a.criado_em DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$artigos = $stmt->fetchAll();

// Jogos em destaque na lateral direita
$jogosDestaque = $pdo->query("SELECT * FROM jogos ORDER BY total_dicas DESC LIMIT 6")->fetchAll();

// Artigos recentes para a lateral direita
$artigosRecentes = $pdo->query("SELECT * FROM artigos_wiki ORDER BY criado_em DESC LIMIT 4")->fetchAll();

require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/nav.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- COLUNA 1: SIDEBAR ESQUERDA -->
        <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

        <!-- COLUNA 2: CONTEÚDO PRINCIPAL (WIKI HUB) -->
        <main class="flex-1 min-w-0">

            <!-- BANNER HERO WIKI (Com visual roxo característico) -->
            <div class="relative overflow-hidden bg-gradient-to-r from-purple-950 via-dark-900 to-indigo-950/80 border border-purple-500/40 rounded-3xl p-6 sm:p-8 shadow-[0_0_35px_rgba(168,85,247,0.2)] mb-8">
                <!-- Efeito visual de luz e coroa de fundo -->
                <div class="absolute -right-8 -top-8 w-64 h-64 bg-purple-600/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="max-w-xl text-center md:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-900/50 border border-purple-400/30 text-purple-300 text-xs font-semibold mb-3">
                            <i class="fa-solid fa-crown text-purple-400"></i> Central do Conhecimento
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-rajdhani font-black text-white uppercase tracking-wider">
                            WIKI <span class="text-purple-400">TROPA DO RESPAWN</span>
                        </h1>
                        <p class="mt-2 text-sm text-gray-300 leading-relaxed">
                            Aqui você encontra tudo sobre os jogos, guias, tutoriais, dicas, listas e muito mais. Explore e domine com a gente!
                        </p>
                    </div>

                    <!-- Mascote Emblema -->
                    <div class="shrink-0 w-24 h-24 rounded-2xl overflow-hidden border-2 border-purple-500/50 shadow-[0_0_20px_rgba(168,85,247,0.4)]">
                        <img src="assets/img/logo.jpeg" alt="Wiki" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Barra de Pesquisa Hero -->
                <form action="wiki.php" method="GET" class="mt-6">
                    <div class="relative flex items-center">
                        <input type="text" name="busca" value="<?= e($busca) ?>" placeholder="Pesquisar na Wiki..." class="w-full pl-12 pr-16 py-3.5 bg-dark-950/90 border border-purple-500/50 rounded-2xl text-sm text-white placeholder-gray-400 focus:outline-none focus:border-purple-400 focus:ring-2 focus:ring-purple-500/50 shadow-inner transition-all">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 text-purple-400 text-base"></i>
                        <button type="submit" class="absolute right-2 px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-xl shadow-md shadow-purple-600/40 transition-all">
                            <i class="fa-solid fa-arrow-right text-sm"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- FILTROS ATIVOS (Se houver) -->
            <?php if (!empty($busca) || !empty($categoriaFiltro) || !empty($jogoFiltro)): ?>
                <div class="mb-6 flex flex-wrap items-center justify-between gap-3 p-4 bg-dark-900 border border-purple-900/40 rounded-2xl">
                    <div class="flex items-center gap-2 text-sm text-gray-300">
                        <span class="text-purple-400 font-bold">Filtros:</span>
                        <?php if (!empty($busca)): ?>
                            <span class="px-2.5 py-1 rounded-lg bg-purple-950/70 border border-purple-500/40 text-xs font-mono text-purple-300">
                                Busca: "<?= e($busca) ?>"
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($categoriaFiltro)): ?>
                            <span class="px-2.5 py-1 rounded-lg bg-purple-950/70 border border-purple-500/40 text-xs font-mono text-purple-300">
                                Categoria: <?= e($categoriaFiltro) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <a href="wiki.php" class="text-xs text-purple-400 hover:text-white underline">Limpar filtros</a>
                </div>
            <?php endif; ?>

            <!-- SEÇÃO DE CATEGORIAS (Grid Original) -->
            <?php if (empty($busca) && empty($categoriaFiltro) && empty($jogoFiltro)): ?>
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="fa-solid fa-grid-2 text-purple-400"></i>
                        <h2 class="text-xl font-rajdhani font-black text-white uppercase tracking-wider">CATEGORIAS</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                        
                        <!-- 1. JOGOS -->
                        <div class="group bg-dark-900 border border-purple-900/40 hover:border-purple-500/60 rounded-2xl p-5 flex flex-col justify-between gamer-card shadow-lg">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-950/70 border border-purple-500/40 flex items-center justify-center text-purple-400 text-lg">
                                        <i class="fa-solid fa-gamepad"></i>
                                    </div>
                                    <span class="text-[10px] font-mono text-purple-400 font-bold uppercase">Wiki</span>
                                </div>
                                <h3 class="text-base font-rajdhani font-bold text-white uppercase tracking-wider">JOGOS</h3>
                                <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                                    Informações sobre todos os jogos da comunidade.
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-purple-900/30 flex justify-end">
                                <a href="jogos.php" class="w-8 h-8 rounded-lg bg-purple-600/30 group-hover:bg-purple-600 border border-purple-500/40 text-purple-300 group-hover:text-white flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>

                        <!-- 2. GUIAS -->
                        <div class="group bg-dark-900 border border-purple-900/40 hover:border-purple-500/60 rounded-2xl p-5 flex flex-col justify-between gamer-card shadow-lg">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-950/70 border border-purple-500/40 flex items-center justify-center text-purple-400 text-lg">
                                        <i class="fa-solid fa-book-bookmark"></i>
                                    </div>
                                    <span class="text-[10px] font-mono text-purple-400 font-bold uppercase">Estratégias</span>
                                </div>
                                <h3 class="text-base font-rajdhani font-bold text-white uppercase tracking-wider">GUIAS</h3>
                                <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                                    Passo a passo, estratégias e builds para você se tornar um jogador melhor.
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-purple-900/30 flex justify-end">
                                <a href="wiki.php?categoria=Guias" class="w-8 h-8 rounded-lg bg-purple-600/30 group-hover:bg-purple-600 border border-purple-500/40 text-purple-300 group-hover:text-white flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>

                        <!-- 3. TUTORIAIS -->
                        <div class="group bg-dark-900 border border-purple-900/40 hover:border-purple-500/60 rounded-2xl p-5 flex flex-col justify-between gamer-card shadow-lg">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-950/70 border border-purple-500/40 flex items-center justify-center text-purple-400 text-lg">
                                        <i class="fa-solid fa-graduation-cap"></i>
                                    </div>
                                    <span class="text-[10px] font-mono text-purple-400 font-bold uppercase">Aprendizado</span>
                                </div>
                                <h3 class="text-base font-rajdhani font-bold text-white uppercase tracking-wider">TUTORIAIS</h3>
                                <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                                    Aprenda desde o básico até o avançado de forma prática.
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-purple-900/30 flex justify-end">
                                <a href="wiki.php?categoria=Tutoriais" class="w-8 h-8 rounded-lg bg-purple-600/30 group-hover:bg-purple-600 border border-purple-500/40 text-purple-300 group-hover:text-white flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>

                        <!-- 4. LISTAS -->
                        <div class="group bg-dark-900 border border-purple-900/40 hover:border-purple-500/60 rounded-2xl p-5 flex flex-col justify-between gamer-card shadow-lg">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-950/70 border border-purple-500/40 flex items-center justify-center text-purple-400 text-lg">
                                        <i class="fa-solid fa-list-ol"></i>
                                    </div>
                                    <span class="text-[10px] font-mono text-purple-400 font-bold uppercase">Tier Lists</span>
                                </div>
                                <h3 class="text-base font-rajdhani font-bold text-white uppercase tracking-wider">LISTAS</h3>
                                <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                                    Tier lists, rankings e recomendações dos melhores personagens e armas.
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-purple-900/30 flex justify-end">
                                <a href="wiki.php?categoria=Listas" class="w-8 h-8 rounded-lg bg-purple-600/30 group-hover:bg-purple-600 border border-purple-500/40 text-purple-300 group-hover:text-white flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>

                        <!-- 5. COMUNIDADE -->
                        <div class="group bg-dark-900 border border-purple-900/40 hover:border-purple-500/60 rounded-2xl p-5 flex flex-col justify-between gamer-card shadow-lg">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-950/70 border border-purple-500/40 flex items-center justify-center text-purple-400 text-lg">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                    <span class="text-[10px] font-mono text-purple-400 font-bold uppercase">Fóruns</span>
                                </div>
                                <h3 class="text-base font-rajdhani font-bold text-white uppercase tracking-wider">COMUNIDADE</h3>
                                <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                                    Participe dos tópicos, troque ideias e faça novos amigos gamers.
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-purple-900/30 flex justify-end">
                                <a href="comunidade.php" class="w-8 h-8 rounded-lg bg-purple-600/30 group-hover:bg-purple-600 border border-purple-500/40 text-purple-300 group-hover:text-white flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>

                        <!-- 6. REGRAS -->
                        <div class="group bg-dark-900 border border-purple-900/40 hover:border-purple-500/60 rounded-2xl p-5 flex flex-col justify-between gamer-card shadow-lg">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-950/70 border border-purple-500/40 flex items-center justify-center text-purple-400 text-lg">
                                        <i class="fa-solid fa-shield-halved"></i>
                                    </div>
                                    <span class="text-[10px] font-mono text-purple-400 font-bold uppercase">Diretrizes</span>
                                </div>
                                <h3 class="text-base font-rajdhani font-bold text-white uppercase tracking-wider">REGRAS</h3>
                                <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                                    Saiba o que pode e o que não pode na nossa comunidade de jogadores.
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-purple-900/30 flex justify-end">
                                <a href="wiki.php?categoria=Regras" class="w-8 h-8 rounded-lg bg-purple-600/30 group-hover:bg-purple-600 border border-purple-500/40 text-purple-300 group-hover:text-white flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endif; ?>

            <!-- LISTAGEM DE ARTIGOS DA WIKI -->
            <div class="mt-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-rajdhani font-black text-white uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-file-lines text-purple-400"></i>
                        <?= !empty($categoriaFiltro) ? 'GUIAS EM ' . strtoupper(e($categoriaFiltro)) : 'ARTIGOS & GUIAS RECENTES' ?>
                    </h2>
                    <span class="text-xs font-mono text-purple-400"><?= count($artigos) ?> artigos encontrados</span>
                </div>

                <?php if (empty($artigos)): ?>
                    <div class="p-8 bg-dark-900 border border-purple-900/30 rounded-2xl text-center">
                        <i class="fa-solid fa-magnifying-glass text-4xl text-purple-900 mb-3"></i>
                        <p class="text-gray-400 text-sm">Nenhum artigo encontrado com os critérios selecionados.</p>
                        <a href="wiki.php" class="inline-block mt-3 text-xs text-purple-400 hover:underline">Ver todos os guias</a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($artigos as $art): ?>
                            <a href="guia.php?slug=<?= urlencode($art['slug']) ?>" class="group bg-dark-900 border border-purple-900/40 hover:border-purple-500/60 rounded-2xl p-5 flex flex-col justify-between gamer-card shadow-lg transition-all">
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <span class="px-2.5 py-0.5 rounded-md bg-purple-950/80 border border-purple-500/40 text-[10px] font-mono text-purple-300 font-bold uppercase">
                                            <?= e($art['categoria']) ?>
                                        </span>
                                        <span class="text-[11px] font-mono text-gray-500">
                                            <?= e($art['tempo_leitura']) ?> • <?= formatarTempoRelativo($art['criado_em']) ?>
                                        </span>
                                    </div>
                                    <h3 class="text-base font-rajdhani font-bold text-white group-hover:text-purple-300 transition-colors line-clamp-2">
                                        <?= e($art['titulo']) ?>
                                    </h3>
                                    <p class="text-xs text-gray-400 mt-2 line-clamp-2 leading-relaxed">
                                        <?= e($art['resumo']) ?>
                                    </p>
                                </div>
                                <div class="mt-4 pt-3 border-t border-purple-900/30 flex items-center justify-between text-xs text-gray-400">
                                    <span class="flex items-center gap-1.5 text-purple-400 font-medium">
                                        <i class="fa-solid fa-gamepad text-[10px]"></i> <?= e($art['jogo_nome'] ?? 'Geral') ?>
                                    </span>
                                    <span class="text-purple-400 font-rajdhani font-bold flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                        Ler guia completo <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </main>

        <!-- COLUNA 3: LATERAL DIREITA (JOGOS EM DESTAQUE & ARTIGOS RECENTES) -->
        <aside class="w-full lg:w-72 shrink-0 flex flex-col gap-6">
            
            <!-- Widget Jogos em Destaque -->
            <div class="bg-dark-900 border border-purple-900/40 rounded-2xl p-5 shadow-lg">
                <div class="flex items-center justify-between pb-3 border-b border-purple-900/30 mb-4">
                    <h4 class="text-xs font-rajdhani font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-gamepad text-purple-400"></i> JOGOS EM DESTAQUE
                    </h4>
                    <a href="jogos.php" class="text-[10px] font-rajdhani font-bold uppercase text-purple-400 hover:text-purple-300">
                        VER TODOS <i class="fa-solid fa-arrow-right text-[8px]"></i>
                    </a>
                </div>

                <div class="flex flex-col gap-2.5">
                    <?php foreach ($jogosDestaque as $jd): ?>
                        <a href="wiki.php?jogo=<?= urlencode($jd['slug']) ?>" class="group flex items-center justify-between p-2 rounded-xl hover:bg-purple-950/40 border border-transparent hover:border-purple-500/30 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg overflow-hidden border border-purple-500/30 shrink-0">
                                    <img src="<?= e($jd['imagem']) ?>" alt="<?= e($jd['nome']) ?>" class="w-full h-full object-cover">
                                </div>
                                <div class="truncate">
                                    <span class="block text-xs font-bold text-white group-hover:text-purple-300 transition-colors truncate">
                                        <?= e($jd['nome']) ?>
                                    </span>
                                    <span class="block text-[10px] text-gray-400 truncate">
                                        <?= e($jd['categoria']) ?>
                                    </span>
                                </div>
                            </div>
                            <i class="fa-solid fa-arrow-right text-[10px] text-gray-500 group-hover:text-purple-400 group-hover:translate-x-0.5 transition-all"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Widget Artigos Recentes -->
            <div class="bg-dark-900 border border-purple-900/40 rounded-2xl p-5 shadow-lg">
                <div class="flex items-center justify-between pb-3 border-b border-purple-900/30 mb-4">
                    <h4 class="text-xs font-rajdhani font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-newspaper text-purple-400"></i> ARTIGOS RECENTES
                    </h4>
                    <a href="wiki.php" class="text-[10px] font-rajdhani font-bold uppercase text-purple-400 hover:text-purple-300">
                        VER TODOS <i class="fa-solid fa-arrow-right text-[8px]"></i>
                    </a>
                </div>

                <div class="flex flex-col gap-3">
                    <?php foreach ($artigosRecentes as $ar): ?>
                        <a href="guia.php?slug=<?= urlencode($ar['slug']) ?>" class="group block p-2 rounded-xl hover:bg-purple-950/40 border border-transparent hover:border-purple-500/30 transition-all">
                            <p class="text-xs font-bold text-white group-hover:text-purple-300 line-clamp-2 transition-colors">
                                <?= e($ar['titulo']) ?>
                            </p>
                            <span class="block text-[10px] font-mono text-purple-400 mt-1">
                                <?= formatarTempoRelativo($ar['criado_em']) ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

        </aside>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
