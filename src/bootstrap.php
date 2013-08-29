<?php
/**
 * このファイルはアプリケーションで利用するライブラリや設定項目などを定義する場所
 */
require __DIR__ .'/../vendor/autoload.php';

$container = include __DIR__ . '/config.php';

// Twig
$container['twig'] = $container->protect(function($request, $router) use ($container) {
        \Slim\Extras\Views\Twig::$twigTemplateDirs = $container['twig.templateDir'];

        $twig = new \Slim\Extras\Views\Twig;
        $env = $twig->getEnvironment();
        // asset function
        $env->addFunction(new Twig_SimpleFunction('asset', function ($path) use ($request) {
                return $request->getRootUri() . '/' .  trim($path, '/');
            }));
        // urlFor function
        $env->addFunction(new Twig_SimpleFunction('urlFor', function ($name, $params = []) use ($router) {
                return $router->urlFor($name, $params);
            }));
        // debug
        $env->addFunction(new Twig_SimpleFunction('debug', function (){
                echo "<pre>";
                debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
                echo "</pre>";
            }));
        // global session
        $env->addGlobal('session', $container['session']);

        return $twig;
    });

// データベース PDO
$container['db'] = $container->share(function($c){
        return \Vg\Database::connection($c['db.host'], $c['db.database'], $c['db.user'], $c['db.password']);
    });

// セッション
$container['session'] = $container->share(function() {
        return new \Vg\Session();
    });

// ユーザーリポジトリ
$container['repository.user'] = $container->share(function($c){
        return new \Vg\Repository\UserRepository($c['db']);
    });

// テーマリポジトリ
$container['repository.theme'] = $container->share(function($c){
        return new \Vg\Repository\ThemeRepository($c['db']);
    });

// イメージリポジトリ
$container['repository.image'] = $container->share(function($c){
        return new \Vg\Repository\ImageRepository($c['db']);
    });

// フレームリポジトリ
$container['repository.frame'] = $container->share(function($c){
        return new \Vg\Repository\FrameRepository($c['db']);
    });

// ストーリリポジトリ
$container['repository.story'] = $container->share(function($c){
        return new \Vg\Repository\StoryRepository($c['db']);
});

// フレーム・ストーリリポジトリ
$container['repository.frame_story'] = $container->share(function($c){
        return new \Vg\Repository\FrameStoryRepository($c['db']);
});

// ストーリリポジトリ
$container['repository.liked'] = $container->share(function($c){
        return new \Vg\Repository\LikedRepository($c['db']);
});






return $container;
