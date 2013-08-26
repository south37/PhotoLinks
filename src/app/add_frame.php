<?php
/**
 * view4:add_frame controller
 */

// 通常のget
$app->get('/add_frame', function() use ($app) {
        $app->render('add_frame/add_frame.html.twig');
    })
    ->name('add_frame')
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
    
