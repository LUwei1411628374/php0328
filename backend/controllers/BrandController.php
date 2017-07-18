<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-18
 * Time: 16:10
 */
namespace backend\controllers;

use backend\models\Brand;
use Psr\Http\Message\UploadedFileInterface;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends Controller
{
//    添加
    public function actionAdd(){
        $model= new Brand();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            //实例化文件上传对象
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
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
                    $model->save();
                }
                return $this->redirect(['brand/index']);
            }

        }else{
            var_dump($model->getErrors());
        }

        return $this->render('add',['model'=>$model]);
    }

//    列表
    public function actionIndex(){
        $model=Brand::find()->where(['!=','status','-1']);
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
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
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
                    $model->save();
                }
                return $this->redirect(['brand/index']);
            }

        }else{
            var_dump($model->getErrors());
        }
        return $this->render('add',['model'=>$model]);
    }

//    伪删除
    public function actionDelete($id){
        $model=Brand::findOne(['id'=>$id]);
        $model->status='-1';
        $model->save();
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
        $articles=$model->limit($page->limit)->offset($page->offset)->all();
        return $this->render('index',['page'=>$page,'articles'=>$articles]);
    }

//    回收站删除
    public function actionDeletes($id){
        $model=Brand::findOne(['id'=>$id]);
        if($model->logo){
            unlink(\Yii::getAlias('@webroot').$model->logo);
        }
        $model->delete();
        return $this->redirect(['hsz']);
    }

//    回收站还原
    public function actionUpdate($id){
        $model=Brand::findOne(['id'=>$id]);
        $model->statua=1;
        $model->save();
        return $this->redirect(['index']);
    }

}