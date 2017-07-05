<?php


/**
 * 礼品卡
 */
namespace MyApp\Controllers;


use MyApp\Models\Card;
use MyApp\Models\Page;
use Phalcon\Mvc\Dispatcher;
use MyApp\Models\Utils;

class CardController extends ControllerBase
{
    private $cardModel;
    private $pageModel;
    private $utilsModel;

    public function initialize()
    {
        parent::initialize();
        $this->cardModel = new Card();
        $this->pageModel = new Page();
        $this->utilsModel = new Utils();
    }

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

        $currentPage = $this->request->get('page', 'int') ? $this->request->get('page', 'int') : 1;
        $pagesize = 10;

        $data['title'] = $this->request->get('title', ['string', 'trim']);
        $data['type'] = $this->request->get('type', ['string', 'trim']);
        $data['page'] = $currentPage;
        $data['size'] = $pagesize;

        $result = $this->cardModel->getLists($data);

        $this->view->page = '';
        if (isset($result['count']) && $result['count'] > 0) {
            $this->view->page = $this->pageModel->getPage($result['count'], $pagesize, $currentPage);
        }

        foreach($result['data'] as $key=>$item){
            $result['data'][$key]['expire'] = strtotime($item['expired_in']) < time() ? 1 : 0;
        }

        $this->view->lists = $result['data'];
        $this->view->query = $data;
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
                $data['id'] = $this->request->get('id', 'int');
                if (!$data['id']) {
                    Utils::tips('error', '数据不完整', '/card/index');
                }

                $card = $this->cardModel->findCard($data);
                if (!$card) {
                    Utils::tips('error', '没有此数据', '/card/index');
                }

                if ($_POST) {
                    $this->edit();
                }

                $this->view->card = $card['data'];
                $this->view->pick("card/edit");
                break;

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
            $data['title'] = $this->request->get('title', ['string', 'trim']);
            $data['type'] = $this->request->get('type', ['string', 'trim']);
            $data['expired_in'] = $this->request->get('expired_in', ['string', 'trim']);
            $data['data'] = $this->request->get('data', ['string', 'trim']);
            $data['count'] = $this->request->get('count', ['string', 'trim']);
            $data['intro'] = $this->request->get('formcontent');

            if (!$data['title'] || !$data['type'] || !$data['expired_in'] || !$data['data'] || !$data['count']) {
                Utils::tips('error', '数据不完整', '/card/manage?do=create');
            }

            if($data['count'] <= 0 ){
                Utils::tips('error', '数量必须大于0', '/card/manage?do=create');
            }

            $data['expired_in'] = $this->utilsModel->toTimeZone($data['expired_in'], 'UTC',
                $this->utilsModel->getTimeZone());

            $result = $this->cardModel->createCard($data);
            if ($result) {
                Utils::tips('success', '添加成功', '/card/index');
            }
            else {
                Utils::tips('error', '添加失败', '/card/index');
            }
        }
        $this->view->pick("card/create");
    }


    // 卡片 - 编辑
    private function edit()
    {
        if ($_POST) {
            $data['id'] = $this->request->get('id', 'int');
            $data['title'] = $this->request->get('title', ['string', 'trim']);
            $data['type'] = $this->request->get('type', ['string', 'trim']);
            $data['expired_in'] = $this->request->get('expired_in', ['string', 'trim']);
            $data['data'] = $this->request->get('data', ['string', 'trim']);
            $data['intro'] = $this->request->get('formcontent');

            $result = $this->cardModel->editCard($data);

            $data['expired_in'] = $this->utilsModel->toTimeZone($data['expired_in'], 'UTC',
                $this->utilsModel->getTimeZone());

            if ($result) {
                Utils::tips('success', '修改成功', '/card/index');
            }
            else {
                Utils::tips('error', '修改失败', '/card/index');
            }
        }
    }


    // 卡片 - 删除
    private function remove()
    {
        $data['id'] = $this->request->get('id', ['string', 'trim']);
        if (!$data['id']) {
            Utils::tips('error', '数据不完整', '/card/index');
        }

        $activity = $this->cardModel->findCard($data);
        if ($activity == 1) {
            Utils::tips('error', '没有此数据', '/card/index');
        }

        $result = $this->cardModel->removeCard($data);

        if ($result) {
            Utils::tips('success', '删除成功', '/card/index');
        }
        else {
            Utils::tips('error', '删除失败', '/card/index');
        }
    }

}