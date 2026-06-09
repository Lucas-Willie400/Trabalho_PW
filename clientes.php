<?php
session_start();
include 'auth.php';
include 'db.php';


$mensagem = '';
$erro = '';
$editando = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');
    $id = $_POST['id'] ?? null;

    if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Preencha nome e um email válido.';
    } else {
        if ($id) {
            atualizarCliente($pdo, $id, $nome, $email, $telefone, $endereco);
            $msg = 'Cliente atualizado com sucesso!';
        } else {
            adicionarCliente($pdo, $nome, $email, $telefone, $endereco);
            $msg = 'Cliente cadastrado com sucesso!';
        }
        header('Location: clientes.php?ok=' . urlencode($msg));
        exit;
    }
}

if (isset($_GET['delete'])) {
    deletarCliente($pdo, (int) $_GET['delete']);
    header('Location: clientes.php?ok=Cliente+excluido');
    exit;
}

if (isset($_GET['edit'])) {
    $editando = obterCliente($pdo, (int) $_GET['edit']);
}

$clientes = listarClientes($pdo);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Clientes · Doce Sonho</title>
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
                <p class="eyebrow">Sua base</p>
                <h1 class="title">Clientes</h1>
                <p class="subtitle">Mantenha contato com quem ama seus doces.</p>
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
                    <?= $editando ? 'Editar Cliente' : 'Novo Cliente' ?>
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
                        <label>Email</label>
                        <input type="email" name="email" required value="<?= h($editando['email'] ?? '') ?>">
                    </div>
                    <div class=" field">
                        <label>Telefone</label>
                        <input type="text" name="telefone" value="<?= h($editando['telefone'] ?? '') ?>">
                    </div>
                    <div class=" field col-2">
                        <label>Endereço</label>
                        <textarea name="endereco" rows="2"><?= h($editando['endereco'] ?? '') ?></textarea>
                    </div>
                    <div class="actions col-2">
                        <?php if ($editando): ?>
                            <a href="clientes.php" class="btn-outline">Cancelar</a>
                        <?php endif; ?>
                        <button type="submit" class="btn-rose"><i class="fas fa-save"></i>
                            <?= $editando ? 'Salvar' : 'Criar' ?>
                        </button>
                    </div>
                </form>
            </section>

            <section class="card table-card">
                <?php if (empty($clientes)): ?>
                    <div class="empty"><i class="fas fa-users"></i>
                        <p>Nenhum cliente cadastrado.</p>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Contato</th>
                                <th>Endereço</th>
                                <th style="text-align:right">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientes as $c): ?>
                                <tr>
                                    <td><strong>
                                            <?= h($c['nome']) ?>
                                        </strong></td>
                                    <td>
                                        <div><i class="fas fa-envelope"></i>
                                            <?= h($c['email']) ?>
                                        </div>
                                        <?php if ($c['telefone']): ?>
                                            <div><i class="fas fa-phone"></i>
                                                <?= h($c['telefone']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= h($c['endereco'] ?: '—') ?>
                                    </td>
                                    <td style="text-align:right">
                                        <a href="clientes.php?edit=<?= $c['id'] ?>" class=" btn-outline-sm"><i class="fas
                                                fa-pen"></i></a>
                                        <a href="clientes.php?delete=<?= $c['id'] ?>"
                                            onclick=" return confirm('Excluir este cliente?')" class="btn-danger-sm"><i
                                                class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>

</html>