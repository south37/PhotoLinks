<?php
namespace Vg\Model;

class Liked 
{
    public $id;
    public $story_id;
    public $user_id;

    public function __construct()
    {
    }

    public function setProperties($data)
    {
        foreach (array('id', 'story_id', 'user_id') as $property) {
            $this->{$property} = (isset($data[$property]))? $data[$property]: "";
        }
    }
}
