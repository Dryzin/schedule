
    //teste de fazer um popup apareca e depois some e da um reset na pagina

    $('#schedule-form').on('submit', function (e) {
        e.preventDefault();

        // faz o envio do formulário via AJAX
        $.ajax({
            url: './save_schedule.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function () {
                // exibe a mensagem de sucesso na caixa de diálogo
                $('#success-modal').modal('show');

                $('#schedule-form').show().fadeOut(3000, function () {
                    // recarrega a página após a mensagem desaparecer
                    location.reload();
                });
                // limpa o formulário
                $('#schedule-form').trigger('reset');
            }
        });
    });
