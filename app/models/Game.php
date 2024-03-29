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

    /*
     * 补发操作
     */
    public function setProp($type, $data)
    {
        $result = $this->utilsModel->yarRequest('prop', $type, $data);
        return $result;
    }

    /*
     * 获取补发项
     */
    public function getAttribute()
    {
        if (empty($_COOKIE['attach'])) {
            $result = $this->utilsModel->yarRequest('prop', 'attribute', array());
            $_COOKIE['attach'] = json_encode($result);
            setcookie('attach', json_encode($result), time() + 7200, '/');
        }
        return json_decode($_COOKIE['attach'], true);
    }

    //dbTop
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

    public function saveData($data)
    {
        $sql = 'TRUNCATE TABLE class;';
        DI::getDefault()->get('dbData')->execute($sql);

        $sql = 'TRUNCATE TABLE games;';
        DI::getDefault()->get('dbData')->execute($sql);

        foreach ($data['data'] as $item) {
            $this->saveClass($item);

            foreach ($item['game'] as $game) {
                $this->saveGame($game);
            }
        }
    }

    private function saveClass($data)
    {
        $sql = "INSERT INTO `class`(`id`, `class_id`,`name`,create_time) VALUES (?, ?, ?, ? )";
        DI::getDefault()->get('dbData')->execute($sql, array(
            $data['id'],
            $data['class_id'],
            $data['name'],
            date('Y-m-d H:i:s', time()),
        ));
    }

    private function saveGame($data)
    {
        $sql = "INSERT INTO `games`(`id`,`game_id`, `class_id`,`version`, `name`,`en_name`,`status`,`domain`,`icon`,create_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
        DI::getDefault()->get('dbData')->execute($sql, array(
            $data['id'],
            $data['game_id'],
            $data['class_id'],
            $data['version'],
            $data['name'],
            $data['en_name'],
            $data['status'],
            $data['domain'],
            $data['icon'],
            date('Y-m-d H:i:s', time()),
        ));
    }
}