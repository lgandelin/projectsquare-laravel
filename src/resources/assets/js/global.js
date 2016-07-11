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

    //BETA FORM
    $('body').on('click', '.beta-form .toggle', function() {
        $('#beta-form-modal').modal('show');
    });

    $('#beta-form-modal .valid-beta-form').click(function() {
        var data = {
            title: $('#beta-form-modal input[name="title"]').val(),
            message: $('#beta-form-modal textarea[name="message"]').val(),
            _token: $('#csrf_token').val()
        };

        $.ajax({
            type: "POST",
            url: route_beta_form,
            data: data,
            success: function(data) {
                window.location.reload();
            }
        });

    });

    //TASKS BOX 
      $('.tasks-link').click(function() {
        $('.tasks').slideToggle(200);
          $('.notifications').hide(200);
    });


    //NOTIFICATIONS BOX
    $('.notifications-link').click(function() {
        $('.notifications').slideToggle(200);
        $('.tasks').hide(200);
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

    $('.notifications .close').click(function() {
        $('.notifications').hide(200);
    });

    //DELETE BUTTONS
    $('.btn-delete').click(function(e) {
        if (!confirm('Etes-vous sûrs de vouloir supprimer cet élément ?')) {
            return false;
        }
    });

    //LEFT BAR MINIFIED
    $('body').on('mouseover', '.left-bar-minified .line', function() {
        $('.left-bar-minified .menu .sub-menu').hide();
        $(this).closest('.menu').find('.sub-menu').show();
    });

    $('body').on('click', '.left-bar .toggle-left-bar', function() {
        $(this).toggleClass('glyphicon-triangle-right').toggleClass('glyphicon-triangle-left');
        $('.left-bar').toggleClass('left-bar-minified');
        $('.content').toggleClass('content-expanded');
        $('.left-bar .menu .sub-menu').show();
        $('.left-bar-minified .menu .sub-menu').hide();

        var current = readCookie('left-bar');
        if (current == null) {
            current = 'opened';
        }

        if (current == 'opened') {
            createCookie('left-bar', 'closed');
        } else {
            createCookie('left-bar', 'opened');
        }
    });

    $('body').on('mouseover', '.left-bar-minified .toggle-left-bar', function() {
        $('.left-bar-minified .menu .sub-menu').hide();
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

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function createCookie(name,value) {
    var date = new Date();
    date.setTime(date.getTime()+(365*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
    document.cookie = name+"="+value+expires+"; path=/";
}

