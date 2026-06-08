CREATE DATABASE IF NOT EXISTS confeitaria_doce_sonho
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE confeitaria_doce_sonho;

-- Tabela 1: Produtos
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    descricao TEXT,
    categoria VARCHAR(50) DEFAULT 'Geral',
    disponivel TINYINT(1) DEFAULT 1,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela 2: Clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(30),
    endereco TEXT,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela 3: Pedidos
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 1,
    preco_unitario DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('Pendente','Em Preparo','Pronto','Entregue','Cancelado') DEFAULT 'Pendente',
    observacoes TEXT,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Dados de exemplo (opcional)
INSERT INTO produtos (nome, preco, descricao, categoria) VALUES
('Bolo de Chocolate', 65.00, 'Bolo recheado com brigadeiro e cobertura cremosa', 'Bolos'),
('Torta de Morango', 55.00, 'Massa amanteigada com creme e morangos frescos', 'Tortas'),
('Macaron Sortido', 4.50, 'Macarons franceses em diversos sabores', 'Doces');

INSERT INTO clientes (nome, email, telefone, endereco) VALUES
('Maria Silva', 'maria@email.com', '(11) 99999-1234', 'Rua das Flores, 100');

