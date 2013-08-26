<?php
namespace Vg\Repository;

use Vg\Model\Theme;

class ThemeRepository
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * テーマ登録
     * @param $user
     */
    public function insert($user)
    {
        $sth = $this->db->prepare('insert into theme set
                                         user_id=:user_id,
                                         theme_id=:theme_id,
                                         image_id=:image_id,
                                         parent_id=:parent_id,
                                         last_story_id=:last_story_id,
                                         created_at=now(),
                                         updated_at=now()');

        $sth->bindValue(':user_id', $user->user_id, \PDO::PARAM_INT);
        $sth->bindValue(':theme_id', $user->theme_id, \PDO::PARAM_INT);
        $sth->bindValue(':image_id', $user->image_id, \PDO::PARAM_INT);
        $sth->bindValue(':parent_id', $user->parent_id, \PDO::PARAM_INT);
        $sth->bindValue(':last_story_id', $user->last_story_id, \PDO::PARAM_INT);
        $sth->execute();
    }
}
