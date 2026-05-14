
<?php
include 'db.php';

$erro = '';
$editando = null;
$status_list = ['Pendente', 'Em Preparo', 'Pronto', 'Entregue', 'Cancelado'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = (int) ($_POST['cliente_id'] ?? 0);
    $produto_id = (int) ($_POST['produto_id'] ?? 0);
    $quantidade = max(1, (int) ($_POST['quantidade'] ?? 1));
    $status = $_POST['status'] ?? 'Pendente';
    $observacoes = trim($_POST['observacoes'] ?? '');
    $id = $_POST['id'] ?? null;

    if (!$cliente_id || !$produto_id) {
        $erro = 'Selecione cliente e produto.';
    } else {
        if ($id) {
            atualizarPedido($pdo, $id, $cliente_id, $produto_id, $quantidade, $status, $observacoes);
            $msg = 'Pedido atualizado com sucesso!';
        } else {
            adicionarPedido($pdo, $cliente_id, $produto_id, $quantidade, $status, $observacoes);
            $msg = 'Pedido criado com sucesso!';
        }
        header('Location: pedidos.php?ok=' . urlencode($msg));
        exit;
    }
}

if (isset($_GET['delete'])) {
    deletarPedido($pdo, (int) $_GET['delete']);
    header('Location: pedidos.php?ok=Pedido+excluido');
    exit;
}

if (isset($_GET['edit'])) {
    $editando = obterPedido($pdo, (int) $_GET['edit']);
}

$pedidos = listarPedidos($pdo);
$clientes = listarClientes($pdo);
$produtos = listarProdutos($pdo);

$status_class = [
    'Pendente' => 'amber',
    'Em Preparo' => 'blue',
    'Pronto' => 'green',
    'Entregue' => 'gray',
    'Cancelado' => 'rose',
];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Pedidos · Doce Sonho</title>
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
                <p class="eyebrow">Encomendas</p>
                <h1 class="title">Pedidos</h1>
                <p class="subtitle">Acompanhe o que está sendo preparado.</p>
            </header>

            <?php if (!empty($_GET['ok'])): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i>
                    <?= h($_GET['ok']) ?>
                </div>
            <?php endif; ?>
            <?php if ($erro): ?>
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i>
                    <?= h($erro) ?>
                </div>
            <?php endif; ?>

            <section class="card form-card">
                <h2 class="card-title">
                    <?= $editando ? 'Editar Pedido' : 'Novo Pedido' ?>
                </h2>
                <?php if (empty($clientes) || empty($produtos)): ?>
                    <p class="empty-msg">
                        <i class="fas fa-info-circle"></i>
                        Você precisa ter ao menos um <a href="clientes.php">cliente</a> e um <a
                            href="produtos.php">produto</a> cadastrado antes de criar pedidos.
                    </p>
                <?php else: ?>
                    <form method="POST" class="form-grid">
                        <?php if ($editando): ?>
                            <input type="hidden" name="id" value="<?= h($editando['id']) ?>">
                            <?php endif; ?>
                            <div class=" field">
                        <label>Cliente</label>
                        <select name="cliente_id" required>
                            <option value="">Selecione</option>
                            <?php foreach ($clientes as $c): ?>
                                <option value="<?= $c['id'] ?>"
                                                <?= ($editando['cliente_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                                                <?= h($c['nome']) ?>
                                            </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class=" field">
                                <label>Produto</label>
                                <select name="produto_id" required>
                                    <option value="">Selecione</option>
                                    <?php foreach ($produtos as $p): ?>
                                        <option value="<?= $p['id'] ?>"
                                                <?= ($editando['produto_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
                                                <?= h($p['nome']) ?> — R$
                                                <?= number_format($p['preco'], 2, ',', '.') ?>
                                            </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class=" field">
                                        <label>Quantidade</label>
                                        <input type="number" name="quantidade" min="1" required value="<?= h($editando['quantidade'] ?? 1) ?>">
                            </div>
                            <div class=" field">
                                        <label>Status</label>
                                        <select name="status">
                                            <?php foreach ($status_list as $s): ?>
                                                <option value="<?= h($s) ?>"
                                                <?= ($editando['status'] ?? 'Pendente') === $s ? 'selected' : '' ?>>
                                                <?= h($s) ?>
                                            </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class=" field col-2">
                                                <label>Observações</label>
                                                <textarea name="observacoes"
                                                    rows="2"><?= h($editando['observacoes'] ?? '') ?></textarea>
        </div>
        <div class="actions col-2">
            <?php if ($editando): ?>
                <a href="pedidos.php" class="btn-outline">Cancelar</a>
            <?php endif; ?>
            <button type="submit" class="btn-rose"><i class="fas fa-save"></i>
                <?= $editando ? 'Salvar' : 'Criar' ?>
            </button>
        </div>
        </form>
    <?php endif; ?>
    </section>

    <section class="orders-list">
        <?php if (empty($pedidos)): ?>
            <div class="empty"><i class="fas fa-shopping-bag"></i>
                <p>Nenhum pedido ainda.</p>
            </div>
        <?php else: ?>
            <?php foreach ($pedidos as $p): ?>
                <article class="order-card">
                    <div class="order-head">
                        <div>
                            <h3>
                                <?= h($p['cliente_nome']) ?> <span class="badge <?= $status_class[$p['status']] ?? 'gray' ?>">
                                                    <?= h($p['status']) ?>
                                                </span>
                                            </h3>
                                            <p class=" muted">
                                    <?= h(date('d/m/Y H:i', strtotime($p['criado_em']))) ?>
                                    </p>
                        </div>
                        <div class="order-actions">
                            <span class="order-total">R$
                                <?= number_format($p['total'], 2, ',', '.') ?>
                            </span>
                            <a href="pedidos.php?edit=<?= $p['id'] ?>" class=" btn-outline-sm"><i class="fas
                                            fa-pen"></i></a>
                            <a href="pedidos.php?delete=<?= $p['id'] ?>" onclick=" return confirm('Excluir este pedido?')"
                                class="btn-danger-sm"><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                    <div class="order-body">
                        <div class="order-item">
                            <span>
                                <?= (int) $p['quantidade'] ?>×
                                <?= h($p['produto_nome']) ?>
                            </span>
                            <span class="muted">R$
                                <?= number_format($p['preco_unitario'], 2, ',', '.') ?> un.
                            </span>
                        </div>
                        <?php if ($p['observacoes']): ?>
                            <p class="order-obs"><em>Obs:
                                    <?= h($p['observacoes']) ?>
                                </em></p>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
    </main>
    </div>
</body>

</html>