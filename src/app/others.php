<?php
use Respect\Validation\Validator as v;

他の: {
    $app->post('/story-view', function() use ($app, $container) {
        $input = $app->request()->post();
        echo 'story view' . PHP_EOL;
        var_dump($input);
    })
        ->name('story_view_post')
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
