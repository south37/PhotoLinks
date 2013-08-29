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

    /**
     * 指定したユーザが「いいね」したストーリーを全てFavoritの降順でソートして取得する
     *
     * @param $userId
     *
     * @return Story[] 
     * 
     */
    public function findsFavoriteStoriesByUserId($userId)
    {
        $sql = <<< SQL
            SELECT
                story.id, story.user_id, story.title, COUNT(*) AS like_count
            FROM story 
                INNER JOIN liked
                    ON story.id = liked.story_id 
            WHERE story.id IN
                (SELECT DISTINCT story_id FROM liked WHERE user_id = :userId)
            GROUP BY story.id ORDER BY like_count DESC;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':userId', $userId, \PDO::PARAM_INT);
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
    
    /**
     * 最近のストーリーを取得する 
     *
     * @return Story[] 
     *
     * 取得件数を定数の20としてるので後で使用者の方で適当に変更しておいてください.
     */
    public function findsRecentStory()
    {
        $sql = <<< SQL
            SELECT * FROM story
            ORDER BY id DESC LIMIT 0, 4;
SQL;
        $sth = $this->db->prepare($sql);
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

    /**
     * 指定したuserがつなげた、締めた、storyのframe郡をFavoritの降順でソートして返す
     *
     * @param $userId
     *
     * @return Story[]
     */
     public function findsPopularJoinedStoryByUserId($userId)
     {
        $sql = <<< SQL
            SELECT
                story.id, story.user_id, story.title, story.favorite, COUNT(*) AS like_count
            FROM story 
            WHERE story.id IN
                (SELECT DISTINCT story.id FROM story
                    INNER JOIN frame_story
                        ON story.id = frame_story.story_id
                    INNER JOIN frame
                        ON frame_story.frame_id = frame.id
                WHERE frame.user_id = :userId)
            GROUP BY story.id ORDER BY like_count DESC;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':userId', $userId, \PDO::PARAM_INT);
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
