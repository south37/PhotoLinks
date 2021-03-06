<?php
namespace Vg\Model;

class Stretcher
{
    public $algorithm;
    public $num;

    const SHA256_10000 = 'sha256_10000';
    const SHA256_20000 = 'sha256_20000';

    public static function currentMethod()
    {
        return self::SHA256_20000;
    }

    public static function create($method)
    {
        if ($method === self::SHA256_20000) {
            return new Stretcher('sha256', 20000);
        } elseif ($method === self::SHA256_10000) {
            return new Stretcher('sha256', 10000);
        } else {
            //フォールバック。謎のメソッドが指定されたらデフォルトを返す
            //XXX 例外を送出すべきか？
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
