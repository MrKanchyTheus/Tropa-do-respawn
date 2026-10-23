<?php
/**
 * Tropa do Respawn - Sistema de Auditoria e Logs de Alterações
 * Atende ao requisito CORE: O sistema deve ter uma página de históricos
 * e manter sempre os logs de qualquer alteração feita por qualquer usuário.
 */

require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/functions.php';

/**
 * Registra um evento de auditoria no banco de dados com PDO
 */
function registrarLog(string $acao, string $detalhes, string $entidade = 'sistema', ?int $registroId = null): bool {
    try {
        $pdo = obterConexao();

        $usuarioId = $_SESSION['usuario_id'] ?? null;
        $usuarioNome = $_SESSION['usuario_username'] ?? 'Visitante';
        $ip = obterIpCliente();

        $stmt = $pdo->prepare("
            INSERT INTO historico_logs (usuario_id, usuario_nome, acao, entidade, registro_id, detalhes, ip_origem)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $usuarioId,
            $usuarioNome,
            $acao,
            $entidade,
            $registroId,
            $detalhes,
            $ip
        ]);
    } catch (Exception $e) {
        error_log("Erro ao registrar log de auditoria: " . $e->getMessage());
        return false;
    }
}
