<?php
/**
 * Ação de Envio de Chamado de Suporte
 */

require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/logger.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../suporte.php');
    exit;
}

$nome      = trim($_POST['nome'] ?? '');
$email     = trim($_POST['email'] ?? '');
$canal     = trim($_POST['canal'] ?? 'Formulário');
$categoria = trim($_POST['categoria'] ?? 'Dúvida Geral');
$mensagem  = trim($_POST['mensagem'] ?? '');

if (empty($nome) || empty($email) || empty($mensagem)) {
    definirToast('error', 'Por favor, preencha todos os campos do formulário de suporte!');
    header('Location: ../suporte.php#formulario');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    definirToast('error', 'Por favor, informe um e-mail válido!');
    header('Location: ../suporte.php#formulario');
    exit;
}

try {
    $pdo = obterConexao();
    $usuarioId = $_SESSION['usuario_id'] ?? null;

    $stmt = $pdo->prepare("
        INSERT INTO chamados_suporte (usuario_id, nome, email, canal, categoria, mensagem, status)
        VALUES (?, ?, ?, ?, ?, ?, 'Aberto')
    ");

    $stmt->execute([
        $usuarioId,
        $nome,
        $email,
        $canal,
        $categoria,
        $mensagem
    ]);

    $chamadoId = (int) $pdo->lastInsertId();

    // Log de auditoria
    registrarLog(
        'CHAMADO_SUPORTE',
        "Novo chamado #{$chamadoId} aberto por '{$nome}' ({$email}) na categoria '{$categoria}'.",
        'chamados_suporte',
        $chamadoId
    );

    definirToast('success', 'Mensagem enviada com sucesso! Nossa equipe gamer responderá em breve.');
    header('Location: ../suporte.php?enviado=1#formulario');
    exit;

} catch (Exception $e) {
    error_log("Erro ao salvar chamado de suporte: " . $e->getMessage());
    definirToast('error', 'Não foi possível enviar sua mensagem no momento.');
    header('Location: ../suporte.php#formulario');
    exit;
}
