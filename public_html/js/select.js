"use strict";

(function(){
    $('div.frames img').click( function () {
        var selected_id = $(this).attr('value');
        var child_div   = $('#' + selected_id);
        var now_depth   = $(this).parents('.frames').data('depth');

        $('div.frames').each( function() {
            var depth = $(this).data('depth');
            if(depth > now_depth) {
                //クリック画像以下すべて非表示
                $(this).css({'display':'none'});
                $(this).find('[name=parent-id]').attr('value', '');
                $(this).find('img').css({'border': '0px solid #000000'});
            } else if (depth === now_depth) {
                //クリック画像の段の枠を消す
                $(this).find('img').css({'border': '0px solid #000000'});
            }
        });


        $(this).css({'border': '2px solid #009999'});
        child_div.css({'display':'block'});
        child_div.find('[name=parent-id]').attr('value', selected_id);

        // 文字列結合
        var frames = '';
        var first = true;
        $('.frames').each( function() {
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
