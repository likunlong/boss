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
        $this->view->gateways = $this->gatewaysModel->getList();
    }

    /**
     * 创建网关
     */
    public function creategatewaysAction()
    {
        if ($_POST) {
            $this->gatewaysModel->app_id = $this->session->get('app');
            $this->gatewaysModel->gateway = $this->request->get('gateway', 'string');
            $this->gatewaysModel->name = $this->request->get('name', 'string');
            $this->gatewaysModel->sub = $this->request->get('sub', 'string');
            $this->gatewaysModel->type = $this->request->get('type', 'string');
            $this->gatewaysModel->remark = $this->request->get('remark', 'string');
            $this->gatewaysModel->currency = $this->request->get('currency', 'string');
            $this->gatewaysModel->parent = $this->request->get('parent', 'int');
            $this->gatewaysModel->sort = $this->request->get('sort', 'int');
            $this->gatewaysModel->sandbox = $this->request->get('sandbox', 'int');
            $this->gatewaysModel->tips = $this->request->get('tips', 'string');

            if (!$this->gatewaysModel->name || !$this->gatewaysModel->app_id || !$this->gatewaysModel->gateway || !$this->gatewaysModel->sub) {
                Utils::tips('error', '数据不完整', '/setting/creategateways');
            }

            $this->gatewaysModel->save();
            Utils::tips('success', '添加成功', '/setting/gateways');
        }

        $this->view->parent = $this->gatewaysModel->getParent();
    }

    /**
     * 修改网关
     */
    public function editgatewaysAction()
    {
        $id = $this->request->get('id');
        if (!$id) {
            Utils::tips('error', '数据不完整', '/setting/gateways');
        }

        $gateways = $this->gatewaysModel->findFirst($id);
        if (!$gateways) {
            Utils::tips('error', '没有此数据', '/setting/gateways');
        }

        if ($_POST) {
            $gateways->app_id = $this->session->get('app');
            $gateways->gateway = $this->request->get('gateway', 'string');
            $gateways->name = $this->request->get('name', 'string');
            $gateways->sub = $this->request->get('sub', 'string');
            $gateways->type = $this->request->get('type', 'string');
            $gateways->remark = $this->request->get('remark', 'string');
            $gateways->currency = $this->request->get('currency', 'string');
            $gateways->parent = $this->request->get('parent', 'int');
            $gateways->sort = $this->request->get('sort', 'int');
            $gateways->sandbox = $this->request->get('sandbox', 'int');
            $gateways->tips = $this->request->get('tips', 'string');

            if (!$gateways->name || !$gateways->app_id || !$gateways->gateway || !$gateways->sub) {
                Utils::tips('error', '数据不完整', '/setting/editgateways?id=' . $gateways['id']);
            }

            $gateways->save();
            Utils::tips('success', '修改成功', '/setting/gateways');
        }

        $this->view->parent = $this->gatewaysModel->getParent();
        $this->view->gateways = $gateways->toArray();
    }

    /**
     * 修改网关
     */
    public function removegatewaysAction()
    {
        $id = $this->request->get('id');
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
            $timeZone = $this->request->get('timeZone');

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
            echo json_encode(array('error' => 0, 'data' => '时区切换成功'));
            exit;
        }

    }

}