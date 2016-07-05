$(document).ready(function() {

    //REPLY TO MESSAGE
    $('.conversation').on('click', '.reply-message', function() {
        var conversation = $(this).closest('.conversation');
        conversation.find('.new-message').show();
        conversation.find('.new-message textarea').focus();

        conversation.find('.submit').hide();
    });

    //CANCEL MESSAGE
    $('.conversation').on('click', '.cancel-message', function() {
        var conversation = $(this).closest('.conversation');
        conversation.find('.new-message textarea').val('');
        conversation.find('.new-message').hide();

        conversation.find('.submit').show();
    });

    //VALID MESSAGE
    $('.conversation').on('click', '.valid-message', function() {
        var conversation = $(this).closest('.conversation');
        var message = conversation.find('.new-message textarea').val();
        var data = {
            conversation_id: $(this).data('id'),
            message: message,
            _token: $('#csrf_token').val()
        };

        if (message == '') {
            alert('Veuillez entrer un message');

            return false;
        }

        $.ajax({
            type: "POST",
            url: route_message_reply,
            data: data,
            success: function(data) {
                window.location.reload();
            },
            error: function(data) {
                data = $.parseJSON(data.responseText);
                alert(data.message)
            }
        });
    });
    
    //DASHBOARD - CREATE CONVERSATION
    $('body').on('click', '.create-conversation', function() {
        $('#create-conversation-modal').modal('show');
    });

    $('.valid-create-conversation').click(function() {
        var data = {
            title: $('input[name="title"]').val(),
            message: $('textarea[name="message"]').val(),
            _token: $('#csrf_token').val()
        };

        $.ajax({
            type: "POST",
            url: route_add_conversation,
            data: data,
            success: function(data) {
                window.location.reload();
            }
        });
    });
});