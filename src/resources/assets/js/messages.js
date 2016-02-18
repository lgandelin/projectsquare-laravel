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
            message: conversation.find('.new-message textarea').val()
        };

        //TODO : send Ajax request

        var result = {
            datetime: '18/02/2016 18:24',
            username: 'Louis Gandelin',
            message: 'Il faut aller sur https://www.google.com/analytics/web.',
            count: 2,
            id: 2
        };

        conversation.find('.new-message textarea').val('');
        conversation.find('.new-message').hide();

        var html = loadTemplate('message-template', result);
        $(conversation).find('.message-inserted').prepend(html);

        conversation.find('.count .number').text(result.count);
    });
});