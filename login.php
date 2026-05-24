<?php
session_start();

// Usuários do sistema
$usuarios = [
    'cliente' => [
        'senha' => '',
        'tipo' => 'cliente',
        'nome' => 'Cliente'
    ],
    'admin' => [
        'senha' => '123',
        'tipo' => 'admin',
        'nome' => 'Confeiteira'
    ]
];

// Se já estiver logado
if (!empty($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = strtolower(trim($_POST['usuario'] ?? ''));
    $senha = trim($_POST['senha'] ?? '');

    if (isset($usuarios[$usuario])) {

        $dadosUsuario = $usuarios[$usuario];

        // Cliente entra sem senha
        if (
            $usuario === 'cliente'
            ||
            ($usuario === 'admin' && $senha === $dadosUsuario['senha'])
        ) {

            $_SESSION['logged_in'] = true;
            $_SESSION['usuario'] = $dadosUsuario['nome'];
            $_SESSION['tipo'] = $dadosUsuario['tipo'];

            header('Location: index.php');
            exit;
        }
    }

    $erro = 'Usuário ou senha inválidos.';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Login • Doce Sonho</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="login.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Quicksand:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>

    <div class="login-page">

        <div class="login-decor decor-1"></div>
        <div class="login-decor decor-2"></div>
        <div class="login-decor decor-3"></div>

        <div class="login-card">

            <div class="brand">
                <div class="brand-icon">
                    <i class="fas fa-birthday-cake"></i>
                </div>

                <h1 class="brand-name">Doce Sonho</h1>
                <p class="brand-tag">Confeitaria</p>
            </div>

            <div class="welcome">
                <p class="eyebrow">Área do sistema</p>

                <h2 class="welcome-title">
                    Faça seu <span class="italic-rose">login</span>
                </h2>

                <p class="welcome-sub">
                    Entre para acessar o painel da confeitaria.
                </p>
            </div>

            <?php if ($erro): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= $erro ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="login-form">

                <div class="field">
                    <label>Usuário</label>

                    <div class="input-wrap">
                        <i class="fas fa-user"></i>

                        <input type="text" name="usuario" placeholder="Digite seu usuário" required>
                    </div>
                </div>

                <div class="field">
                    <label>Senha</label>

                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>

                        <input type="password" name="senha" placeholder="Digite sua senha">
                    </div>
                </div>

                <button type="submit" class="btn-rose">
                    <i class="fas fa-heart"></i>
                    Entrar
                </button>

                <p class="hint">
                    <i class="fas fa-info-circle"></i>

                    Cliente:
                    <strong>cliente</strong>

                    <br><br>

                    Admin:
                    <strong>admin</strong>
                    |
                    Senha:
                    <strong>123</strong>
                </p>

            </form>

            <div class="footer-note">
                <i class="fas fa-heart"></i>
                <span>Feito com carinho para tornar cada dia mais doce.</span>
            </div>

        </div>
    </div>

</body>

</html>