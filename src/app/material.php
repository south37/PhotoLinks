<?php

素材: {
    $app->get('/material/:page', function($page) use ($app, $container) {
        $image_repository = $container['repository.image'];
        $images = $image_repository->findByPage($page-1);
        $image_array = [];
        foreach ($images as $image) {
            array_push($image_array, [
                'id'  => $image->id,
                'src' => $image->path
            ]);
        }

        $image_num    = $image_repository->getNumOfImageRow();
        $all_page_num = (INT)($image_num / 20) + 1;

        $app->render('material/material.html.twig', ['images' => $image_array, 'page' => $page, 'all_page_num' => $all_page_num]);
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
