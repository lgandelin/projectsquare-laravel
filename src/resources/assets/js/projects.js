$(document).ready(function() {

    $('#create-client-btn').click(function() {
        $('.create-client-inline .step-1').hide();
        $('.create-client-inline .step-2').show();

        $('#new-client-name').focus();
    });

    $('#cancel-new-client').click(function() {
        $('.create-client-inline .step-2').hide();
        $('.create-client-inline .step-1').show();
    });

    $('.create-client-inline #valid-new-client').click(function(e) {
        e.preventDefault();

        var data = {
            name: $('#new-client-name').val(),
            address: "",
            _token: $('#csrf_token').val()
        };

        $.ajax({
            type: "POST",
            url: route_client_create,
            data: data,
            success: function(data) {
                if (data.success) {
                    $('select[name="client_id"]').append($('<option>', {
                        value: data.client_id,
                        text: data.client_name
                    })).val(data.client_id);

                    $('#new-client-name').val('');

                    var confirmation = $('<div class="bg-success info">Client ajouté avec succès !</div>');
                    confirmation.insertAfter('#cancel-new-client');
                    confirmation.delay(2000).fadeOut();
                    $('.create-client-inline .step-2').delay(2000).fadeOut();
                    $('.create-client-inline .step-1').delay(2000).fadeIn();
                }
            },
            error: function(data) {
                data = $.parseJSON(data.responseText);
                alert(data.error);
            }
        });

    });
});