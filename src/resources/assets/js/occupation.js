function displayMonth(index) {
    $('.month').hide();
    $('.month[data-month=' + index + ']').show();
    $('#months_index').val(index);

    $('.previous').removeClass('disabled');
    $('.next').removeClass('disabled');

    if (index == 0) $('.previous').addClass('disabled');
    if (index == parseInt($('#months_number').val()) - 1) $('.next').addClass('disabled');
}

$(document).ready(function() {
    displayMonth(0);

    $('.previous').click(function() {
        var index = $('#months_index').val();
        if (index > 0) {
            index--;
            displayMonth(index);
        }
    });

    $('.next').click(function() {
        var index = $('#months_index').val();
        if (index < parseInt($('#months_number').val()) - 1) {
            index++;
            displayMonth(index);
        }
    })
});