$(document).ready(function() {

    $('.ticket-infos-valid .update').click(function(e) {
        e.preventDefault();

        $(this).hide();
        $('.ticket-infos-valid .valid').show();
        $('.ticket-infos-valid .notice-notification').show();
        $('.ticket-infos-valid .btn-cancel').show();
        $('.ticket-infos select, .ticket-infos input, .ticket-infos textarea').prop('disabled', false);
    });

    $('.ticket-infos-valid .btn-cancel').click(function(e) {
        e.preventDefault();

        $(this).hide();
        $('.ticket-infos-valid .valid').hide();
        $('.ticket-infos-valid .notice-notification').hide();
        $('.ticket-infos-valid .update').show();
        $('.ticket-infos select, .ticket-infos input, .ticket-infos textarea').prop('disabled', true);
    });
});