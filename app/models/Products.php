<?php

namespace MyApp\Models;

use Phalcon\Mvc\Model;
use Phalcon\DI;
use Phalcon\Db;

class Products extends Model
{
    private $utilsModel;

    public function initialize()
    {
        $this->setConnectionService('dbTrade');
        $this->setSource("products");
        $this->utilsModel = new Utils();
    }

    public function getProductList($gateway)
    {
        $sql = "SELECT id,app_id,package,name,product_id,gateway,price,currency,coin,status,remark,create_time,update_time FROM products WHERE 1=1 AND gateway=:gateway";
        $bind = array('gateway' => $gateway);
        if (!empty(DI::getDefault()->get('session')->get('app'))) {
            $sql .= " AND app_id=:app_id";
            $bind['app_id'] = DI::getDefault()->get('session')->get('app');
        }
        $sql .= " ORDER BY product_id DESC, sort DESC";

        $query = DI::getDefault()->get('dbTrade')->query($sql, $bind);
        $query->setFetchMode(Db::FETCH_ASSOC);
        $data = $query->fetchAll();
        $newData = [];
        foreach ($data as $item) {
            $item['create_time'] = $this->utilsModel->toTimeZone($item['create_time'],
                $this->utilsModel->getTimeZone());
            $item['update_time'] = $this->utilsModel->toTimeZone($item['update_time'],
                $this->utilsModel->getTimeZone());
            $newData[] = $item;
        }

        return $newData;
    }
}
