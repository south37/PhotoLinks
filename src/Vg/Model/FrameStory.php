<?php
namespace Vg\Model;

class FrameStory 
{
    public $id;
    public $frame_id;
    public $story_id;

    public function __construct()
    {
    }

    public function setProperties($data)
    {
        foreach (array('id', 'frame_id', 'story_id') as $property) {
            $this->{$property} = (isset($data[$property]))? $data[$property]: "";
        }
    }
}
