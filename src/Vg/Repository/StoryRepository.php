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
     * 登録
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
     * 更新
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
                favorite=:favorite
            WHERE id=:id;
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
     * IDで検索する
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
    
    
    /**
     * IDで検索する
     *
     * @param $id
     *
     * @return Story
     * 
     * 自作自演は防げるが、ログインが必須になってしまう点が問題
     */
    public function incrementFavorite($storyId, $userId)
    {
        // ストーリーの投稿者と同じユーザが「いいね」しているかを調べる
        $sql = <<< SQL
            SELECT * FROM story
            WHERE id = :storyId AND user_id = :userId;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':storyId', $storyId, \PDO::PARAM_INT);
        $sth->bindValue(':userId', $userId, \PDO::PARAM_INT);
        $sth->execute();
        // 一致するデータがあれば、同じユーザが「いいね」している為、無効にする
        if($sth->fetchColumn() !== 0)
        {
            return false;
        }

        // 「いいね」の件数をインクリメントする
        $sql = <<< SQL
            UPDATE story SET
               favorite = favorite + 1
            WHERE id = :id;
SQL;
        $sth = $this->db->prepare($sql);
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
}
