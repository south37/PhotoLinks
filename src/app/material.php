<?php

素材: {
    $app->get('/material', function() use ($app, $container) {
        $images = [
            ['id' =>  1, 'src' => 'img/ismTest/hatsune01.png'],
            ['id' =>  2, 'src' => 'img/ismTest/hatsune02.png'],
            ['id' =>  3, 'src' => 'img/ismTest/hatsune01.png'],
            ['id' =>  4, 'src' => 'img/ismTest/hatsune02.png'],
            ['id' =>  5, 'src' => 'img/ismTest/hatsune01.png'],
            ['id' =>  6, 'src' => 'img/ismTest/hatsune02.png'],
            ['id' =>  7, 'src' => 'img/ismTest/hatsune01.png'],
            ['id' =>  8, 'src' => 'img/ismTest/hatsune02.png'],
            ['id' =>  9, 'src' => 'img/ismTest/hatsune01.png'],
            ['id' => 10, 'src' => 'img/ismTest/hatsune02.png'],
            ['id' => 11, 'src' => 'img/ismTest/hatsune01.png'],
            ['id' => 12, 'src' => 'img/ismTest/hatsune02.png'],
            ['id' => 13, 'src' => 'img/ismTest/hatsune01.png'],
            ['id' => 14, 'src' => 'img/ismTest/hatsune02.png'],
            ['id' => 15, 'src' => 'img/ismTest/hatsune01.png'],
            ['id' => 16, 'src' => 'img/ismTest/hatsune02.png'],
            ['id' => 17, 'src' => 'img/ismTest/hatsune01.png'],
            ['id' => 18, 'src' => 'img/ismTest/hatsune02.png'],
            ['id' => 19, 'src' => 'img/ismTest/hatsune01.png'],
            ['id' => 20, 'src' => 'img/ismTest/hatsune02.png']
        ];

        $app->render('material/material.html.twig', ['images' => $images]);
    })
        ->name('material')
        ;

    $app->post('/material', function() use ($app, $container) {
        $input = $app->request()->post();
        $image_id = $input['image-id'];
        var_dump($input);
        $app->redirect($app->urlFor('add_frame_from_upload', ['image_id' => $image_id]));
    })
        ->name('material_post')
        ;
}
