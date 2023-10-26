jQuery(document).ready(function($) {
    $('#suporte-wordpress-form').on('submit', function(event) {
        event.preventDefault();

        var formData = $(this).serializeArray();
        formData.push({name: 'action', value: 'contato_dashboard_widget_form_submit'});

        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: ajax_object.ajax_url,
            data: formData,
            success: function(response) {
                $('#form-response').html(response);
                if (response.indexOf('color:white') !== -1) {
                    $('#suporte-wordpress-form').trigger('reset');
                }
                $('#form-response-close').show(); // exibe o botão de fechar
            },
            error: function() {
                $('#form-response').html('<button id="form-response-close" class="close-message">&times;</button><p style="color:white;background-color:red;padding:10px">Ocorreu um erro ao enviar a mensagem. Por favor, tente novamente.</p>');
                $('#form-response-close').show(); // exibe o botão de fechar
            }
        });
    });

    // Adicionar evento click no botão fechar
    $(document).on("click", "#form-response-close", function() {
        $(this).parent().hide();
    });
});
