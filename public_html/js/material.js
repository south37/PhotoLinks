"use strict";

(function(){
    $('div.images img').click( function () {
        $(this).parent().children('img').css({'border': '0px solid #000000'});
        $(this).css({'border': '2px solid #009999'});

        var selected_val = $(this).attr('value');
        $('#image-select [name=image-id]').val(selected_val);
    });
})();
