<?php
/**
 * mypage画面
 */
$app->get('/mypage/:user_id', function($user_id) use ($app, $container) {
    // get top story array
    $userName = $container['session']->get('user.name');
    $userId   = $container['session']->get('user.id');
/*
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
    $repository_image = $container['repository.image'];
    $repository_frame = $container['repository.frame'];
    $repository_story = $container['repository.story'];
    $repository_liked = $container['repository.liked'];

    $stories = $repository_story->findsPopularJoinedStoryByUserId($userId);
    $storyArray = [];
    $rank = 0;
    foreach ($stories as $story) {
            $rank = $rank+1;
            $favNum = $repository_liked->getNumberOfLikedByStoryId(($story->id));
            $frames = [];
            $tmpFrames = $repository_frame->findsByStoryId($story->id);
            foreach ($tmpFrames as $tmpFrame) {
                $imagePath = $repository_image->findById($tmpFrame->image_id);
                $is_users = ($tmpFrame->user_id === $userId);
                array_push($frames,array("path"=>$imagePath->path,"caption"=>$tmpFrame->caption,"is_users"=>$is_users));
            }
            $storyArray += array(array("rank"=>$rank,"storyTitle"=>$story->title,"favNum"=>$favNum,"frames"=>$frames));
    }
var_dump($storyArray);
    $app->render('mypage/mypage.html.twig',['storyArray' => $storyArray, 'userName' => $userName]);
        }) 
        ->name('mypage')
    ;
