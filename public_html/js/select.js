"use strict";

(function(){
    $('div.frames img').click( function () {
        var selected_id = $(this).attr('value');
        var child_div   = $('#' + selected_id);
        var now_depth   = $(this).parent().parent().data('depth');

        $('div.frames').each( function() {
            var depth = $(this).data('depth');
            if(depth > now_depth) {
                $(this).css({'display':'none'});
                $(this).find('[name=parent-id]').attr('value', '');
                $(this).find('img').css({'border': '0px solid #000000'});
            } else if (depth === now_depth) {
                $(this).find('img').css({'border': '0px solid #000000'});
            }
        });

        $(this).css({'border': '2px solid #009999'});
        child_div.css({'display':'block'});
        child_div.find('[name=parent-id]').attr('value', selected_id);

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
