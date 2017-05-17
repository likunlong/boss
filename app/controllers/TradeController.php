<?php


/**
 * 订单Trade
 */
namespace MyApp\Controllers;


use MyApp\Models\Page;
use MyApp\Models\Trade;
use MyApp\Models\Utils;
use Phalcon\Mvc\Dispatcher;

class TradeController extends ControllerBase
{
    private $tradeModel;
    private $pageModel;

    public function initialize()
    {
        parent::initialize();
        $this->tradeModel = new Trade();
        $this->pageModel = new Page();

    }

    /**
     * 订单列表
     */
    public function indexAction()
    {
        $currentPage = $this->request->get('page', 'int') ? $this->request->get('page', 'int') : 1;
        $pagesize = 10;
        $data['transaction'] = $this->request->get('transaction', 'string');
        $data['user_id'] = $this->request->get('user_id', 'string');
        $data['status'] = $this->request->get('status', 'string');
        $data['gateway'] = $this->request->get('gateway', 'string');
        $data['product_id'] = $this->request->get('product_id', 'string');
        $data['custom'] = $this->request->get('custom', 'string');
        $data['start_time'] = $this->request->get('start_time', 'string');
        $data['end_time'] = $this->request->get('end_time', 'string');
        $data['trade_no'] = $this->request->get('trade_no', 'string');

        $count = $this->tradeModel->getCount($data);
        $this->view->trade = $this->tradeModel->getList($data, $currentPage, $pagesize);
        $this->view->page = $this->pageModel->getPage($count, $pagesize, $currentPage);
        $this->view->query = $data;
        $this->view->isquery = empty(array_filter($data)) ? 0 : 1;
    }


    /**
     * 订单详细
     */
    public function viewAction()
    {
        $id = $this->request->get('id');
        if (!$id) {
            Utils::tips('error', '数据不完整', '/trade/index');
        }

        $trade = $this->tradeModel->getTradeById($id);
        if (!$trade) {
            Utils::tips('error', '没有此数据', '/trade/index');
        }

        $this->view->trade = $trade;
    }


    /**
     * 追加补充订单
     */
    public function appendAction()
    {
    }

}