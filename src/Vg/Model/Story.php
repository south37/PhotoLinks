<?php
namespace Vg\Model;

class Story 
{
    public $id;
    public $user_id;
    public $title;
    public $favorite;

    public function __construct()
    {
    }

    public function setProperties($data)
    {
        foreach (array('id', 'user_id', 'title', 'favorite') as $property) {
            $this->{$property} = (isset($data[$property]))? $data[$property]: "";
        }
    }
}
