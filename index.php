<?php require_once('db-connect.php') ?>
<!DOCTYPE html>
<html lang="pt-br">
<!-- Teste commit brash -->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduling</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="fullcalendar/lib/main.min.css">

    <!-- teste de fazer um popup apareca e depois some e da um reset na pagina de salvar e deletar-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" integrity="sha512-CtCzRfZlMEvJzFpKkAl97SlNf1ysh3/nqK/O47XQ2NlA/h8zv+QlLx0cZdHw78W8evv+E0g0Xr85tHrT0Z8RvA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js" integrity="sha512-Gi7RveP32a9p7VU1OWaqcWfZiFGmpn4n4+hGKnIMHmAT8yvy/KPlm9mSdFzsB6ZcJdjmnFca0If0I4d4wSMkCg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- teste de fazer um popup apareca e depois some e da um reset na pagina de salvar e deletar-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script> <!-- Script para que os inputs quando selecionados ja preencherem -->
    <script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>

    <!-- teste de popup de nao pode modificar um feriado para adicionar uma aula -->



    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="fullcalendar/lib/main.min.js"></script>
    <script src='js/pt-br.js'></script>

    <style>
        :root {
            --bs-success-rgb: 71, 222, 152 !important;
        }

        html,
        body {
            height: 100%;
            width: 100%;
            font-family: roboto;
        }

        .btn-info.text-light:hover,
        .btn-info.text-light:focus {
            background: #000;
        }

        table,
        tbody,
        td,
        tfoot,
        th,
        thead,
        tr {
            border-color: #ededed !important;
            border-style: solid;
            border-width: 1px !important;
        }

        ul li {
            list-style: none;
            position: inline-block;
            float: left;
            color: #fff;
            padding: 0 0 0 30px;
        }
    </style>
</head>


