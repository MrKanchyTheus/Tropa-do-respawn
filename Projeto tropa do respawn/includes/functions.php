<?php
/**
 * Funções utilitárias do sistema Tropa do Respawn
 */

require_once __DIR__ . '/../config/bd.php';

/**
 * Sanitiza strings para exibição segura contra XSS
 */
function e(?string $texto): string {
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Define uma mensagem toast para ser exibida na próxima requisição
 */
function definirToast(string $tipo, string $mensagem): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['toast'] = [
        'type' => in_array($tipo, ['success', 'error', 'warning', 'info']) ? $tipo : 'info',
        'message' => $mensagem
    ];
}

/**
 * Calcula a idade de um usuário com base na data de nascimento
 */
function calcularIdade(string $dataNascimento): int {
    try {
        $nascimento = new DateTime($dataNascimento);
        $hoje = new DateTime('today');
        return $nascimento->diff($hoje)->y;
    } catch (Exception $e) {
        return 0;
    }
}

/**
 * Verifica se a idade cumpre o requisito mínimo para criação de fóruns
 */
function atendeIdadeMinimaForum(string $dataNascimento): bool {
    return calcularIdade($dataNascimento) >= IDADE_MINIMA_FORUM;
}

/**
 * Captura o IP do cliente de forma confiável
 */
function obterIpCliente(): string {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

/**
 * Formata data e hora para o padrão gamer / relativo
 */
function formatarTempoRelativo(string $dataHora): string {
    try {
        $data = new DateTime($dataHora);
        $agora = new DateTime();
        $diff = $agora->diff($data);

        if ($diff->y > 0) {
            return $diff->y === 1 ? '1 ano atrás' : "{$diff->y} anos atrás";
        }
        if ($diff->m > 0) {
            return $diff->m === 1 ? '1 mês atrás' : "{$diff->m} meses atrás";
        }
        if ($diff->d >= 7) {
            $semanas = (int) floor($diff->d / 7);
            return $semanas === 1 ? '1 semana atrás' : "{$semanas} semanas atrás";
        }
        if ($diff->d > 0) {
            return $diff->d === 1 ? '1 dia atrás' : "{$diff->d} dias atrás";
        }
        if ($diff->h > 0) {
            return $diff->h === 1 ? '1 hora atrás' : "{$diff->h} horas atrás";
        }
        if ($diff->i > 0) {
            return $diff->i === 1 ? '1 minuto atrás' : "{$diff->i} minutos atrás";
        }
        return 'Agora mesmo';
    } catch (Exception $e) {
        return $dataHora;
    }
}

/**
 * Formata data em formato brasileiro dd/mm/aaaa às hh:mm
 */
function formatarDataBr(string $dataHora): string {
    try {
        $dt = new DateTime($dataHora);
        return $dt->format('d/m/Y \à\s H:i');
    } catch (Exception $e) {
        return $dataHora;
    }
}

/**
 * Gera um slug limpo para URLs amigáveis
 */
function gerarSlug(string $texto): string {
    $texto = preg_replace('~[^\pL\d]+~u', '-', $texto);
    $texto = iconv('utf-8', 'us-ascii//TRANSLIT', $texto);
    $texto = preg_replace('~[^-\w]+~', '', $texto);
    $texto = trim($texto, '-');
    $texto = preg_replace('~-+~', '-', $texto);
    $texto = strtolower($texto);
    return empty($texto) ? 'n-a' : $texto;
}
