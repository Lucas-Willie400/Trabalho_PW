<?php

$DB_HOST = 'localhost';
$DB_NAME = 'confeitaria_doce_sonho';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die('Erro ao conectar no banco: ' . $e->getMessage());
}

// Helper para escapar saídas
function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// ============== PRODUTOS ==============
function listarProdutos($pdo)
{
    return $pdo->query("SELECT * FROM produtos ORDER BY criado_em DESC")->fetchAll();
}

function obterProduto($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function adicionarProduto($pdo, $nome, $preco, $descricao, $categoria, $disponivel)
{
    $stmt = $pdo->prepare(
        "INSERT INTO produtos (nome, preco, descricao, categoria, disponivel)
         VALUES (?, ?, ?, ?, ?)"
    );
    return $stmt->execute([$nome, $preco, $descricao, $categoria, $disponivel]);
}

function atualizarProduto($pdo, $id, $nome, $preco, $descricao, $categoria, $disponivel)
{
    $stmt = $pdo->prepare(
        "UPDATE produtos SET nome=?, preco=?, descricao=?, categoria=?, disponivel=?
         WHERE id=?"
    );
    return $stmt->execute([$nome, $preco, $descricao, $categoria, $disponivel, $id]);
}

function deletarProduto($pdo, $id)
{
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
    return $stmt->execute([$id]);
}

// ============== CLIENTES ==============
function listarClientes($pdo)
{
    return $pdo->query("SELECT * FROM clientes ORDER BY criado_em DESC")->fetchAll();
}

function obterCliente($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function adicionarCliente($pdo, $nome, $email, $telefone, $endereco)
{
    $stmt = $pdo->prepare(
        "INSERT INTO clientes (nome, email, telefone, endereco) VALUES (?, ?, ?, ?)"
    );
    return $stmt->execute([$nome, $email, $telefone, $endereco]);
}

function atualizarCliente($pdo, $id, $nome, $email, $telefone, $endereco)
{
    $stmt = $pdo->prepare(
        "UPDATE clientes SET nome=?, email=?, telefone=?, endereco=? WHERE id=?"
    );
    return $stmt->execute([$nome, $email, $telefone, $endereco, $id]);
}

function deletarCliente($pdo, $id)
{
    $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = ?");
    return $stmt->execute([$id]);
}

// ============== PEDIDOS ==============
function listarPedidos($pdo)
{
    $sql = "SELECT p.*, c.nome AS cliente_nome, pr.nome AS produto_nome
            FROM pedidos p
            INNER JOIN clientes c ON c.id = p.cliente_id
            INNER JOIN produtos pr ON pr.id = p.produto_id
            ORDER BY p.criado_em DESC";
    return $pdo->query($sql)->fetchAll();
}

function obterPedido($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function adicionarPedido($pdo, $cliente_id, $produto_id, $quantidade, $status, $observacoes)
{
    // Busca preço atual do produto
    $stmtP = $pdo->prepare("SELECT preco FROM produtos WHERE id = ?");
    $stmtP->execute([$produto_id]);
    $produto = $stmtP->fetch();
    if (!$produto)
        return false;

    $preco_unitario = (float) $produto['preco'];
    $total = $preco_unitario * (int) $quantidade;

    $stmt = $pdo->prepare(
        "INSERT INTO pedidos
            (cliente_id, produto_id, quantidade, preco_unitario, total, status, observacoes)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    return $stmt->execute([$cliente_id, $produto_id, $quantidade, $preco_unitario, $total, $status, $observacoes]);
}

function atualizarPedido($pdo, $id, $cliente_id, $produto_id, $quantidade, $status, $observacoes)
{
    $stmtP = $pdo->prepare("SELECT preco FROM produtos WHERE id = ?");
    $stmtP->execute([$produto_id]);
    $produto = $stmtP->fetch();
    if (!$produto)
        return false;

    $preco_unitario = (float) $produto['preco'];
    $total = $preco_unitario * (int) $quantidade;

    $stmt = $pdo->prepare(
        "UPDATE pedidos
            SET cliente_id=?, produto_id=?, quantidade=?, preco_unitario=?, total=?, status=?, observacoes=?
          WHERE id=?"
    );
    return $stmt->execute([$cliente_id, $produto_id, $quantidade, $preco_unitario, $total, $status, $observacoes, $id]);
}

function deletarPedido($pdo, $id)
{
    $stmt = $pdo->prepare("DELETE FROM pedidos WHERE id = ?");
    return $stmt->execute([$id]);
}

// Dashboard
function estatisticas($pdo)
{
    return [
        'produtos' => (int) $pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn(),
        'clientes' => (int) $pdo->query("SELECT COUNT(*) FROM clientes")->fetchColumn(),
        'pedidos' => (int) $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn(),
        'receita' => (float) $pdo->query("SELECT COALESCE(SUM(total),0) FROM pedidos WHERE status <> 'Cancelado'")->fetchColumn(),
        'pendentes' => (int) $pdo->query("SELECT COUNT(*) FROM pedidos WHERE status='Pendente'")->fetchColumn(),
    ];
}