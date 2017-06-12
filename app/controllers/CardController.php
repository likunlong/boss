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
        $do = $this->request->get('do', ['string', 'trim']);

        switch ($do) {
            case 'view':
                $this->view();
                break;
            case 'logs':
                $this->logs();
                break;
        }
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


    // 卡片 - 日志
    private function logs()
    {
    }


    // 卡片 - 详细预览
    private function view()
    {
    }


    // 卡片 - 创建
    private function create()
    {
        if ($_POST) {
        }
    }


    // 卡片 - 编辑
    private function edit()
    {
        if ($_POST) {
        }
    }


    // 卡片 - 删除
    private function remove()
    {
    }

}