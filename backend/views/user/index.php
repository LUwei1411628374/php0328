<?php
    if(Yii::$app->user->can('user/add')){
        echo \yii\bootstrap\Html::a('添加',['add'],['class'=>'btn btn-info']);
    }
?>

<table class="table table-bordered">

    <?php
        if(!Yii::$app->user->isGuest){
            echo \yii\bootstrap\Html::a('修改密码',['my'],['class'=>'btn btn-danger']);
        }
    ?>
    <table class="table table-bordered">

    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>修改时间</th>
        <th>操作</th>

    </tr>
    <?php foreach ($users as $user):?>
    <tr>
        <td><?=$user->id?></td>
        <td><?=$user->username?></td>
        <td><?=$user->email?></td>
        <td><?=$user->status?></td>
        <td><?=date('Y-m-d',$user->created_at)?></td>
        <td><?=$user->updated_at==0?'没有修改过':date('Y-m-d',$user->updated_at)?></td>
        <td>
            <?php
                if(Yii::$app->user->can('user/edit')){
                    echo  \yii\helpers\Html::a('修改',['edit','id'=>$user->id],['class'=>'btn btn-warning']);
                }
            ?>
            &emsp;
            <?php
            if(Yii::$app->user->can('user/delete')){
                echo  \yii\helpers\Html::a('删除',['user/delete','id'=>$user->id],['class'=>'btn btn-danger']);
            }
            ?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$page,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页']);