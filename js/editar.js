$('#schedule-form').on('reset', function () {
    $(this).find('input:hidden').val('')
    $(this).find('input:visible').first().focus()
})

// Editar botão de criar aula
$(document).ready(function () {

    $('#edit').click(function () {
        var id = $(this).attr('data-id')
        if (!!scheds[id]) {
            var _form = $('#schedule-form')
            console.log(String(scheds[id].horario_inicio), String(scheds[id].horario_inicio).replace(" ", "\\t"))
            _form.find('[name="id"]').val(id)
            _form.find('[name="title"]').val(scheds[id].ra_docente) //altera para usuario_id
            _form.find('[name="description"]').val(scheds[id].id_uc)
            _form.find('[name="start_datetime"]').val(String(scheds[id].horario_inicio).replace(" ", "T"))
            _form.find('[name="end_datetime"]').val(String(scheds[id].horario_fim).replace(" ", "T"))
            $('#event-details-modal').modal('hide')
            _form.find('[name="title"]').focus()
        } else {
            alert("Event is undefined");
        }
    })
});


// Editar botão de feriado
$(document).ready(function () {
    $('#edit-evento').click(function () {
        var id = $(this).attr('data-id')
        if (!!scheds[id]) {
            var _form = $('#schedule-form')
            console.log(String(scheds[id].horario_inicio), String(scheds[id].horario_inicio).replace(" ", "\t"))
            _form.find('[name="id"]').val(id)
            _form.find('[name="title"]').val(scheds[id].ra_docente) //altera para usuario_id
            _form.find('[name="description"]').val(scheds[id].id_uc)
            _form.find('[name="start_datetime"]').val(String(scheds[id].horario_inicio).replace(" ", "T"))
            _form.find('[name="end_datetime"]').val(String(scheds[id].horario_fim).replace(" ", "T"))
            $('#event-details-modal-feriado').modal('hide')
            _form.find('[name="title]').focus()
        } else {
            alert("Event is undefined");
        }
    })
});
