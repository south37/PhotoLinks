<?php
/**
 * mypage画面
 */
$app->get('/mypage/:user_id', function($user_id) use ($app, $container) {
    // get top story array
/*
    $user_name = $container['session']->get('user.name');
    $user_id   = $container['session']->get('user.id');

    $repository_image = $container['repository.image'];
    $repository_frame = $container['repository.frame'];
    $repository_story = $container['repository.story'];

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
            $is_users = ($frame->user_id === $user_id);
            array_push($story_array[$rank]['frames'], [
                'caption' => $frame->caption,
                'path' => $image_path,
                'is_users' => $is_users
                ]);
        }
    }
*/

    $story_array = $container['repository.story']->findsPopularJoinedStoriesByUserId($user_id);
    

var_dump($story_array);
    $app->render('mypage/mypage.html.twig',['story_array' => $story_array, 'user_name' => $user_name]);
        }) 
        ->name('mypage')
    ;
