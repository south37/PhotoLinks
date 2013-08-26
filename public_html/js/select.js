"use strict";

(function(){
    $('.frames img').click( function () {
        var selected_val = $(this).attr('value');
        $(this).parent().parent().next('div').find('input').attr('value', selected_val);

        var frames_container = $('#story-fm [name=selected-frames-id]');
        var frames = frames_container.val();
        frames_container.val(frames + ',' + selected_val);
    });
})();
