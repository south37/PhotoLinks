<?php
/**
 * story_view controller
 */

    // 画像PathとCaptionをDBから取得
    function select_frame_data_list($container,$frameList) {

        $frameDataList = [];

	for($i = 0; $i < count($frameList); $i++){
        $tmpFrame = $container['repository.frame']->findById($frameList[$i]);
        // theme_idを取得
        $theme_id = $tmpFrame->theme_id;

	    $tmpImage = $container['repository.image']->findByFrameId($tmpFrame->id);
            $tmpCaption = $container['repository.frame']->findById($frameList[$i]);
            $tmpFrameData = array("image"=>$tmpImage->path,"caption"=>$tmpCaption->caption); 
            array_push($frameDataList,$tmpFrameData);
	}
        return [$frameDataList, $theme_id];
    }
   

    // Topページからの遷移
    $app->get('/story_view/story/:story_id',function($storyId) use($app, $container){
       $tmpStory = $container['repository.story']->findByID($storyId);
       if (!$tmpStory->id) {
           $app->flash('info', 'そのページは存在しません');
           $app->redirect($app->urlFor('welcome'));
       } else {
           $storyTitle = $tmpStory->title;
           $tmpUser = $container['repository.user']->findById($tmpStory->user_id);
           // 各種SNSへのShareのために、GETパラメータを含めたURL、タイトル
           $shareUrl = '/story_view/story/'. $storyId;
           $shareTitle = "PhotoLinks";
           $tmpFrameStories = $container['repository.frame']->findsByStoryId($storyId);
           $frameList = []; 
           foreach( $tmpFrameStories as $tmpFrameStory){
               array_push($frameList,$tmpFrameStory->id);
           }
           $userId = $container['session']->get('user.id');
           $liked = $container['repository.liked']->isSameLikedUser($storyId,$userId);
           $favNum = $container['repository.liked']->getNumberOfLikedByStoryId($storyId);

           $tmp = select_frame_data_list($container, $frameList);
           $frameDataList = $tmp[0];
           $theme_id = $tmp[1];
           $app->render('story_view/story_view.html.twig',
                ["userId"=>$userId,"storyId"=>$storyId,"storyTitle"=>$storyTitle,"liked"=>$liked,"favNum"=>$favNum,"frameDataList"=>$frameDataList, 'theme_id' => $theme_id,
                "shareURL" => $shareUrl, "shareTitle" => $shareTitle]);  
        }
    }) ->name('story_view_story')
    ;

    // Selectページからの遷移
    $app->get('/story_view/frames',function() use($app, $container){
    $input = $app->request()->get();
	// todo intのエラー処理！


	//postでframe_idをカンマ区切りで取得．
	$frameListStr = $input["selected-frames-id"];
	$frameList = explode(',',$frameListStr);
        $tmpFrame =  $container['repository.frame']->findById($frameList[count($frameList)-1]);
        $storyId = $tmpFrame->last_story_id;
        if ($storyId) {
             $userId = $container['session']->get('user.id');
             $tmpStory = $container['repository.story']->findByID($storyId);
             $storyTitle = $tmpStory->title;
             $liked = $container['repository.liked']->isSameLikedUser($storyId,$userId);
             $favNum = $container['repository.liked']->getNumberOfLikedByStoryId($storyId);
        } else {
            $userId = NULL;
            $storyTitle = NULL;
            $liked = false;
            $favNum = NULL;
        }
        $tmp = select_frame_data_list($container, $frameList);
        $frameDataList = $tmp[0];
        $theme_id = $tmp[1];
    $app->render('story_view/story_view.html.twig',["userId"=>$userId,"storyId"=>$storyId,"liked"=>$liked,"favNum"=>$favNum,"storyTitle"=>$storyTitle,"frameDataList"=>$frameDataList, "theme_id" => $theme_id]);
    })  ->name('story_view_frames')
    ;

    // いいね機能
    $app->post('/story_view/favorite/:story_id',function($storyId) use($app,$container){
        $userId = $container['session']->get('user.id');
        $favorite = $container['repository.liked']->incrementFavorite($storyId, $userId);
        $favNum = $container['repository.liked']->getNumberOfLikedByStoryId($storyId);
        print $favNum;
    }) ->name('story_view_favorite')
    ;
