<?php
  require_once('../db-connect.php');

  // Definir codificação para exibir caracteres especiais corretamente
  $conn->set_charset("utf8");

// Buscar a lista de usuários e turmas
$resultados = $conn->query("SELECT usuario .ra_user, usuario .nome, usuario .email, turma.nome AS turma_nome, turma.tipo, turma.sala, turma.turno, turma.carga_horaria 
                            FROM usuario  
                            JOIN turma  ON usuario.nome = turma.nome 
                            ORDER BY usuario.nome");

// Verificar se existem resultados
if ($resultados->num_rows > 0) {
    echo '<table>';
    echo '<tbody id="conteudo">';
  
    // Loop para exibir cada resultado
    while ($row = $resultados->fetch_assoc()) {
      echo "<tr>";
      echo '<td style="width: 20%; overflow: hidden;">' . $row['nome'] . '</td>';
      echo '<td style="width: 20%; overflow: hidden;">' . $row['ra_user'] . '</td>';
      echo '<td style="width: 20%; overflow: hidden;">' . $row['email'] . '</td>';
      echo '<td style="width: 20%; overflow: hidden;">' . $row['turma_nome'] . '</td>';
      echo '<td style="width: 10%; overflow: hidden;">' . $row['tipo'] . '</td>';
      echo '<td style="width: 10%; overflow: hidden;">' . $row['sala'] . '</td>';
      echo '<td style="width: 10%; overflow: hidden;">' . $row['turno'] . '</td>';
      echo '<td style="width: 10%; overflow: hidden;">' . $row['carga_horaria'] . '</td>';
      echo "</tr>";
    }
    echo '</tbody>';
    echo '</table>';
  } else {
    echo "Nenhum resultado encontrado.";
  }

  // Fechar a conexão com o banco de dados
  $conn->close();
?>