<?php
/**
 * story_view controller
 */

    // 画像PathとCaptionをDBから取得
    function select_frame_data_list($container,$frameList) {

        $frameDataList = [];

	for($i = 0; $i < count($frameList); $i++){
	    $tmpFrame = $container['repository.frame']->findById($frameList[$i]);
	    $tmpImage = $container['repository.image']->findByFrameId($tmpFrame->id);
            $tmpCaption = $container['repository.frame']->findById($frameList[$i]);
            $tmpFrameData = array("image"=>$tmpImage->path,"caption"=>$tmpCaption->caption); 
            array_push($frameDataList,$tmpFrameData);
	}

        return $frameDataList;
    }
   

    // Topページからの遷移
    // $app->get('/story_view/story/:story_id',function($storyId) use($app, $container){
    $app->get('/story_view/story',function() use($app, $container){
       //  $input = $app->request()->get();
       $storyId = 1;
       $tmpStory = $container['repository.story']->findByID($storyId);
       $storyTitle = $tmpStory->title;
       $tmpUser = $container['repository.user']->findById($tmpStory->user_id);
       $namedUserName = $tmpUser->name;
       // 各種SNSへのShareのために、GETパラメータを含めたURL、タイトル
       $shareUrl = '/story_view/story/story_id='. $storyId;
       $shareTitle = "Photo Story（仮）";

       $tmpFrameStories = $container['repository.frame']->findsByStoryId($storyId);
       $frameList = []; 
       foreach( $tmpFrameStories as $tmpFrameStory){
           array_push($frameList,$tmpFrameStory->id);
       }
       $app->render('story_view/story_view.html.twig',
            ["storyTitle"=>$storyTitle,"namedUserName"=>$namedUserName,"frameDataList"=>select_frame_data_list($container,$frameList),
            "shareURL" => $shareUrl, "shareTitle" => $shareTitle]);  
    }) ->name('story_view_story')
    ;

    // Selectページからの遷移
    $app->get('/story_view/frames',function() use($app, $container){
	$input = $app->request()->get();
	var_dump($input);
	// todo intのエラー処理！


	//postでframe_idをカンマ区切りで取得．
	$frameListStr = $input["selected-frames-id"];
	$frameList = explode(',',$frameListStr);

	$app->render('story_view/story_view.html.twig',["frameDataList"=>select_frame_data_list($container,$frameList)]);
    })  ->name('story_view_frames')
    ;