<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-gradient" id="topNavBar">
        <div class="container">
            <a class="navbar-brand" href="https://mg.senac.br">
                Senac
            </a>

            <div>
                <ul>
                    <li>Home</li>
                    <li>Aulas</li>
                    <li>Docente</li>
                    <li>Cursos</li>
                </ul>
            </div>
        </div>
    </nav>

    </table>

    <div class="container py-5" id="page-container">


        <!-- teste de fazer um popup apareca e depois some e da um reset na pagina -->


        <!-- <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
            Agendamento salvo com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div> -->

        <!-- teste de poup de agendar evento ou feriado do proprio senac -->

        <div id="popup-container">
            <div id="popup-content">
                <h2 id="popup-title">Agendar Evento</h2>
                <form id="event-form">
                    <label for="event-title">Título:</label>
                    <input type="text" id="event-title" name="titulo" required><br>

                    <label for="event-description">Descrição:</label>
                    <input type="text" id="event-description" name="descricao" required><br>

                    <label for="event-start">Horário de início:</label>
                    <input type="datetime-local" id="event-start" name="horario_inicio" onchange="verificarFeriado()" required><br>

                    <label for="event-end">Horário de término:</label>
                    <input type="datetime-local" id="event-end" name="horario_fim" onchange="verificarFeriado()" required><br>


                    <button type="submit" id="save-event">Salvar</button>
                    <button type="button" id="cancel-event">Cancelar</button>
                </form>
            </div>
        </div>

        <script>
            function verificarFeriado() {
                // Lista de feriados
                var holidays = [];

                // Loop para percorrer os anos desejados
                for (var year = 2023; year <= 2030; year++) {
                    // Ano novo
                    holidays.push(moment(year + '-01-01').format('YYYY-MM-DD'));

                    // Carnaval
                    holidays.push(moment(year + '-02-20').format('YYYY-MM-DD'));
                    holidays.push(moment(year + '-02-21').format('YYYY-MM-DD'));
                    holidays.push(moment(year + '-02-22').format('YYYY-MM-DD'));
                    // Tiradentes
                    holidays.push(moment(year + '-04-21').format('YYYY-MM-DD'));

                    // Dia do Trabalho
                    holidays.push(moment(year + '-05-01').format('YYYY-MM-DD'));

                    // Independência do Brasil
                    holidays.push(moment(year + '-09-07').format('YYYY-MM-DD'));

                    // Nossa Senhora Aparecida
                    holidays.push(moment(year + '-10-12').format('YYYY-MM-DD'));

                    // Finados
                    holidays.push(moment(year + '-11-02').format('YYYY-MM-DD'));

                    // Proclamação da República
                    holidays.push(moment(year + '-11-15').format('YYYY-MM-DD'));

                    // Natal
                    holidays.push(moment(year + '-12-25').format('YYYY-MM-DD'));
                }

                // Verifica se a data selecionada é um feriado
                if (holidays.includes(moment($('#event-start').val()).format('YYYY-MM-DD'))) {
                    // Exibe mensagem de erro
                    alert('Não é possível criar uma aula em um feriado.');
                    $('#event-start').val('');
                    return;
                }
                if (holidays.includes(moment($('#event-end').val()).format('YYYY-MM-DD'))) {
                    // Exibe mensagem de erro
                    alert('Não é possível criar uma aula em um feriado.');
                    $('#event-end').val('');
                    return;
                }

            }
            
        </script>



        <style>
            #popup-container {
                display: none;
                position: fixed;
                z-index: 9999;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.5);
            }

            #popup-content {
                background-color: #fefefe;
                margin: 10% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 20%;
            }



            #popup-title {
                text-align: center;
            }

            form {
                display: flex;
                flex-direction: column;
            }

            label {
                margin-top: 10px;
            }

            input[type="text"],
            input[type="datetime-local"] {
                margin-bottom: 20px;
            }

            #save-event,
            #cancel-event {
                margin-top: 20px;
                width: 100%;
            }
        </style>
        <!-- teste de poup de agendar evento ou feriado do proprio senac -->


        <!-- teste de fazer um popup apareca e teste  de impeca de criar aula em feriados-->
        <div class="modal" id="feriado-modal" tabindex="-1" role="dialog" aria-labelledby="feriado-modal-title">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="feriado-modal-title">Não é possível criar uma aula em um feriado.</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Por favor, selecione outra data para criar a aula.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                    </div>
                </div>

            </div>
        </div>

        <!-- teste de fazer um popup apareca e teste  de impeca de criar aula em feriados -->

        <!-- teste de fazer um popup apareca e depois some e da um reset na pagina -->
        <div id="success-modal" class="modal fade" tabindex="-1" aria-labelledby="success-modal-label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="success-modal-label">Agendamento salvo com sucesso!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Pagina sera resetada!
                    </div>
                </div>
            </div>
        </div>
        <!-- teste de fazer um popup apareca e depois some e da um reset na pagina -->

        <!-- teste de fazer um popup de delete -->
        <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered d-flex justify-content-center" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="delete-modal-label">Deletar evento agendado</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza de que deseja excluir este evento agendado?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="confirm-delete">Excluir</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- teste de fazer um popup de delete -->


        <div class="row">
            <div class="col-md-9">
                <div id="calendar"></div>
            </div>
            <div class="col-md-3">
                <div class="cardt rounded-0 shadow">
                    <div class="card-header bg-gradient bg-primary text-light">
                        <h5 class="card-title">Criar Aula</h5>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">
                            <form action="save_schedule.php" method="post" id="schedule-form">
                                <input type="hidden" name="id" value="">
                                <div class="form-group mb-2">
                                    <label for="title" class="control-label">RA</label>
                                    <input type="text" class="form-control form-control-sm rounded-0" name="title" id="title" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="description" class="control-label">ID (Unidade)</label>
                                    <textarea rows="3" class="form-control form-control-sm rounded-0" name="description" id="description" required></textarea>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="start_datetime" class="control-label">Começo</label>
                                    <input type="datetime-local" class="form-control form-control-sm rounded-0" name="start_datetime" id="start_datetime"  onchange="verificarFeriado()"required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="end_datetime" class="control-label">Fim</label>
                                    <input type="datetime-local" class="form-control form-control-sm rounded-0" name="end_datetime" id="end_datetime" onchange="verificarFeriado()" required>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-center">
                            <button class="btn btn-primary btn-sm rounded-0" type="button" id="save-btn"><i class="fa fa-save"></i> Salvar</button>

                            <button class="btn btn-default border btn-sm rounded-0" type="reset" form="schedule-form"><i class="fa fa-reset"></i> Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        <button type="button" class="btn btn-primary btn-sm rounded-0" id="edit" data-id="">Editar</button>
                        <button type="button" class="btn btn-danger btn-sm rounded-0" id="delete" data-id="">Deletar</button>
                        <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Event Details Modal -->

    <?php
    // Lógica PHP para obter os dados de várias tabelas
    $locale = 'pt-br';
    $schedules1 = $conn->query("SELECT * FROM `calendario_de_aula`");
    $schedules2 = $conn->query("SELECT * FROM `feriado`"); // Adicione quantas tabelas desejar
    $sched_res = [];
    while ($row = $schedules1->fetch_assoc()) {
        $row['sdate'] = date("F d, Y h:i A", strtotime($row['horario_inicio']));
        $row['edate'] = date("F d, Y h:i A", strtotime($row['horario_fim']));
        $row['tipo'] = 'Aula'; // Indica que este evento é uma aula
        $sched_res[] = $row;
    }
    while ($row = $schedules2->fetch_assoc()) {
        $row['sdate'] = date("F d, Y h:i A", strtotime($row['horario_inicio']));
        $row['edate'] = date("F d, Y h:i A", strtotime($row['horario_fim']));
        $row['title'] = $row['titulo']; // Título do evento
        $row['description'] = $row['descricao']; // Descrição do evento
        $row['tipo'] = 'feriado'; // Indica que este evento é de outro tipo
        $sched_res[] = $row;
    }
    usort($sched_res, function ($a, $b) {
        return strtotime($a['sdate']) - strtotime($b['sdate']);
    }); // Ordena os eventos por data/hora de início
    if (isset($conn)) $conn->close();
    ?>

</body>
<script>
    var scheds = $.parseJSON('<?= json_encode($sched_res) ?>')
</script>
<script src="./js/script.js"></script>
<script src="js/poup.js"></script>

</html>