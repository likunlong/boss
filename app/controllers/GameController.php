<?php


/**
 * 游戏信息
 */
namespace MyApp\Controllers;

use MyApp\Models\Game;
use MyApp\Models\Trade;
use Myapp\Models\Utils;
use MyApp\Models\Server;
use Phalcon\Mvc\Dispatcher;

class GameController extends ControllerBase
{
    private $gameModel;
    private $tradeModel;
    private $serverModel;

    public function initialize()
    {
        $this->gameModel = new Game();
        $this->tradeModel = new Trade();
        $this->serverModel = new Server();
    }

    public function indexAction()
    {
        if ($_POST) {
            $server = $this->request->get('server', ['string', 'trim']);
            $role_id = $this->request->get('role_id', ['string', 'trim']);
            $data['role_name'] = $this->request->get('role_name', ['string', 'trim']);
            $data['user_id'] = $this->request->get('user_id', ['string', 'trim']);
            $data['id'] = $server . '-' . $role_id;

            if (!$data['id']) {
                Utils::tips('error', '数据不完整', '/game/index');
                exit;
            }

            $result = $this->gameModel->profile($data);

            if ($result['code'] == 1) {
                Utils::tips('error', '没有该用户', '/game/index');
                exit;
            }
            if ($result['data']['account_id']) {
                $where['user_id'] = $result['data']['account_id'];
                $count = $this->tradeModel->getCount($where);
                $this->view->trade = $this->tradeModel->getList($where, 1, $count);

                $this->view->user = $result['data'];
                $this->view->server = $server;
                $this->view->pick("game/player");
            }
            else {
                $this->view->pick("game/index");
            }
        }

        $result = $this->serverModel->getLists();
        $this->view->lists = $result;

    }


    /**
     * 玩家信息
     */
    public function playerAction()
    {

    }


    /**
     * 信息完善奖励
     */
    public function completeAction()
    {
    }


    /**
     * 站内邮件以及附件
     */
    public function mailAction()
    {
    }


    /**
     * 充值排行
     */
    public function topAction()
    {
    }

    /**
     * 玩家查询
     */
    public function searchAction()
    {

    }

    /**
     * 补发管理
     */
    public function reissueAction()
    {
        $result = $this->gameModel->attribute();
        $this->view->lists = $result['data'];
    }

    /**
     * 补发其他属性
     */
    public function otherAction()
    {
        if ($_POST) {
            $type = $this->request->get('action', ['string', 'trim']);
            $data['user_id'] = $this->request->get('user_id', ['string', 'trim']);
            $data['amount'] = $this->request->get('amount', ['string', 'trim']);
            $data['msg'] = $this->request->get('msg', ['string', 'trim']);

            $result = $this->gameModel->prop($type, $data);

            if ($result) {
                echo json_encode(array('error' => 0, 'data' => '补发成功'));
                exit;
            }
            else {

                echo json_encode(array('error' => 1, 'data' => '补发失败'));
                exit;
            }
        }
    }

    /**
     * 补发记录
     */
    public function logAction()
    {

    }

    /**
     * 等级分布
     */
    public function levelAction()
    {

    }

    /**
     * 补发道具
     */
    public function attachAction()
    {
        if ($_POST) {
            $data['user_id'] = $this->request->get('user_id', ['string', 'trim']);
            $data['attach'] = $this->request->get('attach', ['string', 'trim']);
            $data['msg'] = $this->request->get('coin_msg', ['string', 'trim']);

            if (!$data['user_id'] || !$data['attach']) {
                Utils::tips('error', '数据不完整', '/game/reissue');
            }
            
            $result = $this->gameModel->attach($data);

            if ($result) {
                Utils::tips('success', '补发道具成功', '/game/reissue');
            }
            else {
                Utils::tips('error', '补发道具失败', '/game/reissue');
            }
        }

        header('Location: /game/reissue');
        exit;
    }

}