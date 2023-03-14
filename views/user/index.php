<?php require_once('../../db-connect.php') ?>

<!DOCTYPE html>
<html lang="pt-br">
<!-- Teste commit brash -->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduling</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../fullcalendar/lib/main.min.css">
    <link rel="stylesheet" href="../../css/test.css">


    <!-- teste de fazer um popup apareca e depois some e da um reset na pagina de salvar e deletar-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" integrity="sha512-CtCzRfZlMEvJzFpKkAl97SlNf1ysh3/nqK/O47XQ2NlA/h8zv+QlLx0cZdHw78W8evv+E0g0Xr85tHrT0Z8RvA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js" integrity="sha512-Gi7RveP32a9p7VU1OWaqcWfZiFGmpn4n4+hGKnIMHmAT8yvy/KPlm9mSdFzsB6ZcJdjmnFca0If0I4d4wSMkCg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- teste de fazer um popup apareca e depois some e da um reset na pagina de salvar e deletar-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script> <!-- Script para que os inputs quando selecionados ja preencherem -->
    <script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>

    <!-- teste de popup de nao pode modificar um feriadoz para adicionar uma aula -->
    <script src="../../js/jquery-3.6.0.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../fullcalendar/lib/main.min.js"></script>
    <script src='../../js/pt-br.js'></script>

   
</head>


<body class="bg-light">


    </table>

    <div class="container py-3" id="page-container">




        <!-- teste de fazer um popup de delete -->



        <div class="row">
            <div class="col-md-9">
                <div id="calendar"></div>


    <!-- Event Details Modal -->
    <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">
                <div class="modal-header rounded-0">
                    <h5 class="modal-title">Detalhes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body rounded-0">
                    <div class="container-fluid">
                        <dl>
                            <dt class="text-muted">RA</dt>
                            <dd id="title" class="fw-bold fs-4"></dd>
                            <dt class="text-muted"> ID (Unidade) </dt>
                            <dd id="description" class=""></dd>
                            <dt class="text-muted">Começo</dt>
                            <dd id="start" class=""></dd>
                            <dt class="text-muted">Fim</dt>
                            <dd id="end" class=""></dd>
                        </dl>
                    </div>
                </div>
                <div class="modal-footer rounded-0">
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


      <!-- Aqui ele exibe os detalhes dos feriados -->
      <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal-feriado">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0">
                <h5 class="modal-title">Detalhes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body rounded-0">
                <div class="container-fluid">
                    <dl>
                        <dt class="text-muted">Título</dt>
                        <dd id="event-title" class="fw-bold fs-4"></dd>
                        <dt class="text-muted">Descrição</dt>
                        <dd id="event-description" class=""></dd>
                        <dt class="text-muted">Começo</dt>
                        <dd id="event-start" class=""></dd>
                        <dt class="text-muted">Fim</dt>
                        <dd id="event-end" class=""></dd>
                    </dl>
                </div>
            </div>
            <div class="modal-footer rounded-0">
                <div class="text-end">
                    <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

          
</div>
    </div>          
        </div>

    <!-- Event Details Modal -->

    <?php

locale:
'pt-br';

$schedules = $conn->query("SELECT id, ra_docente, id_uc, horario_inicio, horario_fim, 'aula' as tipo FROM `calendario_de_aula`
                          UNION ALL
                          SELECT id, titulo, descricao, horario_inicio, horario_fim, 'feriado' as tipo FROM `feriado`");

$sched_res = [];
foreach ($schedules->fetch_all(MYSQLI_ASSOC) as $row) {  
    $row['sdate'] = date("F d, Y h:i A", strtotime($row['horario_inicio']));
    $row['edate'] = date("F d, Y h:i A", strtotime($row['horario_fim']));
    $sched_res[$row['id']] = $row;
}

if (isset($conn)) $conn->close();
?>

</body>
<script>
var scheds = $.parseJSON('<?= json_encode($sched_res) ?>');
</script>

<script src="../../js/script.js"></script>

</html>