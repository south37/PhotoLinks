"use strict";

(function(){
    $(function () {
        
        $('[id^=field-]').hide();
        $('#field-0').toggle();

        
    });

    /*
     * 画像クリック時：選択ノード以下展開．
     * 1.選択ノードdepth取得 -> now_depth
     * 2.選択ノード画像pathをpreviewにset
     * 3.選択ノード以下depth -> display:none
     * 4.選択画像 -> border 2px
     * 5.選択画像child -> display:block,
     * 6.view3用.selectedFrameIdのlist,カンマ区切り文字列生成
     * */
    $('div.frames-field img.frame').click( function () {
        // step1.
        var selected_id = $(this).attr('value'); // frame
        var child_div   = $('#childlen-of-' + selected_id);  // frames
        var now_depth   = $(this).parents('[id^="field-"]').data('depth');

        // step2.
        $('#preview-'+now_depth).attr('src', $(this).attr('src'));

        // step3
        $('div.frames-field').each( function() {
            var depth = $(this).data('depth');
            if(depth > now_depth) {
                //クリック画像以下すべて非表示
                //$(this).css({'display':'none'});
                $(this).hide();
                $(this).children('div.frames').hide();
                $(this).children('div.frames').attr('data-parent', '');
                $(this).find('img').css({'border': '0px solid #000000'});
            } else if (depth === now_depth) {
                //クリック画像段は枠だけを消す
                $(this).find('img').css({'border': '0px solid #000000'});
            }
                //同一レベルより上であればなにもしない
        });

        // step4
        $(this).css({'border': '2px solid #009999'});

        // step5
        $('#field-' + (now_depth+1)).show();
        $('#field-' + (now_depth+1)).children('div.frames').show();
        alert("sel:"+selected_id + " now_depth:"+now_depth);
        if(now_depth !== 0){
            alert('if');
            //$('#field-' + (now_depth+1)).children('.frames').each( function() {
            $('#field-'+now_depth).children('div.frames').each( function() {
                    //alert("sel:"+selected_id+" parent:" + $(this).data('parent'));
                    alert("sel:"+selected_id);
                    });
        }
        child_div.find('div.frames').attr('data-parent', selected_id);//<-何のため？

        // step6
        var frames = '';
        var first = true;
        $('.frames').each( function() {
            var val = $(this).find('input').attr('value');//<-親ノードidが格納されてる
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
