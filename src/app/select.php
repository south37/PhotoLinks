<?php
use Respect\Validation\Validator as v;

function list_to_tree($list) {
   if (! is_array($list)){
       return false;
   }
   $tree  = [];
   $index = [];

   foreach($list as $value){
       $id  = $value['id'];
       $pid = $value['parent_id'];

       if (isset($index[$id])){
           $value["kids"] = $index[$id]["kids"];
           $index[$id] = $value;
       } else {
           $index[$id] = $value;
       }

       if ($pid == 0){
           $tree[] = & $index[$id];
       } else {
           $index[$pid]["kids"][] = & $index[$id];
       }
   }
   return $tree;
}

function tree_to_list_with_depth($tree) {
    $list  = [];
    $depth = 0;
    inner($list, $tree, $depth);
    return $list;
}

function inner(&$list, $tree, $depth) {
    foreach ($tree as $node) {
        if (!array_key_exists($depth, $list)) {
            $list[$depth] = [];
        }
        array_push($list[$depth], [
            'id'  => $node['id'],
            'src' => $node['src']
        ]);
        
        if (array_key_exists('kids', $node)) {
            inner($list, $node['kids'], $depth+1);
        }
    }
}

見る: {
    $app->get('/select', function() use ($app, $container) {
        $theme_id = 1;

        $theme_repository = $container['repository.theme'];
        $theme = $theme_repository->findById($theme_id);
        $root_id = $theme->frame_id;

        $frame_repository = $container['repository.frame'];
        $frames = $frame_repository->findsByThemeId($theme_id);

//        $image_repository = $container['repository.image'];

        $frames = [
            ['id' => 1, 'parent_id' => 0, 'src' => '/img/ismTest/hatsune01.png'],
            ['id' => 2, 'parent_id' => 1, 'src' => '/img/ismTest/hatsune02.png'],
            ['id' => 3, 'parent_id' => 1, 'src' => '/img/plus.png'],
            ['id' => 4, 'parent_id' => 2, 'src' => '/img/plus.png'],
            ['id' => 5, 'parent_id' => 3, 'src' => '/img/plus.png'] 
        ];

        $frame_tree = list_to_tree($frames);
        $frame_rows = tree_to_list_with_depth($frame_tree);
        array_push($frame_rows, []);

        $first_frame = $frame_rows[0][0];
        unset($frame_rows[0]);

        $app->render('select/select.html.twig', ['first_frame' => $first_frame, 'frame_rows' => $frame_rows]);
        })
        ->name('select')
    ;
}

