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

    public function attach($data){
        $result = $this->utilsModel->yarRequest('prop', 'attach', $data);
        if($result['code'] == 0){
            return true;
        }else{
            return false;
        }
    }

    public function prop($type, $data){
        $result = $this->utilsModel->yarRequest('prop', $type, $data);
        if($result['code'] == 0){
            return true;
        }else{
            return false;
        }
    }

    public function attribute(){
        $result = $this->utilsModel->yarRequest('prop', 'attribute', array());
        return $result;
    }

    public function getGame(){
        $sql = "SELECT * FROM class WHERE 1=1  ORDER BY create_time DESC";
        $query = DI::getDefault()->get('dbData')->query($sql);
        $query->setFetchMode(Db::FETCH_ASSOC);
        $data = $query->fetchAll();
        return $data;
    }

    public function getVersionList($gameid){
        $sql = "SELECT * FROM games WHERE 1=1 AND game_id LIKE '".$gameid."%' ORDER BY id DESC";
        $query = DI::getDefault()->get('dbData')->query($sql);
        $query->setFetchMode(Db::FETCH_ASSOC);
        $data = $query->fetchAll();
        return $data;
    }
}