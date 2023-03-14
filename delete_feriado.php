<?php 
require_once('db-connect.php');
if(!isset($_GET['id'])){
    echo "<script> alert('ID de agendamento indefinido.'); location.replace('./') </script>";
    $conn->close();
    exit;
}

$delete1 = $conn->query("DELETE FROM `feriado` where id = '{$_GET['id']}'");
// if($delete){
//     echo "<script> alert('O evento foi excluído com sucesso.'); location.replace('./') </script>";
// }else{
//     echo "<pre>";
//     echo "An Error occured.<br>";
//     echo "Error: ".$conn->error."<br>";
//     echo "SQL: ".$sql."<br>";
//     echo "</pre>";
// }


$conn->close();
header("Location: http://localhost/schedule/views/adm/"); // substitua a barra com a URL da sua página inicial
exit();
?>