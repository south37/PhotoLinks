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
     * @return frameId 
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
        $sth->execute();
        $frameId = $this->getLatestId();
        return $frameId;
    }
    
    /**
    * 最後のframeカラムのIDを取得する
    * @return FrameID
    */
    private function getLatestId()
    {
        // IDを降順にして取得
        $sql = <<< SQL
            SELECT * FROM frame ORDER BY id DESC;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->execute();
        // 最初の一個目を取得
        $data = $sth->fetch(\PDO::FETCH_ASSOC);
        return $data['id'];
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
     * @return Frame[]
     */
     public function findsByThemeId($themeId)
     {
        $sql = <<< SQL
            SELECT * FROM frame
            WHERE theme_id = :themeId;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':themeId', $themeId, \PDO::PARAM_INT);
        $sth->execute();
        $frames = [];
        while($data = $sth->fetch(\PDO::FETCH_ASSOC))
        {
            $frame = new Frame();
            $frame->setProperties($data);
            array_push($frames, $frame);
        }
        return $frames;
     }
    
    /**
     * storyIDで検索する
     *
     * @param $storyId
     *
     * @return Frame[]
     */
     public function findsByStoryId($storyId)
     {
        $sql = <<< SQL
            SELECT
                frame.id, frame.user_id, frame.theme_id, frame.image_id,
                frame.parent_id, frame.last_story_id, frame.caption
            FROM frame
                INNER JOIN frame_story
                    ON frame.id = frame_story.frame_id
                INNER JOIN story
                    ON frame_story.story_id = story.id
            WHERE story_id = :storyId
            ORDER BY frame.id;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':storyId', $storyId, \PDO::PARAM_INT);
        $sth->execute();
        $frames = [];
        while($data = $sth->fetch(\PDO::FETCH_ASSOC))
        {
            $frame = new Frame();
            $frame->setProperties($data);
            array_push($frames, $frame);
        }

        return $frames;
     }
    
    /**
     * 指定されたIDの親フレームを検索する
     *
     * @param $id
     *
     * @return Frame
     */
     public function findParentById($id)
     {
        $sql = <<< SQL
            SELECT 
                f1.id, f1.user_id, f1.theme_id, f1.image_id,
                f1.parent_id, f1.last_story_id, f1.caption
            FROM frame f1, frame f2
            WHERE f1.id = f2.parentId AND f2.id = :id;
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
     * 指定したuserがつなげた、締めた、storyのframe郡を返す
     *
     * @param $userId
     *
     * @return Frame[][]
     */
     public function findsFramesEachStoryByUserId($userId)
     {
        $sql = <<< SQL
            SELECT
               frame.id, frame.user_id, frame.theme_id, frame.parent_id,
               frame.last_story_id, frame.caption, frame_story.story_id, story.title
            FROM frame
                INNER JOIN frame_story
                    ON frame.id = frame_story.frame_id
                INNER JOIN story
                    ON frame_story.story_id = story.id
            WHERE frame.user_id = :userId
            ORDER BY story.id, frame.id;
SQL;
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':userId', $userId, \PDO::PARAM_INT);
        $sth->execute();
        // 一つ前のストーリーID保持用
        $preStoryId = -1;
        // ストーリーID毎にフレーム郡を格納する二次元配列
        $storyFrames = [[]];
        // フレーム郡を一時的に格納する配列
        $frames = null;
        while($data = $sth->fetch(\PDO::FETCH_ASSOC))
        {
            // storyIdが前のフレームと異なっていた場合
            if($preStoryId != $data['story_id'])
            {
                // 初めては無視
                // 後は全てstoryIdが変わった時に、二次元配列に格納
                if($preStoryId != -1) array_push($storyFrames, $frames);
                // frame郡を格納する配列の初期化
                $frames = [];
                // 現在のストーリーIDを一つ前のストーリーID保持用変数に格納
                $preStoryId = $data['story_id'];
            }
            // Frameクラスをインスタンス化し、配列に格納
            $frame = new Frame();
            $frame->setProperties($data);
            array_push($frames, $frame);
        }
        // 最後のフレーム郡をnullでなければ二次元配列に格納する
        if($frames != null) array_push($storyFrames, $frames);
        return $storyFrames;
     }
}

