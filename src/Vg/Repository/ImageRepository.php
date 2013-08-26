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
     * @return boolean 
     +
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
        try
        {
            $sth->execute();
        }
        catch (PDOException $e)
        {
            die ($e->getMassage());
            return false;
        }
        return true;
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
}
