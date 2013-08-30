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
        ->key('caption', v::string()->setName('caption')->notEmpty()->length(1,20))
        ->key('image_id',v::int()->setName('image_id')->notEmpty()->min(0));
        
    }

    /*
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
                                                 'image_id' => "fileが選択されていません",
                                                 'image_id.equals' => 'それはだめだよ',
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
