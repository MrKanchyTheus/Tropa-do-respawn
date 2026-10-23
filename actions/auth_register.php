<?php
/**
 * Ação de Registro de Novo Usuário
 * Requisitos:
 * - Tratar senhas de usuários com hash bcrypt
 * - Narrativa 1: O autor principal é o usuário e ele acessa o site e cria sua conta
 * - Narrativa 2: Data de nascimento para controle de idade mínima
 * - Manter sempre os logs de auditoria
 */

require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/logger.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../registro.php');
    exit;
}

$username       = trim($_POST['username'] ?? '');
$nomeCompleto   = trim($_POST['nome_completo'] ?? '');
$email          = trim($_POST['email'] ?? '');
$senha          = $_POST['senha'] ?? '';
$confirmarSenha = $_POST['confirmar_senha'] ?? '';
$dataNascimento = trim($_POST['data_nascimento'] ?? '');
$termos         = !empty($_POST['termos']);

// Validações
if (empty($username) || empty($nomeCompleto) || empty($email) || empty($senha) || empty($dataNascimento)) {
    definirToast('error', 'Por favor, preencha todos os campos obrigatórios!');
    header('Location: ../registro.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    definirToast('error', 'O formato do e-mail inserido é inválido!');
    header('Location: ../registro.php');
    exit;
}

if (strlen($senha) < 6) {
    definirToast('error', 'A senha precisa ter no mínimo 6 caracteres!');
    header('Location: ../registro.php');
    exit;
}

if ($senha !== $confirmarSenha) {
    definirToast('error', 'As senhas digitadas não coincidem!');
    header('Location: ../registro.php');
    exit;
}

if (!$termos) {
    definirToast('error', 'Você deve concordar com os Termos de Uso e Política de Privacidade!');
    header('Location: ../registro.php');
    exit;
}

// Validação da data de nascimento
$idade = calcularIdade($dataNascimento);
if ($idade < 5 || $idade > 110) {
    definirToast('error', 'Por favor, insira uma data de nascimento válida!');
    header('Location: ../registro.php');
    exit;
}

try {
    $pdo = obterConexao();

    // Verifica se username ou email já existem
    $stmtCheck = $pdo->prepare("SELECT id, username, email FROM usuarios WHERE username = ? OR email = ?");
    $stmtCheck->execute([$username, $email]);
    $existente = $stmtCheck->fetch();

    if ($existente) {
        if (strtolower($existente['username']) === strtolower($username)) {
            definirToast('error', 'Este nome de usuário gamer já está em uso. Escolha outro!');
        } else {
            definirToast('error', 'Já existe uma conta cadastrada com este e-mail!');
        }
        header('Location: ../registro.php');
        exit;
    }

    // Hash da senha com BCRYPT (Requisito CORE)
    $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

    $stmtInsert = $pdo->prepare("
        INSERT INTO usuarios (username, nome_completo, email, senha, data_nascimento, avatar, nivel, xp, tipo)
        VALUES (?, ?, ?, ?, ?, 'assets/img/mascote.jpeg', 1, 100, 'usuario')
    ");

    $stmtInsert->execute([
        $username,
        $nomeCompleto,
        $email,
        $senhaHash,
        $dataNascimento
    ]);

    $novoId = (int) $pdo->lastInsertId();

    // Inicia sessão automaticamente
    $_SESSION['usuario_id'] = $novoId;
    $_SESSION['usuario_username'] = $username;
    $_SESSION['usuario_nome'] = $nomeCompleto;
    $_SESSION['usuario_email'] = $email;

    // Registra Log de Auditoria (Requisito CORE)
    registrarLog(
        'CADASTRO_USUARIO',
        "Conta gamer criada com sucesso para '{$username}' ({$email}), idade calculada: {$idade} anos.",
        'usuarios',
        $novoId
    );

    definirToast('success', "Bem-vindo à Tropa do Respawn, {$username}! Sua conta foi criada com sucesso.");
    header('Location: ../wiki.php');
    exit;

} catch (Exception $e) {
    error_log("Erro no cadastro: " . $e->getMessage());
    definirToast('error', 'Ocorreu um erro ao processar seu cadastro. Tente novamente!');
    header('Location: ../registro.php');
    exit;
}
