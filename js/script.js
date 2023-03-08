var calendar;
var Calendar = FullCalendar.Calendar;
var events = [];
$(function () {
    if (!!scheds) {
        Object.keys(scheds).map(k => {
            var row = scheds[k]
            events.push({ id: row.id, title: row.ra_docente, start: row.horario_inicio, end: row.horario_fim });
        })
    }
    var date = new Date()
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear()

    calendar = new Calendar(document.getElementById('calendar'), {
        headerToolbar: {
            left: 'prev,next today',
            right: 'dayGridMonth,dayGridWeek,list',
            center: 'title',
            locale: 'pt-br'
        },
        locale: 'pt-br',
        navLinks: true, // can click day/week names to navigate views
        selectable: true, // inicio do evento de select de varias datas e ja preenchar os inputs da data inicio e fim
        editable: true, // inicio evento de fazer as edições
        droppable: true,  // inicio evento que pode arrastar datas 
        dayMaxEvents: true, // allow "more" link when too many events
        themeSystem: 'bootstrap',
        //Random default events
        events: events,


        // inicio evento de varias datas e ja preenchar os inputs da data inicio e fim e ja formatado para o banco reconhecer como string
        select: async (arg) => {
            // console.log(arg);
            var startDatetime = moment(arg.start).format('YYYY-MM-DDTHH:mm');
            var endDatetime = moment(arg.end).subtract(1, 'day').format('YYYY-MM-DDTHH:mm');  //Aqui foi alterado para que ele diminua um dia, pois por padrão ele seleciona mais um dia adicional, assim a função (.subtract(1, 'day') ) ela permite que deminua um dia para que fique certo quando selecionar as datas.
            $('#start_datetime').val(startDatetime);
            $('#end_datetime').val(endDatetime);


            // Preenche os inputs com as datas selecionadas
            $('#event-start').val(startDatetime);
            $('#event-end').val(endDatetime);

            // Exibe o popup
            $('#popup-container').show();
        },


        // Fim evento de varias datas e ja preenchar os inputs da data inicio e fim e ja formatado para o banco reconhecer como string

        // inicio da função que vai faz que o banco ja conserta para string e va para o arquivo que faz o update para o banco de arrastar 
        eventDrop: function (info) {
            resizeAndDrop(info);
        },
        eventResize: function (info) {
            resizeAndDrop(info);
        },


        eventClick: function (info) {
            // Carregue o idioma português para o moment.js
            moment.locale('pt-br');
            var _details = $('#event-details-modal')
            var id = info.event.id

            if (!!scheds[id]) {
                _details.find('#title').text(scheds[id].ra_docente)
                _details.find('#description').text(scheds[id].id_uc)

                // Formate as datas usando o moment.js
                var startDate = moment(scheds[id].sdate).format('LLL');
                var endDate = moment(scheds[id].edate).format('LLL');

                _details.find('#start').text(startDate)
                _details.find('#end').text(endDate)
                _details.find('#edit,#delete').attr('data-id', id)
                _details.modal('show')
            } else {
                alert("Event is undefined");
            }
        },
        eventDidMount: function (info) {
            // Do Something after events mounted
        },

        dateClick: function (info) {
            // evento que select de uma data e ja preenchar os inputs da data inicio e fim
            var clickedDate = moment(info.dateStr).format('YYYY-MM-DDTHH:mm');
            $('#start_datetime').val(clickedDate);
            $('#end_datetime').val(clickedDate);
            // ...
        }
    });


    calendar.render();



    // teste de api de feriados
    getFeriados().then(function (feriados) {
        $.each(feriados, function (index, feriado) {
            // cria um objeto de evento para cada feriado
            var event = {
                title: feriado.nome,
                start: feriado.data,
                allDay: true,
                backgroundColor: '#f00',// fundo vermelho
                textColor: '#fff' // texto em branco
            };
            // adiciona o objeto de evento à lista de eventos do calendário
            calendar.addEvent(event);
        });
    });

    // Aqui ele acha a api de feriados
    function getFeriados() {
        return $.ajax({
            url: 'http://localhost/schedule/api_feriado.php',
            dataType: 'json',
            success: function (response) {
                return response;
            }
        });
    }
    // teste de api de feriados

    // teste  de impeca de criar aula em feriados:
    $(document).ready(function () {
        // Adicionar ouvinte de evento ao botão salvar
        $('#save-btn').click(function () {
            var start_date = new Date($('#start_datetime').val());
            var year = start_date.getFullYear();
            var month = start_date.getMonth() + 1;
            var day = start_date.getDate();
            var is_holiday = getFeriados(year, month, day);

            if (is_holiday) {
                $('#feriado-modal').modal('show');
            } else {
                $('#schedule-form').submit(); // Envia o formulário
            }
            $('#feriado-modal .close').click(function () { // ele faz que quando cancelar ele volta para a tela de editar
                $('#feriado-modal').modal('hide');
            });
            $('#feriado-modal .close, #feriado-modal .modal-footer button').click(function () {
                $('#feriado-modal').modal('hide');
            });


        });

        //Função que retorna true se a data for feriado
        function getFeriados(year, month, day) {
            var holidays = {
                '01-01': 'Ano Novo',
                '02-20': 'Carnaval',
                '02-21': 'Carnaval',
                '02-22': 'Carnaval',
                '04-21': 'Tiradentes',
                '05-01': 'Dia do Trabalho',
                '09-07': 'Independência do Brasil',
                '10-12': 'Nossa Senhora Aparecida',
                '11-02': 'Finados',
                '11-15': 'Proclamação da República',
                '12-25': 'Natal'
            };

            var holiday = false;
            var date_string = year + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;

            for (var d in holidays) {
                var holiday_date = year + '-' + d;
                if (holiday_date === date_string) {
                    holiday = true;
                    break;
                }
            }

            return holiday;
        }
    });


    //teste de fazer um popup apareca e depois some e da um reset na pagina de deletar
    $('#delete').click(function () {
        var id = $(this).attr('data-id');
        if (!!scheds[id]) {
            $('#event-details-modal').modal('hide');
            $('#delete-modal').modal('show');
            $('#confirm-delete').click(function () {
                location.href = "./delete_schedule.php?id=" + id;
            });
            $('#delete-modal').on('hidden.bs.modal', function (e) {
                $('#event-details-modal').modal('show');
            });
            $('#delete-modal').on('shown.bs.modal', function (e) { // ele faz que quando cancelar ele volta para a tela de editar
                $('#confirm-delete').focus();
            });
            $('#delete-modal .close').click(function () { // ele faz que quando cancelar ele volta para a tela de editar
                $('#delete-modal').modal('hide');
            });
            $('#delete-modal .btn-secondary').click(function () { // ele faz que quando cancelar ele volta para a tela de editar
                $('#delete-modal').modal('hide');
            });
        } else {
            alert("Evento não definido");
        }
    });



    //  teste de  justificativa

// Obtém o formulário e adiciona o evento "submit"
const eventForm = document.getElementById("event-form");
eventForm.addEventListener("submit", (event) => {
  event.preventDefault(); // Impede que a página seja recarregada

  // Obtém os dados do formulário
  const formData = new FormData(event.target);

  // Verifica se os campos obrigatórios foram preenchidos
  if (!eventForm.checkValidity()) {
    // Exibe mensagem de erro
    console.error("Erro ao salvar evento! Campos obrigatórios não preenchidos.");
    return;
  }

  // Realiza a requisição AJAX utilizando o método POST
  fetch("./save_feriado.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      // Verifica se a resposta da requisição foi bem sucedida
      if (response.ok) {
        // Exibe mensagem de sucesso
        console.log("Evento salvo com sucesso!");
        // Reseta o formulário
        eventForm.reset();
        // Fecha o popup
        closePopup();
        // Reseta a página
        window.location.reload();
      } else {
        // Exibe mensagem de erro
        console.error("Erro ao salvar evento!");
      }
    })
    .catch((error) => {
      console.error(error);
    });
});

// Obtém o elemento input da data de início
const startInput = document.getElementById("event-start");
startInput.addEventListener("change", () => {
  // Obtém o valor da data selecionada
  const startDate = startInput.value;
  // Preenche os inputs de data com o valor da data selecionada
  document.getElementById("event-start").value = startDate;
  document.getElementById("event-end").value = startDate;
});

// Mostra o popup ao clicar no botão "Adicionar Evento"
$("#add-event").click(function () {
  // exibe o conteúdo do pop-up
  $("#popup-container").show();
});
// aqui o usuario não queira fazer mais feriados e cancela e ja sai da pagina
$("#cancel-event").click(function () {
  // esconde o conteúdo do pop-up
  $("#popup-container").hide();
});
// aqui quando o usuario clickar ele envia o formulario e ja da o reset
$("#save-event").click(function () {
  // Dispara o evento "submit" do formulário
  eventForm.dispatchEvent(new Event("submit"));
});

// fazer a correção de salvar e não resetar a pagina 


    // $('#schedule-form').on('submit', function(e) {
    //     e.preventDefault();


    //     // faz o envio do formulário via AJAX
    //     $.ajax({
    //       url: './save_schedule.php',
    //       method: 'POST',
    //       data: $(this).serialize(),
    //       success: function() {
    //         // exibe a mensagem de sucesso e faz ela desaparecer após 5 segundos
    //         $('#success-alert').show().fadeOut(5000, function() {
    //           // recarrega a página após a mensagem desaparecer
    //           location.reload();
    //         });

    //         // limpa o formulário
    //         $('#schedule-form').trigger('reset');
    //       }
    //     });
    //   });

    // teste de não aparecer o poup de agenda de eventos para colocar um feriado somente para o senac



    // Form reset listener
    $('#schedule-form').on('reset', function () {
        $(this).find('input:hidden').val('')
        $(this).find('input:visible').first().focus()
    })

    // Edit Button
    $('#edit').click(function () {
        var id = $(this).attr('data-id')
        if (!!scheds[id]) {
            var _form = $('#schedule-form')
            console.log(String(scheds[id].horario_inicio), String(scheds[id].horario_inicio).replace(" ", "\\t"))
            _form.find('[name="id"]').val(id)
            _form.find('[name="title"]').val(scheds[id].ra_docente)
            _form.find('[name="description"]').val(scheds[id].id_uc)
            _form.find('[name="start_datetime"]').val(String(scheds[id].horario_inicio).replace(" ", "T"))
            _form.find('[name="end_datetime"]').val(String(scheds[id].horario_fim).replace(" ", "T"))
            $('#event-details-modal').modal('hide')
            _form.find('[name="title"]').focus()
        } else {
            alert("O evento é indefinido");
        }
    })

    // Delete Button / Deleting an Event
    // $('#delete').click(function () {
    //     var id = $(this).attr('data-id')
    //     if (!!scheds[id]) {
    //         var _conf = confirm("Tem certeza de que deseja excluir este evento agendado?");
    //         if (_conf === true) {
    //             location.href = "./delete_schedule.php?id=" + id;
    //         }
    //     } else {
    //         alert("Event is undefined");
    //     }
    // })


    //Arraste e redimensionamento de eventos
    // função que faz a converção de mês, data e minutos para string, para o banco reconhcer 
    async function resizeAndDrop(info) {
        let newDate = new Date(info.event.start);
        let month = ((newDate.getMonth() + 1) < 9) ? "0" + (newDate.getMonth() + 1) : newDate.getMonth() + 1;
        let day = ((newDate.getDate()) < 9) ? "0" + newDate.getDate() : newDate.getDate();
        let minutes = ((newDate.getMinutes()) < 9) ? "0" + newDate.getMinutes() : newDate.getMinutes();
        newDate = `${newDate.getFullYear()}-${month}-${day} ${newDate.getHours()}:${minutes}:00`

        let newDateEnd = new Date(info.event.end);
        let monthEnd = ((newDateEnd.getMonth() + 1) < 9) ? "0" + (newDateEnd.getMonth() + 1) : newDateEnd.getMonth() + 1;
        let dayEnd = ((newDateEnd.getDate()) < 9) ? "0" + newDateEnd.getDate() : newDateEnd.getDate();
        let minutesEnd = ((newDateEnd.getMinutes()) < 9) ? "0" + newDateEnd.getMinutes() : newDateEnd.getMinutes();
        newDateEnd = `${newDateEnd.getFullYear()}-${monthEnd}-${dayEnd} ${newDateEnd.getHours()}:${minutesEnd}:00`


        let reqs = await fetch('http://localhost/schedule/ControllerDrop.php', {    // função o envio de conversão para o update no banco, ( mudar o caminho da url para que assim ele reconheça o caminho do arquivo EX: http://localhost/nome-que-esta-na-url/ControllerDrop.php' ) 
            method: 'post',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${info.event.id}&start=${newDate}&end=${newDateEnd}`
        });
        let ress = await reqs.json();
    }

})