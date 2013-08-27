<?php
namespace Vg\Repository;

use Vg\Model\FrameStory;

class FrameStoryRepository
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * 登録
     * @param $frame_story
     *
     * @return boolean
     *
     */
    public function insert($frame_story)
    {
        $sth = $this->db->prepare('insert into frame_story set
                                         frame_id=:frame_id,
                                         story_id=:story_id,
                                         created_at=now(),
                                         updated_at=now()');

        $sth->bindValue(':frame_id', $frame_story->frame_id, \PDO::PARAM_INT);
        $sth->bindValue(':story_id', $frame_story->story_id, \PDO::PARAM_INT);
        try
        {
            $sth->execute();
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * 更新
     * @param $frame_story
     *
     * @return boolean
     *
     */
    public function update($frame_story)
    {
        $sql = <<< SQL
            UPDATE frame_story SET 
                frame_id=:frame_id,
                story_id=:story_id
            WHERE id=:id;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':id', $story->id, \PDO::PARAM_INT);
        $sth->bindValue(':frame_id', $frame_story->frame_id, \PDO::PARAM_INT);
        $sth->bindValue(':story_id', $frame_story->story_id, \PDO::PARAM_INT);
        try
        {
            $sth->execute();
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * IDで検索する
     *
     * @param $id
     *
     * @return FrameStory
     */
    public function findById($id)
    {
        $sql = <<< SQL
            SELECT * FROM frame_story 
            WHERE id = :id;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $sth->execute();
        $data = $sth->fetch(\PDO::FETCH_ASSOC);
        $frame_story = new FrameStory();
        $frame_story->setProperties($data);

        return $frame_story;
    }
}
