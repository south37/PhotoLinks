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
$app->get('/add_frame', function() use ($app,$container) {
    //addressを無理やり入れられた時の対策をしましょう


    //

    $input = $app->request()->get();
    var_dump($input);

    $container['session']->set('theme_id',1);
    $container['session']->set('parent_id',$input['parent-id']);

    $image_id = -1;
    $parent_id = $input['parent-id'];
    $imgPath = "/img/ismTest/placeholder.png";

    $app->render('add_frame/add_frame.html.twig',
        ["image_id" => $image_id,
        "parent_id" => $parent_id,
        "imgPath" => $imgPath]);
})
    ->name('add_frame_from_select');

// geti_from_view5
$app->get('/add_frame/:image_id', function($image_id) use ($app,$container) {
    $input = $app->request()->get();
    $parent_id = $container['session']->get('parent_id');

    $repository = $container['repository.image'];
    try{
        $img = $repository->findById($image_id);
    } catch (Exception $e) {
        $app->halt(500, $e->getMessage());
    }

    $app->render('add_frame/add_frame.html.twig',
        ["image_id" => $image_id,
        "parent_id" => $parent_id,
        "imgPath" => $img->path]);
    })
    ->name('add_frame_from_upload')
    ;

// push_make_frame 
$app->post('/add_frame/make_frame', function() use ($app,$container) { // form情報を取得 
    // make data
    $input = $app->request()->post();
    $input['last_story_id'] = 0;
    $input['user_id'] = $container['session']->get('user.id');
    $input['theme_id'] = $container['session']->get('theme_id');

    var_dump($input);

    // validation
    $validator = new \Vg\Validator\AddFrame();

    if (!$validator->validate($input)){
        $app->render('add_frame/add_frame.html.twig',['errors'=> $validator->errors(),
            'image_id'=>$input['image_id'],'parent_id'=>$input['parent_id']]);
        exit;
    }

    // makeFrame + addDB 
    if (!makeFrame($input,$app,$container)){
        exit;
    }

    $app->redirect($app->urlFor('select'));

})->name('make_frame');

// ストーリーを作成 
$app->post('/add_frame/make_story', function() use ($app,$container) {
    // make data 
    $input = $app->request()->post();
    $input['last_story_id'] = 0;
    $input['user_id'] = $container['session']->get('user.id');
    $input['theme_id'] = $container['session']->get('theme_id');

    var_dump($input);

    // validation
    $validator_frame = new  \Vg\Validator\AddFrame();
    $validator_story = new  \Vg\Validator\AddStory();

    if (!$validator_story->validate($input)){
        $app->render('add_frame/add_frame.html.twig',['errors'=> $validator_story->errors(),
            'image_id'=>$input['image_id'],'parent_id'=>$input['parent_id']]);
        exit;
    }

    // makeStory + addDB
    if($storyId = makeStory($input,$app,$container) < 0){
        exit;
    }

    $input['last_story_id'] = $storyId; 

    if (!$validator_frame->validate($input)){
        $app->render('add_frame/add_frame.html.twig',['errors'=> $validator_frame->errors(),
            'image_id'=>$input['image_id'],'parent_id'=>$input['parent_id']]);
        exit;
    }

    // makeFrame + addDB 
    if ($lastFrameId = makeFrame($input,$app,$container) < 0){
        exit;
    }

    // connect frame to story
    if (!connectFramesToStory($lastFrameId,$storyId,$app,$container)){
        exit;
    }

    $app->redirect($app->urlFor('select'));
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
        return $newStoryId;
    } catch (Exception $e) {
        $app->halt(500, $e->getMessage());
        return -1;
    }
};

// story_frame関連付け
function connectFramesToStory($lastFrameId, $storyId, $app, $c){
    $repository_Frame = $c['repository.frame'];
    $repository_FrameStory = $c['repository.story_frame'];

    try{
        $tmpFrame = $repository_Frame->findById($lastFrameId);

        while($tmpFrame->parent_id){
            $tmpConnect = ['frame_id' => $tmpFrame->id, 'story_id' => $storyId];
            $repository_FrameStory->insert($tmpConnect);
            $tmpFrame = $repository_Frame->findById($tmpFrame->parent_id);
        }
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
