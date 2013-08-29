<?php
/**
 * top画面 
 */
$app->get('/', function() use ($app, $container) {

    // get top story array
    
    $repository_story = $container['repository.story'];
    $repository_frame = $container['repository.frame'];
    $repository_image = $container['repository.image'];
    $repository_liked = $container['repository.liked'];

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
            array_push($frameArray, $add_frame);
        }

    }catch(Exception $e){
        $app->halt(500, $e->getMessage());

    }

    $repository_theme = $container['repository.theme'];
    try{
        $recentThemes = $repository_theme->findsRecentThemes(0);
        $themeArray = [];
        foreach ($recentThemes as $theme) {
            $first_frame = $repository_frame->findById($theme->frame_id);
            $first_image = $repository_image->findById($first_frame->image_id);
            array_push($themeArray, ['id' => $theme->id, 'image_path' => $first_image->path]);
        }
    }catch(Exception $e){
        $app->halt(500, $e->getMessage());

    }

    try{
        // storyの配列
        $recentStories= $repository_story->findsRecentStory(0);
        $storyArray   = []; 
        foreach ($recentStories as $no => $story) {
            $storyArray[$no] = [];
            $storyArray[$no]['id'] = $story->id;
            $storyArray[$no]['title']  = $story->title;
            $storyArray[$no]['frames'] = [];
            // $favNums = $repository_favNum->getNumberOfLikedByStoryId((INT)($story->id));
            $storyArray[$no]['favNum'] = $repository_liked->getNumberOfLikedByStoryId((INT)($story->id));
            $frames = $repository_frame->findsByStoryId((INT)($story->id));
            foreach ($frames as $frame) {
                $image = $repository_image->findById($frame->image_id);
                array_push($storyArray[$no]['frames'], [
                    'caption' => $frame->caption,
                    'path'    => $image->path
                    ]);
            }

        }
var_dump($storyArray);

    }catch(Exception $e){
        $app->halt(500, $e->getMessage());

    }

    // rendering
    $app->render('top/index.html.twig',["frameArray"=>$frameArray, "themeArray" => $themeArray, "storyArray" => $storyArray]);
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
