<?php

namespace MyApp\Models;

use Phalcon\Mvc\Model;
use Phalcon\DI;
use Phalcon\Db;

class Activity extends Model
{
    private $utilsModel;

    public function initialize()
    {
        $this->utilsModel = new Utils();
    }

    public function getLists($data)
    {
        $result = $this->utilsModel->yarRequest('Activity', 'lists', $data);
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

    public function createActivity($data)
    {
        $result = $this->utilsModel->yarRequest('Activity', 'create', $data);
        if ($result['code'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function findActivity($data)
    {
        $result = $this->utilsModel->yarRequest('Activity', 'item', $data);
        $result['data']['start_time'] = $this->utilsModel->toTimeZone($result['data']['start_time'],
            $this->utilsModel->getTimeZone());
        $result['data']['end_time'] = $this->utilsModel->toTimeZone($result['data']['end_time'],
            $this->utilsModel->getTimeZone());
        return $result;
    }

    public function editActivity($data)
    {
        $result = $this->utilsModel->yarRequest('Activity', 'modify', $data);
        if ($result['code'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function removeActivity($data)
    {
        $result = $this->utilsModel->yarRequest('Activity', 'remove', $data);
        if ($result['code'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function listActivityCfg($data)
    {
        $result = $this->utilsModel->yarRequest('Activity', 'lists_cfg', $data);
        return $result;
    }

    public function createActivityCfg($data)
    {
        $result = $this->utilsModel->yarRequest('Activity', 'create_cfg', $data);
        if ($result['code'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function editActivityCfg($data)
    {
        $result = $this->utilsModel->yarRequest('Activity', 'modify_cfg', $data);
        if ($result['code'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function removeActivityCfg($data)
    {
        $result = $this->utilsModel->yarRequest('Activity', 'remove_cfg', $data);
        if ($result['code'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }

}
