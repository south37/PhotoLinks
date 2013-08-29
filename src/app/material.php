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

        $frameListStr = $container['session']->get('frame_ids');
        $frameList = explode(',',$frameListStr);
        
        $frame_repository = $container['repository.frame'];
        $image_repository = $container['repository.image'];
        $frames = [];
        foreach($frameList as $frame_id) {
            $frame = $frame_repository->findById($frame_id);
            $image = $image_repository->findById($frame->image_id);
            array_push($frames, ['path' => $image->path, 'caption' => $frame->caption]);
        }

        $app->render('material/material.html.twig', [
            'images' => $image_array, 
            'page' => $page, 
            'all_page_num' => $all_page_num,
            'frames' => $frames]);
    })
        ->name('material')
        ;

    $app->post('/material', function() use ($app, $container) {
        $input = $app->request()->post();
        $image_id = $input['image-id'];
        $app->redirect($app->urlFor('add_frame_from_upload', ['image_id' => $image_id]));
    })
        ->name('material_post')
        ;
}
