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
                                         frame_id=:frame_id,
                                         title=:title,
                                         fix_num=:fix_num,
                                         frame_num=:frame_num,
                                         created_at=now(),
                                         updated_at=now()');
        $sth->bindValue(':user_id', $user->user_id, \PDO::PARAM_INT);
        $sth->bindValue(':frame_id', $user->frame_id, \PDO::PARAM_INT);
        $sth->bindValue(':title', $user->title, \PDO::PARAM_STR);
        $sth->bindValue(':fix_num', $user->fix_num, \PDO::PARAM_INT);
        $sth->bindValue(':frame_num', $user->frame_num, \PDO::PARAM_INT);
        try
        {
            $sth->execute();
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }
    }
}
