<?php
session_start(); // Sempre a primeira linha!
include 'auth.php';
include 'db.php';
$mensagem = '';
$erro = '';
$editando = null;

// CREATE / UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $preco = (float) str_replace(',', '.', $_POST['preco'] ?? 0);
    $descricao = trim($_POST['descricao'] ?? '');
    $categoria = trim($_POST['categoria'] ?? 'Geral');
    $disponivel = isset($_POST['disponivel']) ? 1 : 0;
    $id = $_POST['id'] ?? null;

    if ($nome === '' || $preco <= 0) {
        $erro = 'Preencha nome e preço corretamente.';
    } else {
        if ($id) {
            atualizarProduto($pdo, $id, $nome, $preco, $descricao, $categoria, $disponivel);
            $mensagem = 'Produto atualizado com sucesso!';
        } else {
            adicionarProduto($pdo, $nome, $preco, $descricao, $categoria, $disponivel);
            $mensagem = 'Produto cadastrado com sucesso!';
        }
        header('Location: produtos.php?ok=' . urlencode($mensagem));
        exit;
    }
}

// DELETE
if (isset($_GET['delete'])) {
    deletarProduto($pdo, (int) $_GET['delete']);
    header('Location: produtos.php?ok=Produto+excluido');
    exit;
}

// EDIT
if (isset($_GET['edit'])) {
    $editando = obterProduto($pdo, (int) $_GET['edit']);
}

$produtos = listarProdutos($pdo);
$categorias = ['Bolos', 'Tortas', 'Doces', 'Salgados', 'Bebidas', 'Geral'];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Produtos · Doce Sonho</title>
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
                <p class="eyebrow">Cardápio</p>
                <h1 class="title">Produtos</h1>
                <p class="subtitle">Cadastre e gerencie os doces da confeitaria.</p>
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
                    <?= $editando ? 'Editar Produto' : 'Novo Produto' ?>
                </h2>
                <form method="POST" class="form-grid">
                    <?php if ($editando): ?>
                        <input type="hidden" name="id" value="<?= h($editando['id']) ?>">
                    <?php endif; ?>
                    <div class=" field col-2">
                    <label>Nome</label>
                    <input type="text" name="nome" required value="<?= h($editando['nome'] ?? '') ?>">
                    </div>
                    <div class=" field">
                    <label>Preço (R$)</label>
                    <input type="number" step="0.01" name="preco" required value="<?= h($editando['preco'] ?? '') ?>">
                    </div>
                    <div class=" field">
                    <label>Categoria</label>
                    <select name="categoria">
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= h($c) ?>"
                                        <?= ($editando['categoria'] ?? 'Geral') === $c ? 'selected' : '' ?>>
                                        <?= h($c) ?>
                                    </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class=" field col-2">
                            <label>Descrição</label>
                            <textarea name="descricao" rows="2"><?= h($editando['descricao'] ?? '') ?></textarea>
    </div>
    <div class="field switch-field col-2">
        <label>
            <input type="checkbox" name="disponivel" <?= (!$editando || $editando['disponivel']) ? 'checked' : '' ?>>
            Disponível para venda
        </label>
    </div>
    <div class="actions col-2">
        <?php if ($editando): ?>
            <a href="produtos.php" class="btn-outline">Cancelar</a>
        <?php endif; ?>
        <button type="submit" class="btn-rose"><i class="fas fa-save"></i>
            <?= $editando ? 'Salvar' : 'Criar' ?>
        </button>
    </div>
    </form>
    </section>

    <section class="grid-cards">
        <?php if (empty($produtos)): ?>
            <div class="empty"><i class="fas fa-birthday-cake"></i>
                <p>Nenhum produto ainda. Cadastre o primeiro!</p>
            </div>
        <?php else: ?>
            <?php foreach ($produtos as $p): ?>
                <div class="prod-card">
                    <div class="prod-badges">
                        <span class="badge rose">
                            <?= h($p['categoria']) ?>
                        </span>
                        <?php if ($p['disponivel']): ?>
                            <span class="badge green">Disponível</span>
                        <?php else: ?>
                            <span class="badge gray">Indisponível</span>
                        <?php endif; ?>
                    </div>
                    <h3>
                        <?= h($p['nome']) ?>
                    </h3>
                    <p class="prod-desc">
                        <?= h($p['descricao'] ?: 'Sem descrição') ?>
                    </p>
                    <p class="prod-price">R$
                        <?= number_format($p['preco'], 2, ',', '.') ?>
                    </p>
                    <div class="card-actions">
                        <a href="produtos.php?edit=<?= $p['id'] ?>" class=" btn-outline-sm"><i class="fas
                                        fa-pen"></i> Editar</a>
                        <a href="produtos.php?delete=<?= $p['id'] ?>" onclick=" return confirm('Excluir este produto?')"
                            class="btn-danger-sm"><i class="fas fa-trash"></i></a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
    </main>
    </div>
</body>

</html>