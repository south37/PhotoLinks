<?php
namespace Vg\Model;

class User
{
    public $id;
    public $name;
    public $email;
    public $birthday;
    public $salt;
    public $password;

    public function __construct()
    {
    }

    public function setProperties($data)
    {
        foreach (array('name', 'id', 'email', 'password', 'salt', 'birthday') as $property) {
            $this->{$property} = (isset($data[$property]))? $data[$property]: "";
        }
    }

    public static function generateSalt()
    {
        return base64_encode(hash('sha256', strval(mt_rand()) . strval(time()) . strval(mt_rand()), true));
    }

    public static function hashPassword($password, $salt)
    {
        $x = '';
        for ($i = 0; $i < 1000; $i++) {
            $x = hash('sha256', $x . $password . $salt, true);
        }

        return base64_encode($x);
    }
}
