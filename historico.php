<?php
/**
 * Página de Históricos de Auditoria - Tropa do Respawn
 * Implementa a Regra de Negócio CORE obrigatória:
 * "O sistema deve ter uma página de históricos e manter sempre os logs de qualquer alteração
 * feita por qualquer usuário, para auditorias futuras."
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Histórico de Auditoria & Logs | Tropa do Respawn';
$pdo = obterConexao();

// Filtros de Auditoria
$filtroAcao = trim($_GET['acao'] ?? '');
$filtroUsuario = trim($_GET['usuario'] ?? '');
$filtroBusca = trim($_GET['busca'] ?? '');
$filtroData = trim($_GET['data'] ?? '');

$sql = "SELECT * FROM historico_logs WHERE 1=1";
$params = [];

if (!empty($filtroAcao)) {
    $sql .= " AND acao = ?";
    $params[] = $filtroAcao;
}

if (!empty($filtroUsuario)) {
    $sql .= " AND usuario_nome LIKE ?";
    $params[] = "%{$filtroUsuario}%";
}

if (!empty($filtroBusca)) {
    $sql .= " AND (detalhes LIKE ? OR ip_origem LIKE ?)";
    $params[] = "%{$filtroBusca}%";
    $params[] = "%{$filtroBusca}%";
}

if (!empty($filtroData)) {
    $sql .= " AND DATE(criado_em) = ?";
    $params[] = $filtroData;
}

$sql .= " ORDER BY criado_em DESC LIMIT 100";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$logs = $stmt->fetchAll();

// Métricas de auditoria
$totalLogs = (int) $pdo->query("SELECT COUNT(*) FROM historico_logs")->fetchColumn();
$totalUsuarios = (int) $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$totalAcoesForuns = (int) $pdo->query("SELECT COUNT(*) FROM historico_logs WHERE entidade IN ('foruns', 'forum_comentarios', 'forum_avaliacoes')")->fetchColumn();

// Lista de ações distintas para o dropdown de filtro
$stmtAcoes = $pdo->query("SELECT DISTINCT acao FROM historico_logs ORDER BY acao ASC");
$acoesDisponiveis = $stmtAcoes->fetchAll(PDO::FETCH_COLUMN);

require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/nav.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">
    
    <!-- Topo da Página de Histórico -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-purple-900/40 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-950/70 border border-purple-500/40 text-purple-300 text-xs font-mono uppercase font-semibold mb-2">
                <i class="fa-solid fa-shield-halved text-purple-400"></i> Módulo de Auditoria & Conformidade
            </div>
            <h1 class="text-3xl sm:text-4xl font-rajdhani font-black text-white uppercase tracking-wider">
                HISTÓRICO DE ALTERAÇÕES & LOGS
            </h1>
            <p class="text-xs sm:text-sm text-gray-400 mt-1 max-w-2xl">
                Registro permanente e imutável de todas as modificações, cadastros, publicações de fóruns, avaliações e acessos realizados pelos usuários.
            </p>
        </div>

        <!-- Indicador de Status do Log -->
        <div class="flex items-center gap-3 p-3 bg-dark-900 border border-purple-900/40 rounded-2xl shrink-0">
            <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></span>
            <div>
                <span class="block text-xs font-rajdhani font-bold text-white uppercase">AUDITORIA ATIVA</span>
                <span class="block text-[10px] font-mono text-purple-400"><?= $totalLogs ?> eventos monitorados</span>
            </div>
        </div>
    </div>

    <!-- Cards de Métricas -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-dark-900 border border-purple-900/40 rounded-2xl p-5 shadow-lg flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-950/70 border border-purple-500/30 flex items-center justify-center text-purple-400 text-xl shrink-0">
                <i class="fa-solid fa-database"></i>
            </div>
            <div>
                <span class="text-xs font-mono text-gray-400 uppercase">Total de Logs</span>
                <span class="block text-2xl font-rajdhani font-black text-white"><?= $totalLogs ?></span>
            </div>
        </div>

        <div class="bg-dark-900 border border-purple-900/40 rounded-2xl p-5 shadow-lg flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-950/70 border border-indigo-500/30 flex items-center justify-center text-indigo-400 text-xl shrink-0">
                <i class="fa-solid fa-comments"></i>
            </div>
            <div>
                <span class="text-xs font-mono text-gray-400 uppercase">Eventos da Comunidade</span>
                <span class="block text-2xl font-rajdhani font-black text-white"><?= $totalAcoesForuns ?></span>
            </div>
        </div>

        <div class="bg-dark-900 border border-purple-900/40 rounded-2xl p-5 shadow-lg flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-950/70 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-xl shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <span class="text-xs font-mono text-gray-400 uppercase">Usuários Rastreados</span>
                <span class="block text-2xl font-rajdhani font-black text-white"><?= $totalUsuarios ?></span>
            </div>
        </div>
    </div>

    <!-- Barra de Filtros -->
    <div class="bg-dark-900 border border-purple-900/40 rounded-2xl p-4 sm:p-5 shadow-lg mb-8">
        <form action="historico.php" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-center">
            
            <!-- Filtro por Ação -->
            <div>
                <select name="acao" class="w-full px-3 py-2 bg-dark-950 border border-purple-900/40 rounded-xl text-xs text-white focus:outline-none focus:border-purple-500">
                    <option value="">Todas as Ações</option>
                    <?php foreach ($acoesDisponiveis as $ac): ?>
                        <option value="<?= e($ac) ?>" <?= $filtroAcao === $ac ? 'selected' : '' ?>><?= e($ac) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filtro por Usuário -->
            <div>
                <input type="text" name="usuario" value="<?= e($filtroUsuario) ?>" placeholder="Filtrar por Usuário..." class="w-full px-3 py-2 bg-dark-950 border border-purple-900/40 rounded-xl text-xs text-white placeholder-gray-500 focus:outline-none focus:border-purple-500">
            </div>

            <!-- Filtro por Data -->
            <div>
                <input type="date" name="data" value="<?= e($filtroData) ?>" class="w-full px-3 py-2 bg-dark-950 border border-purple-900/40 rounded-xl text-xs text-white focus:outline-none focus:border-purple-500">
            </div>

            <!-- Busca Livre -->
            <div>
                <input type="text" name="busca" value="<?= e($filtroBusca) ?>" placeholder="Buscar em detalhes/IP..." class="w-full px-3 py-2 bg-dark-950 border border-purple-900/40 rounded-xl text-xs text-white placeholder-gray-500 focus:outline-none focus:border-purple-500">
            </div>

            <!-- Botões -->
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full py-2 px-4 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-rajdhani font-bold text-xs uppercase tracking-wider transition-all">
                    Filtrar
                </button>
                <?php if (!empty($filtroAcao) || !empty($filtroUsuario) || !empty($filtroBusca) || !empty($filtroData)): ?>
                    <a href="historico.php" class="text-xs text-purple-400 hover:text-white underline shrink-0 px-2">Limpar</a>
                <?php endif; ?>
            </div>

        </form>
    </div>

    <!-- Tabela de Logs de Auditoria -->
    <div class="bg-dark-900 border border-purple-900/40 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-purple-900/40 bg-dark-950/80 text-[11px] font-rajdhani font-bold uppercase tracking-wider text-purple-300">
                        <th class="py-4 px-4 sm:px-6">Data / Hora</th>
                        <th class="py-4 px-4">Usuário</th>
                        <th class="py-4 px-4">Ação</th>
                        <th class="py-4 px-4">Entidade</th>
                        <th class="py-4 px-4">Detalhes da Alteração</th>
                        <th class="py-4 px-4 sm:px-6">IP Origem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-900/20 text-xs">
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-500">
                                Nenhum registro de auditoria encontrado com os filtros aplicados.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <?php
                                // Cores dos badges conforme a ação
                                $acao = $log['acao'];
                                $badgeClass = 'bg-purple-950/70 border-purple-500/40 text-purple-300';
                                if (strpos($acao, 'CADASTRO') !== false || strpos($acao, 'SUCESSO') !== false) {
                                    $badgeClass = 'bg-emerald-950/70 border-emerald-500/40 text-emerald-300';
                                } elseif (strpos($acao, 'FALHA') !== false || strpos($acao, 'EXCLUIU') !== false) {
                                    $badgeClass = 'bg-red-950/70 border-red-500/40 text-red-300';
                                } elseif (strpos($acao, 'BLOQUEADA') !== false) {
                                    $badgeClass = 'bg-amber-950/70 border-amber-500/40 text-amber-300';
                                } elseif (strpos($acao, 'AVALIOU') !== false) {
                                    $badgeClass = 'bg-indigo-950/70 border-indigo-500/40 text-indigo-300';
                                }
                            ?>
                            <tr class="hover:bg-purple-950/20 transition-colors">
                                <!-- Data e Hora -->
                                <td class="py-3.5 px-4 sm:px-6 font-mono text-[11px] text-gray-400 whitespace-nowrap">
                                    <span class="block text-white font-medium"><?= formatarDataBr($log['criado_em']) ?></span>
                                    <span class="block text-[10px] text-purple-400"><?= formatarTempoRelativo($log['criado_em']) ?></span>
                                </td>

                                <!-- Usuário -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-dark-950 border border-purple-900/30 text-xs font-semibold text-white">
                                        <i class="fa-solid fa-user text-[10px] text-purple-400"></i>
                                        <?= e($log['usuario_nome']) ?>
                                    </span>
                                </td>

                                <!-- Ação -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-lg border text-[10px] font-mono font-bold uppercase tracking-wide <?= $badgeClass ?>">
                                        <?= e($log['acao']) ?>
                                    </span>
                                </td>

                                <!-- Entidade -->
                                <td class="py-3.5 px-4 whitespace-nowrap font-mono text-[11px] text-gray-400">
                                    <?= e($log['entidade']) ?> <?= $log['registro_id'] ? '#' . $log['registro_id'] : '' ?>
                                </td>

                                <!-- Detalhes -->
                                <td class="py-3.5 px-4 text-gray-300 leading-relaxed max-w-md">
                                    <?= e($log['detalhes']) ?>
                                </td>

                                <!-- IP de Origem -->
                                <td class="py-3.5 px-4 sm:px-6 font-mono text-[11px] text-gray-500 whitespace-nowrap">
                                    <?= e($log['ip_origem']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
