<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Location;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\captcha\CaptchaAction;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class MemberController extends \yii\web\Controller
{
    //关闭默认场景
    public $layout = false;
    //关闭scrf
    public $enableCsrfValidation = false;
//    首页
    public function actionIndex()
    {
        $models = GoodsCategory::find()->where(['parent_id'=>0])->all();

        $content=$this->render('index',['models'=>$models]);
        file_put_contents('index.html',$content);

    }
//    商品列表

    public function actionList($id){
        $a = GoodsCategory::find()->select(['tree', 'lft', 'rgt'])->where(['id' => $id])->one();
        $id = GoodsCategory::find()->select('id')
            ->andWhere(['tree' => $a->tree])
            ->andWhere(['>=', 'lft', $a->lft])
            ->andWhere(['<=', 'rgt', $a->rgt])
            ->all();
        $b = ArrayHelper::map($id, 'id', 'id');

//        var_dump($b); exit;
        $lists=Goods::find()->where(['in','goods_category_id',$b])->all();
        //var_dump($model);exit;
        return $this->render('list',['lists'=>$lists]);
    }
//    商品详情
    public function actionGoods($id){
        $models = Goods::findOne(['id'=>$id]);
       // var_dump($models);exit;
        //var_dump($models->galleries);exit;
        $pics = $models->galleries;
        //var_dump($pic);exit;
        return $this->render('goods',['models'=>$models,'pics'=>$pics]);
    }


//  商品列表
    /*public function actionLists($id){
        $a = GoodsCategory::find()->where(['id'=>$id])->one();
        $models = GoodsCategory::find()->where(['id'=>$a->parent_id])->one();
        //var_dump($models->name);exit;
        $lists = Goods::find()->where(['goods_category_id'=>$id])->all();

        return $this->render('list',['lists'=>$lists,'models'=>$models]);
    }*/
    //使用模版
    public function actionRegist(){
        $model = new Member();
       // $model->scenario = Member::SCENARIO_REGIST;
        return $this->render('regist',['model'=>$model]);
    }
//    注册
    public function actionAjaxRegist(){
        $model = new Member();
       // $model->scenario = Member::SCENARIO_REGIST;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $code = \Yii::$app->session->get('code_'.$model->tel);
            if($model->telCode == $code){
                $model->auth_key = \Yii::$app->security->generateRandomString();
                $model->created_at = time();
                $model->status = 1;
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
                $model->save(false);
                //保存数据提示验证成功
                return Json::encode(['status'=>true,'msg'=>'注册成功']);
            }else{
                $model->addError('telCode',"手机或者手机验证码不正确 ");
                return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
            }
        }else{
            //返回错误信息
            return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
        }


    }
//    登录视图
    public function actionLogin(){
        $model = new LoginForm();

        return $this->render('login',['model'=>$model]);
    }
//    登录数据处理
    public function actionAjaxLogin(){
        $model = new LoginForm();
        //var_dump($_POST);exit;
        //var_dump($model->load(\Yii::$app->request->post()));exit;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $member = Member::findOne(['username'=>$model->username]);
           // var_dump($member);exit;
            if($member){
                if(\Yii::$app->security->validatePassword($model->password,$member->password_hash)){
                    //密码正确
                    $log = $model->rememberMe?24*3600:0;
                  //  \Yii::$app->user->login($member,$log);
                    \Yii::$app->user->login($member,$log);
                    $member->last_login_time=time();
                    $member->last_login_ip=ip2long(\Yii::$app->request->userIP);
                    $member->save(false);
                    //2 登录(保存用户信息到session)
                    //同步cookie到数据库
                    $cookies = \Yii::$app->request->cookies;
                    $shop = $cookies->get('shop');
                    $member_id = \Yii::$app->user->id;
                    if($shop){
                        $shops = unserialize($shop);
                        foreach ($shops as $goods_id=>$amount){

                            $shopes = Cart::find()
                                ->andWhere(['member_id'=>$member_id])
                                ->andWhere(['goods_id'=>$goods_id])
                                ->one();
                            if($shopes){
                                $shopes->amount+=$amount;
                                $shopes->save();
                            }else{
                                $cart = new Cart();
                                $cart->amount=$amount;
                                $cart->goods_id=$goods_id;
                                $cart->member_id=$member_id;
                                $cart->save();
                            }
                        }

                        \Yii::$app->response->cookies->remove('shop');
                    }

                    return Json::encode(['status'=>true,'msg'=>'登录成功']);
                }else{
                    //返回错误信息
                    $model->addError('password','密码错误');
                    return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
                }
            }else{
                //返回错误信息
                $model->addError('username','用户名不存在');
                return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
            }
        }else{
            //返回错误信息
            return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
        }
    }

    //定义验证码操作
    public function actions(){
        return [
            'captcha'=>[
//                'class'=>'yii\captcha\CaptchaAction',
                'class'=>CaptchaAction::className(),
                'minLength'=>3,
                'maxLength'=>3,
            ]
        ];
    }
