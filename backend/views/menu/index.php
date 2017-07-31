<?php
/* @var $this yii\web\View */
    if(Yii::$app->user->can('menu/add')){
        echo \yii\bootstrap\Html::a('添加',['add'],['class'=>'btn btn-info']);
    }
?>


<table class="table table-responsive table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>菜单名称</th>
            <th>菜单路由</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->url?></td>
            <td>
                <?php
                    if(Yii::$app->user->can('menu/edit')){
                        echo \yii\bootstrap\Html::a('修改',['edit','id'=>$model->id],['class'=>'btn btn-xs btn-warning']);
                    }
                    if(Yii::$app->user->can('menu/delete')){
                        echo \yii\bootstrap\Html::a('删除',['delete','id'=>$model->id],['class'=>'btn btn-xs btn-danger']);
                    }
                ?>
            </td>
        </tr>
            <?php foreach ($model->children as $child):?>
                    <tr>
                        <td><?=$child->id?></td>
                        <td>——<?=$child->name?></td>
                        <td><?=$child->url?></td>
                        <td>
                            <?php
                            if(Yii::$app->user->can('menu/edit')){
                                echo \yii\bootstrap\Html::a('修改',['edit','id'=>$child->id],['class'=>'btn btn-xs btn-warning']);
                            }
                            ?>
                            &emsp;
                            <?php
                            if(Yii::$app->user->can('menu/delete')){
                                echo \yii\bootstrap\Html::a('删除',['delete','id'=>$child->id],['class'=>'btn btn-xs btn-danger']);
                            }
                            ?>
                        </td>
                    </tr>
            <?php endforeach;?>
    <?php endforeach;?>

    </tbody>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('//cdn.datatables.net/1.10.15/css/dataTables.bootstrap.css');

$this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);

$this->registerJsFile('//cdn.datatables.net/1.10.15/js/dataTables.bootstrap.js',['depends'=>\yii\web\JqueryAsset::className()]);

$this->registerJs('$(".table").DataTable({
language: {
    url: "//cdn.datatables.net/plug-ins/1.10.15/i18n/Chinese.json"
    }
});');
