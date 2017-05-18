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
        $result = $this->utilsModel->yarRequest('Zone', 'lists', array());
        if(!$result['data']){
            return array();
        }else{
            return $result['data'];
        }
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