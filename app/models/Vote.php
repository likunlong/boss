<?php

namespace MyApp\Models;

use Phalcon\Mvc\Model;
use Phalcon\DI;
use Phalcon\Db;

class Vote extends Model
{
    private $utilsModel;

    public function initialize()
    {
        $this->utilsModel = new Utils();
    }

    public function getLists($data)
    {
        $result = $this->utilsModel->yarRequest('Vote', 'lists_topic', $data);
        $newData = [];
        if ($result['count'] > 0) {
            foreach ($result['data'] as $item) {
                $item['start_time'] = $this->utilsModel->toTimeZone($item['start_time'],
                    $this->utilsModel->getTimeZone());
                $item['end_time'] = $this->utilsModel->toTimeZone($item['end_time'], $this->utilsModel->getTimeZone());
                $newData[] = $item;
            }
            unset($result['data']);
        }
        $result['data'] = $newData;
        return $result;
    }

    public function createVote($data)
    {
        $result = $this->utilsModel->yarRequest('Vote', 'create_topic', $data);
        if ($result['code'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function findVote($data)
    {
        $result = $this->utilsModel->yarRequest('Vote', 'item_topic', $data);
        $result['data']['start_time'] = $this->utilsModel->toTimeZone($result['data']['start_time'],
            $this->utilsModel->getTimeZone());
        $result['data']['end_time'] = $this->utilsModel->toTimeZone($result['data']['end_time'],
            $this->utilsModel->getTimeZone());
        return $result;
    }

    public function editVote($data)
    {
        $result = $this->utilsModel->yarRequest('Vote', 'modify_topic', $data);
        if ($result['code'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function removeVote($data)
    {
        $result = $this->utilsModel->yarRequest('Vote', 'remove_topic', $data);
        if ($result['code'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function listVoteOption($data)
    {
        $result = $this->utilsModel->yarRequest('Vote', 'lists_option', $data);
        return $result;
    }

    public function createOption($data)
    {
        $result = $this->utilsModel->yarRequest('Vote', 'create_option', $data);
        return $result;
    }

    public function findOption($data)
    {
        $result = $this->utilsModel->yarRequest('Vote', 'item_option', $data);
        return $result;
    }

    public function editOption($data)
    {
        $result = $this->utilsModel->yarRequest('Vote', 'modify_option', $data);
        if ($result['code'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function removeOption($data)
    {
        $result = $this->utilsModel->yarRequest('Vote', 'remove_option', $data);
        if ($result['code'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }
}