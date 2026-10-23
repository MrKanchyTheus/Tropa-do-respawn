<?php
/**
 * Catálogo de Jogos - Tropa do Respawn
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Catálogo de Jogos | Tropa do Respawn';
$pdo = obterConexao();

$categoriaFiltro = trim($_GET['categoria'] ?? '');
$busca = trim($_GET['busca'] ?? '');

$sql = "SELECT * FROM jogos WHERE 1=1";
$params = [];

if (!empty($categoriaFiltro)) {
    $sql .= " AND categoria LIKE ?";
    $params[] = "%{$categoriaFiltro}%";
}

if (!empty($busca)) {
    $sql .= " AND (nome LIKE ? OR descricao LIKE ?)";
    $params[] = "%{$busca}%";
    $params[] = "%{$busca}%";
}

$sql .= " ORDER BY total_dicas DESC, nome ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jogos = $stmt->fetchAll();

require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/nav.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">
    
    <!-- Topo da Página de Jogos -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-purple-900/40 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs font-mono text-purple-400 uppercase font-semibold">
                <i class="fa-solid fa-gamepad"></i> Base de Conhecimento
            </div>
            <h1 class="text-3xl font-rajdhani font-black text-white uppercase tracking-wider mt-1">
                JOGOS EM DESTAQUE NA TROPA
            </h1>
            <p class="text-xs text-gray-400 mt-1">
                Selecione um jogo para ver guias, combos, estratégias e fóruns da comunidade.
            </p>
        </div>

        <!-- Barra de Busca Rápida de Jogos -->
        <form action="jogos.php" method="GET" class="w-full md:w-72">
            <div class="relative">
                <input type="text" name="busca" value="<?= e($busca) ?>" placeholder="Buscar jogo..." class="w-full pl-10 pr-4 py-2.5 bg-dark-900 border border-purple-900/50 rounded-xl text-xs text-white placeholder-gray-500 focus:outline-none focus:border-purple-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-xs text-purple-400"></i>
            </div>
        </form>
    </div>

    <!-- Pílulas de Categorias -->
    <div class="flex flex-wrap items-center gap-2 mb-8">
        <a href="jogos.php" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold tracking-wide <?= empty($categoriaFiltro) ? 'bg-purple-600 text-white shadow-[0_0_15px_rgba(168,85,247,0.4)]' : 'bg-dark-900 text-gray-300 hover:text-white border border-purple-900/40' ?>">
            Todos os Jogos
        </a>
        <?php 
        $categorias = ['FPS', 'MOBA', 'Battle Royale', 'Sandbox', 'Esports', 'RPG', 'Luta', 'Anime'];
        foreach ($categorias as $cat): 
        ?>
            <a href="jogos.php?categoria=<?= urlencode($cat) ?>" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold tracking-wide <?= $categoriaFiltro === $cat ? 'bg-purple-600 text-white shadow-[0_0_15px_rgba(168,85,247,0.4)]' : 'bg-dark-900 text-gray-300 hover:text-white border border-purple-900/40' ?>">
                <?= $cat ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Grid de Jogos -->
    <?php if (empty($jogos)): ?>
        <div class="p-12 bg-dark-900 border border-purple-900/30 rounded-3xl text-center">
            <i class="fa-solid fa-ghost text-5xl text-purple-900 mb-3"></i>
            <h3 class="text-lg font-rajdhani font-bold text-white uppercase">Nenhum jogo encontrado</h3>
            <p class="text-xs text-gray-400 mt-1">Tente buscar por outro nome ou limpe os filtros de categoria.</p>
            <a href="jogos.php" class="inline-block mt-4 px-4 py-2 bg-purple-600 text-xs font-semibold text-white rounded-xl">Ver Todos</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($jogos as $j): ?>
                <div class="group bg-dark-900 border border-purple-900/40 hover:border-purple-500/60 rounded-3xl p-6 gamer-card shadow-xl flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-14 h-14 rounded-2xl overflow-hidden border border-purple-500/40 shrink-0 shadow-[0_0_15px_rgba(168,85,247,0.25)]">
                                <img src="<?= e($j['imagem']) ?>" alt="<?= e($j['nome']) ?>" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <span class="px-2 py-0.5 rounded bg-purple-950/80 border border-purple-500/30 text-[10px] font-mono text-purple-300 uppercase font-semibold">
                                    <?= e($j['categoria']) ?>
                                </span>
                                <h3 class="text-lg font-rajdhani font-bold text-white uppercase mt-1 group-hover:text-purple-300 transition-colors">
                                    <?= e($j['nome']) ?>
                                </h3>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 leading-relaxed line-clamp-3">
                            <?= e($j['descricao']) ?>
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-purple-900/30 flex items-center justify-between">
                        <span class="text-xs font-mono text-purple-400">
                            <i class="fa-solid fa-file-lines mr-1 text-[10px]"></i> <?= $j['total_dicas'] ?> Dicas & Guias
                        </span>
                        <a href="wiki.php?jogo=<?= urlencode($j['slug']) ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-600/20 hover:bg-purple-600 border border-purple-500/40 text-purple-300 hover:text-white text-xs font-semibold transition-all">
                            Ver Dicas <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
