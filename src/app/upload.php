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

                    $image = new \Vg\Model\Image();
                    $imagefile = array("user_id"=>($container['session']->get('user.id')),"path"=>"","scope"=>0,"deleted"=>0); 
                    $image->setProperties($imagefile);
                    $repository = $container['repository.image'];
                    // 画像データをDBにInsert
                     try {
                        $image_id = $repository->insert($image);
                    } catch (Exception $e) {
                        $app->halt(500, $e->getMessage());
                    }

                    // image_idと画像の拡張子を取得してpathを更新
                    $extension = pathinfo($_FILES['upfile']['name'], PATHINFO_EXTENSION);
                    $imagefile = array("id"=>$image_id,"user_id"=>($container['session']->get('user.id')),"path"=>"/img/public_img/".$image_id.".".$extension,"scope"=>0,"deleted"=>0);
                    $image->setProperties($imagefile);
                     try {
                        $repository->update($image);
                    } catch (Exception $e) {
                        $app->halt(500, $e->getMessage());
                    }
                    // リネーム処理
                    rename(__DIR__.'/../../public_html/img/public_img/'.$_FILES['upfile']['name'],__DIR__."/../../public_html/img/public_img/".$image_id.".".$extension);

                    $app->redirect($app->urlFor('add_frame_from_upload',array("image_id" => $image_id)));
                } else {
                    $app->flash('info', 'アップロード失敗。');
                    $app->redirect($app->urlFor('upload_post'));
                }
            } else {
                    $app->flash('info', 'アップロード失敗');
                    $app->redirect($app->urlFor('upload_post'));
            }
        })
        ->name('upload_post')
    ;

}

