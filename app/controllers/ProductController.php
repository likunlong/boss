<?php


/**
 * 商品管理
 */
namespace MyApp\Controllers;


use MyApp\Models\Products;
use Phalcon\Mvc\Dispatcher;
use MyApp\Models\Gateways;
use MyApp\Models\Utils;


class ProductController extends ControllerBase
{
    private $gatewaysModel;
    private $productModel;

    public function initialize()
    {
        parent::initialize();
        $this->gatewaysModel = new Gateways();
        $this->productModel = new Products();
    }

    public function indexAction()
    {
        $gateways = $this->gatewaysModel->getGatewaysList();
        $data = [];
        foreach ($gateways as $item) {
            $item['product'] = $this->productModel->getProductList($item['gateway']);
            $data[] = $item;
        }
        $this->view->proData = $data;
    }

    /**
     * 发布产品
     */
    public function createAction()
    {
        if ($_POST) {
            $this->productModel->app_id = $this->session->get('app');
            $this->productModel->gateway = $this->request->get('gateway', ['string','trim']);
            $this->productModel->name = $this->request->get('name', ['string','trim']);
            $this->productModel->product_id = $this->request->get('product_id', ['string','trim']);
            $this->productModel->package = $this->request->get('package', ['string','trim']);
            $this->productModel->price = $this->request->get('price', ['string','trim']);
            $this->productModel->currency = $this->request->get('currency', ['string','trim']);
            $this->productModel->coin = $this->request->get('coin', ['int','trim']);
            $this->productModel->image = $this->request->get('image', ['string','trim']);
            $this->productModel->remark = $this->request->get('remark', ['string','trim']);
            $this->productModel->status = $this->request->get('status', ['int','trim']);
            $this->productModel->sort = $this->request->get('sort', ['string','trim']);
            $this->productModel->create_time = date('Y-m-d H:i:s');
            $this->productModel->update_time = date('Y-m-d H:i:s');

            if (!$this->productModel->name || !$this->productModel->app_id || !$this->productModel->price) {
                Utils::tips('error', '数据不完整', '/product/index');
            }

            $this->productModel->save();

            Utils::tips('success', '添加成功', '/product/index');
        }

        $this->view->parent = $this->gatewaysModel->getParent();
    }

    /**
     * 修改产品
     */
    public function editAction()
    {
        $id = $this->request->get('id', ['int','trim']);
        if (!$id) {
            Utils::tips('error', '数据不完整', '/product/index');
        }

        $product = $this->productModel->findFirst($id);
        if (!$product) {
            Utils::tips('error', '没有此数据', '/product/index');
        }

        if ($_POST) {
            $product->app_id = $this->session->get('app');
            $product->gateway = $this->request->get('gateway', ['string','trim']);
            $product->name = $this->request->get('name', ['string','trim']);
            $product->product_id = $this->request->get('product_id', ['string','trim']);
            $product->package = $this->request->get('package', ['string','trim']);
            $product->price = $this->request->get('price', ['string','trim']);
            $product->currency = $this->request->get('currency', ['string','trim']);
            $product->coin = $this->request->get('coin', ['int','trim']);
            $product->image = $this->request->get('image', ['string','trim']);
            $product->remark = $this->request->get('remark', ['string','trim']);
            $product->status = $this->request->get('status', ['int','trim']);
            $product->sort = $this->request->get('sort', ['int','trim']);
            $product->update_time = date('Y-m-d H:i:s');

            if (!$product->name || !$product->app_id || !$product->price) {
                Utils::tips('error', '数据不完整', '/product/edit?id=' . $product['id']);
            }

            $product->save();
            Utils::tips('success', '修改成功', '/product/index');
        }

        $this->view->parent = $this->gatewaysModel->getParent();
        $this->view->pro = $product->toArray();
    }


    /**
     * 删除产品
     */
    public function removeAction()
    {
        $id = $this->request->get('id', ['int','trim']);
        if (!$id) {
            Utils::tips('error', '数据不完整', '/product/index');
        }

        $product = $this->productModel->findFirst($id);
        if (!$product) {
            Utils::tips('error', '没有此数据', '/product/index');
        }

        $product->delete();
        Utils::tips('success', '删除成功', '/product/index');
    }


    /**
     * 导入产品
     */
    public function importAction()
    {

    }
}