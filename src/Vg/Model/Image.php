<?php
namespace Vg\Model;

class Image
{
    public $id;
    public $user_id;
    public $path;
    public $scope;
    public $deleted;
    public $caption;

    public function __construct()
    {
    }

    public function setProperties($data)
    {
        foreach (array('id', 'user_id', 'path', 'scope', 'deleted', 'caption') as $property) {
            $this->{$property} = (isset($data[$property]))? $data[$property]: "";
        }
    }
}
