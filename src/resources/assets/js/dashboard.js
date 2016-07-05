$(document).ready(function() {

    //PLANNING
    $('#planning').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        allDaySlot: false,
        defaultDate: defaultDate,
        defaultView: "agendaWeek",
        editable: false,
        lang: 'fr',
        aspectRatio: 2,
        weekNumbers: true,
        weekends: false,
        minTime: '08:00',
        maxTime: '21:00',
        droppable: false,
        events: events,
        contentHeight: 'auto'
    });

    $('.block-content').removeClass('loading');

    //DASHBOARD - REPLY TO MESSAGE
    $('.conversation').on('click', '.reply-message', function() {
        var conversation = $(this).closest('.conversation');
        var conversation_reply = $('#' + conversation.attr('id') + '-reply');
        conversation_reply.show().find('.new-message');
        conversation_reply.find('.new-message textarea').focus();

        conversation.find('.submit').hide();
    });

    //DASHBOARD - CANCEL MESSAGE
    $('.conversation-reply').on('click', '.cancel-message', function() {
        var conversation_reply = $(this).closest('.conversation-reply');
        var conversation = $('#conversation-' + conversation_reply.attr('data-id'));
        conversation_reply.find('.new-message textarea').val('');
        conversation_reply.hide();

        conversation.find('.submit').show();
    });

    //DASHBOARD - VALID MESSAGE
    $('.conversation-reply').on('click', '.valid-message', function() {
        var conversation_reply = $(this).closest('.conversation-reply');
        var conversation = $('#conversation-' + conversation_reply.attr('data-id'));
        var message = conversation_reply.find('.new-message textarea').val();

        var data = {
            conversation_id: conversation_reply.attr('data-id'),
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
                conversation_reply.find('.new-message textarea').val('');
                conversation_reply.hide();

                var html = loadTemplate('message-template', data.message);
                $(conversation_reply).find('.message-inserted').append(html);

                //conversation.find('.count .number').text(data.message.count);

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