<?php
/**
 * Created by PhpStorm.
 * User: lkl
 * Date: 2017/5/8
 * Time: 10:34
 */

namespace MyApp\Models;


use Phalcon\Mvc\Model;
use Phalcon\DI;
use Phalcon\Db;

class Game extends Model
{
    private $utilsModel;

    public function initialize()
    {
        $this->setConnectionService('dbData');
        $this->setSource("games");
        $this->utilsModel = new Utils();
    }

    public function profile($data)
    {
        $result = $this->utilsModel->yarRequest('User', 'profile', $data);
        return $result;
    }

    public function prop($type, $data)
    {
        if (empty($_COOKIE['attach'])) {
            $result = $this->utilsModel->yarRequest('prop', $type, $data);
            $_COOKIE['attach'] = json_encode($result);
            setcookie('attach', json_encode($result), time() + 7200, '/');
        }
        return json_decode($_COOKIE['attach'], true);
    }

    public function attribute()
    {
        if (empty($_COOKIE['attach'])) {
            $result = $this->utilsModel->yarRequest('prop', 'attribute', array());
            $_COOKIE['attach'] = json_encode($result);
            setcookie('attach', json_encode($result), time() + 7200, '/');
        }
        return json_decode($_COOKIE['attach'], true);
    }

    public function getGame($class_id)
    {
        $sql = "SELECT * FROM class WHERE 1=1 AND id = '$class_id' ORDER BY create_time DESC";
        $query = DI::getDefault()->get('dbData')->query($sql);
        $query->setFetchMode(Db::FETCH_ASSOC);
        $data = $query->fetch();
        return $data;
    }

    public function getVersionList($gameid)
    {
        $sql = "SELECT * FROM games WHERE 1=1 AND game_id LIKE '" . $gameid . "%' ORDER BY id DESC";
        $query = DI::getDefault()->get('dbData')->query($sql);
        $query->setFetchMode(Db::FETCH_ASSOC);
        $data = $query->fetchAll();
        return $data;
    }
}