"use strict";

(function(){
    $(function () {
        
        $('[id^=field-]').hide();
        $('#field-0').toggle();
        $('[id^=preview-]').css({'border': '2px solid #000000'});

        $('#field-0 img.frame').click();

        
    });

    /*
     * 画像クリック時：選択ノード以下展開．
     * 1.選択ノードdepth取得 -> now_depth
     * 2.選択ノード画像pathをpreviewにset
     * 3.選択ノード以下depth -> display:none
     * 4.選択画像 -> border 2px
     * 5.選択画像child -> display:block,
     * 6.表示数制限．適当にhideする
     * 7.view3用.selectedFrameIdのlist,カンマ区切り文字列生成
     * */
    $('div.frames-field img.frame').click( function () {
        // step1.
        var selected_id = $(this).attr('value'); // frame
        var child_div   = $('#children-of-' + selected_id);  // frames
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
                $(this).find('div.frames').hide();
                //$(this).find('div.frames').attr('data-parent', '');
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
        $('#field-' + (now_depth+1)).find('div.frames').show();
        if(now_depth !== 0){
            //$('#field-'+(now_depth+1)).find('.frames','[data-parent!='+selected_id+']').hide();
            $('#field-' + (now_depth+1)).find('.frames').each( function() {
                 
                    if(selected_id == $(this).data('parent')){
                        $(this).show();
                    }else{
                        $(this).hide();
                    }});
        }

        // step 6
        /*
        var num = $('#field-' + (now_depth+1)).find('.frames').size();
        var maxNum = 5;
         
        $('#field-' + (now_depth+1)).find('.frames').each( function() {
                 
                    if(num > maxNum){
                        $(this).hide();
                        num -= 1;
                    }else{
                        $(this).show();
                    }});
*/
        child_div.find('div.frames').attr('data-parent', selected_id);//<-make_frameにおけるparent-id

     
        // step7
        var frames = '';
        var first  = true;

        //$('[id^="children-of-"]',).each( function(){
        $("div.frames").each( function(){
                if($(this).css('display')=='none'){return;}

                var tmp_depth = $(this).parents('.frames-field').data('depth');
                if((tmp_depth <= 0) || (tmp_depth > now_depth+1)){return;}

                var tmp_selected_id = $(this).data('parent');
                if (tmp_selected_id !== '') {
                   if (!first) {
                      frames += ',';
                  }
                  frames += tmp_selected_id;
                  $(this).parents("#field-" + tmp_depth).find('[name=parent-id]').val(tmp_selected_id);
                  first = false;
                }
                
                });
        /*
        $('[id^=add_frame_]').each( function() {
            var val = $(this).find('input').attr('value');//<-親ノードidが格納されてる
            if (val !== '') {
                if (!first) {
                    frames += ',';
                }
                frames += val;
                first = false;
            }
        });
        */
        $('#story-fm [name=selected-frames-id]').val(frames);
    });
})();
