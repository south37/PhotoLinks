"use strict";

(function(){
    $('div.frames img').click( function () {
        $(this).parent().children('img').css({'border': '0px solid #000000'});
        $(this).css({'border': '2px solid #009999'});

        var next_depth   = $(this).parent().parent().data('depth')+1;
        var selected_val = $(this).attr('value');
        var next_div     = $('[parent-id=' + selected_val + ']');
    
        $('[data-depth=' + next_depth + ']').css({'display':'none'});
        next_div.css({'display':'block'});
        next_div.find('input').attr('value', selected_val);

        var frames = '';
        var first = true;
        $('div.frames').each( function() {
            var val = $(this).find('input').attr('value');
            if (val !== '') {
                if (!first) {
                    frames += ',';
                }
                frames += val;
                first = false;
            }
        });
        $('#story-fm [name=selected-frames-id]').val(frames);
    });
})();
