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

    $('.notification .status').on('click', function() {
        $(this).toggleClass('read', 'not-read');
        //AJAX
        $(this).closest('.notification').delay(750).slideUp(200).remove();
        var notifications_count = parseInt($('.notifications-link').find('.new-notifications').text());
        notifications_count--;
        $('.notifications-link').find('.new-notifications').text(notifications_count);

        if (notifications_count == 0) {
            $('.notifications-link').find('.badge').removeClass('new-notifications');
            $('.notifications').append('<div class="no-new-notifications">Aucune nouvelle notification !</div>');
        }
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
                $('.notifications .notifications-link .badge').addClass('new-notifications').text(data.notifications.length);
            }
        }
    });
}