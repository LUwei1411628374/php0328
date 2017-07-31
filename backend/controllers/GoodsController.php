<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Goods;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\GoodsSearchForm;
use flyok666\qiniu\Qiniu;
use flyok666\uploadifive\UploadAction;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class GoodsController extends \yii\web\Controller
{
//列表
    public function actionIndex()
    {


        $query = Goods::find()->where(['>','status',0])->orderBy('id');
        $model = new GoodsSearchForm();
        $model->search($query);
        $pager = new Pagination([
            'totalCount'=>$query->count(),
            'pageSize'=>5
        ]);

        $models = $query->limit($pager->limit)->offset($pager->offset)->all();


        return $this->render('index',['model'=>$model,'models'=>$models,'pager'=>$pager]);
    }
//添加

    public function actionAdd(){
        $model=new Goods();
        $models=new GoodsIntro();
        if($model->load(\Yii::$app->request->post()) && $models->load(\Yii::$app->request->post())){
            if($model->validate() && $models->validate()){
                //生成随机货号
                $day= date('Y-m-d');
                //查询今天是否有记录
                $goodsCount=GoodsDayCount::findOne(['day'=>$day]);
                //如果没有
                //$goodsCount=new GoodsDayCount();
                if($goodsCount==null){
                   $goodsCount=new GoodsDayCount();
                    $goodsCount->day=$day;
                    $goodsCount->count=0;
                    $goodsCount->save();
                }
                $model->sn = date('Ymd').sprintf("%06d",$goodsCount->count +1);
               // $model->sn = date('Ymd').sprintf("%04d",$goodsCount->count+1);
                $model->create_time=time();
                $model->status=1;
                $model->save();
                $models->goods_id=$model->id;
                $models->save();
                $goodsCount->count++;
                $goodsCount->save();
                \Yii::$app->session->setFlash('success','商品添加成功!');
                return $this->redirect(['goods/gallery','id'=>$model->id]);
            }
        }
        return $this->render('add',['model'=>$model,'models'=>$models]);
    }


//修改

    public function actionEdit($id){
        $model= Goods::findOne(['id'=>$id]);
        $models=GoodsIntro::findOne(['goods_id'=>$id]);
        if($model->load(\Yii::$app->request->post()) && $models->load(\Yii::$app->request->post())){
            if($model->validate() && $models->validate()){
                //生成随机货号
//                $day= date('Y-m-d');
//                //查询今天是否有记录
//                $counts=GoodsDayCount::findOne(['day'=>$day]);
//                //如果没有
//                if($counts==null){
//                    $goodscount=new GoodsDayCount();
//                    $goodscount->day=$day;
//                    $goodscount->count=0;
//                    $goodscount->save();
//                }
//                $model->sn = date('Ymd').sprintf("%06d",$goodscount->count+1);
//                $model->create_time=time();
//                $model->status=1;
                $model->save();
//                $models->goods_id=$model->id;
                $models->save();
                \Yii::$app->session->setFlash('success','商品修改成功!');
                return $this->redirect(['index']);
            }
        }else{
            var_dump($model->getErrors() && $models->getErrors());
        }
        return $this->render('add',['model'=>$model,'models'=>$models]);


    }
//伪删除、

    public function actionDelete($id){
        $model=Goods::findOne(['id'=>$id]);
        $model->status=0;
        $model->save();
        \Yii::$app->session->setFlash('success','商品删除成功');
        return $this->redirect(['index']);
    }
//    相册

    public function actionGallery($id)
    {
        $goods = Goods::findOne(['id'=>$id]);
//        $goods_galleries=GoodsGallery::find()->where(['=','goods_id',$id]);

        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
        }
        return $this->render('gallery',['goods'=>$goods]);

    }
//    删除图片
    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsGallery::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }

    }
//回收站
    public function actionHsz(){
        $query = Goods::find()->where(['=','status',0])->orderBy('id');
        $pager = new Pagination([
            'totalCount'=>$query->count(),
            'pageSize'=>5
        ]);

        $models = $query->limit($pager->limit)->offset($pager->offset)->all();


        return $this->render('hsz',['models'=>$models,'pager'=>$pager]);
    }
    //预览商品信息
    public function actionView($id)
    {
        $model = Goods::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('商品不存在');
        }
        return $this->render('view',['model'=>$model]);

    }
    //七牛云
    public function actions() {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://admin.yii2shop.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/{yyyy}{mm}{dd}/{time}{rand:6}" ,//上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ],
            ],
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
                'overwriteIfExist' => true,//如果文件已存在，是否覆盖
                /* 'format' => function (UploadAction $action) {
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
                },//文件的保存方式
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $goods_id = \Yii::$app->request->post('goods_id');
                    if($goods_id){
                        $model = new GoodsGallery();
                        $model->goods_id = $goods_id;
                        $model->path = $action->getWebUrl();
                        $model->save();
                        $action->output['fileUrl'] = $model->path;
                        $action->output['id'] = $model->id;
                    }else{
                        $action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
                    }

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
                    's-upload','upload'
                ]
            ]
        ];
    }

}
