
<?=\yii\bootstrap\Html::a('添加',['rule-add'],['class'=>'btn btn-info'])?>
<table class="table table-bordered">

    <tr>
        <th>角色名</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as  $model):?>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->description?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['rule-edit','name'=>$model->name],['class'=>'btn btn-warning'])?>
            <?=\yii\bootstrap\Html::a('删除',['rule-delete','name'=>$model->name],['class'=>'btn btn-danger'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>