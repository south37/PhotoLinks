<?php
/**
 * view4:add_frame controller
 *
 * 2-4 : get: parent_frame_id
 * 5-4 : get: image_id
 * 6-4 : get: image_id
 * 
 * 
 */

use Respect\Validation\Validator as v;
 
// get_from_view2
$app->get('/add_frame', $redirectIfNotLogin($container['session']), function() use ($app,$container) {
    $input = $app->request()->get();
    if ($input === []) {
        $app->redirect($app->urlFor('welcome'));
    }

    $container['session']->set('theme_id',  $input['theme-id']);
    $container['session']->set('frame_ids', $input['frame-ids']);

    $frameList = explode(',', $input['frame-ids']);
    $is_last_frame = (count($frameList) > 2);
    $container['session']->set('is_last_frame', $is_last_frame);
    $parent_id = $frameList[count($frameList)-1];

    $frame_repository = $container['repository.frame'];
    $image_repository = $container['repository.image'];
    $frames = [];
    foreach($frameList as $frame_id) {
        $frame = $frame_repository->findById($frame_id);
        $image = $image_repository->findById($frame->image_id);
        array_push($frames, ['path' => $image->path, 'caption' => $frame->caption]);
    }

    $image_id = -1;
    $imgPath = "/img/public_img/200x200.jpg";

    $token = $container['session']->id();
    $app->render('add_frame/add_frame.html.twig',
        ["image_id"  => $image_id,
         "parent_id" => $parent_id,
         "token"     => $token,
         "imgPath"   => $imgPath,
         "is_last_frame" => $is_last_frame,
         "frames"    => $frames,
         ]);
})
    ->name('add_frame_from_select');

// geti_from_view5
$app->get('/add_frame/:image_id', $redirectIfNotLogin($container['session']), function($image_id) use ($app,$container) {
    $input = $app->request()->get();
    $is_last_frame = $container['session']->get('is_last_frame');

    $frameListStr = $container['session']->get('frame_ids');
    $frameList = explode(',',$frameListStr);
    $parent_id = $frameList[count($frameList) -1]; 

    $repository = $container['repository.image'];
    try{
        $img = $repository->findById($image_id);
    } catch (Exception $e) {
        $app->halt(500, $e->getMessage());
    }

    $frame_repository = $container['repository.frame'];
    $image_repository = $container['repository.image'];
    $frames = [];
    foreach($frameList as $frame_id) {
        $frame = $frame_repository->findById($frame_id);
        $image = $image_repository->findById($frame->image_id);
        array_push($frames, ['path' => $image->path, 'caption' => $frame->caption]);
    }
    
    $token = $container['session']->id();
    $app->render('add_frame/add_frame.html.twig',
        ["image_id" => $image_id,
         "parent_id" => $parent_id,
         "token" => $token,
         "imgPath" => $img->path,
         "is_last_frame" => $is_last_frame,
         "frames"  => $frames]);
    })
    ->name('add_frame_from_upload')
    
    ;

// push_make_frame 
$app->post('/add_frame/make_frame', $redirectIfNotLogin($container['session']), function() use ($app,$container) { // form情報を取得 
    // make data
    $input = $app->request()->post();
    $input['last_story_id'] = 0;
    $input['user_id'] = $container['session']->get('user.id');
    $input['theme_id'] = $container['session']->get('theme_id');

    // CSRF対策
    if($input['token'] != $container['session']->id())
    {
        $app->flash('info', '画面遷移に失敗しました');
        $app->redirect($app->urlFor('welcome'));
    }

    // validation
    $validator = new \Vg\Validator\AddFrame();

    $token = $container['session']->id();
    if (!$validator->validate($input)){
        // 左のコマ 
        $frameListStr = $container['session']->get('frame_ids');
        $frameList = explode(',', $frameListStr);
        $frame_repository = $container['repository.frame'];
        $image_repository = $container['repository.image'];
        $frames = [];
        foreach($frameList as $frame_id) {
            $frame = $frame_repository->findById($frame_id);
            $image = $image_repository->findById($frame->image_id);
            array_push($frames, ['path' => $image->path, 'caption' => $frame->caption]);
        }

        $imgPath = "/img/public_img/200x200.jpg";
        $app->render('add_frame/add_frame.html.twig',
            ['errors'=> $validator->errors(),
             'image_id'=>$input['image_id'],
             'imgPath' => $imgPath,
             'token' => $token,
             'parent_id'=>$input['parent_id'],
             'frames' => $frames
            ]);
        exit;
    }

    // makeFrame + addDB 
    if (!makeFrame($input,$app,$container)){
        exit;
    }

    $app->redirect($app->urlFor('select', ['theme_id' => $container['session']->get('theme_id')]));

})->name('make_frame');

