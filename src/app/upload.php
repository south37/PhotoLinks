<?php
use Respect\Validation\Validator as v;

画像アップロード: {
    $redirectIfNotLogin = function ( $session ) {
        return function () use ( $session ) {
            if ( $session->get('isLogin') !== true ) {
                $app = \Slim\Slim::getInstance();
                $app->flash('error', 'Login required');
                $app->redirect($app->urlFor('user_login'));
            }
        };
    };

    $app->get('/upload', $redirectIfNotLogin($container['session']), function() use ($app) {
            $app->render('upload/upload.html.twig');
        })
        ->name('upload_image')
    ;

    $app->post('/upload',$redirectIfNotLogin($container['session']), function () use ($app,$container) {
            if (is_uploaded_file($_FILES['upfile']['tmp_name'])) {
                if(move_uploaded_file($_FILES['upfile']['tmp_name'],__DIR__.'/../../public_html/img/public_img/'.$_FILES['upfile']['name'])) {

                    $imagefile = array("user_id"=>($container['session']->get('user.id')),"path"=>"","scope"=>0,"deleted"=>0); 
                    $image = new \Vg\Model\Image();
                    $image->setProperties($imagefile);
                    $repository = $container['repository.image'];
                    try {
                        $repository->insert($image);
                    } catch (Exception $e) {
                        $app->halt(500, $e->getMessage());
                    }
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

