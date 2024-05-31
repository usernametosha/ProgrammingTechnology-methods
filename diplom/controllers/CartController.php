<?php

namespace app\controllers;

use app\models\Product;
use app\models\ProductAdminSearch;
use app\models\Cart;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\helpers\VarDumper;
/**
 * ProductAdminController implements the CRUD actions for Product model.
 */
class CartController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                            'matchCallback' => function ($rule, $action) {
                                return Yii::$app->user->identity->login;
                            },
                        ],
                        [
                            'denyCallback' => function ($rule, $action) {
                                $this->goHome();
                            }
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }
public function actionView()
{
    if( Yii::$app->request->isPost )
    {
        $btn = Yii::$app->request->get('btn');
        $id = Yii::$app->request->get('id');
        $page = Yii::$app->request->get('page');
        $per_page = Yii::$app->request->get('per_page');

        $url = "/cart/view?";
        $url .= $page ? 'page=' . $page : '';
        $url .= $per_page ? ( $page ? '&' : '') . 'per_page=' . 'per_page=' . $per_page : '';
        $url .= preg_match('/\=/', $url) ? '$' : '';
   
        if( $btn )
        {
             switch($btn)
             {
                 case 'minus': Cart::deleteFromCart($id); break;
                 case 'plus': $res = Cart::addToCart($id); break;
                 case 'trash': Cart::deleteFromCart($id, true); break;
                 case 'clear': $res_js = Cart::clearCart(); break;
                 case 'btn-add': $res_js = Cart::addToCart($id); break;
             }
             if( isset($res_js) )
             {
                 return $this->asJson($res_js);
             }
        }

        $cart = Cart::getCart();
       


        if( !empty($cart['count']) )
        {
            $dataProvider = new ArrayDataProvider([ 'allModels' => $cart['products'], 'pagination' => [
                'pageSize' => 2,
            ],]);
        }
    
        return $this->renderAjax('view', compact('cart', 'dataProvider', 'url', 'res'));
        }
    } 
}
