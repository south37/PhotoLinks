<?php

素材: {
    $app->get('/material/:page', function($page) use ($app, $container) {
        $image_array  = get_image_array($container, $page);
        $image_num    = $container['repository.image']->getNumOfImageRow();
        $all_page_num = (INT)($image_num / 20) + 1;

        $app->render('material/material.html.twig', [
            'image_array' => $image_array, 
            'page' => $page, 
            'all_page_num' => $all_page_num,
            'frames' => $container['session']->get('frames')]);
    })
        ->name('material')
        ;

    $app->post('/material', function() use ($app, $container) {
        $input = $app->request()->post();

        $validator = new \Vg\Validator\Material();
        if (!$validator->validate($input)) {
            $error_message = implode(PHP_EOL, $validator->errors());
            $app->flash('errors', $error_message);
            $app->redirect($app->urlFor('material', ['page' => 1]));
        }

        $image_id = $input['image-id'];
        $image = $container['repository.image']->findbyId($image_id);
        
        if ($image->path === '') {
            $app->flash('errors', '不正な値です');
            $app->redirect($app->urlFor('material', ['page' => 1]));
        }

        $app->redirect($app->urlFor('add_frame_from_upload', ['image_id' => $image_id]));
    })
        ->name('material_post')
        ;
}

function get_image_array($container, $page) {
    $images = $container['repository.image']->findByPage($page-1);
    $image_array = [];
    foreach ($images as $image) {
        array_push($image_array, [
            'id'  => $image->id,
            'src' => $image->path
        ]);
    }
    return $image_array;
}

