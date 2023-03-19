<?php
require_once('../db-connect.php');

// Definir codificação para exibir caracteres especiais corretamente
$conn->set_charset("utf8");

// Verificar se o valor de busca foi recebido
if (isset($_POST['campo'])) {
    // Escapar o valor de busca para evitar injeção de SQL
    $campo = $conn->real_escape_string($_POST['campo']);

    // Verificar se o valor de busca é "all"
    if ($campo == "all") {
        // Buscar todas as turmas
        $turmas = $conn->query("SELECT nome, id, tipo, sala, turno, carga_horaria, COUNT(*) AS total FROM turma GROUP BY nome ORDER BY nome");
    } else {
        // Buscar a lista de turmas que correspondem à busca
        $turmas = $conn->query("SELECT nome, id, tipo, sala, turno, carga_horaria, COUNT(*) AS total FROM turma WHERE nome LIKE '%$campo%' GROUP BY nome ORDER BY nome");
    }

    // Verificar se existem turmas correspondentes
    if ($turmas->num_rows > 0) {
        echo '<table>';
        echo '<tbody id="conteudo">';

        // Loop para exibir cada turma
        while ($turma = $turmas->fetch_assoc()) {
            echo "<tr>";
            echo '<td class="btn trigger" style="width: 25%; overflow: hidden;"data-turma-id="'.$turma['id'].'" id="turma-'.$turma['id'].'">' . $turma['nome'] . '</td>';
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
}
?>