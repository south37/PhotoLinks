"use strict";

(function(){
    $('div.frames img').click( function () {
        $(this).parent().children('img').css({'border': '0px solid #000000'});
        $(this).css({'border': '2px solid #009999'});

        var selected_val = $(this).attr('value');
//        var next_div = $(this).parent().parent().next('div.frames')
        var next_div = $('[parent-id=' + selected_val + ']');
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
