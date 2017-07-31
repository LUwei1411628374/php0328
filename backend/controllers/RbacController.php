<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-26
 * Time: 14:07
 */
namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\PermissionForm;
use backend\models\RuleForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RbacController extends Controller
{
//    权限添加
    public function actionAdd(){
        $model = new PermissionForm();
        //使用场景
        $model->scenario=PermissionForm::SCENARIO_ADD;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //实例化 权限 类
            $authManager= \Yii::$app->authManager;
            //创建权限
            $permission=$authManager->createPermission($model->name);
            $permission->description=$model->description;
//            添加权限 保存到数据库
            $authManager->add($permission);
            \Yii::$app->session->setFlash('success','添加权限成功');
            return $this->redirect('index');
        }
        return $this->render('add',['model'=>$model]);
    }

//    权限列表
    public function actionIndex(){
//        获取所有权限
        $models = \Yii::$app->authManager->getPermissions();
        return $this->render('index',['models'=>$models]);
    }

//    权限修改
    public function actionEdit($name){
        //检测权限是否存在
        $authManager = \Yii::$app->authManager;
        $permission  = $authManager->getPermission($name);
        //var_dump($permission);exit;
        //权限不存在 抛出错误
        if($permission == null){
            throw new NotFoundHttpException('404','权限不存在');
        }
        //权限存在 将权限数据回显到表单  然后接受修改数据 将修改数据保存到数据库
        $model = new PermissionForm();
        if(\Yii::$app->request->isPost){
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                //接受表单提交的数据 赋值给权限
                $permission->name=$model->name;
                $permission->description=$model->description;
                //更新数据保存的数据库
                $authManager->update($name,$permission);
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect('index');
            }
        }else{
            //将权限数据回显到表单
            $model->name=$permission->name;
            $model->description=$permission->description;
        }
            return $this->render('add',['model'=>$model]);
    }

//    权限删除
    public function actionDelete($name){
        //判断权限是否存在
        $authManager=\Yii::$app->authManager;
        $permission=$authManager->getPermission($name);
        //var_dump($permission);exit;
        if($permission == null){
            throw new NotFoundHttpException('404','删除的数据不存在');
        }
        //权限存在
        $authManager->remove($permission);
        \Yii::$app->session->setFlash('success','权限删除成功');
        return $this->redirect('index');
    }

//    角色添加
    public function actionRuleAdd(){
        $model = new RuleForm();
        $model->scenario=RuleForm::SCENARIO_RULEADD;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //创建角色
            $authManager=\Yii::$app->authManager;
            $rule = $authManager->createRole($model->name);
            $rule->description = $model->description;
            $authManager->add($rule);
            //给角色赋予权限
            if(is_array($model->permissions)){
                foreach ($model->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    if($permission) $authManager->addChild($rule,$permission);
                }
            }

            \Yii::$app->session->setFlash('success','角色添加成功');
            return $this->redirect('rule-index');
        }

        return $this->render('rule-add',['model'=>$model]);
    }

//    角色列表
    public function actionRuleIndex(){
        $models = \Yii::$app->authManager->getRoles();
        return $this->render('rule-index',['models'=>$models]);
    }

//    角色修改
    public function actionRuleEdit($name){
        $model = new  RuleForm();
        $authManager=\Yii::$app->authManager;
        $rule=$authManager->getRole($name);
        if($rule == null){
            throw new NotFoundHttpException('404','修改的角色不存在');
        }

        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //先取消关联
            $authManager->removeChildren($rule);
            $rule->description = $model->description;
            $authManager->update($name,$rule);
            //给角色赋予权限
            if(is_array($model->permissions)){
                foreach ($model->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    if($permission) $authManager->addChild($rule,$permission);
                }
            }

            \Yii::$app->session->setFlash('success','角色修改成功');
            return $this->redirect('rule-index');
        }

        $permissions = $authManager->getPermissionsByRole($name);
        $model->name=$rule->name;
        $model->description=$rule->description;
        $model->permissions=ArrayHelper::map($permissions,'name','name');
        return $this->render('rule-add',['model'=>$model]);
    }

//    角色删除
    public function actionRuleDelete($name){
        $authManager=\Yii::$app->authManager;
        $model=$authManager->getRole($name);
//        var_dump($model);
       $authManager->remove($model);
       \Yii::$app->session->setFlash('success','角色删除成功');
       return $this->redirect('rule-index');
    }

    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }

}

