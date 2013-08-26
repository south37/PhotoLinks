<?php
/**
 * story_view controller
 */


$app->get('/story_view', function() use ($app){

    //debug
    $imgPathList = [1,2,3,4,5,6,7,8,9,10];

    //DBから画像パスを取得．
    for($i = 0; $i < count($imgPathList); $i++){
        $imgPath = "../public_html/img/ismTest/hatsune01.png";
        array_push($imgPathList, $imgPath);
    }


    $app->render('story_view/story_view.html.twig',["imgPathList" => $imgPathList]);
})
    ->name('story_view')
    ;


$app->post('/story_view',function() use($app){
    $input = $app->request()->post();

    //postでframe_idをカンマ区切りで取得．
    //$frameListStr = $input["parent-frame-id"];
    $testStr = ",1,4,6";
    $frameList = "hoge" ;

    $imgPathList =[];

    //DBから画像パスを取得．
    for($i = 0; $i < count($frameList); $i++){
        $imgPath = "../public_html/img/ismTest/hatsune01.png";
        array_push($imgPathList, $imgPath);
    }

    $app->render('story_view/story_view.html.twig',["imgPathList" => $imgPathList]);

})->name('story_view_post')
    ;

// コマ選択画面に戻る.
$app->post('/story_view',function() use($app){
    echo "return";
})
    ->name('return_select')
    ;


