<?php 
try {
    require_once('../db-connect.php');
    if($_SERVER['REQUEST_METHOD'] !='POST'){
        throw new Exception('Error: No data to save.');
    }
    extract($_POST);
    $allday = isset($allday);

    if(empty($id)){
        $sql = "INSERT INTO `calendario_de_aula` (`ra_docente`,`id_uc`,`horario_inicio`,`horario_fim`) VALUES ('$title','$description','$start_datetime','$end_datetime')";
    }else{
        $sql = "UPDATE `calendario_de_aula` set `ra_docente` = '{$title}', `id_uc` = '{$description}', `horario_inicio` = '{$start_datetime}', `horario_fim` = '{$end_datetime}' where `id` = '{$id}'";
    }

    $save = $conn->query($sql);
    if($save){
        echo "<script> alert('Schedule Successfully Saved.'); location.replace('./') </script>";
    }else{
        throw new Exception("An error occurred while saving data to the database.<br>Error: ".$conn->error."<br>SQL: ".$sql."<br>");
    }
    $conn->close();
} catch (Exception $e) {
    echo "<pre>";
    echo "An Error occurred.<br>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "</pre>";
    $conn->close();
}

$conn->close();
header("Location: http://localhost/schedule/views/adm/"); // substitua a barra com a URL da sua página inicial
exit();
?>
