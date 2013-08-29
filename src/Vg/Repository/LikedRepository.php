<?php
namespace Vg\Repository;

use Vg\Model\Liked;

class LikedRepository
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * 登録
     * @param $liked
     *
     * @return boolean
     *
     */
    public function insert($liked)
    {
        $sth = $this->db->prepare('insert into frame_story set
                                         story_id=:storyId,
                                         user_id=:userId,
                                         created_at=now(),
                                         updated_at=now()');

        $sth->bindValue(':story_id', $liked->story_id, \PDO::PARAM_INT);
        $sth->bindValue(':user_id', $liked->user_id, \PDO::PARAM_INT);
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
     * @param $liked
     *
     * @return boolean
     *
     */
    public function update($liked)
    {
        $sql = <<< SQL
            UPDATE liked SET 
                story_id=:storyId,
                user_id=:userId
            WHERE id=:id;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':id', $story->id, \PDO::PARAM_INT);
        $sth->bindValue(':storyId', $frame_story->story_id, \PDO::PARAM_INT);
        $sth->bindValue(':userId', $frame_story->user_id, \PDO::PARAM_INT);
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
     * @return Liked
     */
    public function findById($id)
    {
        $sql = <<< SQL
            SELECT * FROM liked 
            WHERE id = :id;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $sth->execute();
        $data = $sth->fetch(\PDO::FETCH_ASSOC);
        $liked = new Liked();
        $liked->setProperties($data);

        return $liked;
    }
    
    /**
     * 「いいね」の件数を増やす
     * （Likedテーブルにカラムを増やす)
     *
     * @param $storyId, $userId
     *
     * @return boolean 
     * 
     */
    public function incrementFavorite($storyId, $userId)
    {
        // 指定しストーリーに同じユーザが「いいね」しているかを調べる
        if($this->isSameLikedUser($storyId, $userId)) return false;
        // 「いいね」の件数を増やす
        $liked = new Liked();
        $liked->setProperties(['story_id' => $storyId, 'user_id' => $userId]);
        $this->insert($liked);
        return true;
    }

    /**
     * 指定したストーリーに同じユーザが「いいね」しているかを調べる
     *
     * @param $storyId, $userId
     *
     * @return boolean 
     * 
     */
    public function isSameLikedUser($storyId, $userId)
    {
        $sql = <<< SQL
            SELECT * FROM story
                INNER JOIN liked
                    ON story.id = liked.story_id
            WHERE story.id = :storyId AND liked.user_id = :userId;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':storyId', $storyId, \PDO::PARAM_INT);
        $sth->bindValue(':userId', $userId, \PDO::PARAM_INT);
        $sth->execute();
        // 一致するデータがあれば、同じユーザが「いいね」している為、無効にする
        if($sth->rowCount() !== 0) return false;
        return true;
    }

    /**
     * 指定したストーリーの「いいね」された数を取得する
     *
     * @param $storyId
     *
     * @return boolean 
     * 
     */
    public function getNumberOfLikedByStoryId($storyId)
    {
        $sql = <<< SQL
            SELECT * FROM liked
            WHERE story_id = :storyId;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':storyId', $storyId, \PDO::PARAM_INT);
        $sth->execute();
        return $sth->rowCount();
    }
}
