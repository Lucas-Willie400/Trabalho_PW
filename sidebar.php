<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <a href="index.php" class="brand">
        <div class="brand-logo"><i class="fas fa-birthday-cake"></i></div>
        <div>
            <h2>Doce Sonho</h2>
            <span>Confeitaria</span>
        </div>
    </a>
    <nav class="nav">
        <a href="index.php" class="<?= $current === 'index.php' ? 'active' : '' ?>"><i class=" fas fa-home"></i>
            Início</a>
        <a href="produtos.php" class="<?= $current === 'produtos.php' ? 'active' : '' ?>"><i class=" fas
            fa-birthday-cake"></i> Produtos</a>
        <a href="clientes.php" class="<?= $current === 'clientes.php' ? 'active' : '' ?>"><i class=" fas fa-users"></i>
            Clientes</a>
        <a href="pedidos.php" class="<?= $current === 'pedidos.php' ? 'active' : '' ?>"><i class=" fas
            fa-shopping-bag"></i> Pedidos</a>
    </nav>
    <div class="sidebar-footer">
        <i class="fas fa-heart"></i>
        <p>Feito com carinho para tornar cada dia mais doce.</p>
    </div>
</aside>