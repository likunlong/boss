<?php


/**
 * 礼品卡
 */
namespace MyApp\Controllers;


use Phalcon\Mvc\Dispatcher;

class CardController extends ControllerBase
{

    /**
     * 卡片 - 概况信息
     */
    public function indexAction()
    {
    }


    /**
     * 卡片 - 日志
     */
    public function logsAction()
    {
    }


    /**
     * 卡片 - 管理
     */
    public function manageAction()
    {
        $do = $this->request->get('do', ['string', 'trim']);

        switch ($do) {
            case 'create':
                $this->create();
                break;
            case 'edit':
                $this->edit();
                break;
            case 'remove':
                $this->remove();
                break;
        }
    }


    private function create()
    {
        if ($_POST) {
        }
    }


    private function edit()
    {
        if ($_POST) {
        }
    }


    private function remove()
    {
    }


}