<?php
namespace Vg\Model;

class Frame 
{
    public $id;
    public $user_id;
    public $theme_id;
    public $image_id;
    public $parent_id;
    public $last_story_id;
    public $caption;
    

    public function __construct()
    {
    }

    public function setProperties($data)
    {
        foreach (array('id', 'user_id', 'theme_id', 'image_id', 'parent_id', 'last_story_id', 'caption') as $property) {
            $this->{$property} = (isset($data[$property]))? $data[$property]: "";
        }
    }
}
