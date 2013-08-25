<?php
use Respect\Validation\Validator as v;

見る: {
    $app->get('/select', function() use ($app, $container) {
        $app->render('select/index.html.twig');
        })
        ->name('select')
    ;
}

他の: {
    $app->map('/story-view', function() use ($app, $container) {
        $input = $app->request()->post();
        echo 'story view' . PHP_EOL;
        var_dump($input);
    })
        ->name('story_view')
        ->via('GET', 'POST')
        ;

    $app->map('/add', function() use ($app, $container) {
        $input = $app->request()->post();
        echo 'add' . PHP_EOL;
        var_dump($input);
    })
        ->name('add')
        ->via('GET', 'POST')
        ;
}
