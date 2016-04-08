$(document).ready(function() {

    //CALENDAR
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
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
        events: events
    });

    $('.block-content').removeClass('loading');

    setTimeout(function() {
        setInterval(reloadNotificationsPanel, 30000);
    }, 30000);



    //NOTIFICATIONS BOX
    $('.notifications-link').click(function() {
        $('.notifications').toggle(200);
    });

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
                    $('.notifications-link').find('.badge').removeClass('new-notifications');
                    $('.no-new-notifications').show();
                }
            }
        });
    });

    $('.notifications .close').click(function() {
        $('.notifications').hide(200);
    });
});

function reloadNotificationsPanel() {
    var data = {
        _token: $('#csrf_token').val()
    };

    $.ajax({
        type: "GET",
        url: route_refresh_notifications,
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