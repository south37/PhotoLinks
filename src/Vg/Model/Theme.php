<?php
namespace Vg\Model;

class Theme 
{
    public $id;
    public $user_id;
    public $frame_id;
    public $title;
    public $fix_num;
    public $frame_num;

    public function __construct()
    {
    }

    public function setProperties($data)
    {
        foreach (array('id', 'user_id', 'frame_id', 'title', 'fix_num', 'frame_num') as $property) {
            $this->{$property} = (isset($data[$property]))? $data[$property]: "";
        }
    }
}
