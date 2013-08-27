<?php
namespace Vg\Repository;

use Vg\Model\Image;

class ImageRepository
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * 画像登録
     * @param $image
     *
     * @return imageId 
     *
     */
    public function insert($image)
    {
        $sth = $this->db->prepare('insert into image set
                                         user_id=:user_id,
                                         path=:path,
                                         scope=:scope,
                                         deleted=:deleted,
                                         created_at=now(),
                                         updated_at=now()');

        $sth->bindValue(':user_id', $image->user_id, \PDO::PARAM_INT);
        $sth->bindValue(':path', $image->path, \PDO::PARAM_STR);
        $sth->bindValue(':scope', $image->scope, \PDO::PARAM_INT);
        $sth->bindValue(':deleted', $image->deleted, \PDO::PARAM_INT);
        $sth->execute();
        // insertされたカラムのIDを取得する
        $imageId = $this->getLatestId();
        return $imageId;
        // return true;
    }

    /**
     * image更新
     * @param $image
     *
     * @return boolean
     *
     */
    public function update($image)
    {
        $sql = <<< SQL
            UPDATE image SET 
                user_id=:user_id,
                path=:path,
                scope=:scope,
                deleted=:deleted
            WHERE id=:id;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':id', $image->id, \PDO::PARAM_INT);
        $sth->bindValue(':user_id', $image->user_id, \PDO::PARAM_INT);
        $sth->bindValue(':path', $image->path, \PDO::PARAM_STR);
        $sth->bindValue(':scope', $image->scope, \PDO::PARAM_INT);
        $sth->bindValue(':deleted', $image->deleted, \PDO::PARAM_INT);
        try
        {
            $sth->execute();
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }
    }

    /**
    * 最後のimageカラムのIDを取得する
    * @return imageID
    */
    private function getLatestId()
    {
        // IDを降順にして取得
        $sql = <<< SQL
            SELECT * FROM image ORDER BY id DESC;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->execute();
        // 最初の一個目を取得
        $data = $sth->fetch(\PDO::FETCH_ASSOC);
        return $data['id'];
    }
    
    /**
     * イメージIDで検索する
     *
     * @param $id
     *
     * @return Image
     */
    public function findById($id)
    {
        $sql = <<< SQL
            SELECT * FROM image
            WHERE id = :id;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $sth->execute();
        $data = $sth->fetch(\PDO::FETCH_ASSOC);
        $image = new Image();
        $image->setProperties($data);

        return $image;
    }

    /**
     * frameIDで検索する
     *
     * @param $frameId
     *
     * @return Image
     */
     public function findByFrameId($frameId)
     {
        $sql = <<< SQL
            SELECT * FROM image
                INNER JOIN frame
                    ON image.id = frame.image_id
            WHERE frame.id = :frameId;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':frameId', $frameId, \PDO::PARAM_INT);
        $sth->execute();
        $data = $sth->fetch(\PDO::FETCH_ASSOC);
        $image = new Image();
        $image->setProperties($data);
        
        return $image;
     }

    /**
     * 最新の画像を件数を指定してページ単位で検索する
     *
     * @param $page
     *
     * @return Image[]
     *
     * 件数は20の定数なので、変更したければ引数に加えてください
     */
     public function findByPage($page)
     {
         $sql = <<< SQL
            SELECT * FROM image
            OREDER BY id DESC LIMIT :start, 20;
SQL;
         $sth = $this->db->prepare($sql);
         $sth->bindValue(':start', $page * 20, \PDO::PARAM_INT);
         $sth->execute();
         $images = [];
         while($data = $sth->fetch(\PDO::FETCH_ASSOC))
         {
             $image = new Image();
             $image->setProperties($data);
             array_push($images, $image);
         }
         return $images;
     }

    /**
     * frameIDで画像パスを検索する
     *
     * @param $frameId
     *
     * @return Image
     */
    /*
     public function findByFrameId($frameId)
     {
        $sql = <<< SQL
            SELECT * FROM image
                INNER JOIN frame
                    ON image.id = frame.image_id
            WHERE frame.id = :frameId;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':frameId', $frameId, \PDO::PARAM_INT);
        $sth->execute();
        $data = $sth->fetch(\PDO::FETCH_ASSOC);
        return $data['path'];
     }
     */
}
