<?php


/**
 * 公共类
 */

namespace MyApp\Controllers;


use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use MyApp\Models\Game;
use MyApp\Models\Utils;
use Phalcon\DI;

class UtilsController extends Controller
{
    private $gameModel;

    public function initialize()
    {
        $this->view->common = [
            'user_id'   => $this->session->get('user_id'),
            'name'      => $this->session->get('name'),
            'username'  => $this->session->get('username'),
            'avatar'    => $this->session->get('avatar'),
            'menu_true' => $this->session->get('resources')['menu_tree'],
        ];
        $this->gameModel = new Game();
    }

    public function switch_appAction()
    {
        $allow_game = DI::getDefault()->get('session')->get('resources')['allow_game'];

        if (empty($allow_game)) {
            Utils::tips('error', '没有权限', '', 999999);
        }


        foreach ($allow_game as $item) {
            $class_id = substr($item, 0, 3);
            $classid[] = $class_id;
        }

        $classid = (array_unique($classid));

        foreach ($classid as $value) {
            $gameList[] = $this->gameModel->getGame($value);
        }
        foreach ($gameList as $game) {
            $gameid[] = $game['id'];
            $apps[$game['class_id']]['info'] = $game;
        }
        foreach ($gameid as $game_id) {
            $version[] = $this->gameModel->getVersionList($game_id);
        }

        foreach ($version as $ver) {
            foreach ($ver as $v) {
                $apps[$v['class_id']]['data']["$v[game_id]"] = $v;
            }
        }

        foreach ($apps as $app) {
            $count = count($app);
            if ($count == 1) {
                $apps[$app['info']['class_id']]['data'] = array();
            }

        }
        $this->view->apps = empty($apps) ? array() : $apps;
    }

    /**
     * 切换游戏版本
     */
    public function setGameVersionAction()
    {
        if ($_GET) {
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
            DI::getDefault()->get('session')->set('game_name', $game->name);

            if (isset($_COOKIE['attach'])) {
                $_COOKIE['attach'] = '';
                setcookie('attach', '', -86400, '/');
            }

            if (isset($_COOKIE['serverLists'])) {
                $_COOKIE['serverLists'] = '';
                setcookie('serverLists', '', -86400, '/');
            }
            
        }
    }

}