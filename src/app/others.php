<?php
use Respect\Validation\Validator as v;

他の: {
    $app->get('/story-view', function() use ($app, $container) {
        $input = $app->request()->get();
        echo 'story view' . PHP_EOL;
        var_dump($input);
    })
        ->name('story_view_get')
        ;

    $app->get('/add', function() use ($app, $container) {
        $input = $app->request()->get();
        echo 'add' . PHP_EOL;
        var_dump($input);
    })
        ->name('add')
        ;
}
