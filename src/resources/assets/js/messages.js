$(document).ready(function() {
    $('.conversation').on('click', '.reply-message', function() {
        var conversation = $(this).closest('.conversation');
        conversation.find('.new-message').show();
        conversation.find('.new-message textarea').focus();
    });

    //DASHBOARD - REPLY TO MESSAGE
    $('.conversation').on('click', '.cancel-message', function() {
        var conversation = $(this).closest('.conversation');
        conversation.find('.new-message textarea').val('');
        conversation.find('.new-message').hide();
    });

    $('.conversation').on('click', '.valid-message', function() {
        var conversation = $(this).closest('.conversation');
        var data = {
            conversation_id: $(this).data('id'),
            message: conversation.find('.new-message textarea').val(),
            _token: $('#csrf_token').val()
        };

        $.ajax({
            type: "POST",
            url: route_message_reply,
            data: data,
            success: function(data) {
                conversation.find('.new-message textarea').val('');
                conversation.find('.new-message').hide();

                var html = loadTemplate('message-template', data.message);
                $(conversation).find('.message-inserted').prepend(html);

                conversation.find('.count .number').text(data.message.count);
            }
        });
    });
});