<?php

namespace MyApp\Models;

use Phalcon\Mvc\Model;
use Phalcon\DI;
use Phalcon\Db;

class Notice extends Model
{
    private $utilsModel;

    public function initialize()
    {
        $this->utilsModel = new Utils();
    }

    public function getLists($data)
    {
        $result = $this->utilsModel->yarRequest('Notice', 'lists', $data);
        $newData = [];
        if (isset($result['count']) && $result['count'] > 0) {
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

    public function createNotice($data)
    {
        $result = $this->utilsModel->yarRequest('Notice', 'create', $data);
        if ($result['code'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function findNotice($data)
    {
        $result = $this->utilsModel->yarRequest('Notice', 'item', $data);
        $result['data']['start_time'] = $this->utilsModel->toTimeZone($result['data']['start_time'],
            $this->utilsModel->getTimeZone());
        $result['data']['end_time'] = $this->utilsModel->toTimeZone($result['data']['end_time'],
            $this->utilsModel->getTimeZone());
        return $result;
    }

    public function editNotice($data)
    {
        $result = $this->utilsModel->yarRequest('Notice', 'modify', $data);
        if ($result['code'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function removeNotice($data)
    {
        $result = $this->utilsModel->yarRequest('Notice', 'remove', $data);
        if ($result['code'] == 0) {
            return true;
        }
        else {
            return false;
        }
    }
}
