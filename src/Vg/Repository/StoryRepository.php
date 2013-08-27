<?php
namespace Vg\Repository;

use Vg\Model\Story;

class StoryRepository
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * 画像登録
     * @param $story
     *
     * @return storyId 
     *
     */
    public function insert($story)
    {
        $sth = $this->db->prepare('insert into story set
                                         user_id=:user_id,
                                         title=:title,
                                         favorite=:favorite,
                                         created_at=now(),
                                         updated_at=now()');

        $sth->bindValue(':user_id', $story->user_id, \PDO::PARAM_INT);
        $sth->bindValue(':title', $story->title, \PDO::PARAM_STR);
        $sth->bindValue(':favorite', $story->favorite, \PDO::PARAM_INT);
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
     * image更新
     * @param $story
     *
     * @return boolean
     *
     */
    public function update($story)
    {
        $sql = <<< SQL
            UPDATE image SET 
                user_id=:user_id,
                title=:title,
                favorite=:favorite;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':user_id', $story->user_id, \PDO::PARAM_INT);
        $sth->bindValue(':title', $story->title, \PDO::PARAM_STR);
        $sth->bindValue(':favorite', $story->favorite, \PDO::PARAM_INT);
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
     * フレームIDで検索する
     *
     * @param $id
     *
     * @return Story
     */
    public function findById($id)
    {
        $sql = <<< SQL
            SELECT * FROM story 
            WHERE id = :id;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $sth->execute();
        $data = $sth->fetch(\PDO::FETCH_ASSOC);
        $story = new Story();
        $story->setProperties($data);

        return $story;
    }
}
