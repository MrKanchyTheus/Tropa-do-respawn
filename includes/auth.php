<?php
/**
 * Tropa do Respawn - Gerenciamento de Autenticação e Sessões
 */

require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/functions.php';

/**
 * Retorna se há um usuário autenticado na sessão atual
 */
function estaLogado(): bool {
    return !empty($_SESSION['usuario_id']);
}

/**
 * Retorna os dados completos do usuário autenticado
 */
function usuarioLogado(): ?array {
    if (!estaLogado()) {
        return null;
    }

    $pdo = obterConexao();
    $stmt = $pdo->prepare("SELECT id, username, nome_completo, email, data_nascimento, avatar, nivel, xp, tipo, criado_em FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        $usuario['idade'] = calcularIdade($usuario['data_nascimento']);
        $usuario['pode_criar_forum'] = ($usuario['idade'] >= IDADE_MINIMA_FORUM);
        return $usuario;
    }

    return null;
}

/**
 * Exige que o usuário esteja logado, caso contrário redireciona com Toast
 */
function exigirLogin(string $redirecionar = 'login.php'): void {
    if (!estaLogado()) {
        definirToast('error', 'Você precisa estar logado para acessar esta página!');
        header("Location: {$redirecionar}");
        exit;
    }
}
