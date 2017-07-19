<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-19
 * Time: 16:34
 */
namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller
{
    public function actionIndex($keywords='')
    {
        $model=Article::find()->where(['and','status>-1',"name like '%{$keywords}%'"]);

        $total=$model->count();
        $pages=2;
        $page= new Pagination([
           'totalCount'=>$total,
            'defaultPageSize'=>$pages
        ]);
        $articles=$model->limit($page->limit)->offset($page->offset)->all();
        return $this->render('index',['articles'=>$articles,'page'=>$page]);
    }

    public function actionAdd(){
        $model = new Article();
        $models=new ArticleDetail();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $models->load($request->post());
            $model->create_time=time();
            $model->save();
            $models->article_id=$model->id;
            $models->save();
            return $this->redirect(['index']);
        }else{
            var_dump($model->getErrors() && $models->getErrors());
        }

        return  $this->render('add',['model'=>$model,'models'=>$models]);
    }


    public function actionEdit($id){
        $model =Article::findOne(['id'=>$id]);
        $models=ArticleDetail::findOne(['article_id'=>$id]);
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $models->load($request->post());
            $model->create_time=time();
            $model->save();
            $models->article_id=$model->id;
            $models->save();
            return $this->redirect(['index']);
        }else{
            var_dump($model->getErrors() && $models->getErrors());
        }

        return  $this->render('add',['model'=>$model,'models'=>$models]);
    }

    public function actionDelete($id){
        $model=Article::findOne(['id'=>$id]);
        $model->status='-1';
        $model->save();
        return $this->redirect(['index']);
    }


    public function actionLook($id){
        $models=Article::findOne(['id'=>$id]);
        $model=ArticleDetail::findOne(['article_id'=>$id]);
        return $this->render('look',['model'=>$model,'models'=>$models]);
    }

    public function actionHsz(){
        $model=Article::find()->where(['=','status','-1'])->orderBy('sort');

        $total=$model->count();
        $pages=2;
        $page= new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$pages
        ]);
        $articles=$model->limit($page->limit)->offset($page->offset)->all();
        return $this->render('hsz',['articles'=>$articles,'page'=>$page]);
    }


}