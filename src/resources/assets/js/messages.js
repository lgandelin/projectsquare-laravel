$(document).ready(function() {

    //DASHBOARD - REPLY TO MESSAGE
    $('.conversation').on('click', '.reply-message', function() {
        var conversation = $(this).closest('.conversation');
        conversation.find('.new-message').show();
        conversation.find('.new-message textarea').focus();

        conversation.find('.submit').hide();
    });

    //DASHBOARD - CANCEL MESSAGE
    $('.conversation').on('click', '.cancel-message', function() {
        var conversation = $(this).closest('.conversation');
        conversation.find('.new-message textarea').val('');
        conversation.find('.new-message').hide();

        conversation.find('.submit').show();
    });

    //DASHBOARD - VALID MESSAGE
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
                $(conversation).find('.message-inserted').append(html);

                conversation.find('.count .number').text(data.message.count);

                conversation.find('.submit').show();
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