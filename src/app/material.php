<?php

素材: {
    $app->get('/material', function() use ($app, $container) {
        $images = $container['repository.image']->findByPage(0);
        $image_array = [];
        foreach ($images as $image) {
            array_push($image_array, [
                'id' => $image->id,
                'src' => $image->path
            ]);
        }
        
        $app->render('material/material.html.twig', ['images' => $image_array]);
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
