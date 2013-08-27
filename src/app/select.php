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
            $list[$depth][$parent_id]['depth']     = $depth;
            $list[$depth][$parent_id]['parent_id'] = $parent_id;
            $list[$depth][$parent_id]['childs']    = [];
        }
        array_push($list[$depth][$parent_id]['childs'], [
            'id'        => $node['id'],
            'src'       => $node['src']
        ]);
        
        if (array_key_exists('kids', $node)) {
            inner($list, $node['kids'], $depth+1);
        }
    }
}

見る: {
    $app->get('/select', function() use ($app, $container) {
        $input = $app->request()->get();
        if (isset($input['theme_id'])) {
            $theme_id = $input['theme_id'];
        }
        $theme_id = 1;

        $theme = $container['repository.theme']->findById($theme_id);
        $root_id = $theme->frame_id;

        $frame_array = $container['repository.frame']->findsByThemeId($theme_id);
        $image_repository = $container['repository.image'];

        $frames = [];
        foreach ($frame_array as $frame) {
            $temp_frame = [
                'id'        => (INT) $frame->id,
                'parent_id' => (INT) $frame->parent_id,
                'src'       => $image_repository->findByFrameId($frame->id)->path
            ];

            if ($temp_frame['id'] === $root_id) {
                $temp_frame['parent_id'] = 0;
            }
            array_push($frames, $temp_frame);
        }
       
        $frame_tree = list_to_tree($frames);
        $frame_rows = tree_to_list_with_depth($frame_tree);
        $rows_num = count($frame_rows);
        $frame_rows[$rows_num] = [];

        foreach ($frame_rows[$rows_num-1] as $frame_row) {
            foreach ($frame_row['childs'] as $frame) {
                $parent_id = $frame['id'];
                if (!(array_key_exists($parent_id, $frame_rows[$rows_num]))) {
                    $frame_rows[$rows_num][$parent_id] = [];
                    $frame_rows[$rows_num][$parent_id]['childs'] = [];
                }
                $frame_rows[$rows_num][$parent_id]['depth']     = $rows_num;
                $frame_rows[$rows_num][$parent_id]['parent_id'] = $parent_id;
            }
        }
        var_dump($frame_rows);

        $first_frame = $frame_rows[0][0]['childs'][0];
        unset($first_frame[0]);

        $app->render('select/select.html.twig', ['first_frame' => $first_frame, 'frame_rows' => $frame_rows, 'theme_id' => $theme_id]);
        })
        ->name('select')
    ;
}

