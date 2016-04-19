$('input.datepicker').datepicker({
    language: "fr",
    autoclose: true,
    forceParse: false
});

function loadTemplate(templateID, params) {
    var template = Handlebars.compile($('#' + templateID).html());

    return template(params);
}

Handlebars.registerHelper('ifCond', function(v1, v2, options) {
    if(v1 === v2) {
        return options.fn(this);
    }
    return options.inverse(this);
});

function uniqid() {
    var n=Math.floor(Math.random()*11);
    var k = Math.floor(Math.random()* 1000000);
    return String.fromCharCode(n)+k;
}

$(document).ready(function() {

    //NOTIFICATIONS BOX
    $('.notifications-link').click(function() {
        $('.notifications').toggle(200);
    });

    setTimeout(function() {
        setInterval(reloadNotificationsPanel, 15000);
    }, 15000);

    $('.notifications').on('click', '.notification .status', function() {
        $(this).toggleClass('read', 'not-read');

        var notification = $(this).closest('.notification');
        var data = {
            id: notification.data('id'),
            _token: $('#csrf_token').val()
        };

        $.ajax({
            type: "POST",
            url: route_read_notification,
            data: data,
            success: function(data) {
                notification.delay(750).slideUp(200).remove();
                var notifications_count = parseInt($('.notifications-link').find('.new-notifications').text());
                notifications_count--;
                $('.notifications-link').find('.new-notifications').text(notifications_count);

                if (notifications_count == 0) {
                    $('.notifications .title').text('Aucune nouvelle notification');
                    $('.notifications-link').find('.badge').removeClass('new-notifications');
                }
            }
        });
    });
});

function reloadNotificationsPanel() {
    var data = {
        _token: $('#csrf_token').val()
    };

    $.ajax({
        type: "GET",
        url: route_get_notifications,
        data: data,
        success: function(data) {
            if (data.notifications && data.notifications.length > 0) {
                $('.notifications-link').find('.badge').addClass('new-notifications');
                $('.notifications-link').find('.badge').text(data.notifications.length);

                var notification_ids = [];
                $('.notifications .notification').each(function() {
                    notification_ids.push(parseInt($(this).data('id')))
                });

                for (i in data.notifications) {
                    var notification = data.notifications[i];

                    if (!contains(notification_ids, notification.id)) {
                        var html = loadTemplate('notification-template', notification);
                        $('.notifications').append(html);
                    }
                }

                $('.notifications .no-new-notifications').hide();
            } else {
                $('.notifications-link').find('.badge').removeClass('new-notifications');
                $('.notifications-link').find('.badge').text('0');
                $('.notifications .no-new-notifications').show();
            }
        }
    });
}

function contains(array, obj) {
    for (var i = 0; i < array.length; i++) {
        if (array[i] === obj) {
            return true;
        }
    }
    return false;
}