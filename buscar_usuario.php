<?php
require_once('db-connect.php');

// Buscar a lista de usuários do tipo "docente"
$usuarios = $conn->query("SELECT nome, ra_user FROM `usuario` WHERE tipo='docente'");



if ($usuarios->num_rows > 0) {
    // Criar um array associativo contendo os dados dos usuários
    $data = array();
    while ($row = $usuarios->fetch_assoc()) {
        $data[] = array('nome' => $row['nome'], 'ra_user' => $row['ra_user']);
    }
    
    // Retornar a resposta JSON
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    echo "Nenhum usuário do tipo 'docente' encontrado.";
}

$conn->close();
?>
