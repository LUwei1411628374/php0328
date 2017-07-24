

<?=\yii\bootstrap\Html::a('添加',['add'],['class'=>'btn btn-info'])?>

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
                <td><?=\yii\bootstrap\Html::img($model->logo,['style'=>'max-height:50px'])?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('相册',['gallery','id'=>$model->id],['class'=>'btn btn-primary'])?>
                    <?=\yii\bootstrap\Html::a('编辑',['edit','id'=>$model->id],['class'=>'btn btn-warning'])?>
                    <?=\yii\bootstrap\Html::a('删除',['del','id'=>$model->id],['class'=>'btn btn-danger'])?>
                    <?=\yii\bootstrap\Html::a('预览',['view','id'=>$model->id],['class'=>'btn btn-success'])?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?=\yii\widgets\LinkPager::widget([
    'pagination'=>$pager
])?>