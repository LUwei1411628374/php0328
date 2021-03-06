
<?php
    if(Yii::$app->user->can('goods-category/add')){
        echo \yii\bootstrap\Html::a('添加',['goods-category/add'],['class'=>'btn btn-info']);
    }
?>


<table class="table table-responsive table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model['id']?></td>
            <td><?=str_repeat('—',$model['depth']).$model['name']?></td>
            <td>
                <?php
                    if(Yii::$app->user->can('goods-category/edit')){
                        echo \yii\bootstrap\Html::a('修改',['edit','id'=>$model['id']],['class'=>'btn btn-xs btn-warning']);
                    }
                ?>
                &emsp;
                <?php
                    if(Yii::$app->user->can('goods-category/delete')){
                        echo \yii\bootstrap\Html::a('删除',['delete','id'=>$model['id']],['class'=>'btn btn-xs btn-danger']);
                    }
                ?>
            </td>
        </tr>

    <?php endforeach;?>
</table>

<?php
/*echo \yii\widgets\LinkPager::widget(['pagination'=>$page,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页']);*/
