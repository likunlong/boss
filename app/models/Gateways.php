<?php

namespace MyApp\Models;

use Phalcon\Mvc\Model;
use Phalcon\DI;
use Phalcon\Db;

class Gateways extends Model
{

    public function initialize()
    {
        $this->setConnectionService('dbTrade');
        $this->setSource("gateways");
    }

    public function getList()
    {
        $sql = "SELECT id,app_id,gateway,sub,type,visible,name,remark,currency,parent,sort,sandbox,tips FROM gateways WHERE 1=1 ";
        $bind = [];
        if (!empty(DI::getDefault()->get('session')->get('app'))) {
            $sql .= " AND app_id=:app_id";
            $bind['app_id'] = DI::getDefault()->get('session')->get('app');
        }
        $sql .= " ORDER BY sort DESC, id DESC";
        $query = DI::getDefault()->get('dbTrade')->query($sql, $bind);
        $query->setFetchMode(Db::FETCH_ASSOC);
        $data = $query->fetchAll();
        if (!$data) {
            return $data;
        }

        foreach ($data as $key => $value) {
            if ($value['type'] == 'wallet') {
                $data[$key]['typename'] = '钱包';
            }
            else {
                if ($value['type'] == 'card') {
                    $data[$key]['typename'] = '预付卡';
                }
                else {
                    if ($value['type'] == 'telecom') {
                        $data[$key]['typename'] = '运营商';
                    }
                }
            }
        }
        return $data;
    }

    public function getGatewaysList()
    {
        $sql = "SELECT id,gateway,name FROM gateways WHERE 1=1 AND parent = 0 ";
        $bind = [];
        if (!empty(DI::getDefault()->get('session')->get('app'))) {
            $sql .= " AND app_id=:app_id";
            $bind['app_id'] = DI::getDefault()->get('session')->get('app');
        }
        $sql .= " GROUP BY gateway ORDER BY sort DESC, id DESC";
        $query = DI::getDefault()->get('dbTrade')->query($sql, $bind);
        $query->setFetchMode(Db::FETCH_ASSOC);
        $data = $query->fetchAll();

        return $data;
    }

    public function getParent()
    {
        $sql = "SELECT id,gateway,name, COUNT(DISTINCT gateway) FROM gateways WHERE 1=1  AND parent = 0 ";
        $bind = [];
        if (!empty(DI::getDefault()->get('session')->get('app'))) {
            $sql .= " AND app_id=:app_id";
            $bind['app_id'] = DI::getDefault()->get('session')->get('app');
        }

        $sql .= " GROUP BY gateway ORDER BY sort DESC, id DESC";
        $query = DI::getDefault()->get('dbTrade')->query($sql, $bind);
        $query->setFetchMode(Db::FETCH_ASSOC);
        $data = $query->fetchAll();

        return $data;
    }
}
