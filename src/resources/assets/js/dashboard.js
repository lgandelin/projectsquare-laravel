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
        locale: 'fr',
        aspectRatio: 2,
        weekNumbers: true,
        weekends: false,
        minTime: '08:00',
        maxTime: '19:00',
        droppable: false,
        events: events,
        contentHeight: 'auto',

        eventRender: function(event, element) {
            event_title = "";
            if (event.project_name) event_title += '[' + event.project_name + '] <br/>';
            event_title += element.find('.fc-title').text();
            element.find('.fc-title').html(event_title);
        },
    });

    $('.block-content').removeClass('loading');

    //DASHBOARD - CANCEL MESSAGE
    $('.conversation').on('click', '.cancel-message', function() {
        var conversation = $(this).closest('.conversation');
        var conversation_id = $(this).data('id');
        $('#conversation-' + conversation_id + '-modal').modal('hide');
    });

    //DASHBOARD - VALID MESSAGE
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

    //DASHBOARD - WIDGETS
    $('.widget .block').resizable({
        resize: function( event, ui ) {
            var col = Math.round((ui.size.width / $('.dashboard-content .row').width()) * 12);
            ui.element.removeAttr('style');
            ui.element.closest('.widget').removeClass(classMatchHandler(/^col-lg-/)).addClass('col-lg-' + col);
            ui.element.closest('.widget').attr('data-w', col)
        },
        stop: function( event, ui ) {
            updateWidgets();
        },
        handles: 'e'
    });

    $('.dashboard-content .row').sortable({
        cursor: "move",
        tolerance: "intersect",
        placeholder: "ui-state-highlight",
        handle: ".move-widget",
        helper: "clone",
        items: ".widget:not(.total-width)",
        containment: "html  ",
        sort: function(event, ui) {
            var col = Math.round((ui.item.width() / $('.dashboard-content .total-width').width()) * 12);
            ui.placeholder.addClass('col-lg-' + col);
            ui.placeholder.height(ui.item.height()-30);
        },
        stop: function ( event, ui ) {
            updateWidgets();
        }
    });

    function classMatchHandler(regex) {
        return function (index, classes) {
            return classes.split(/\s+/).filter(function (el) {return regex.test(el);}).join(' ');
        }
    }

    function updateWidgets() {
        var widgetIDs = $( ".dashboard-content .row" ).sortable( "toArray" );
        widgets = []
        for (var i in widgetIDs) {
            var widgetID = widgetIDs[i];
            var width = $('#' + widgetID).attr('data-w');
            widgets.push({name: widgetID.replace('-widget', ''), width: width});
        }
        var string = JSON.stringify(widgets);
        if (string != "") {
            createCookie('dashboard-widgets-' + $('#current-user-id').val(), JSON.stringify(widgets));
        }
    }

    //DASHBOARD - REPORTING WIDGET
    $('.reporting .project .toggle-progress').click(function(e) {
        e.preventDefault();

        $(this).closest('.project').find('.project-progress').slideToggle(200);
        $(this).toggleClass('opened');
    });
});