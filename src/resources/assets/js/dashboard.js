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
});