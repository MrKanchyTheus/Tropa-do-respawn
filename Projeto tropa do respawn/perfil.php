<?php
/**
 * Perfil do Jogador - Tropa do Respawn
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

exigirLogin('login.php');

$pageTitle = 'Meu Perfil Gamer | Tropa do Respawn';
$usuario = usuarioLogado();
$pdo = obterConexao();

// Busca fóruns criados por este usuário
$stmtForuns = $pdo->prepare("SELECT * FROM foruns WHERE usuario_id = ? ORDER BY criado_em DESC");
$stmtForuns->execute([$usuario['id']]);
$meusForuns = $stmtForuns->fetchAll();

// Busca últimos logs deste usuário
$stmtLogs = $pdo->prepare("SELECT * FROM historico_logs WHERE usuario_id = ? ORDER BY criado_em DESC LIMIT 10");
$stmtLogs->execute([$usuario['id']]);
$meusLogs = $stmtLogs->fetchAll();

require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/nav.php';
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">
    
    <!-- CARD DO PERFIL GAMER -->
    <div class="relative overflow-hidden bg-dark-900 border border-purple-900/50 rounded-3xl p-6 sm:p-10 shadow-2xl mb-8">
        
        <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-6">
            
            <!-- Avatar com borda neon -->
            <div class="relative w-28 h-28 rounded-3xl overflow-hidden border-2 border-purple-500/60 shadow-[0_0_25px_rgba(168,85,247,0.4)] shrink-0">
                <img src="<?= e($usuario['avatar']) ?>" alt="Avatar" class="w-full h-full object-cover">
            </div>

            <!-- Informações Principais -->
            <div class="flex-1 text-center sm:text-left">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5 mb-2">
                    <h1 class="text-3xl font-rajdhani font-black text-white uppercase tracking-wider">
                        <?= e($usuario['username']) ?>
                    </h1>
                    <span class="px-2.5 py-0.5 rounded-lg bg-purple-950/80 border border-purple-500/40 text-[10px] font-mono text-purple-300 font-bold uppercase">
                        <?= strtoupper(e($usuario['tipo'])) ?>
                    </span>
                    <?php if ($usuario['pode_criar_forum']): ?>
                        <span class="px-2.5 py-0.5 rounded-lg bg-green-950/80 border border-green-500/40 text-[10px] font-mono text-green-300 font-bold uppercase">
                            <i class="fa-solid fa-check"></i> Apto para Fóruns
                        </span>
                    <?php else: ?>
                        <span class="px-2.5 py-0.5 rounded-lg bg-amber-950/80 border border-amber-500/40 text-[10px] font-mono text-amber-300 font-bold uppercase">
                            <i class="fa-solid fa-triangle-exclamation"></i> Idade < <?= IDADE_MINIMA_FORUM ?> anos
                        </span>
                    <?php endif; ?>
                </div>

                <p class="text-xs text-gray-400 font-mono">
                    <?= e($usuario['nome_completo']) ?> • <?= e($usuario['email']) ?>
                </p>

                <!-- Barra de XP e Nível -->
                <div class="mt-4 max-w-md">
                    <div class="flex justify-between text-xs font-mono mb-1.5">
                        <span class="text-white font-bold">Nível <?= $usuario['nivel'] ?></span>
                        <span class="text-purple-400"><?= $usuario['xp'] ?> / <?= ($usuario['nivel'] * 500) ?> XP</span>
                    </div>
                    <div class="w-full h-2.5 bg-dark-950 rounded-full overflow-hidden border border-purple-900/40">
                        <?php 
                        $pct = min(100, round(($usuario['xp'] % 500) / 5));
                        ?>
                        <div class="h-full bg-gradient-to-r from-purple-600 to-indigo-500 rounded-full" style="width: <?= max(5, $pct) ?>%"></div>
                    </div>
                </div>

                <!-- Detalhes do Membro -->
                <div class="mt-6 flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs font-mono text-gray-400">
                    <div>
                        <span class="text-gray-500">Idade Registrada:</span>
                        <strong class="text-white ml-1"><?= $usuario['idade'] ?> anos</strong>
                    </div>
                    <span>•</span>
                    <div>
                        <span class="text-gray-500">Membro desde:</span>
                        <strong class="text-white ml-1"><?= formatarDataBr($usuario['criado_em']) ?></strong>
                    </div>
                    <span>•</span>
                    <div>
                        <span class="text-gray-500">Fóruns Criados:</span>
                        <strong class="text-purple-400 ml-1"><?= count($meusForuns) ?></strong>
                    </div>
                </div>

            </div>

            <!-- Botão de Criar Fórum Rápido -->
            <?php if ($usuario['pode_criar_forum']): ?>
                <div class="shrink-0">
                    <a href="forum_novo.php" class="px-5 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-rajdhani font-bold text-xs uppercase tracking-wider shadow-lg shadow-purple-600/30 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-plus text-xs"></i> Criar Fórum
                    </a>
                </div>
            <?php endif; ?>

        </div>

    </div>

    <!-- SEÇÕES: MEUS FÓRUNS E HISTÓRICO RECENTE -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Meus Fóruns -->
        <div class="lg:col-span-7 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-purple-900/40">
                <h3 class="text-base font-rajdhani font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-comments text-purple-400"></i> MEUS TÓPICOS NO FÓRUM (<?= count($meusForuns) ?>)
                </h3>
            </div>

            <?php if (empty($meusForuns)): ?>
                <div class="p-8 bg-dark-900 border border-purple-900/30 rounded-2xl text-center text-xs text-gray-500">
                    Você ainda não publicou nenhum fórum na comunidade.
                    <?php if ($usuario['pode_criar_forum']): ?>
                        <div class="mt-3">
                            <a href="forum_novo.php" class="text-purple-400 hover:underline font-bold">+ Criar meu primeiro tópico</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php foreach ($meusForuns as $mf): ?>
                    <div class="p-4 bg-dark-900 border border-purple-900/40 rounded-2xl flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <span class="text-[10px] font-mono text-purple-400 uppercase font-semibold"><?= e($mf['categoria']) ?></span>
                            <a href="forum_detalhe.php?id=<?= $mf['id'] ?>" class="block">
                                <h4 class="text-sm font-rajdhani font-bold text-white hover:text-purple-300 truncate mt-0.5">
                                    <?= e($mf['titulo']) ?>
                                </h4>
                            </a>
                            <div class="flex items-center gap-3 text-[11px] font-mono text-gray-500 mt-1">
                                <span>★ <?= number_format($mf['media_avaliacao'], 1) ?> (<?= $mf['total_avaliacoes'] ?> votos)</span>
                                <span>• <?= $mf['total_comentarios'] ?> respostas</span>
                                <span>• <?= formatarTempoRelativo($mf['criado_em']) ?></span>
                            </div>
                        </div>
                        <a href="forum_detalhe.php?id=<?= $mf['id'] ?>" class="shrink-0 p-2 rounded-lg bg-purple-600/20 text-purple-300 hover:bg-purple-600 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Histórico Pessoal de Ações -->
        <div class="lg:col-span-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-purple-900/40">
                <h3 class="text-base font-rajdhani font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-purple-400"></i> MINHAS ATIVIDADES RECENTES
                </h3>
                <a href="historico.php?usuario=<?= urlencode($usuario['username']) ?>" class="text-[11px] font-mono text-purple-400 hover:underline">Ver tudo</a>
            </div>

            <div class="space-y-2.5">
                <?php if (empty($meusLogs)): ?>
                    <div class="p-8 bg-dark-900 border border-purple-900/30 rounded-2xl text-center text-xs text-gray-500">
                        Nenhuma atividade registrada ainda.
                    </div>
                <?php else: ?>
                    <?php foreach ($meusLogs as $ml): ?>
                        <div class="p-3 bg-dark-900 border border-purple-900/30 rounded-xl text-xs">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] font-mono font-bold text-purple-400 uppercase"><?= e($ml['acao']) ?></span>
                                <span class="text-[10px] font-mono text-gray-500"><?= formatarTempoRelativo($ml['criado_em']) ?></span>
                            </div>
                            <p class="text-gray-300 text-[11px] leading-relaxed">
                                <?= e($ml['detalhes']) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
