<?php
/* @var $this yii\web\View */
?>
    <h1>商品列表</h1>
<?php

$form = \yii\bootstrap\ActiveForm::begin([
    'method' => 'get',
    //get方式提交,需要显式指定action
    'action'=>\yii\helpers\Url::to(['goods/index']),
    'layout'=>'inline',
]);
echo $form->field($model,'name')->textInput(['placeholder'=>'商品名'])->label(false);
echo $form->field($model,'sn')->textInput(['placeholder'=>'货号'])->label(false);
echo $form->field($model,'minPrice')->textInput(['placeholder'=>'￥'])->label(false);
echo $form->field($model,'maxPrice')->textInput(['placeholder'=>'￥'])->label('-');
echo \yii\bootstrap\Html::submitButton('<span class="glyphicon glyphicon-search"></span>',['class'=>'btn btn-default']);
\yii\bootstrap\ActiveForm::end();
?>
<table class="table table-bordered" style="margin-top: 10px">
    <tr>
        <th>ID</th>
        <th>货号</th>
        <th>分类</th>
        <th>名称</th>
        <th>价格</th>
        <th>库存</th>
        <th>LOGO</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->sn?></td>
            <td><?=$model->goodsCategory->name?></td>
            <td><?=$model->name?></td>
            <td><?=$model->shop_price?></td>
            <td><?=$model->stock?></td>
            <td><?=\yii\bootstrap\Html::img('@web'.$model->logo,['style'=>'max-height:50px'])?></td>
            <td>
                <?=\yii\bootstrap\Html::a('相册',['gallery','id'=>$model->id],['class'=>'btn btn-primary'])?>
                <?=\yii\bootstrap\Html::a('编辑',['edit','id'=>$model->id],['class'=>'btn btn-warning'])?>
                <?=\yii\bootstrap\Html::a('删除',['delete','id'=>$model->id],['class'=>'btn btn-danger'])?>
                <?=\yii\bootstrap\Html::a('预览',['view','id'=>$model->id],['class'=>'btn btn-success'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?=\yii\widgets\LinkPager::widget([
    'pagination'=>$pager
])?>