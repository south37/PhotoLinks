"use strict";

(function(){
    $(function () {
        
        $('[id^=field-]').hide();
        $('#field-0').toggle();

        $('[id^=preview-frame]').css({'border': '2px solid #000000'});
        $('[id^=preview-caption]').css({'border': '0px solid #000000'});

        $('#field-0 img.frame').click();

        
    });

    $('img:not(.preview-frame):not(#field-0 img)').hover(function(){
            $(this).css("cursor","pointer");
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
        console.log(child_div);

        // step2.
        $('#preview-'+now_depth).attr('src', $(this).attr('src'));
        $('#preview-caption-'+now_depth).empty().append($(this).attr('alt'));

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
        if(now_depth < 3){
          $('#field-' + (now_depth+1)).show();
          $('#field-' + (now_depth+1)).find('[id^=preview-]').attr("src","/img/assets/searchIcon.png");
          $('#field-' + (now_depth+1)).find('div.frames').show();
          if(now_depth == 2){
            $('#field-' + (now_depth+1)).find('[id^=add-frame-]').hide();
          }
        }

        if(now_depth != 0){
            //$('#field-'+(now_depth+1)).find('.frames','[data-parent!='+selected_id+']').hide();
            $('#field-' + (now_depth+1)).find('.frames').each( function() {
                 
                    if(selected_id == $(this).data('parent')){
                        $(this).show();
                    }else{
                        $(this).hide();
                    }});
        }

        // step 6
        
        var maxNum = 5;
        var num =child_div.find('img.frame').size(); 
        console.log(num);
        /*
        child_div.find('img.frame').each(function(i){
                    if(i >= maxNum){
                        $(this).hide();
                    }else{
                        $(this).show();
                    }});
*/
        var item =child_div.find('img.frame').get().sort(function(){return Math.random()-0.5;});
        console.log(item);

        for(var i=0; i<item.length; i++){
            console.log(item[i]);
            if (i<maxNum) {
                //item[i].show();
                $(item[i]).show();
            }else{
                $(item[i]).hide();
            }
        }
        /*
        item.slice(0,maxNum).show();
        item.slice(maxNum,num).hide();
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

        $('#story-fm [name=selected-frames-id]').val(frames);
        $('[id^=add-frame-] [name=frame-ids]').val(frames);
    });
})();
