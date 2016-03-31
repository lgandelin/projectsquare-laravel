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
                $('.notifications .badge').addClass('new-notifications').text(data.notifications.length);
            }
        }
    });
}