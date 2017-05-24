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
        $this->view->common = [
            'user_id'  => $this->session->get('user_id'),
            'name'     => $this->session->get('name'),
            'username' => $this->session->get('username'),
            'avatar'   => $this->session->get('avatar'),
        ];
        $this->gameModel = new Game();
    }

    public function switch_appAction()
    {
        if ($_POST) {
            $gameid = $this->request->get('gameid', 'int');

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
            $id = $this->request->get('id', 'int');

            if (!$id) {
                echo json_encode(array('error' => 1, 'data' => '参数错误'));
                exit;
            }

            $game = $this->gameModel->findFirst($id);
            if (!$game) {
                echo json_encode(array('error' => 1, 'data' => '没有此版本'));
                exit;
            }
            if (!in_array($game->game_id, DI::getDefault()->get('session')->get('resources')['allow_game'])) {
                echo json_encode(array('error' => 1, 'data' => '没有权限'));
                exit;
            }

            DI::getDefault()->get('session')->set('app', $game->game_id);
            DI::getDefault()->get('session')->set('group', $game->class_id);
            DI::getDefault()->get('session')->set('lang', $game->version);

            echo json_encode(array('error' => 0, 'data' => array()));
            exit;
        }
    }

}