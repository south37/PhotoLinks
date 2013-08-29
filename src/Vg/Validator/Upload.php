<?php
namespace Vg\Validator;

use Respect\Validation\Validator as v;

// http://documentup.com/Respect/Validation/
class Upload
{
    private $validator;
    private $errors = [];

    /**
     * バリデータを作成
     */
    public function __construct()
    {
        $this->validator = v::arr()
        ->key('policy', v::string()->setName('policy')->notEmpty()
            )
        ;
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
                                                 'policy' => '利用規約に同意してください',
                                                 ]);
            return false;
        }
      
        $extension = pathinfo($input['image_name'], PATHINFO_EXTENSION);
        if (!in_array($extension, ['jpeg', 'jpg', 'png', 'gif'])) {
            $this->errors = ['画像の形式はjpgかpngかgifでお願いします'];
            return false;
        }

        $mime_extension = 'image/' . $extension;
        if ($extension === 'jpg') {
            $mime_extension = 'image/jpeg';
        }
        
        $mime_type = image_type_to_mime_type(exif_imagetype($input['image_path']));
        if ($mime_extension !== $mime_type) {
            $this->errors = ['画像の形式と拡張子が一致していません'];
            return false;
        }
    
        $binary = file_get_contents($input['image_path']);
        $size_MB = strlen($binary)/(1024*1024); 
        if ($size_MB > 2) {
            $this->errors = ['画像のサイズが2MBを超えています'];
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
