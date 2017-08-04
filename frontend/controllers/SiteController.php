<?php
namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Cart;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\Cookie;
use yii\web\Request;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
//    添加到购物车
    public function actionAddCart($goods_id,$amount){
//        未登录保存到cookie
        if(Yii::$app->user->isGuest){
            $cookies = Yii::$app->request->cookies;
//            获取cookies里购物车数据
            $shop = $cookies->get('shop');
//            判断cookies里有没有数据
            if($shop==null){
                $shops = [$goods_id=>$amount];
            }else{
                $shops = unserialize($shop->value);
//                判断购物车里面是否已经有此商品 有就增加 没有就添加新数据
                if(isset($shops[$goods_id])){
                    $shops[$goods_id] +=$amount;
                }else{
//                    没有就添加新数据
                    $shops[$goods_id] = $amount;
                }
            }
//            将数据写入cookie
            $cookies = Yii::$app->response->cookies;
            //将数据以数组的形式保存到cookie中
            $cookie = new Cookie([
               'name'=>'shop',
                'value'=>serialize($shops),
                'expire'=>7*24*3600+time()
            ]);
            $cookies->add($cookie);
            //var_dump($cookies->get(''));
        }else{
            $cart=new Cart();
            $member_id = Yii::$app->user->id;
            $cart->amount= Yii::$app->request->get('amount');
            $cart->goods_id = Yii::$app->request->get('goods_id');
            if($cart->validate()){
                $model = Cart::find()->where(['and',"member_id=$member_id","goods_id=$cart->goods_id"])->one();
                if($model==null){
                    $cart->member_id=$member_id;
                    $cart->save();
                }else{

                    $model->amount += $amount;
                    $model->save();
                }
            }
        }
        return $this->redirect(['cart']);
    }


//    购物车展示
    public function actionCart(){
        $this->layout = false;
        //判断用户属否登录 没有登录从cookie中取数据  登录从数据库取数据
        if(Yii::$app->user->isGuest){
            $cookies=Yii::$app->request->cookies;
            $shop = $cookies->get('shop');
            //判断cookie中是否有数据  没有返回空数组
            if($shop==null){
                $shops= [];
            }else{
                $shops = unserialize($shop);
            }
            //var_dump($shops);exit;
            $models = Goods::find()->where(['in','id',array_keys($shops)])->asArray()->all();
        }else{
            //用户登录状态从数据表取出数据
            $member_id = \Yii::$app->user->id;
            $models = Cart::find()->where(['member_id' => $member_id])->all();
            $goods_id = [];
            $shops = [];
            foreach ($models as $model) {
                //将得到得goods_id和放入数组中
                $goods_id[] = $model->goods_id;

                $shops[$model->goods_id] = $model->amount;
            }
            //查询出所有商品
            $models = Goods::find()->where(['in', 'id', $goods_id])->all();
        }
        return $this->render('cart',['models'=>$models,'shops'=>$shops]);
    }
//    修改购物车数量
    public function actionAjaxCart(){
        //接收数据
        $goods_id = Yii::$app->request->post('goods_id');
        $amount = Yii::$app->request->post('amount');
        //判断是否登录
        if(Yii::$app->user->isGuest){
            $cookies = Yii::$app->request->cookies;
            $shop = $cookies->get('shop');
            //判断是否有数据
            if($shop==null){
                //没有数据就添加
                $shops = [$goods_id=>$amount];
            }else{
                //有数据就判断里面有没有此商品没有就添加，有就增加数量
                $shops = unserialize($shop->value);
                if(isset($shops[$goods_id])){
                    //有就更新数量
                    $shops[$goods_id]=$amount;
                }else{
                    //没有就添加
                    $shops[$goods_id]=$amount;
                }
            }
            //将商品id和商品数量写入cookie
            $cookies = Yii::$app->response->cookies;
            $cookie = new Cookie([
               'name'=>'shop',
                'value'=>serialize($shops),
                'expire'=>7*24*3600+time()
            ]);
            $cookies->add($cookie);
            return 'success';
        }else{
            $member_id = \Yii::$app->user->identity->getId();
            $models = Cart::find()->where(['and','member_id'=>$member_id,'goods_id'=>$goods_id])->one();
            $models->amount = $amount;
            $models->save();
        }
    }

//    删除
    public function actionDelete($id){
        if(!\Yii::$app->user->isGuest){
            $member_id=\Yii::$app->user->identity->getId();
            $models = Cart::find()
                ->andWhere(['member_id'=>$member_id])
                ->andWhere(['goods_id'=>$id])
                ->one();
            $models->delete();
            return $this->redirect(['site/cart']);
        }
        else{

            $cookies=\Yii::$app->request->cookies;
            $carts=unserialize($cookies->get('goods'));
            unset($carts[$id]);
            $cookies=\Yii::$app->response->cookies;
            //实例化cookie
            $cookie=new Cookie([
                'name'=>'goods',//cookie名
                'value'=>serialize($carts) ,//cookie值
                'expire'=>3*24*3600+time(),//设置过期时间
            ]);
            $cookies->add($cookie);//将数据保存到cookie
            return $this->redirect(['site/cart']);

        }
    }

}


