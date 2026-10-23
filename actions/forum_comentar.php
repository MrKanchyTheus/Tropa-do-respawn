<?php
/**
 * Ação de Comentário em Fóruns
 * Requisitos:
 * - Usuário logado
 * - Auditoria em historico_logs
 * - Notificação em TOAST
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
    definirToast('error', 'Você precisa fazer login para comentar neste fórum!');
    header('Location: ../login.php');
    exit;
}

$forumId  = (int) ($_POST['forum_id'] ?? 0);
$conteudo = trim($_POST['conteudo'] ?? '');

if ($forumId <= 0 || empty($conteudo)) {
    definirToast('error', 'Por favor, escreva uma mensagem para enviar o comentário!');
    header("Location: ../forum_detalhe.php?id={$forumId}");
    exit;
}

try {
    $pdo = obterConexao();
    $usuario = usuarioLogado();

    // Insere o comentário
    $stmt = $pdo->prepare("INSERT INTO forum_comentarios (forum_id, usuario_id, conteudo) VALUES (?, ?, ?)");
    $stmt->execute([$forumId, $usuario['id'], $conteudo]);
    $comentarioId = (int) $pdo->lastInsertId();

    // Atualiza contagem de comentários no fórum
    $pdo->prepare("UPDATE foruns SET total_comentarios = total_comentarios + 1 WHERE id = ?")->execute([$forumId]);

    // Recompensa com XP (+15 XP)
    $novoXp = $usuario['xp'] + 15;
    $pdo->prepare("UPDATE usuarios SET xp = ? WHERE id = ?")->execute([$novoXp, $usuario['id']]);

    // Registra log de auditoria
    registrarLog(
        'COMENTOU_FORUM',
        "Usuário '{$usuario['username']}' publicou um comentário no fórum #{$forumId}.",
        'forum_comentarios',
        $comentarioId
    );

    definirToast('success', 'Comentário publicado! Você ganhou +15 XP.');
    header("Location: ../forum_detalhe.php?id={$forumId}#comentarios");
    exit;

} catch (Exception $e) {
    error_log("Erro ao comentar no fórum: " . $e->getMessage());
    definirToast('error', 'Não foi possível publicar seu comentário no momento.');
    header("Location: ../forum_detalhe.php?id={$forumId}");
    exit;
}
