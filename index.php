<?php
session_start(); // Adicionado para permitir o uso de $_SESSION
include 'auth.php';
include 'db.php';
$stats = estatisticas($pdo);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Doce Sonho Confeitaria</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Quicksand:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="layout">
        <?php include 'sidebar.php'; ?>
        <main class="main">
            <header class="page-header">
                <p class="eyebrow">Bem-vinda de volta
                    <?= !empty($_SESSION['user_email']) ? ', ' . htmlspecialchars($_SESSION['user_email'], ENT_QUOTES, 'UTF-8') : '' ?>
                </p>
                <h1 class="title">Doce Sonho <span class="italic-rose">Confeitaria</span></h1>
                <p class="subtitle">Gerencie produtos, clientes e pedidos da sua confeitaria com elegância.</p>
                <div style="margin-top:20px;">
                    <a href="logout.php" class="btn-rose">
                        <i class="fas fa-sign-out-alt"></i>
                        Sair
                    </a>
                </div>
            </header>

            <section class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon rose"><i class="fas fa-birthday-cake"></i></div>
                    <p class="stat-label">Produtos</p>
                    <p class="stat-value">
                        <?= $stats['produtos'] ?>
                    </p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon gold"><i class="fas fa-users"></i></div>
                    <p class="stat-label">Clientes</p>
                    <p class="stat-value">
                        <?= $stats['clientes'] ?>
                    </p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon pink"><i class="fas fa-shopping-bag"></i></div>
                    <p class="stat-label">Pedidos</p>
                    <p class="stat-value">
                        <?= $stats['pedidos'] ?>
                    </p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon mix"><i class="fas fa-dollar-sign"></i></div>
                    <p class="stat-label">Receita Total</p>
                    <p class="stat-value">R$
                        <?= number_format($stats['receita'], 2, ',', '.') ?>
                    </p>
                </div>
            </section>

            <section class="pending-banner">
                <div class="pending-icon"><i class="fas fa-clock"></i></div>
                <div class="pending-text">
                    <strong>
                        <?= $stats['pendentes'] ?> pedido
                        <?= $stats['pendentes'] == 1 ? '' : 's' ?>
                        pendente
                        <?= $stats['pendentes'] == 1 ? '' : 's' ?>
                    </strong>
                    <span>Acompanhe e atualize o status na página de pedidos.</span>
                </div>
                <a href="pedidos.php" class="btn-rose">Ver pedidos</a>
            </section>

            <section class="quick-grid">
                <a href="produtos.php" class="quick-card">
                    <i class="fas fa-birthday-cake"></i>
                    <h3>Gerenciar Produtos</h3>
                    <p>Cadastre bolos, doces e tortas.</p>
                </a>
                <a href="clientes.php" class="quick-card">
                    <i class="fas fa-users"></i>
                    <h3>Gerenciar Clientes</h3>
                    <p>Mantenha seus clientes organizados.</p>
                </a>
                <a href="pedidos.php" class="quick-card">
                    <i class="fas fa-shopping-bag"></i>
                    <h3>Gerenciar Pedidos</h3>
                    <p>Crie e acompanhe pedidos.</p>
                </a>
            </section>
        </main>
    </div>
</body>

</html>