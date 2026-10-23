<?php
/**
 * Ação de Criação de Fórum
 * Atende às Regras de Negócio:
 * - Usuário deve estar com conta logada
 * - Usuário deve possuir a idade mínima requerida (Narrativa 2)
 * - Registro de auditoria em historico_logs
 * - Retorno de avisos em TOAST e MODAL
 */

require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/logger.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../forum_novo.php');
    exit;
}

// 1. Verificação de Autenticação
if (!estaLogado()) {
    definirToast('error', 'Acesso restrito! Você precisa estar conectado à sua conta para criar um fórum.');
    header('Location: ../login.php');
    exit;
}

$usuario = usuarioLogado();
if (!$usuario) {
    definirToast('error', 'Sessão inválida. Por favor, entre novamente!');
    header('Location: ../login.php');
    exit;
}

// 2. Verificação da Idade Mínima (Narrativa 2 - Requisito Mandatório)
$idade = calcularIdade($usuario['data_nascimento']);
if ($idade < IDADE_MINIMA_FORUM) {
    registrarLog(
        'TENTATIVA_FORUM_BLOQUEADA',
        "Criação de fórum bloqueada para '{$usuario['username']}': usuário possui {$idade} anos, exigido mínimo de " . IDADE_MINIMA_FORUM . " anos.",
        'foruns'
    );
    definirToast('error', "Você possui {$idade} anos. A idade mínima permitida para criar tópicos na comunidade é de " . IDADE_MINIMA_FORUM . " anos!");
    header('Location: ../comunidade.php');
    exit;
}

// 3. Captura e validação dos dados
$titulo    = trim($_POST['titulo'] ?? '');
$categoria = trim($_POST['categoria'] ?? 'Geral');
$jogoId    = !empty($_POST['jogo_id']) ? (int) $_POST['jogo_id'] : null;
$conteudo  = trim($_POST['conteudo'] ?? '');
$tags      = trim($_POST['tags'] ?? '');

if (empty($titulo) || empty($conteudo)) {
    definirToast('error', 'Por favor, preencha o título e o conteúdo do fórum!');
    header('Location: ../forum_novo.php');
    exit;
}

if (strlen($titulo) < 5) {
    definirToast('error', 'O título deve ter pelo menos 5 caracteres!');
    header('Location: ../forum_novo.php');
    exit;
}

try {
    $pdo = obterConexao();

    $stmt = $pdo->prepare("
        INSERT INTO foruns (usuario_id, jogo_id, categoria, titulo, conteudo, tags, visualizacoes, media_avaliacao, total_avaliacoes, total_comentarios)
        VALUES (?, ?, ?, ?, ?, ?, 1, 5.00, 1, 0)
    ");

    $stmt->execute([
        $usuario['id'],
        $jogoId,
        $categoria,
        $titulo,
        $conteudo,
        $tags
    ]);

    $forumId = (int) $pdo->lastInsertId();

    // Cria a avaliação inicial do próprio autor (5 estrelas)
    $stmtRate = $pdo->prepare("INSERT INTO forum_avaliacoes (forum_id, usuario_id, nota) VALUES (?, ?, 5)");
    $stmtRate->execute([$forumId, $usuario['id']]);

    // Recompensa de XP para o usuário gamer (+50 XP)
    $novoXp = $usuario['xp'] + 50;
    $pdo->prepare("UPDATE usuarios SET xp = ? WHERE id = ?")->execute([$novoXp, $usuario['id']]);

    // Registra log de auditoria
    registrarLog(
        'CRIOU_FORUM',
        "Usuário '{$usuario['username']}' criou o fórum #{$forumId} ('{$titulo}') na categoria '{$categoria}'.",
        'foruns',
        $forumId
    );

    definirToast('success', 'Fórum publicado com sucesso na comunidade! Você ganhou +50 XP.');
    header("Location: ../forum_detalhe.php?id={$forumId}");
    exit;

} catch (Exception $e) {
    error_log("Erro ao criar fórum: " . $e->getMessage());
    definirToast('error', 'Ocorreu um erro ao salvar o fórum. Tente novamente!');
    header('Location: ../forum_novo.php');
    exit;
}
