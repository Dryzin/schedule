<?php
require_once('db-connect.php');

// Busca todos os usuários
$users = $conn->query("SELECT ra_user, nome FROM usuario");

// Monta as opções do select
$options = "";
while ($user = $users->fetch_assoc()) {
    $options .= "<option value='{$user['ra_user']}' data-nome='{$user['nome']}'>{$user['ra_user']}</option>";
}

// Retorna as opções
echo $options;

$conn->close();
?>
