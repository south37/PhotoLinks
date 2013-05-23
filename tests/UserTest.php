<?php
use Vg\Model\User;

class UserTest extends PHPUnit_Framework_TestCase
{
    public function testUserのプロパティをセットできること()
    {
        $properties = [
            'id' => 2,
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => 'password',
            'birthday' => '2013-07-07',
        ];

        $user = new User();
        $user->setProperties($properties);

        $this->assertEquals(2, $user->id, 'IDをセットできること');
        $this->assertEquals('test', $user->name, '名前をセットできること');
        $this->assertEquals('test@example.com', $user->email, 'メールアドレスをセットできること');
        $this->assertEquals('password', $user->password, 'メールアドレスをセットできること');
        $this->assertEquals('2013-07-07', $user->birthday, '誕生日をセットできること');
    }

}