<?php
/**
 * story_view controller
 */


$app->get('/story_view', function() use ($app){

    //debug
    $imgPathList = [];

    //Default画像を出すテスト
    for($i = 0; $i < 10; $i++){
        $imgPath = "/img/ismTest/hatsune01.png";
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
    $frameList = explode(',',$testStr);
    echo $frameList;

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


