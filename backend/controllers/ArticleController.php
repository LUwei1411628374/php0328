<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-19
 * Time: 16:34
 */
namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use \kucha\ueditor\UEditor;

class ArticleController extends Controller
{
    //列表展示
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
//    添加
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
            \Yii::$app->session->setFlash('success','添加文章成功');
            return $this->redirect(['index']);
        }else{
            var_dump($model->getErrors() && $models->getErrors());
        }

        return  $this->render('add',['model'=>$model,'models'=>$models]);
    }
//    修改
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
            \Yii::$app->session->setFlash('success','修改文章成功');
            return $this->redirect(['index']);
        }else{
            var_dump($model->getErrors() && $models->getErrors());
        }

        return  $this->render('add',['model'=>$model,'models'=>$models]);
    }
//    伪删除
    public function actionDelete($id){
        $model=Article::findOne(['id'=>$id]);
        $model->status='-1';
        $model->save();
        \Yii::$app->session->setFlash('success','删除文章成功');
        return $this->redirect(['index']);
    }

//    查看
    public function actionLook($id){
        $models=Article::findOne(['id'=>$id]);
        $model=ArticleDetail::findOne(['article_id'=>$id]);
        return $this->render('look',['model'=>$model,'models'=>$models]);
    }
//    回收站
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
//    还原
    public function actionUpdate($id){
        $model=Article::findOne(['id'=>$id]);
        $model->status='1';
        $model->save();
        \Yii::$app->session->setFlash('success','还原文章成功');
        return $this->redirect(['index']);
    }

//    回收站删除
    public function actionDeletes($id){
        $model=Article::findOne(['id'=>$id]);
        $models=ArticleDetail::findOne(['id'=>$id]);
        $model->delete();
        $models->delete();
        \Yii::$app->session->setFlash('success','删除文章成功');
        return $this->redirect(['index']);
    }
//    编辑器配置
    public function actions()
    {
        return [

            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',

                'config' => [
                    "imageUrlPrefix"  => '',//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",//上传保存路径
                    "imageRoot" => \Yii::getAlias('@webroot')
                ]
            ]
        ];
    }

    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'except'=>[
                    'upload'
                ]
            ]

        ];
    }
}