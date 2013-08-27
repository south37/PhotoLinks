<?php
/**
 * view4:add_frame controller
 *
 * 2-4 : get: parent_frame_id
 * 5-4 : get: image_id
 * 6-4 : get: image_id
 * 
 * 
 */




// 通常のget
$app->get('/add_frame', function() use ($app) {
    $input = $app->request()->get();
    var_dump($input);

    $image_id = -1;
    $app->render('add_frame/add_frame.html.twig',["image_id"=>$image_id]);
    })
    ->name('add_frame_from_select')
    ;

//通常のget
$app->get('/add_frame/:image_id', function($image_id) use ($app) {
    $input = $app->request()->get();
    echo $image_id;

    $app->render('add_frame/add_frame.html.twig',["image_id"=>$image_id]);
    })
    ->name('add_frame_from_upload')
    ;


// フレームを追加
$app->post('/add_frame', function() use ($app) {
    $input = $app->request()->post();
    var_dump($input);
    $app->render('add_frame/add_frame.html.twig');

})->name('make_frame');


// ストーリーを作成 
$app->post('/add_frame', function() use ($app) {
    $input = $app->request()->post();
    var_dump($input);
    $app->render('add_frame/add_frame.html.twig');

})->name('make_story');

//フレーム追加
function addFrame(){
    


};

