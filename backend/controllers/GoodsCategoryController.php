<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-21
 * Time: 14:33
 */
namespace backend\controllers;
use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class GoodsCategoryController extends Controller
{
//    添加
    public function actionAdd(){
        $model = new GoodsCategory(['parent_id'=>0]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            //$model->save();
            //判断是否是添加一级分类
            if($model->parent_id){
                //非一级分类
                $category = GoodsCategory::findOne(['id'=>$model->parent_id]);

                if($category){
                    $model->prependTo($category);
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }
            }else{
                //一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','分类添加成功');
            return $this->redirect(['index']);
        }
        //获取所以分类数据
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
//    列表
    public function actionIndex(){
        $models=GoodsCategory::find()->orderBy('tree,lft')->asArray()->all();
         /*$total= $model->count();
       // var_dump($total);exit;
        $pages=2;
        $page= new Pagination([
           'totalCount'=>$total,
            'defaultPageSize'=>$pages
        ]);
        $categories= $model->limit($page->limit)->offset($page->offset)->all();
        return $this->render('index',['categories'=>$categories,'page'=>$page]);*/
         return $this->render('index',['models'=>$models]);
    }


//    删除
    public function actionDelete($id){
        $model = GoodsCategory::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('商品分类不存在');
        }
        //判断是否是叶子节点，非叶子节点说明有子分类
        if(!$model->isLeaf()){
            throw new ForbiddenHttpException('该分类下有子分类，无法删除');
        }
       // $model->delete();
        $model->deletewithChildren();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['index']);



    }

//    修改

    public function actionEdit($id)
    {
        $model = GoodsCategory::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('分类不存在');
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //$model->save();
            //不能移动节点到自己节点下
            /*if($model->parent_id == $model->id){
                throw new HttpException(404,'不能移动节点到自己节点下');
            }*/
            try{
                //判断是否是添加一级分类
                if($model->parent_id){
                    //非一级分类

                    $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    if($category){
                        $model->appendTo($category);
                    }else{
                        throw new HttpException(404,'上级分类不存在');
                    }

                }else{
                    //一级分类
                    //bug fix:修复根节点修改为根节点的bug
                    if($model->oldAttributes['parent_id']==0){
                        $model->save();
                    }else{
                        $model->makeRoot();
                    }

                }
                \Yii::$app->session->setFlash('success','分类添加成功');
                return $this->redirect(['index']);
            }catch (Exception $e){
                $model->addError('parent_id',GoodsCategory::exceptionInfo($e->getMessage()));
            }


        }

        //获取所以分类数据
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
}