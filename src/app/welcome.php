<?php
/**
 * top画面 
 */
$app->get('/', function() use ($app) {
    $app->render('top/index.html.twig');

    // top story 取得

})
    ->name('welcome')
    ;

/**
 * Demo 用
 */
$app->get('/hello/:name', function ($name) use ($app, $container) {
        // テンプレートでコードを表示するために
        $phpcode = $contents = highlight_file(__FILE__, TRUE);
        $twigcode = file_get_contents($container['twig.templateDir'] . '/top/hello.html.twig');
        $app->render('top/hello.html.twig', ['name' => $name, 'phpcode' => $phpcode, 'twigcode' => $twigcode]);
    })
    ->name('hello')
    ->conditions(array('name' => '\w+'))
;
