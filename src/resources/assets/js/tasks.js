$(document).ready(function() {

    $('.phases-list').on('click', '.add-phase', function() {
        var html = loadTemplate('phase-template', {
            id: 'xxx',
            name: 'Nouvelle phase',
        });
        
        $('.phases-list .phases').append(html);
    });
});