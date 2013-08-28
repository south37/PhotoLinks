"use strict";

(function(){
    $('#image-select').change(function() {
        var file = $(this).prop('files')[0];
        var fr = new FileReader();
        fr.onload = function() {
            $('#preview').attr('src', fr.result);   // 読み込んだ画像データをsrcにセット
        }
        fr.readAsDataURL(file);  // 画像読み込み
    });
})();