//    判断是否是游客
    public function actionUser(){
        var_dump(\Yii::$app->user->isGuest);
    }


//    添加收货地址
    public function actionAddress(){
        //实例化模型
        $model=new Address();
        $member_id=\Yii::$app->user->id;
        $addresses=Address::find()->where(['member_id'=>$member_id])->all();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->province=Address::getName($model->province)->name;
//            var_dump($model->province);exit;
            $model->city=Address::getName($model->city)->name;
            $model->area=Address::getName($model->area)->name;
            $model->address=($model->province).($model->city).($model->area).($model->address);
           // var_dump($model->status);exit;
//            var_dump($model->address);exit;
            if($model->status){
// $addresses->status = 0;
                $model->status=1;
            }else{
                $model->status=0;
            }
            $model->member_id=\Yii::$app->user->id;

            $model->save();
            //成功，提示
            \Yii::$app->session->setFlash('success','收货地址添加成功');
            //跳转
            return $this->redirect(['member/address']);
        }
        return $this->render('address',['model'=>$model,'addresses'=>$addresses]);
    }
//    删除收货地址
    public function actionAddDel($id){
        $model = Address::findOne(['id'=>$id]);
        if(!$model){
            throw new NotFoundHttpException('地址不存在');
        }
        $model->delete();
        return $this->redirect(['member/address']);
    }

//    修改收货地址
    public function actionAddEdit($id){
        $model = new Address();
        $member_id=\Yii::$app->user->id;
        $addresses = $model->find()->where(['member_id'=>$member_id])->all();
        $model = Address::findOne(['id'=>$id]);
       $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
            }else{
                print_r($model->getErrors());
            }
        }

        return $this->render('address',['model'=>$model,'addresses'=>$addresses]);

    }


//    设置默认地址
    public function actionAddStatus($id){
        $model=Address::findOne(['id'=>$id]);
        if($model->status==0){
            $model->status=1;
        }
        $model->save();
        return $this->redirect(['member/address']);
    }

    //获取三级联动地址
    public function actionLocation($id){
        $model =new Location();
        return $model->getProvince($id);
    }

//    测试阿里大于
    public function actionAli(){
        /*$classFile = \Yii::getAlias('@Aliyun/Core/Profile/DefaultProfile.php');
        var_dump($classFile);exit;*/
        $code = rand(100000,999999);
        $tel = '15928496450';
        \Yii::$app->sms->setPhoneNumbers($tel)->setTemplateParam(['code'=>$code])->send();
    }

//    手机验证码

    public function actionTel(){
        //$goods_id = Yii::$app->request->post('goods_id');
        $tels =\Yii::$app->request->post('tels');
        $code = rand(100000,999999);
        //$tel = '15928496450';
       $a= \Yii::$app->sms->setPhoneNumbers($tels)->setTemplateParam(['code'=>$code])->send();
       if($a){
           \Yii::$app->session->set('code_'.$tels,$code);
           return Json::encode(['status'=>true,'msg'=>'短信发送成功']);
       }

    }
}
