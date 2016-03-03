$('input.datepicker').datepicker({
    language: "fr",
    daysOfWeekDisabled: "0",
    autoclose: true,
    forceParse: false
});

function loadTemplate(templateID, params) {
    var template = Handlebars.compile($('#' + templateID).html());

    return template(params);
}