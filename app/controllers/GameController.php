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

    }


    /**
     * 玩家信息
     */
    public function playerAction()
    {
        $show = $server = 0;
        $users = [];
        if ($_POST) {
            $server = $this->request->get('server', ['string', 'trim']);
            $user_id = $this->request->get('user_id', ['string', 'trim']);
            $data['name'] = $this->request->get('name', ['string', 'trim']);
            $data['account_id'] = $this->request->get('account_id', ['string', 'trim']);
            $data['user_id'] = $server . '-' . $user_id;

            if (empty($user_id) && !isset($data['name']) && !isset($data['account_id'])) {
                Utils::tips('error', '数据不完整', '/game/player');
                exit;
            }

            $result = $this->gameModel->profile($data);
            if(empty($result)){
                Utils::tips('error', '服务器ID错误', '/game/player');
                exit;
            }

            if ($result['code'] == 1) {
                Utils::tips('error', '没有该用户', '/game/player');
                exit;
            }
            if ($result['count'] == 1) {
                $where['user_id'] = $result['data']['account_id'];
                $count = $this->tradeModel->getCount($where);
                $this->view->trade = $this->tradeModel->getList($where, 1, $count);

                $this->view->user = $result['data'];
                $this->view->server = $server;
                $this->view->pick("game/playerone");
            }
            else {
                $show = 1;
                $users = $result['data'];
                $this->view->pick("game/player");
            }
        }

        $result = $this->serverModel->getLists();

        $this->view->users = $users;
        $this->view->show = $show;
        $this->view->server = $server;
        $this->view->lists = $result;
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
        if ($_POST) {
            $type = $this->request->get('action', ['string', 'trim']);
            $data['user_id'] = $this->request->get('user_id', ['string', 'trim']);
            $data['amount'] = $this->request->get('amount', ['string', 'trim']);
            $data['msg'] = $this->request->get('msg', ['string', 'trim']);

            if(empty($type)){
                unset($data['amount']);
                $type = 'attach';
                $data['attach'] = $this->request->get('attach', ['string', 'trim']);
            }

            $fail = 0;
            $tmparray = explode('-',$data['user_id']);
            if(count($tmparray) <= 1){
                $fail = 1;
            }

            if($type == 'attach'){
                if($fail == 1){
                    Utils::tips('error', '用户ID不正确', '/game/reissue');
                }
                if (!$data['attach']) {
                    Utils::tips('error', '数据不完整', '/game/reissue');
                }
            }else{
                if($fail == 1){
                    echo json_encode(array('error' => 1, 'data' => '用户ID不正确'));
                    exit;
                }
                if ($type == 'mail'){
                    if (!$data['msg']) {
                        echo json_encode(array('error' => 1, 'data' => '数据不完整'));
                        exit;
                    }
                }else{
                    if (!$data['amount']) {
                        echo json_encode(array('error' => 1, 'data' => '数据不完整'));
                        exit;
                    }
                }
            }

            $result = $this->gameModel->prop($type, $data);

            if ($result) {
                if($type == 'attach'){
                    Utils::tips('success', '补发成功', '/game/reissue');
                }else{
                    echo json_encode(array('error' => 0, 'data' => '补发成功'));
                    exit;
                }
            }
            else {
                if($type == 'attach'){
                    Utils::tips('error', '补发失败', '/game/reissue');
                }else{
                    echo json_encode(array('error' => 1, 'data' => '补发失败'));
                    exit;
                }
            }
        }

        $result = $this->gameModel->attribute();
        $this->view->lists = $result['data'];
    }

    /**
     * 补发其他属性
     */
    public function otherAction()
    {

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

}