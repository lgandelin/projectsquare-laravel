$(document).ready(function() {

    //NOTIFICATIONS BOX
    $('.notifications-link').click(function() {
        $('.notifications').toggle(200);

        if ($('.top-right-menu .notifications .content-tab[data-content="1"]').children('.notification').length == 0 && $('.top-right-menu .notifications .content-tab[data-content="2"]').children('.notification').length > 0) {
            $('.top-right-menu .notifications .tabs li[data-tab="2"]').trigger('click');
        }

        $('.todos').hide(200);
    });

    //NOTIFICATION TABS
    $('.top-right-menu .notifications .tabs li').click(function() {
        $('.top-right-menu .notifications .content-tab').hide();
        $('.top-right-menu .notifications .tabs li').removeClass('current');

        var tab = $(this).data('tab');
        $('.top-right-menu .notifications .tabs li[data-tab="' + tab + '"]').addClass('current');
        $('.top-right-menu .notifications .content-tab[data-content="' + tab + '"]').show();
    });

    //READ NOTIFICATION
    $('.notifications').on('click', '.notification .notification-status', function() {
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
                var notifications_count = parseInt($('.notifications-link').find('.badge').text());
                notifications_count--;
                $('.notifications-link').find('.badge').text(notifications_count);

                if (notifications_count == 0) {
                    $('.notifications-link').find('.badge').css('visibility', 'hidden');
                }
            }
        });
    });

    $('.notifications .close').click(function() {
        $('.notifications').hide(200);
    });


    setTimeout(function() {
        setInterval(reloadNotificationsPanel, 15000);
    }, 15000);
});

function notify(author, body, icon, link)
{
    if('Notification' in window) {
        Notification.requestPermission(function(permission) {
            if (permission === 'granted') {
                var notification = new Notification(author, {
                    body: body,
                    icon: icon,
                    link: link
                });

                if (link) {
                    notification.onclick = function () {
                        window.open(link);
                    };
                }
            }
        });
    }
}

function reloadNotificationsPanel() {
    var data = {
        _token: $('#csrf_token').val()
    };

    $.ajax({
        type: "GET",
        url: route_get_notifications,
        data: data,
        success: function(data) {
            var notification_ids = [];
            $('#top-bar .notifications .notification').each(function() {
                notification_ids.push(parseInt($(this).data('id')))
            });

            var count = 0;
            for (i in data.notifications) {
                var notification = data.notifications[i];
                count++;

                if (!array_contains(notification_ids, notification.id)) {
                    var html = loadTemplate('notification-template', notification);
                    if (notification.type == 'MESSAGE_CREATED') {
                        $('#top-bar .notifications .content-tab[data-content="2"]').prepend(html);
                    } else {
                        $('#top-bar .notifications .content-tab[data-content="1"]').prepend(html);
                    }

                    notify(notification.authorCompleteName, notification.title, notification.authorAvatar, notification.link);
                }
            }

            if (count > 0) {
                $('.notifications-link').find('.badge').text(count).css('visibility', 'visible');
            }
        }
    });
}

function array_contains(array, obj) {
    for (var i = 0; i < array.length; i++) {
        if (array[i] === obj) {
            return true;
        }
    }
    return false;
}
