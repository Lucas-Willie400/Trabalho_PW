<?php
// Se a sessão ainda não foi iniciada em lugar nenhum, inicia aqui
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se a chave 'user_email' NÃO existe na sessão
if (!isset($_SESSION['user_email'])) {
    // Se não existir, manda de volta para o login
    header("Location: login.php");
    exit();
}
?>