$('input.datepicker').datepicker({
    language: "fr",
    autoclose: true,
    forceParse: false,
    format: 'dd/mm/yyyy',
    todayHighlight: true
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

//Overwrites ":contains" jQuery selector
$.expr[':'].contains = function(a, i, m) {
    return $(a).text().toUpperCase()
            .indexOf(m[3].toUpperCase()) >= 0;
};

$(document).ready(function() {

    //NOTIFICATIONS
    $('.info').delay(15000).fadeOut();

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
    $('.todos-link').click(function() {
        $('.todos').slideToggle(200);
        $('.notifications').hide(200);
    });

    //NOTIFICATIONS BOX
    $('.notifications-link').click(function() {
        $('.notifications').animate({width: 'toggle'});
        $('.todos').hide(200);
    });

    $('.top-right-menu .notifications .tabs li').click(function() {
        $('.top-right-menu .notifications .content-tab').hide();

        var tab = $(this).data('tab');
        $('.top-right-menu .notifications .content-tab[data-content="' + tab + '"]').show();
    });

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
    $(document).on('click', '.btn-delete', function() {
        if (!confirm('Etes-vous sûrs de vouloir supprimer cet élément ?')) {
            return false;
        }
    });

    //LEFT BAR MINIFIED
    $('body').on('mouseover', '.left-bar-minified .line', function() {
        $('.left-bar-minified .menu').removeClass('current-menu');
        $(this).closest('.menu').addClass('current-menu');
    });

    var is_mouse_over = false;
    var timeoutLeftBar;
    $('body').on('mouseenter', '.left-bar-minified .sub-menu', function() {
        is_mouse_over = true;
        clearTimeout(timeoutLeftBar);
    }).on('mouseleave', '.left-bar-minified .sub-menu', function() {
        is_mouse_over = false;
        var submenu=$(this);

        timeoutLeftBar = setTimeout(function() {
            if (!is_mouse_over) {
                submenu.closest('.menu').removeClass('current-menu');
            }
        }, 500);
    });

    $('body').on('click', '.left-bar .toggle-left-bar', function() {
        $(this).closest('.left-bar').find('.icon').toggleClass('glyphicon-triangle-right').toggleClass('glyphicon-triangle-left');
        $('.left-bar').toggleClass('left-bar-minified');
        $('.content').toggleClass('content-expanded');

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

    if (!$('.left-bar').hasClass('left-bar-minified') && ($(window).width() <= 1000)) {
        $('.left-bar .toggle-left-bar').trigger('click');
    }

    //LEFT BAR SEARCH
    $('.left-bar .filter-project input[type="text"]').on('keyup', function() {
        filter_projects_list();
    });

     //TOOLTIPS
    $('.tooltip-icon').tooltipster({
        animation: 'fade',
        theme: ['tooltipster-shadow', 'tooltipster-shadow-customized'],
        maxWidth : 300
    });

    //FILTERS
    $('select[name^="filter_"]').on('change', function() {
        $(this).closest('form').submit();
    });

    //SUBMENUS
    $('.left-bar .menu .line').click(function() {
        var id = $(this).data('id');

        var current = readCookie('left-bar-' + id);
        if (current == null) {
            current = 'opened';
        }

        if (current == 'opened') {
            createCookie('left-bar-' + id, 'closed');
        } else {
            createCookie('left-bar-' + id, 'opened');
        }

        $(this).closest('.menu').toggleClass('submenu-closed');
    });

    //MIDDLE COLUMN : Toggle parent list
    $('.middle-column').on('click', '.parent-wrapper', function() {
        $(this).closest('.parent').find('.toggle-childs').toggleClass('glyphicon-triangle-bottom').toggleClass('glyphicon-triangle-top');
        $(this).closest('.parent').find('.childs').slideToggle();
    });

});

function filter_projects_list() {
    var input_search = $('.left-bar .filter-project input[type="text"]');

    $('.left-bar .menu .sub-menu-projects li:not(.filter-disabled)').each(function() {
        var show = false;

        if (input_search.val().length == 0 || $(this).is(':contains("' + input_search.val() + '")'))
            show = true;

        if (show)
            $(this).fadeIn();
        else
            $(this).fadeOut();
    });
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