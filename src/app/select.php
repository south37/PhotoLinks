<?php
use Respect\Validation\Validator as v;

見る: {
    $app->get('/select', function() use ($app, $container) {
        $app->render('select/select.html.twig');
        })
        ->name('select')
    ;
}

