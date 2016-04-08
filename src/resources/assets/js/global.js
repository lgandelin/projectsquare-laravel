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