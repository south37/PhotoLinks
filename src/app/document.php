<?php
ドキュメント: {
    $app->get('/doc', function() use ($app) {
            $app->render('doc/index.html.twig');
        })
        ->name('doc_top')
    ;

    $app->get('/doc/basic', function() use ($app) {
            $markdown = file_get_contents(__DIR__. '/../../docs/basic.md');
            $app->render('doc/markdown.html.twig', ['markdown' => $markdown]);
        })
        ->name('doc_basic')
    ;

    $app->get('/doc/extends', function() use ($app) {
            $markdown = file_get_contents(__DIR__. '/../../docs/extend.md');
            $app->render('doc/markdown.html.twig', ['markdown' => $markdown]);
        })
        ->name('doc_extend')
    ;
}

課題: {
    $app->get('/study/:id', function($id) use ($app) {
            $filePath = sprintf('%s/../../docs/study/%02d.md', __DIR__ , $id);
            if (file_exists($filePath)) {
                $markdown = file_get_contents($filePath);
            } else {
                $app->halt('404', 'ページが見つかりません');
            }
            $app->render('study/markdown.html.twig', ['markdown' => $markdown]);
        })
    ;
}
