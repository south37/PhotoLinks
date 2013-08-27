<?php
namespace Vg\Validator;

use Respect\Validation\Validator as v;

// http://documentup.com/Respect/Validation/
class AddFrame
{
    private $validator;
    private $errors = [];

    /**
     * バリデータを作成
     */
    public function __construct()
    {
        $this->validator = v::arr()
        ->key('caption', v::string()->setName('name')->notEmpty()->length(1,255))
        ;
    }

    /**
     * $input のバリデーションを行う
     *
     * @param  array   $input チェックする値を含む配列
     * @return boolean 有効かどうか
     */
    public function validate($input)
    {
        try {
            $this->validator->assert($input);
        } catch (\InvalidArgumentException $e) {
            $this->errors = $e->findMessages([
                                                 'caption' => '説明文を確認してください',
                                                 'caption.notEmpty' => '説明文を入力してください',
                                                 'caption.length' => '説明文は{{minValue}}〜{{maxValue}}文字内で入力してください',
                                                 ]);

            return false;
        }

        return true;
    }

    /**
     * エラーメッセージの配列を返す
     */
    public function errors()
    {
        return $this->errors;
    }
}
}
