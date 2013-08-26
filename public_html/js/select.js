"use strict";

(function(){
    $('img').click( function () {
        var check_box = $(this).next();
        check_box.prop("checked", !(check_box.prop("checked")));

        var selected_val = $(this).attr('value');
        $('.add-fm [name=last-selected-frame]').val(selected_val);

        var frames_container = $('#story-fm [name=selected-frames]');
        var frames = frames_container.val();
        frames_container.val(frames + ',' + selected_val);
    });
})();
