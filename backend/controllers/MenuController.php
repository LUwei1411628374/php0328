<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Menu;
use yii\web\NotFoundHttpException;

class MenuController extends \yii\web\Controller
{
//    菜单列表
    public function actionIndex()
    {
        $models = Menu::find()->where(['parent_id' => 0])->all();
        return $this->render('index', ['models' => $models]);
    }

//    添加菜单
    public function actionAdd()
    {
        $model = new Menu();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success', '添加菜单成功');
            return $this->redirect('index');
        }
        return $this->render('add', ['model' => $model]);
    }

//    修改菜单
    public function actionEdit($id)
    {
        $model = Menu::findOne(['id' => $id]);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->parent_id && !empty($model->children)) {
                $model->addError('parent_id', '只能为顶级菜单');
            } else {
                $model->save();
                \Yii::$app->session->setFlash('success', '修改菜单成功');
                return $this->redirect(['index']);
            }
        }

        return $this->render('add', ['model' => $model]);
    }

//    删除菜单
    public function actionDelete($id)
    {
        $menu=Menu::findOne(['id'=>$id]);
        if(!$menu){
            throw new NotFoundHttpException('该菜单不存在');
        }
        if(!empty($menu->children)){
            throw new NotFoundHttpException('该菜单有子菜单，不能删除');
        }else{
            $menu->delete();
        //跳转到菜单列表
        return $this->redirect(['menu/index']);
    }

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

