<?php
/**
 * mypage画面
 */
/*
$app->get('/mypage/:user_id', function($user_id) use ($app, $container) {
    // get top story array
    $user_name = $container['session']->get('user.name');

    $repository_story = $container['repository.story'];
    $stories = $repository_story->findsByUserId($user_id, 0);
    $story_array = [];
    foreach ($stories as $story) {
        $story_id   = $story->id;
        $storyTitle = $story->title;

        $tmpFrameStories = $container['repository.frame']->findsByStoryId($story_id);
        $frameIdList = []; 
        foreach( $tmpFrameStories as $tmpFrameStory){
            array_push($frameIdList, $tmpFrameStory->id);
        }
        $frameList = select_frame_data_list($container, $frameIdList);
        array_push($story_array, [
            'title'      => $storyTitle,
            'frame_list' => $frameList
            ]);
    }

    $app->render('mypage/mypage.html.twig',['story_array' => $story_array, 'user_name' => $user_name]);
        }) 
        ->name('mypage')
    ;
 */

$app->get('/mypage/:user_id', function($user_id) use ($app, $container) {
    // get top story array
    $user_name = $container['session']->get('user.name');

    $repository_image = $container['repository.image'];
    $repository_frame = $container['repository.frame'];
    $stories = $repository_frame->findsFramesEachStoryByUserId($user_id);
    $storyFrames = $stories['frames'];
    $storyTitles = $stories['titles'];
    
    $story_array = [];
    foreach ($storyFrames as $rank => $frames) {
        $story_array[$rank] = [
            'title'  => $storyTitles[$rank],
            'frames' => []
        ];
        
        foreach ($frames as $frame) {
            $image = $repository_image->findById($frame->image_id);
            $image_path = $image->path;
            $frame->image_path = $image_path;
            array_push($story_array[$rank]['frames'], [
                'caption' => $frame->caption,
                'path' => $image_path
                ]);
        }
    }
    $app->render('mypage/mypage.html.twig',['story_array' => $story_array, 'user_name' => $user_name]);
        }) 
        ->name('mypage')
    ;
