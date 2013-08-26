<?php
/**
 * story_view controller
 */

/*
$app->get('/story_view', function() use ($app){

    //debug
    $imgPathList = [];

    //Default画像を出すテスト
    for($i = 0; $i < 10; $i++){
        $imgPath = "/img/ismTest/hatsune02.png";
        array_push($imgPathList, $imgPath);
    }

    $app->render('story_view/story_view.html.twig',["imgPathList" => $imgPathList]);
})
    ->name('story_view')
    ;

 */
$app->get('/story_view',function() use($app, $container){
    $input = $app->request()->get();
    var_dump($input);
    //postでframe_idをカンマ区切りで取得．
    $frameListStr = $input["selected-frames-id"];
    $frameList = explode(',',$frameListStr);

    //DBから画像パスを取得．
    $repository = $container['repository.frame'];
    $imgPathList =[];

    for($i = 1; $i < count($frameList); $i++){
        $tmpFrame = $repository->findById($frameList[$i]);
        $imgPath = "/img/ismTest/hatsune01.png";
        array_push($imgPathList, $imgPath);
    }

    $app->render('story_view/story_view.html.twig',["imgPathList" => $imgPathList]);
})->name('story_view_get')
    ;

/*
$app->post('/story_view',function() use($app){
    $input = $app->request()->post();

    //postでframe_idをカンマ区切りで取得．
    $frameListStr = $input["selected-frames-id"];
    $frameList = explode(',',$frameListStr);

    //DBから画像パスを取得．
    $repository = $container['repository.']
    $imgPathList =[];
    for($i = 1; $i < count($frameList); $i++){
        $imgPath = "/img/ismTest/hatsune01.png";
        array_push($imgPathList, $imgPath);
    }

    $app->render('story_view/story_view.html.twig',["imgPathList" => $imgPathList]);
})->name('story_view_post')
    ;
 */
