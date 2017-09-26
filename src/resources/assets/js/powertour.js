$(document).ready(function() {

    $.powerTour({
        tours : [
            {
                trigger            : '',
                startWith          : 1,
                easyCancel         : true,
                escKeyCancel       : true,
                scrollHorizontal   : false,
                keyboardNavigation : true,
                loopTour           : false,
                highlightStartSpeed : 200,
                highlightEndSpeed  : 200,
                onStartTour        : function(ui){ },
                onEndTour          : function(ui){
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                },
                onProgress         : function(ui){ },
                steps : [
                    {
                        content         : '#step-intro',
                        width           : 430,
                        position        : 'sc',
                        offsetY         : 50,
                        highlight       : true,
                    },
                    {
                        hookTo          : '#left-bar',
                        content         : '#step-left-bar',
                        width           : 370,
                        position        : 'rt',
                        offsetX         : 25,
                        offsetY         : 250,
                        highlight       : true,
                    },
                    {
                        hookTo          : '#top-bar',
                        content         : '#step-top-bar',
                        width           : 470,
                        position        : 'br',
                        offsetY         : 25,
                        offsetX         : 25,
                        highlight       : true,
                    },
                    {
                        hookTo          : '#tickets-widget',
                        content         : '#step-tickets-widget',
                        width           : 540,
                        position        : 'rm',
                        offsetX         : 15,
                        highlight       : true,
                    },
                    {
                        hookTo          : '#tasks-widget',
                        content         : '#step-tasks-widget',
                        width           : 490,
                        position        : 'lm',
                        scrollSpeed     : 400,
                        offsetX         : 15,
                        highlight       : true,
                    },
                    {
                        hookTo          : '#planning-widget',
                        content         : '#step-planning-widget',
                        width           : 480,
                        position        : 'tm',
                        scrollSpeed     : 400,
                        offsetX         : 0,
                        offsetY         : 25,
                        highlight       : true,
                    }
                ],
                stepDefaults : [
                    {
                        width           : 300,
                        position        : 'tr',
                        offsetY         : 0,
                        offsetX         : 0,
                        fxIn            : 'fadeIn',
                        fxOut           : 'fadeOut',
                        showStepDelay   : 0,
                        center          : 'step',
                        scrollSpeed     : 250,
                        scrollEasing    : 'swing',
                        scrollDelay     : 0,
                        timer           : '00:00',
                        highlight       : true,
                        keepHighlighted : false,
                        keepVisible     : false,// new 2.2.0
                        onShowStep      : function(ui){ },
                        onHideStep      : function(ui){ }
                    }
                ]
            }
        ]
    });

    $.powerTour('run',1);
});