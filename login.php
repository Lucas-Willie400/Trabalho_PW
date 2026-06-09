<?php
require "db.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $login = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");

    $login->bindValue(':email', $email);

    $login->execute();
    $user = $login->fetch();


    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['user_email'] = $user['email'];
        header("location: index.php");
        exit();
    } else {
        header("location: login.php?msg=Login ou senha incorretos");
        exit();
    }

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

            <form method="POST" class="login-form">

                <div class="field">
                    <label>Email</label>

                    <div class="input-wrap">
                        <i class="fas fa-user"></i>

                        <input type="text" name="email" placeholder="Digite seu email" required>
                    </div>
                </div>

                <?php if (!empty($_GET['msg'])): ?>
                    <div
                        style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center; font-size: 14px;">
                        <?= htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

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