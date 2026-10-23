<?php
/**
 * Ação de Autenticação / Login
 * Requisitos:
 * - Senha validada com password_verify (bcrypt)
 * - Registro de log de auditoria
 * - Retorno claro de erro via Toast
 */

require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/logger.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$login = trim($_POST['login'] ?? '');
$senha = $_POST['senha'] ?? '';

if (empty($login) || empty($senha)) {
    definirToast('error', 'Preencha o e-mail/usuário e a senha!');
    header('Location: ../login.php');
    exit;
}

try {
    $pdo = obterConexao();

    // Busca por e-mail ou por username
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? OR username = ? LIMIT 1");
    $stmt->execute([$login, $login]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Login com sucesso
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_username'] = $usuario['username'];
        $_SESSION['usuario_nome'] = $usuario['nome_completo'];
        $_SESSION['usuario_email'] = $usuario['email'];

        // Atualiza XP de login diário (+10 XP)
        $novoXp = $usuario['xp'] + 10;
        $pdo->prepare("UPDATE usuarios SET xp = ? WHERE id = ?")->execute([$novoXp, $usuario['id']]);

        // Registra log de auditoria
        registrarLog('LOGIN_SUCESSO', "Usuário '{$usuario['username']}' efetuou login no sistema.", 'usuarios', $usuario['id']);

        definirToast('success', "Bem-vindo de volta, {$usuario['username']}!");
        header('Location: ../wiki.php');
        exit;
    } else {
        // Falha no login
        registrarLog('LOGIN_FALHA', "Tentativa de login frustrada para o identificador '{$login}'.", 'usuarios', null);
        definirToast('error', 'Credenciais inválidas. Verifique seu e-mail/usuário e sua senha!');
        header('Location: ../login.php');
        exit;
    }

} catch (Exception $e) {
    error_log("Erro no login: " . $e->getMessage());
    definirToast('error', 'Erro interno no servidor ao tentar realizar login.');
    header('Location: ../login.php');
    exit;
}
