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


見る: {
    $app->get('/select', function() use ($app, $container) {
        $theme_id = 1;

        $first_frame = [
            'src' => 'http://192.168.56.110:18003/img/plus.png',
            'id'  => 1
        ];

        $frame_rows = [
            [
                ['src' => 'http://192.168.56.110:18003/img/plus.png',
                 'id'  => 2],
                ['src' => 'http://192.168.56.110:18003/img/plus.png',
                 'id'  => 3]
            ],
            [
                ['src' => 'http://192.168.56.110:18003/img/plus.png',
                 'id'  => 4],
                ['src' => 'http://192.168.56.110:18003/img/plus.png',
                 'id'  => 5]
            ],
        ];

//        $theme_repository = $container['repository.theme'];
//        $theme = $theme_repository->findById($theme_id);
//        $root_id = $theme->root_id;
        $root_id = 1;

//        $frame_repository = $container['repository.frame'];
//        $frames = $frame_repository->findByThemeId($theme_id);

//        $image_repository = $container['repository.image'];

//        foreach ($frames as $key => $frame) {
//            if ($frame->id === $root_id) {
//                $image = $image_repository->findById($frame->image_id);
//                $first_frame = [
//                    'src' => $image->path,
//                    'id'  => $frame->id
//                ];
//                unset($frames[$key]);
//            }
//        }
//

        $test_data = [
            ['id' => 1, 'parent_id' => 0],
            ['id' => 2, 'parent_id' => 1],
            ['id' => 3, 'parent_id' => 1]
        ];

        $tree = list_to_tree($test_data);
        var_dump($tree);

        array_push($frame_rows, []);
        $app->render('select/select.html.twig', ['first_frame' => $first_frame, 'frame_rows' => $frame_rows]);
        })
        ->name('select')
    ;
}

