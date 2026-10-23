<?php
/**
 * Ação de Avaliação de Fóruns (1 a 5 estrelas)
 * Atende ao requisito: "os usuários vão poder criar fóruns comentar em outros fóruns, avaliar fóruns."
 */

require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/logger.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../comunidade.php');
    exit;
}

if (!estaLogado()) {
    definirToast('error', 'Você precisa entrar na sua conta para avaliar este fórum!');
    header('Location: ../login.php');
    exit;
}

$forumId = (int) ($_POST['forum_id'] ?? 0);
$nota    = (int) ($_POST['nota'] ?? 0);

if ($forumId <= 0 || $nota < 1 || $nota > 5) {
    definirToast('error', 'Por favor, selecione uma nota entre 1 e 5 estrelas!');
    header("Location: ../forum_detalhe.php?id={$forumId}");
    exit;
}

try {
    $pdo = obterConexao();
    $usuario = usuarioLogado();

    // Verifica se o usuário já avaliou este fórum
    $stmtCheck = $pdo->prepare("SELECT id, nota FROM forum_avaliacoes WHERE forum_id = ? AND usuario_id = ?");
    $stmtCheck->execute([$forumId, $usuario['id']]);
    $avaliacaoExistente = $stmtCheck->fetch();

    if ($avaliacaoExistente) {
        $stmtUpdate = $pdo->prepare("UPDATE forum_avaliacoes SET nota = ? WHERE id = ?");
        $stmtUpdate->execute([$nota, $avaliacaoExistente['id']]);
        $mensagemAviso = "Sua nota para este fórum foi atualizada para {$nota} estrelas!";
    } else {
        $stmtInsert = $pdo->prepare("INSERT INTO forum_avaliacoes (forum_id, usuario_id, nota) VALUES (?, ?, ?)");
        $stmtInsert->execute([$forumId, $usuario['id'], $nota]);
        $mensagemAviso = "Você avaliou este fórum com {$nota} estrelas!";
        
        // Recompensa com XP (+10 XP)
        $novoXp = $usuario['xp'] + 10;
        $pdo->prepare("UPDATE usuarios SET xp = ? WHERE id = ?")->execute([$novoXp, $usuario['id']]);
    }

    // Recalcula média geral e total de avaliações do fórum
    $stmtCalc = $pdo->prepare("SELECT AVG(nota) as media, COUNT(*) as total FROM forum_avaliacoes WHERE forum_id = ?");
    $stmtCalc->execute([$forumId]);
    $stats = $stmtCalc->fetch();

    $media = round((float) ($stats['media'] ?? 0), 2);
    $total = (int) ($stats['total'] ?? 0);

    $stmtUpForum = $pdo->prepare("UPDATE foruns SET media_avaliacao = ?, total_avaliacoes = ? WHERE id = ?");
    $stmtUpForum->execute([$media, $total, $forumId]);

    // Registra auditoria
    registrarLog(
        'AVALIOU_FORUM',
        "Usuário '{$usuario['username']}' avaliou o fórum #{$forumId} com {$nota} estrelas (Nova média: {$media}).",
        'forum_avaliacoes',
        $forumId
    );

    definirToast('success', $mensagemAviso);
    header("Location: ../forum_detalhe.php?id={$forumId}");
    exit;

} catch (Exception $e) {
    error_log("Erro ao avaliar fórum: " . $e->getMessage());
    definirToast('error', 'Ocorreu um erro ao processar sua avaliação.');
    header("Location: ../forum_detalhe.php?id={$forumId}");
    exit;
}
