<?php
use Respect\Validation\Validator as v;

他の: {

    $app->get('/add', function() use ($app, $container) {
        $input = $app->request()->get();
        echo 'add' . PHP_EOL;
        var_dump($input);
    })
        ->name('add')
        ;
}
