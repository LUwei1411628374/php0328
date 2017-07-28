<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Request;

class ArticleCategoryController extends \yii\web\Controller
{
//    列表
    public function actionIndex()
    {
        $model=ArticleCategory::find()->where(['>','status','-1'])->orderBy('sort');
        $total = $model->count();
        $pages=2;
        $page = new  Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$pages
        ]);
        $articles=$model->limit($page->limit)->offset($page->offset)->all();
        return $this->render('index',['articles'=>$articles,'page'=>$page]);
    }

//    添加
    public function actionAdd(){
        $model=new ArticleCategory();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
               // $model->status= $model->status+1;
                $model->save();
                \Yii::$app->session->setFlash('success','添加文章分类成功');
                return $this->redirect(['index']);
            }
            var_dump($model->getErrors());
        }
        return $this->render('add',['model'=>$model]);
    }
//    修改
    public function actionEdit($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改文章分类成功');
                return $this->redirect(['index']);
            }
            var_dump($model->getErrors());
        }
        return $this->render('add',['model'=>$model]);
    }
//    伪删除
    public function actionDelete($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        $model->status='-1';
        $model->save();
        \Yii::$app->session->setFlash('success','删除文章分类成功');
        return $this->redirect(['index']);
    }
//    回收站
    public function actionHsz(){
        $model=ArticleCategory::find()->where(['=','status','-1']);
        $total=$model->count();
        $pages=2;
        $page=new Pagination([
           'totalCount'=>$total,
            'defaultPageSize'=>$pages
        ]);
        $articles=$model->limit($page->limit)->offset($page->offset)->all();
        return $this->render('hsz',['page'=>$page,'articles'=>$articles]);
    }

//    回收站删除
    public function actionDeletes($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->setFlash('success','删除文章分类成功');
        return $this->redirect(['hsz']);
    }

//    回收站还原
    public function actionUpdate($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        $model->status='1';
        $model->save();
        \Yii::$app->session->setFlash('success','还原文章分类成功');
        return $this->redirect(['hsz']);
    }
}
