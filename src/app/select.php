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
        
        $parent_id = $node['parent_id'];
        if (!array_key_exists($parent_id, $list[$depth])) {
            $list[$depth][$parent_id] = [];
        }
        $num = array_push($list[$depth][$parent_id], [
            'id'        => $node['id'],
            'src'       => $node['src'],
            'caption'   => $node['caption'],
            'is_grandchild_exists' => true
        ]);
        
        if (array_key_exists('kids', $node)) {
            inner($list, $node['kids'], $depth+1);
        } else {
            $list[$depth][$parent_id][$num-1]['is_grandchild_exists'] = false;
        }
    }
}

見る: {
    $app->get('/select/:theme_id', function($theme_id) use ($app, $container) {
        $input = $app->request()->get();

        $container['session']->set('theme_id', $theme_id);
        $theme = $container['repository.theme']->findById($theme_id);
        $root_id = $theme->frame_id;

        $frame_array = $container['repository.frame']->findsByThemeId($theme_id);
        $image_repository = $container['repository.image'];

        $frames = [];
        foreach ($frame_array as $frame) {
            $temp_frame = [
                'id'        => (INT) $frame->id,
                'parent_id' => (INT) $frame->parent_id,
                'caption'   => $frame->caption,
                'src'       => $image_repository->findByFrameId($frame->id)->path
            ];

            if ($temp_frame['id'] === $root_id) {
                $temp_frame['parent_id'] = 0;
            }
            array_push($frames, $temp_frame);
        }
       
        $frame_tree = list_to_tree($frames);
        $frame_rows = tree_to_list_with_depth($frame_tree);
        
        foreach ($frame_rows as $depth => $frame_row) {
            foreach ($frame_row as $frames) {
                foreach ($frames as $frame) {
                    if (!isset($frame_rows[$depth+1])) {
                        $frame_rows[$depth+1] = [];
                    }
                    $id = $frame['id'];
                    if (!isset($frame_rows[$depth+1][$id])) {
                        $frame_rows[$depth+1][$id] = [];
                    }
                }
            }
        }

        $app->render('select/select.html.twig', ['frame_rows' => $frame_rows, 'theme_id' => $theme_id]);
        })
        ->name('select')
    ;
}

