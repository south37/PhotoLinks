<?php
namespace Vg\Repository;

use Vg\Model\User;

class UserRepository
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * ユーザー登録
     * @param $user
     */
    public function insert($user)
    {
        $user->salt = User::generateSalt();
        $user->password = User::hashPassword($user->password, $user->salt);

        $sth = $this->db->prepare('insert into user set
                                         email=:email,
                                         name=:name,
                                         password=:password,
                                         salt=:salt,
                                         birthday=:birthday,
                                         created_at=now(),
                                         updated_at=now()');

        $sth->bindValue(':email', $user->email, \PDO::PARAM_STR);
        $sth->bindValue(':name', $user->name, \PDO::PARAM_STR);
        $sth->bindValue(':password', $user->password, \PDO::PARAM_STR);
        $sth->bindValue(':salt', $user->salt, \PDO::PARAM_STR);
        $sth->bindValue(':birthday', $user->birthday, \PDO::PARAM_INT);
        $sth->execute();
    }

    /**
     * ユーザー更新
     * @param $user
     */
    public function update($user)
    {
        if ($user->id == "") return;
        $sth = $this->db->prepare('update user set
                                         email=:email,
                                         name=:name,
                                         birthday=:birthday,
                                         updated_at=now()
                                  where id=:id');

        $sth->bindValue(':id', $user->id, \PDO::PARAM_INT);
        $sth->bindValue(':email', $user->email, \PDO::PARAM_STR);
        $sth->bindValue(':name', $user->name, \PDO::PARAM_STR);
        $sth->bindValue(':birthday', $user->birthday, \PDO::PARAM_INT);
        $sth->execute();
    }

    /**
     * ユーザーをメールアドレスとパスワードで探す
     *
     * ログイン時に利用
     *
     * @param $email
     * @param $password
     *
     * @return null|User
     */
    public function findByEmailPassword($email, $password)
    {
        $user = $this->findByEmail($email);

        return ($user->password === User::hashPassword($password, $user->salt))? $user: null;
    }

    /**
     * メールアドレスでユーザーが存在するかを確認する
     *
     * @param $email
     *
     * @return User
     */
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM user WHERE email = :email";
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':email', $email, \PDO::PARAM_STR);
        $sth->execute();
        $data = $sth->fetch(\PDO::FETCH_ASSOC);
        $user = new User();
        $user->setProperties($data);

        return $user;
    }

    /**
     * ユーザーIDで検索する
     *
     * @param $id
     *
     * @return User
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM user WHERE id = :id";
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $sth->execute();
        $data = $sth->fetch(\PDO::FETCH_ASSOC);
        $user = new User();
        $user->setProperties($data);

        return $user;
    }

    /**
     * メールアドレスからユーザーのIDを探す
     *
     * @param $email
     *
     * @return mixed
     */
    public function findIdByEmail($email)
    {
        $sql = "SELECT id FROM user WHERE email = :email";
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':email', $email, \PDO::PARAM_STR);
        $sth->execute();
        $id = $sth->fetchColumn();

        return $id;
    }
}
