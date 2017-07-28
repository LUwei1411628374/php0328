<?php

namespace backend\controllers;

use backend\models\MyForm;
use backend\models\User;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class UserController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model =User::find()->where(['=','status',1]);
        $total= $model->count();
        $pages = 2;
        $page= new Pagination([
           'totalCount'=>$total,
            'defaultPageSize'=>$pages
        ]);
        $users=$model->limit($page->limit)->offset($page->offset)->all();

        return $this->render('index',['page'=>$page,'users'=>$users]);
    }

//    添加
    public function actionAdd(){
        $model =new User();
        $model->scenario = User::SCENARIO_ADD;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
            $model->created_at=time();
            $model->status=1;
            $model->auth_key = \Yii::$app->security->generateRandomString();
           // var_dump($model);exit;
            $model->save(false);
            $authManager = \Yii::$app->authManager;
            if (is_array($model->rules)) {
                foreach ($model->rules as $ruleName) {
                    $rule = $authManager->getRole($ruleName);
                    if ($rule) $authManager->assign($rule, $model->id);
                }
            }
            \Yii::$app->session->setFlash('success','用户添加成功');
            return $this->redirect(['index']);
        }else{
            var_dump($model->getErrors());
        }
        return $this->render('add',['model'=>$model]);
    }
//修改
    public function actionEdit($id){

        $model = User::findOne(['id'=>$id]);
       // $model->scenario = User::SCENARIO_EDIT;//指定当期场景为修改场景
        if($model==null){
            throw new NotFoundHttpException('账号不存在');
        }
        $model->rules=ArrayHelper::map(\Yii::$app->authManager->getRolesByUser($id),'name','description');
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
           // var_dump($model);exit;
            if($model->password){
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
            }
            $model->touch('updated_at');
            // var_dump($model);exit;
            $model->save(false);

            $authManager = \Yii::$app->authManager;
            $authManager->revokeAll($id);
            if (is_array($model->rules)) {
                foreach ($model->rules as $ruleName) {
                    $rule = $authManager->getRole($ruleName);
                    if ($rule) $authManager->assign($rule, $model->id);
                }
            }
            //var_dump($model->getErrors());exit;
            \Yii::$app->session->setFlash('success','用户修改成功');
            return $this->redirect(['index']);
        }
        return $this->render('add',['model'=>$model]);
    }

//    删除
    public function actionDelete($id){
        \Yii::$app->authManager->revokeAll($id);
        $model=User::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->setFlash('success','用户删除成功');
        return $this->redirect(['index']);
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
//登录
    public function actionLogin(){
        $model = new \backend\models\LoginForm();
        $models = new User(['scenario'=>User::SCENARIO_LOGIN]);
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate() && $model->login()){
                //登录成功
                \Yii::$app->session->setFlash('success','登录成功');
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('login',['model'=>$model]);

    }

//    注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['user/login']);
    }

//修改自己密码
    public function actionMy(){
        //获取当前登录用户的id
        $id = \Yii::$app->user->id;
        //如果没有登录跳转登录页面
        if(!$id){
            \Yii::$app->session->setFlash('success','你还没有登录请登录');
            return $this->redirect('login');
        }
        $models =User::findOne(['id'=>$id]);
        $model=new MyForm();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //判断旧密码和数据库是否一致
                if(\Yii::$app->security->validatePassword($model->old_password,$models->password_hash)){
                    //判断旧密码和新密码是否一致
                    if(\Yii::$app->security->validatePassword($model->new_password,$models->password_hash)){
                        \Yii::$app->session->setFlash('success','旧密码和新密码一致');
                        return $this->redirect('my');
                    }
                    $models->password_hash=\Yii::$app->security->generatePasswordHash($model->new_password);
                    $models->save(false);
                    //修改成功注销帐号  重新登录
                    \Yii::$app->user->logout();
                    \Yii::$app->session->setFlash('success','修改密码成功,请重新登录');
                    return $this->redirect('login');
                }else{
                    $model->addError('old_password','旧密码错误');
                }
            }
        }
        return $this->render('my',['model'=>$model]);
    }

}


