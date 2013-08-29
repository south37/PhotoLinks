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
    $frameArray = [];

    try{
        // storyの配列
        $storyArrayOrderedByFavs = $repository_story->findsHotStories(0,5);
        // 
        foreach ($storyArrayOrderedByFavs as $story) {
            $frames = $repository_frame->findsByStoryId((INT)($story->id));
            if (!isset($frames[0])) {
                continue;
            }
            $frame  = $frames[0];
           
            $add_frame = [
                'id'        => (INT) $frame->id,
                'parent_id' => (INT) $frame->parent_id,
                'story_id'  => (INT) $frame->last_story_id,
                'caption'   => $story->title,
                'src'       => $repository_image->findByFrameId($frame->id)->path
                ];
           echo $add_frame['src'];
            array_push($frameArray, $add_frame);
var_dump($add_frame);
echo"\n\n";
        }

    }catch(Exception $e){
        $app->halt(500, $e->getMessage());
   
    }
    
    // rendering
    $app->render('top/index.html.twig',["frameArray"=>$frameArray]);
    //$app->render('top/index.html.twig');
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
