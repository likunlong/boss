<?php


/**
 * 服务器管理
 */
namespace MyApp\Controllers;


use MyApp\Models\Server;
use Phalcon\Mvc\Dispatcher;
use MyApp\Models\Utils;

class ServerController extends ControllerBase
{
    private $serverModel;

    public function initialize()
    {
        parent::initialize();
        $this->serverModel = new Server();

    }

    public function indexAction()
    {
        $result = $this->serverModel->getLists();
        $this->view->lists = $result;
    }

    /**
     * 创建服务器
     */
    public function createAction()
    {
        if ($_POST) {
            $data['id'] = $this->request->get('id', ['string','trim']);
            $data['name'] = $this->request->get('name', ['string','trim']);
            $data['host'] = $this->request->get('host', ['string','trim']);
            $data['port'] = $this->request->get('port', 'int');
            $data['key'] = $this->request->get('key', ['string','trim']);
            $data['status'] = $this->request->get('status', ['string','trim']);
            $data['tag'] = $this->request->get('tag', ['string','trim']);
            $data['custom'] = '';

            if (!$data['id'] || !$data['name'] || !$data['host'] || !$data['status'] || !$data['tag']) {
                Utils::tips('error', '数据不完整', '/server/index');
            }

            $result = $this->serverModel->createServer($data);

            if($result){
                Utils::tips('success', '添加成功', '/server/index');
            }else{
                Utils::tips('error', '添加失败', '/server/index');
            }
        }
    }

    /**
     * 修改服务器
     */
    public function editAction()
    {
        $data['id'] = $this->request->get('id', 'int');
        if (!$data['id']) {
            Utils::tips('error', '数据不完整', '/server/index');
        }

        $server = $this->serverModel->findServer($data);
        if ($server == 1) {
            Utils::tips('error', '没有此数据', '/server/index');
        }

        if ($_POST) {
            $data['name'] = $this->request->get('name', ['string','trim']);
            $data['host'] = $this->request->get('host', ['string','trim']);
            $data['port'] = $this->request->get('port', 'int');
            $data['key'] = $this->request->get('key', ['string','trim']);
            $data['status'] = $this->request->get('status', ['string','trim']);
            $data['tag'] = $this->request->get('tag', ['string','trim']);
            $data['custom'] = '';

            if (!$data['id'] || !$data['name'] || !$data['host'] || !$data['status'] || !$data['tag']) {
                Utils::tips('error', '数据不完整', '/server/index');
            }

            $result = $this->serverModel->editServer($data);

            if($result){
                Utils::tips('success', '修改成功', '/server/index');
            }else{
                Utils::tips('error', '修改失败', '/server/index');
            }
        }

        $this->view->server = $server['data'];
    }

    /**
     * 删除服务器
     */
    public function removeAction(){
        $data['id'] = $this->request->get('id', 'int');
        if (!$data['id']) {
            Utils::tips('error', '数据不完整', '/server/index');
        }

        $activity = $this->serverModel->findServer($data);
        if ($activity == 1) {
            Utils::tips('error', '没有此数据', '/server/index');
        }

        $result = $this->serverModel->removeServer($data);

        if($result){
            Utils::tips('success', '删除成功', '/server/index');
        }else{
            Utils::tips('error', '删除失败', '/server/index');
        }
    }

    /**
     * 软件包
     */
    public function packageAction()
    {

    }

    /**
     * 创建软件包
     */
    public function createpackageAction()
    {

    }

}