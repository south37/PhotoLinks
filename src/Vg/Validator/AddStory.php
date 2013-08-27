<?php
namespace Vg\Validator;

use Respect\Validation\Validator as v;

// http://documentup.com/Respect/Validation/
class AddStory
{
    private $validator;
    private $errors = [];

    /**
     * バリデータを作成
     */
    public function __construct()
    {
        $this->validator = v::arr()
        ->key('title', v::string()->setName('title')->notEmpty()->length(1,255))
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
                                                 'title' => 'タイトルを確認してください',
                                                 'title.notEmpty' => 'タイトルを入力してください',
                                                 'title.length' => 'タイトルは{{minValue}}〜{{maxValue}}文字内で入力してください',
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
