"use strict";

(function(){
    $('div.frames img').click( function () {
        var selected_val = $(this).attr('value');
        $(this).parent().parent().next('div').find('input').attr('value', selected_val);

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
