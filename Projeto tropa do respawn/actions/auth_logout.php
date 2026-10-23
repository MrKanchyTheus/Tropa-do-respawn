<?php
/**
 * Ação de Logout
 * Finaliza a sessão do usuário e registra log de auditoria
 */

require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/logger.php';

if (isset($_SESSION['usuario_id'])) {
    registrarLog('LOGOUT', "Usuário efetuou logout da plataforma.", 'usuarios', $_SESSION['usuario_id']);
}

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();
session_start();

definirToast('info', 'Você saiu da sua conta com segurança.');
header('Location: ../index.php');
exit;
