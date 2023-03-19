<?php
require_once('../db-connect.php');

// Definir codificação para exibir caracteres especiais corretamente
$conn->set_charset("utf8");

// Buscar a lista de turmas
$turmas = $conn->query("SELECT nome, id, tipo, sala, turno, carga_horaria, COUNT(*) AS total FROM turma GROUP BY nome ORDER BY nome");

// Verificar se existem turmas correspondentes
if ($turmas->num_rows > 0) {
  echo '<table>';
  echo '<tbody id="conteudo">';

  // Loop para exibir cada turma
  while ($turma = $turmas->fetch_assoc()) {
      echo "<tr>";
      echo '<td class="btn trigger" style="width: 25%; overflow: hidden;" data-turma-id="'.$turma['id'].'" data-nome="' . $turma['nome'] . '" data-tipo="' . $turma['tipo'] . '" data-id="' . $turma['id'] . '" data-turno="' . $turma['turno'] . '" data-sala="' . $turma['sala'] . '">' . $turma['nome'] . '</td>';
      echo '<td style="width: 25%; overflow: hidden;">' . $turma['id'] . '</td>';
      echo '<td style="width: 25%; overflow: hidden;">' . $turma['tipo'] . '</td>';
      echo '<td style="width: 25%; overflow: hidden;">' . $turma['sala'] . '</td>';
      echo "</tr>";
    }
  echo '</tbody>';
  echo '</table>';
} else {
  echo '<p>Não foram encontradas turmas com esse nome.</p>';
}

// Fechar a conexão com o banco de dados
$conn->close();
?>