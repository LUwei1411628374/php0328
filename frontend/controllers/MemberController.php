<?php

namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\Location;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\captcha\CaptchaAction;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class MemberController extends \yii\web\Controller
{
    //关闭默认场景
    public $layout = false;
    //关闭scrf
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

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
            $model->auth_key = \Yii::$app->security->generateRandomString();
            $model->created_at = time();
            $model->status = 1;
            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
            $model->save(false);
            //保存数据提示验证成功
            return Json::encode(['status'=>true,'msg'=>'注册成功']);

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

                    return Json::encode(['status'=>true,'msg'=>'登录成功']);
                }else{
                    //返回错误信息
                    return Json::encode(['status'=>false,'msg'=>'密码错误']);
                }
            }else{
                //返回错误信息
                return Json::encode(['status'=>false,'msg'=>'用户名不存在']);
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
        $model = new Address();
        $member_id = \Yii::$app->user->id;
        $address = $model->find()->where(['member_id'=>$member_id])->all();
        $request = new Request();

        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $province=Address::getName($model->province)->name;
                $center=Address::getName($model->center)->name;
                $area = Address::getName($model->area)->name;
                $model->city = $province.$center.$area;
                $model->member_id=\Yii::$app->user->id;
                if($model->status){
                    $model->status=1;
                }else{
                    $model->status=0;
                }
                $model->save();
                \Yii::$app->session->setFlash('success','地址添加成功');
                return $this->redirect(['member/address']);
            }else{
                print_r($model->getErrors());
            }
        }

        return $this->render('address',['model'=>$model,'address'=>$address]);
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
        $address = $model->find()->where(['member_id'=>$member_id])->all();
        $model = Address::findOne(['id'=>$id]);
       $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
            }else{
                print_r($model->getErrors());exit;
            }
        }

        return $this->render('address',['model'=>$model,'address'=>$address]);

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
}
