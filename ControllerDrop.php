<?php
require_once('db-connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    // Formata a data e hora no formato desejado (ex: Y-m-d H:i:s)
    $start_datetime = date('Y-m-d H:i:s', strtotime($start));
    $end_datetime = date('Y-m-d H:i:s', strtotime($end));

    // Atualiza o evento no banco de dados
    $query = "UPDATE calendario_de_aula SET horario_inicio='$start_datetime', horario_fim='$end_datetime' WHERE id=$id";
    mysqli_query($conn, $query);
    mysqli_close($conn);
}
