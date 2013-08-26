<?php
use Respect\Validation\Validator as v;

見る: {
    $app->get('/select', function() use ($app, $container) {
        $frame_rows = [
            '0' => [
                ['src' => 'http://192.168.56.110:18003/img/plus.png',
                 'id'  => 1]
            ],
            '1' => [
                ['src' => 'http://192.168.56.110:18003/img/plus.png',
                 'id'  => 2],
                ['src' => 'http://192.168.56.110:18003/img/plus.png',
                 'id'  => 3]
            ]
        ];

        var_dump($frame_rows);
        $app->render('select/select.html.twig', ['frame_rows' => $frame_rows]);
        })
        ->name('select')
    ;
}

