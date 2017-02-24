function displayMonth(index) {
    $('.month').hide();
    $('.month[data-month=' + index + ']').show();
    $('#months_index').val(index);

    $('.previous').removeClass('disabled');
    $('.next').removeClass('disabled');

    if (index == 0) $('.previous').addClass('disabled');
    if (index == parseInt($('#months_number').val()) - 1) $('.next').addClass('disabled');
}

function initCalendarNavigation() {
    $('.occupation-template .previous').click(function() {
        displayPreviousMonth();
    });

    $('.occupation-template .next').click(function() {
        displayNextMonth();
    });
}

function displayPreviousMonth() {
    var index = $('#months_index').val();
    if (index > 0) {
        index--;
        displayMonth(index);
    }
}

function displayNextMonth() {
    var index = $('#months_index').val();
    if (index < parseInt($('#months_number').val()) - 1) {
        index++;
        displayMonth(index);
    }
}

$(document).ready(function() {
    displayMonth(0);
    initCalendarNavigation();

    $(document).keydown(function (e) {
        if (e.which == 39) {
            displayNextMonth();
        }

        if (e.which == 37) {
            displayPreviousMonth();
        }
    });
});