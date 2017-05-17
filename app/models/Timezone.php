<?php

namespace MyApp\Models;

use Phalcon\Mvc\Model;
use Phalcon\DI;
use Phalcon\Db;

class Timezone extends Model
{

    public function initialize()
    {
        $this->setConnectionService('dbData');
        $this->setSource("setting_timezone");
    }

    public function findOne()
    {
        $sql = "SELECT id,app_id,user_id,timezone,create_time FROM setting_timezone WHERE 1=1 ";

        $sql .= " AND user_id=:user_id";
        $bind['user_id'] = DI::getDefault()->get('session')->get('user_id');

        if (!empty(DI::getDefault()->get('session')->get('app'))) {
            $sql .= " AND app_id=:app_id";
            $bind['app_id'] = DI::getDefault()->get('session')->get('app');
        }

        $query = DI::getDefault()->get('dbData')->query($sql, $bind);
        $query->setFetchMode(Db::FETCH_ASSOC);
        $data = $query->fetch();
        return $data;
    }
}
