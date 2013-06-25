<?php
namespace Vg\Model;

class Stretcher
{
    public $algorithm;
    public $num;

    const SHA256_10000 = 'sha256_10000';

    public static function currentMethod()
    {
        return self::SHA256_10000;
    }

    public static function create($method)
    {
        if ($method === self::SHA256_10000) {
            return new Stretcher('sha256', 10000);
        } else {
            return self::create(self::currentMethod());
        }
    }

    private function __construct($algorithm, $num)
    {
        $this->algorithm = $algorithm;
        $this->num = $num;
    }

    public function stretch($password, $salt)
    {
        $x = '';
        for ($i = 0; $i < $this->num; $i++) {
            $x = hash($this->algorithm, $x . $password . $salt, true);
        }

        return base64_encode($x);
    }
}
