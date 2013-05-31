<?php

/**
 * Class Study1Test
 * @group study1
 */
class Study1Test extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function UsetRegisterバリデータに渡されるis_agreedが空だとエラーを返すこと()
    {
        $input = [
            'is_agreed' => '',
            ];
        $validator = new Vg\Validator\UserRegister;
        $validator->validate($input);
        $errors = $validator->errors();
        $this->assertNotEquals('', $errors['is_agreed_notEmpty']);
    }

    /**
     * @test
     */
    public function UsetRegisterバリデータに渡されるis_agreedが空でなければエラーを返さないこと()
    {
        $input = [
            'is_agreed' => 'yes',
        ];
        $validator = new Vg\Validator\UserRegister;
        $validator->validate($input);
        $errors = $validator->errors();
        $this->assertEquals('', $errors['is_agreed_notEmpty']);
    }
}
