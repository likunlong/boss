<?php

namespace MyApp\Models;

use Phalcon\Mvc\Model;
use Phalcon\DI;
use Phalcon\Db;

class Server extends Model
{
    private $utilsModel;

    public function initialize()
    {
        $this->utilsModel = new Utils();
    }

    public function createServer($data)
    {
        $result = $this->utilsModel->yarRequest('Zone', 'create', $data);
        if ($result['code'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function getLists()
    {
        if(empty($_COOKIE['serverLists'])){
            $result = $this->utilsModel->yarRequest('Zone', 'lists', array());
            if(!$result['data']){
                return array();
            }else{
                setcookie('serverLists', json_encode($result['data']), time()+7200);
            }
        }
        return json_decode($_COOKIE['serverLists'], true);
    }

    public function findServer($data){
        return $this->utilsModel->yarRequest('Zone', 'item', $data);
    }

    public function editServer($data)
    {
        $result = $this->utilsModel->yarRequest('Zone', 'modify', $data);
        if ($result['code'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function removeServer($data)
    {
        $result = $this->utilsModel->yarRequest('Zone', 'remove', $data);
        if ($result['code'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }
}