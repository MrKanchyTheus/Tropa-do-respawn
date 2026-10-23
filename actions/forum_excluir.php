<?php
/**
 * Ação de Exclusão de Fórum
 * Permite ao autor ou a um moderador/admin remover o tópico
 */

require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/logger.php';

if (!estaLogado()) {
    definirToast('error', 'Ação não permitida!');
    header('Location: ../login.php');
    exit;
}

$forumId = (int) ($_POST['forum_id'] ?? $_GET['id'] ?? 0);
$usuario = usuarioLogado();

if ($forumId <= 0) {
    definirToast('error', 'Fórum inválido!');
    header('Location: ../comunidade.php');
    exit;
}

try {
    $pdo = obterConexao();

    // Busca o fórum para validar permissão
    $stmt = $pdo->prepare("SELECT id, usuario_id, titulo FROM foruns WHERE id = ?");
    $stmt->execute([$forumId]);
    $forum = $stmt->fetch();

    if (!$forum) {
        definirToast('error', 'Fórum não encontrado!');
        header('Location: ../comunidade.php');
        exit;
    }

    if ($forum['usuario_id'] != $usuario['id'] && ($usuario['tipo'] ?? '') !== 'admin') {
        definirToast('error', 'Você não tem permissão para excluir este fórum!');
        header("Location: ../forum_detalhe.php?id={$forumId}");
        exit;
    }

    // Exclui
    $pdo->prepare("DELETE FROM foruns WHERE id = ?")->execute([$forumId]);

    // Registra log de auditoria
    registrarLog(
        'EXCLUIU_FORUM',
        "Usuário '{$usuario['username']}' excluiu o fórum #{$forumId} ('{$forum['titulo']}').",
        'foruns',
        $forumId
    );

    definirToast('info', 'O fórum foi removido com sucesso.');
    header('Location: ../comunidade.php');
    exit;

} catch (Exception $e) {
    error_log("Erro ao excluir fórum: " . $e->getMessage());
    definirToast('error', 'Erro ao excluir o fórum.');
    header("Location: ../forum_detalhe.php?id={$forumId}");
    exit;
}
