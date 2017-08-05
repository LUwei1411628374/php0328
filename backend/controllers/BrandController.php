<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-18
 * Time: 16:10
 */
namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Brand;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Psr\Http\Message\UploadedFileInterface;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

class BrandController extends Controller
{
//    添加
    public function actionAdd(){
        $model= new Brand();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            //实例化文件上传对象
           /* $model->imgFile=UploadedFile::getInstance($model,'imgFile');*/
            if($model->validate()){
                //判断有没有头像上传
                if($model->imgFile){
                    //得到保存图片的绝对路径
                    $path=\Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    //判断是是否有文件夹  没有就创建
                    if(!is_dir($path)){
                        mkdir($path);
                    }
                    //
                    $filePath='/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$filePath,false);
                    $model->logo=$filePath;

                }
                $model->save();
                \Yii::$app->session->setFlash('success','添加品牌成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());
            }
        }

        return $this->render('add',['model'=>$model]);
    }

//    列表
    public function actionIndex($keywords=''){
        $model=Brand::find()->where(['and','status>-1',"name like '%{$keywords}%'"]);
        $total=$model->count();
        $pages=2;
        $page=new Pagination([
           'totalCount'=>$total,
            'defaultPageSize'=>$pages

        ]);
        $brands=$model->limit($page->limit)->offset($page->offset)->all();
        //var_dump($brand);exit;
        return $this->render('index',['brands'=>$brands,'page'=>$page]);

    }

//     修改
    public function actionEdit($id){
        $model=Brand::findOne(['id'=>$id]);
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            //实例化文件上传对象
            /*$model->imgFile=UploadedFile::getInstance($model,'imgFile');*/
            if($model->validate()){
                //判断有没有头像上传
                /*if($model->imgFile){
                    //得到保存图片的绝对路径
                    $path=\Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    //判断是是否有文件夹  没有就创建
                    if(!is_dir($path)){
                        mkdir($path);
                    }
                    //
                    $filePath='/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$filePath,false);
                    $model->logo=$filePath;

                }*/
                $model->save();
                \Yii::$app->session->setFlash('success','修改品牌成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());
            }

        }
        return $this->render('add',['model'=>$model]);
    }

//    伪删除
    public function actionDelete($id){
        $model=Brand::findOne(['id'=>$id]);
        $model->status='-1';
        $model->save();
        \Yii::$app->session->setFlash('success','删除品牌成功');
       return $this->redirect(['brand/index']);

    }
//    回收站
    public function actionHsz(){
        $model=Brand::find()->where(['=','status','-1']);
        $total=$model->count();
        $pages=2;
        $page=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$pages
        ]);
        $brands=$model->limit($page->limit)->offset($page->offset)->all();
        return $this->render('hsz',['page'=>$page,'brands'=>$brands]);
    }

//    回收站删除
    public function actionDeletes($id){
        $model=Brand::findOne(['id'=>$id]);
        if($model->logo){
            $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
            $qiniu->delete($model->logo);
            unlink($model->logo);
        }
        $model->delete();
        \Yii::$app->session->setFlash('success','删除品牌成功');
        return $this->redirect(['hsz']);
    }

//    回收站还原
    public function actionUpdate($id){
        $model=Brand::findOne(['id'=>$id]);
        $model->status='1';
        $model->save();
        \Yii::$app->session->setFlash('success','还原品牌成功');
        return $this->redirect(['hsz']);
    }

//    实例化七牛云
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                /*'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 2 * 1024 * 1024, //file size
                ],

                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //输出文件的相对路径
                    $action->output['fileUrl'] = $action->getWebUrl();
//
                    //将图片上传到七牛云
                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
                    $qiniu->uploadFile(
                        $action->getSavePath(), $action->getWebUrl()
                    );
                    $url = $qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl']  = $url;
                },
            ],
        ];
    }

    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'except'=>[
                    's-upload'
                ]
            ]
        ];
    }
}