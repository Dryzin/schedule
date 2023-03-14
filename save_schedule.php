<?php 
require_once('db-connect.php');
if($_SERVER['REQUEST_METHOD'] !='POST'){
    echo "<script> alert('Error: No data to save.'); location.replace('./') </script>";
    $conn->close();
    exit;
}
extract($_POST);
$allday = isset($allday);

if(empty($id)){
    $sql = "INSERT INTO `calendario_de_aula` (`ra_docente`,`id_uc`,`horario_inicio`,`horario_fim`) VALUES ('$title','$description','$start_datetime','$end_datetime')";
}else{
    $sql = "UPDATE `calendario_de_aula` set `ra_docente` = '{$title}', `id_uc` = '{$description}', `horario_inicio` = '{$start_datetime}', `horario_fim` = '{$end_datetime}' where `id` = '{$id}'";
}
$save = $conn->query($sql); //teste de poupa
// if ($save) {
//     // exibe a mensagem de sucesso em um popup
//     echo "<div id='success-popup' class='popup'>
//             <div class='popup-message'>Cadastro realizado com sucesso.</div>
//           </div>";
//     echo "<script>
//             // fecha o popup após 3 segundos
//             setTimeout(function() {
//               var popup = document.getElementById('success-popup');
//               popup.style.display = 'none';
//             }, 3000);
  
//             // redireciona para a página inicial após o popup ser fechado
//             setTimeout(function() {
//               window.location.href = 'index.php';
//             }, 3500);
//           </script>";
//   } else {
//     // exibe a mensagem de erro em um popup
//     echo "<div id='error-popup' class='popup'>
//             <div class='popup-message'>Erro ao realizar cadastro.</div>
//           </div>";
//     echo "<script>
//             // fecha o popup após 3 segundos
//             setTimeout(function() {
//               var popup = document.getElementById('error-popup');
//               popup.style.display = 'none';
//             }, 3000);
  
//             // recarrega a página atual após o popup ser fechado
//             setTimeout(function() {
//               window.location.reload();
//             }, 3500);
//           </script>";
//   }
  

$conn->close();

// // redireciona o usuário para a página inicial
header("Location:http://localhost/schedule/views/adm/"); // substitua a barra com a URL da sua página inicial
exit();

?>