<?php

namespace app\controllers;

use app\models\Order;
use app\models\OrderProduct;
use app\models\OrderSearch;
use app\models\Status;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;
use app\models\Cart;
use yii\data\ArrayDataProvider;
use app\models\LoginForm;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\db\Transaction;
/**
 * l-kController implements the CRUD actions for Order model.
 */
class LkController extends Controller
{
    /**
     * @inheritDoc
     */
    public function beforeAction($action)
    {
        // your custom code here, if you want the code to run before action filters,
        // which are triggered on the [[EVENT_BEFORE_ACTION]] event, e.g. PageCache or AccessControl

        if (!parent::beforeAction($action)) {
            return false;
        }

        !Yii::$app->user->isGuest || Yii::$app->user->identity->isAdmin;

        return true; // or false to not run the action
    }
    /**
     * Lists all Order models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {   if ($model = $this->findModel($id)) {
        $products =
                    (new \yii\db\Query())
                                    ->select(['product.title as product_title', 'product.price', 'order_product.count as count_product'])
                                    ->from('order_product')
                                    ->innerJoin('product', 'product.id = order_product.product_id' )
                                    ->where(['order_id' => $id])
                                    ->all();
        $status = Status::getStatus();
        $statusNew = Status::getIdStatusName('Новый');

        return $this->render('view', [
            'model' => $model,
            'products' => $products,
            'status' => $status,
            'statusNew' => $statusNew,
        ]);
        }
         $this->redirect(['index']);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if ($cart = Cart::getCart()) {
            $dataProvider = new ArrayDataProvider(['allModels' => $cart['products'], 'pagination' => ['pageSize' => 4]]);
            $login = new LoginForm();
            $login->username = Yii::$app->user->identity->login;

            if (Yii::$app->request->isAjax && $login->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($login);
            }
            if (Yii::$app->request->isPost && $login->load(Yii::$app->request->post()) && $login->validate()) {
                try {
                    $transaction = Yii::$app->db->beginTransaction();
                    $order = new Order();

                    if ($order->orderCreate($cart))
                        {
//                            var_dump(1);die;
                        if (Orderproduct::orderItemsCreate($cart, $order->id)) {

                            $transaction->commit();
                            Yii::$app->session->remove('cart');
                            Yii::$app->session->setFlash('success', 'Заказ оформлен.');
                            return $this->redirect(['/lk/index']);
                        }
                    }
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Ошибка при оформлении заказа');
                }
            }
            return $this->render('create', compact('login', 'cart', 'dataProvider'));
        }
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
