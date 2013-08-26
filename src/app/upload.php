<?php
use Respect\Validation\Validator as v;

画像アップロード: {
    $app->get('/upload', function() use ($app) {
            $app->render('upload/upload.html.twig');
        })
        ->name('upload_image')
    ;

    $app->post('/upload', function () use ($app) {
            if (is_uploaded_file($_FILES['upfile']['tmp_name'])) {
                if(move_uploaded_file($_FILES['upfile']['tmp_name'],__DIR__.'/../../public_html/img/public_img/'.$_FILES['upfile']['name'])) {
                    $app->render('upload/upload.finish.html.twig');
                } else {
                    $app->render('upload/upload.error.html.twig');
                }
            } else {
                $app->render('upload/upload.error.html.twig');
            }
        })
        ->name('upload_post')
    ;

}

