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

        $sth->execute();

        $storyId = $this->getLatestId();
        return $storyId;
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
    * 最後のstoryカラムのIDを取得する
    * @return storyId
    */
    private function getLatestId()
    {
        // IDを降順にして取得
        $sql = <<< SQL
            SELECT * FROM story ORDER BY id DESC;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->execute();
        // 最初の一個目を取得
        $data = $sth->fetch(\PDO::FETCH_ASSOC);
        return $data['id'];
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
    
    /**
     * storyをfavoriteの降順でソートして返す 
     *
     * @return Story[]
     * 
     */
    public function findsHotStories($startNum, $getNum)
    {
        // ストーリーの「いいね」の多い順にソートする
        $sql = <<< SQL
            SELECT * FROM story
            ORDER BY favorite DESC
            LIMIT :startNum,:getNum;
SQL;
        $sth = $this->db->prepare($sql);

        $sth->bindValue(':startNum', $startNum, \PDO::PARAM_INT);
        $sth->bindValue(':getNum'  , $getNum  , \PDO::PARAM_INT);

        $sth->execute();
        $stories = [];
        while($data = $sth->fetch(\PDO::FETCH_ASSOC))
        {
            $story = new Story();
            $story->setProperties($data);
            array_push($stories, $story);
        }
        return $stories;
    }

    public function findsByUserId($user_id, $page)
    {
        // ストーリーの「いいね」の多い順にソートする
        $sql = <<< SQL
            SELECT * FROM story
            WHERE user_id = :user_id
            ORDER BY favorite DESC
            LIMIT :page, 4;
SQL;
        $sth = $this->db->prepare($sql);

        $sth->bindValue(':user_id', $user_id, \PDO::PARAM_INT);
        $sth->bindValue(':page', $page, \PDO::PARAM_INT);

        $sth->execute();
        $stories = [];
        while($data = $sth->fetch(\PDO::FETCH_ASSOC))
        {
            $story = new Story();
            $story->setProperties($data);
            array_push($stories, $story);
        }
        return $stories;
    }
}
