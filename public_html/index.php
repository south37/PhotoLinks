<?php
$container = include __DIR__ .'/../src/bootstrap.php';

use Slim\Slim;


アプリケーションの構築: {
    $app = new Slim();
    $app->view($container['twig']($app->request(), $app->router()));
}

各コントローラーの読み込み: {
    // コントローラーを増やす場合はここにrequireでコントローラーへのパスを追加する
    require  __DIR__ . '/../src/app/welcome.php';
    require  __DIR__ . '/../src/app/document.php';
    require  __DIR__ . '/../src/app/user.php';

    // add by momoe
    require  __DIR__ . '/../src/app/upload.php';

    // add controller by 1000
    require  __DIR__ . '/../src/app/story_view.php';

    // add by nantyoku
    require  __DIR__ . '/../src/app/select.php';
    require  __DIR__ . '/../src/app/material.php';
    require  __DIR__ . '/../src/app/others.php';

    // add view4 controller
    require  __DIR__ . '/../src/app/add_frame.php';
}

アプリケーションの実行: {
    $app->run();
}

