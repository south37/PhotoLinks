"use strict";

(function(){
    $('div.frames img').click( function () {
        $(this).parent().children('img').css({'border': '0px solid #000000'});
        $(this).css({'border': '2px solid #009999'});

        var selected_val = $(this).attr('value');
        $(this).parent().parent().next('div.frames').find('input').attr('value', selected_val);

        var frames = '-1';
        $('div.frames').each( function() {
            var val = $(this).find('input').attr('value');
            if (val !== '') {
                frames += ',';
                frames += val;
            }
        });
        $('#story-fm [name=selected-frames-id]').val(frames);
    });
})();
