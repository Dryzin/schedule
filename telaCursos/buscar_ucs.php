<?php
require_once('../db-connect.php');

// Definir codificação para exibir caracteres especiais corretamente
$conn->set_charset("utf8");

// Verificar se a variável $_GET['turma_id'] foi definida
if (isset($_GET['turma_id'])) {
  // Recuperar o ID da turma da qual se deseja obter as UCs cadastradas
  $turma_id = $_GET['turma_id'];

  // Buscar o nome da turma correspondente
  $turma_result = $conn->query("SELECT nome FROM turma WHERE id='".$turma_id."'");
  $turma = $turma_result->fetch_assoc();



  // Buscar a lista de UCs associadas a essa turma
  $ucs_turma = $conn->query("SELECT nome_uc FROM uc WHERE num_turma='".$turma_id."' ORDER BY nome_uc ASC");

  // Verificar se existem UCs correspondentes
  if ($ucs_turma->num_rows > 0) {
    // Loop para exibir cada UC associada a essa turma
    while ($uc_turma = $ucs_turma->fetch_assoc()) {
      echo "<p>".$uc_turma['nome_uc']."</p>";
    }
  } else {
    // Caso não existam UCs correspondentes, exibir mensagem
    echo "<p>Nenhuma UC cadastrada para esta turma.</p>";
  }
} else {
  // Caso não tenha sido definido o ID da turma, exibir mensagem
  echo "<p>Selecione uma turma para exibir suas UCs cadastradas.</p>";
}

    ?>