// ストーリーを作成 
$app->post('/add_frame/make_story', $redirectIfNotLogin($container['session']), function() use ($app,$container) {
    // make data 
    $input = $app->request()->post();
    $input['last_story_id'] = 0;
    $input['user_id'] = $container['session']->get('user.id');
    $input['theme_id'] = $container['session']->get('theme_id');


    // CSRF対策
    if($input['token'] != $container['session']->id())
    {
        $app->flash('info', '画面遷移に失敗しました');
        $app->redirect($app->urlFor('welcome'));
    }

    // validation
    $validator_frame = new  \Vg\Validator\AddFrame();
    $validator_story = new  \Vg\Validator\AddStory();
    
    // 左のコマ
    $frameListStr = $container['session']->get('frame_ids');
    $frameList = explode(',', $frameListStr);
    $frame_repository = $container['repository.frame'];
    $image_repository = $container['repository.image'];
    $frames = [];
    foreach($frameList as $frame_id) {
        $frame = $frame_repository->findById($frame_id);
        $image = $image_repository->findById($frame->image_id);
        array_push($frames, ['path' => $image->path, 'caption' => $frame->caption]);
    }
    $imgPath = "/img/public_img/200x200.jpg";

    $token = $container['session']->id();
    if (!$validator_story->validate($input)){

        $app->render('add_frame/add_frame.html.twig',
            ['errors'=> $validator_story->errors(),
            'image_id'=>$input['image_id'],
            'imgPah' => $imgPath,
            'token' => $token,
            'parent_id'=>$input['parent_id'],
            'frames' => $frames]
        );
        exit;
    }

    if (!$validator_frame->validate($input)){
        $app->render('add_frame/add_frame.html.twig',
            ['errors'=> $validator_frame->errors(),
            'image_id'=>$input['image_id'],
            'imgPath' => $imgPath,
            'token' => $token,
            'parent_id'=>$input['parent_id'],
            'frames' => $frames]
        );
        exit;
    }
 
    // makeStory + addDB

    if(($storyId = makeStory($input,$app,$container)) < 0){
        exit;
    }

    $input['last_story_id'] = $storyId; 
   
    // makeFrame + addDB 
    if (($lastFrameId = makeFrame($input,$app,$container)) < 0){
        exit;
    }

    echo $lastFrameId;

    // connect frame to story
    if (!connectFramesToStory($lastFrameId,$storyId,$app,$container)){
        exit;
    }

    $app->redirect($app->urlFor('select', ['theme_id' => $container['session']->get('theme_id')]));
})->name('make_story');

//フレーム追加
function makeFrame($property,$app,$c){
    $frame = new \Vg\Model\Frame();
    $frame->setProperties($property);
    $repository = $c['repository.frame'];
    
    try {
        $newFrameId = $repository->insert($frame);
        return $newFrameId;
    } catch (Exception $e) {
        $app->halt(500, $e->getMessage());
        return -1;
    }
};

// ストーリ追加
function makeStory($property,$app,$c){
    $story = new \Vg\Model\Story();
    $story->setProperties($property);
    $repository = $c['repository.story'];
    try {
        $newStoryId = $repository->insert($story);
        echo "newID:".$newStoryId;
        return $newStoryId;
    } catch (Exception $e) {
        $app->halt(500, $e->getMessage());
        return -1;
    }
};

// story_frame関連付け
function connectFramesToStory($lastFrameId, $storyId, $app, $c){
    $repository_Frame = $c['repository.frame'];
    $repository_FrameStory = $c['repository.frame_story'];

    try{
        echo $lastFrameId;
        $tmpFrame = $repository_Frame->findById($lastFrameId);
        echo "tmpFrameId:" . $tmpFrame->id . "<br>";
       
        while($tmpFrame->parent_id){
            $tmpConnect = new \Vg\Model\FrameStory;
            $tmpConnectProperties = ['frame_id' => $tmpFrame->id, 'story_id' => $storyId];
            $tmpConnect->setProperties($tmpConnectProperties);
             
            $repository_FrameStory->insert($tmpConnect);
            $tmpFrame = $repository_Frame->findById($tmpFrame->parent_id);
        }

        $tmpConnect = new \Vg\Model\FrameStory;
        $tmpConnectProperties = ['frame_id' => $tmpFrame->id, 'story_id' => $storyId];
        $tmpConnect->setProperties($tmpConnectProperties);
         
        $repository_FrameStory->insert($tmpConnect);


        return true;

    } catch (Exception $e){
        $app->halt(500, $e->getMessage());
        
        return false;
    }
}

// 確認画面
$app->get('/add_frame/confirm',function() use ($app){
    $app->render('add_frame/add_frame_confirm.html.twig');
})->name('add_frame_confirm');
