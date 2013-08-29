<?php
use Respect\Validation\Validator as v;



画像アップロード: {
    $app->get('/upload', $redirectIfNotLogin($container['session']), function() use ($app, $container) {
            // CSRF対策のトークンを埋め込む
            $token = $container['session']->id();
            $app->render('upload/upload.html.twig', ['token'=>$token, 'frames'=>$container['session']->get('frames')]);
        })
        ->name('upload_image')
    ;

    $app->post('/upload', $redirectIfNotLogin($container['session']), function () use ($app,$container) {
            $input = $app->request()->post();
            $input['image_path'] = $_FILES['upfile']['tmp_name'];
            $input['image_name'] = $_FILES['upfile']['name'];

            // CSRF対策
            if($input['token'] != $container['session']->id())
            {
                $app->flash('info', '画面遷移に失敗しました');
                $app->redirect($app->urlFor('welcome'));
            }

            if (!is_uploaded_file($_FILES['upfile']['tmp_name'])) {
                $app->flash('info', 'アップロード失敗 画像が取得できませんでした');
                $app->redirect($app->urlFor('upload_image'));
            }

            $validator = new \Vg\Validator\Upload();
            if (!$validator->validate($input)) {
                $error_message = implode(PHP_EOL, $validator->errors());

                $app->flash('info', $error_message);
                $app->redirect($app->urlFor('upload_image'));
            }
                
            if (!move_uploaded_file($_FILES['upfile']['tmp_name'], __DIR__.'/../../public_html/img/public_img/'.$_FILES['upfile']['name'])) {
                $app->flash('info', 'アップロード失敗');
                $app->redirect($app->urlFor('upload_image'));
            }

            $image = new \Vg\Model\Image();
            $imagefile = [
                "user_id" => ($container['session']->get('user.id')),
                "path"    => "",
                "scope"   => 0,
                "deleted" => 0
            ]; 
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
            $imagefile = [
                "id"      => $image_id,
                "user_id" => ($container['session']->get('user.id')),
                "path"    => "/img/public_img/" . $image_id . "." . $extension,
                "scope"   => 0,
                "deleted" => 0
            ];
            $image->setProperties($imagefile);
            try {
                $repository->update($image);
            } catch (Exception $e) {
                $app->halt(500, $e->getMessage());
            }

            // リネーム処理
            rename(__DIR__.'/../../public_html/img/public_img/'.$_FILES['upfile']['name'],
                   __DIR__."/../../public_html/img/public_img/".$image_id.".".$extension);

            $app->redirect($app->urlFor('add_frame_from_upload', ["image_id" => $image_id]));
        })
        ->name('upload_post')
    ;

    $app->get('/upload/policy', $redirectIfNotLogin($container['session']), function () use ($app,$container) {
            $app->render('upload/policy.html.twig');
        })
        ->name('upload_policy')
    ;
}

