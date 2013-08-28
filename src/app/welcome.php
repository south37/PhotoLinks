<?php
/**
 * top画面 
 */
$app->get('/', function() use ($app, $container) {

    // get top story array
    $repository_story = $container['repository.story'];
    $repository_frame = $container['repository.frame'];
    $repository_image = $container['repository.image'];

    $storyArrayOrderedByFavs = [];
    $lastFrameArrayOrderedByFavs = [];
    $frameArray;

    try{
        // storyの配列
        $storyArrayOrderedByFavs = $repository_story->findsHotStories(0,5);
        // 
        foreach ($storyArrayOrderedByFavs as $story) {
            //var_dump($story);
            //var_dump((INT)($story->id));
            $frames = $repository_frame->findsByStoryId(1);
            var_dump($frames);exit;
            $frame  = $frames[count($frames)-1];
           
            $add_frame = [
                'id'        => (INT) $frame->id,
                'parent_id' => (INT) $frame->parent_id,
                'story_id'  => (INT) $frame->last_story_id,
                'src'       => $repository_image->findByFrameId($frame->id)->path
                ];
            array_push($frameArray, $add_frame);
        }

    }catch(Exception $e){
        $app->halt(500, $e->getMessage());
   
    }
    var_dump($frameArray);

    // rendering
    $app->render('top/index.html.twig',["frameArray"=>$frameArray]);

})
    ->name('welcome')
    ;

/**
 * Demo 用
 */
$app->get('/hello/:name', function ($name) use ($app, $container) {
        // テンプレートでコードを表示するために
        $phpcode = $contents = highlight_file(__FILE__, TRUE);
        $twigcode = file_get_contents($container['twig.templateDir'] . '/top/hello.html.twig');
        $app->render('top/hello.html.twig', ['name' => $name, 'phpcode' => $phpcode, 'twigcode' => $twigcode]);
    })
    ->name('hello')
    ->conditions(array('name' => '\w+'))
;
