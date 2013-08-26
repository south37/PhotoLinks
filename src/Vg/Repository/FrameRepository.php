<?php
namespace Vg\Repository;

use Vg\Model\Frame;

class FrameRepository
{
    protected $db;

    public function __construct($db){
        $this->db = $db;
    }

    /**
     * フレーム登録
     +
     * @param $frane
     *
     * @return boolean 
     +
     */
    public function insert($frame){
        $sth = $this->db->prepare('insert into frame set
                                         user_id=:user_id,
                                         theme_id=:theme_id,
                                         image_id=:image_id,
                                         parent_id=:parent_id,
                                         last_story_id=:last_story_id,
                                         caption=:caption,
                                         created_at=now(),
                                         updated_at=now()');

        $sth->bindValue(':user_id', $frame->user_id, \PDO::PARAM_INT);
        $sth->bindValue(':theme_id', $frame->theme_id, \PDO::PARAM_INT);
        $sth->bindValue(':image_id', $frame->image_id, \PDO::PARAM_INT);
        $sth->bindValue(':parent_id', $frame->parent_id, \PDO::PARAM_INT);
        $sth->bindValue(':last_story_id', $frame->last_story_id, \PDO::PARAM_INT);
        $sth->bindValue(':caption', $frame->caption, \PDO::PARAM_STR);
        try
        {
            $sth->execute();
        }
        catch (PDOException $e)
        {
            die($e->getMessage());
            return false;
        }
        return true;
    }
    
    /**
     * フレームIDで検索する
     *
     * @param $id
     *
     * @return Frame
     */
    public function findById($id){
        $sql = <<< SQL
            SELECT * FROM frame
            WHERE id = :id;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $sth->execute();
        $data = $sth->fetch(\PDO::FETCH_ASSOC);
        $frame = new Frame();
        $frame->setProperties($data);

        return $frame;
    }
    
    /**
     * themeIDで検索する
     *
     * @param $themeId
     *
     * @return frame[]
     */
     public function findsByThemeId($themeId)
     {
        $sql = <<< SQL
            SELECT * FROM image
                INNER JOIN frame
                    ON image.id = frame.image_id
                INNER JOIN theme
                    theme.id = frame.theme_id
            WHERE theme.id = :themeId;
SQL;
        $sth->bindValue(':themeId', $themeId, \PDO::PARAM_INT);
        $sth->execute();
        //$frames = new Array();
        $frames = [];
        while($data = $sth->fetch(\PDO::FETCH_ASSOC))
        {
            $frame = new Frame();
            $frame->setProperties($data);
            $frames->push($frame);
        }
        return $frames;
     }
}
