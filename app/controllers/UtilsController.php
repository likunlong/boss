<?php


/**
 * 公共类
 */

namespace MyApp\Controllers;


use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use MyApp\Models\Game;
use Phalcon\DI;

class UtilsController extends Controller
{
    private $gameModel;

    public function initialize()
    {
        $this->view->common = ['user_id' => '', 'name' => '', 'username' => '', 'avatar' => ''];
        $this->gameModel = new Game();
    }

    public function switch_appAction()
    {
        if ($_POST) {
            $gameid = $this->request->get('gameid');

            if (!$gameid) {
                echo json_encode(array('error' => 1, 'data' => '参数错误'));
                exit;
            }

            $version = $this->gameModel->getVersionList($gameid);
            echo json_encode(array('error' => 0, 'data' => $version));
            exit;
        }

        $gameList = $this->gameModel->getGame();
        $this->view->lists = empty($gameList) ? array() : $gameList;
    }

    /**
     * 切换游戏版本
     */
    public function setGameVersionAction()
    {
        if ($_POST) {
            $id = $this->request->get('id', 'string');

            if (!$id) {
                echo json_encode(array('error' => 1, 'data' => '参数错误'));
                exit;
            }

            $version = $this->gameModel->findFirst($id);

            if (!$version) {
                echo json_encode(array('error' => 1, 'data' => '没有此版本'));
                exit;
            }

            DI::getDefault()->get('session')->set('app', $version->game_id);
            DI::getDefault()->get('session')->set('group', $version->class_id);
            DI::getDefault()->get('session')->set('lang', $version->version);

            echo json_encode(array('error' => 0, 'data' => array()));
            exit;
        }
    }

}