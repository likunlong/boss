<?php


/**
 * 设置
 */
namespace MyApp\Controllers;


use MyApp\Models\Timezone;
use Phalcon\Mvc\Dispatcher;
use MyApp\Models\Gateways;
use MyApp\Models\Utils;

class SettingController extends ControllerBase
{

    private $gatewaysModel;

    private $timezoneModel;


    public function initialize()
    {
        parent::initialize();
        $this->gatewaysModel = new Gateways();
        $this->timezoneModel = new Timezone();
    }


    public function indexAction()
    {
    }


    /**
     * 网关
     */
    public function gatewaysAction()
    {

        $do = $this->request->get('do', ['string', 'trim']);

        switch ($do) {

            case 'create':
                if ($_POST) {
                    $this->_gateways_create();
                }

                $this->view->pick("setting/gateways_create");
                $this->view->parent = $this->gatewaysModel->getParent();
                break;

            case 'edit':
                $id = $this->request->get('id', 'int');
                if (!$id) {
                    Utils::tips('error', '数据不完整', '/setting/gateways');
                }

                $gateways = $this->gatewaysModel->findFirst($id);
                if (!$gateways) {
                    Utils::tips('error', '没有此数据', '/setting/gateways');
                }

                if ($_POST) {
                    $this->_gateways_edit($gateways);
                }

                $this->view->parent = $this->gatewaysModel->getParent();
                $this->view->gateways = $gateways->toArray();
                $this->view->pick("setting/gateways_edit");
                break;

            case 'remove':
                $this->_gateways_remove();
            default:
                $this->view->gateways = $this->gatewaysModel->getList();
                $this->view->pick("setting/gateways");
        }
    }


    /**
     * 创建网关
     */
    private function _gateways_create()
    {
        $this->gatewaysModel->app_id = $this->session->get('app');
        $this->gatewaysModel->gateway = $this->request->get('gateway', ['string', 'trim']);
        $this->gatewaysModel->name = $this->request->get('name', ['string', 'trim']);
        $this->gatewaysModel->sub = $this->request->get('sub', ['string', 'trim']);
        $this->gatewaysModel->type = $this->request->get('type', ['string', 'trim']);
        $this->gatewaysModel->remark = $this->request->get('remark', ['string', 'trim']);
        $this->gatewaysModel->currency = $this->request->get('currency', ['string', 'trim']);
        $this->gatewaysModel->parent = $this->request->get('parent', 'int');
        $this->gatewaysModel->sort = $this->request->get('sort', 'int');
        $this->gatewaysModel->visible = $this->request->get('visible', 'int',0);
        $this->gatewaysModel->sandbox = $this->request->get('sandbox', 'int');
        $this->gatewaysModel->tips = $this->request->get('tips', ['string', 'trim']);

        if (!$this->gatewaysModel->name || !$this->gatewaysModel->app_id || !$this->gatewaysModel->gateway) {
            Utils::tips('error', '数据不完整', '/setting/gateways?do=create');
        }

        $this->gatewaysModel->save();
        Utils::tips('success', '添加成功', '/setting/gateways');
    }


    /**
     * 修改网关
     */
    private function _gateways_edit($gateways)
    {
        $gateways->app_id = $this->session->get('app');
        $gateways->gateway = $this->request->get('gateway', ['string', 'trim']);
        $gateways->name = $this->request->get('name', ['string', 'trim']);
        $gateways->sub = $this->request->get('sub', ['string', 'trim']);
        $gateways->type = $this->request->get('type', ['string', 'trim']);
        $gateways->remark = $this->request->get('remark', ['string', 'trim']);
        $gateways->currency = $this->request->get('currency', ['string', 'trim']);
        $gateways->parent = $this->request->get('parent', 'int');
        $gateways->sort = $this->request->get('sort', 'int');
        $gateways->visible = $this->request->get('visible', 'int', 0);
        $gateways->sandbox = $this->request->get('sandbox', 'int');
        $gateways->tips = $this->request->get('tips', ['string', 'trim']);

        if (!$gateways->name || !$gateways->app_id || !$gateways->gateway) {
            Utils::tips('error', '数据不完整', '/setting/editgateways?id=' . $gateways['id']);
        }

        $gateways->save();
        Utils::tips('success', '修改成功', '/setting/gateways');
    }


    /**
     * 修改网关
     */
    private function _gateways_remove()
    {
        $id = $this->request->get('id', 'int');
        if (!$id) {
            Utils::tips('error', '数据不完整', '/setting/gateways');
        }

        $gateways = $this->gatewaysModel->findFirst($id);
        if (!$gateways) {
            Utils::tips('error', '没有此数据', '/setting/gateways');
        }

        $gateways->delete();
        Utils::tips('success', '删除成功', '/setting/gateways');
    }


    /**
     * 设置时区
     */
    public function timezoneAction()
    {
        if ($_POST) {
            $timeZone = $this->request->get('timeZone', ['string', 'trim']);
            $zonetext = $this->request->get('zonetext', ['string', 'trim']);

            if (!$timeZone) {
                echo json_encode(array('error' => 0, 'data' => '参数错误'));
                exit;
            }

            $timezone = $this->timezoneModel->findOne();

            if ($timezone) {
                $timezone['timezone'] = $timeZone;
                $this->timezoneModel->save($timezone);
            }
            else {
                $this->timezoneModel->app_id = $this->session->get('app');
                $this->timezoneModel->user_id = $this->session->get('user_id');
                $this->timezoneModel->timezone = $timeZone;
                $this->timezoneModel->save();
            }

            $this->session->set('timezone', $timeZone);
            $this->session->set('zone_text', $zonetext);
            echo json_encode(array('error' => 0, 'data' => '时区切换成功'));
            exit;
        }

        $userTimeZone = $this->timezoneModel->findOne();
        if (!empty($userTimeZone)) {
            $this->session->set('zone_text', $this->timeText($userTimeZone['timezone']));
        }

    }

    private function timeText($key = 'Asia/Shanghai')
    {
        $timeArray = array(
            'Asia/Shanghai'       => '上海',
            'Asia/Taipei'         => '台北',
            'Asia/Seoul'          => '韩国',
            'Asia/Tokyo'          => '日本',
            'Asia/Bangkok'        => '泰国',
            'Asia/Hanoi'          => '越南',
            'Asia/Singapore'      => '马来西亚',
            'Europe/London'       => '伦敦',
            'Europe/Paris'        => '巴黎',
            'Europe/Rome'         => '罗马',
            'Europe/Berlin'       => '柏林',
            'America/Los_Angeles' => '洛杉矶',
            'America/Chicago'     => '芝加哥',
            'America/Vancouver'   => '温哥华',
        );

        return $timeArray[$key];
    }

}