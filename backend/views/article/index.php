<form action="" method="get">
    <div class="input-group col-md-3 pull-right" style="margin-top:0px positon:relative">
        <input type="text" name="keywords" class="form-control" placeholder="请输入字段名"  / >
        <span class="input-group-btn">
               <button class="btn btn-info btn-search">查找</button>
            </span>
    </div>
</form>
<?php
if(Yii::$app->user->can('article/add')){
    echo \yii\bootstrap\Html::a('添加文章',['article/add'],['class'=>'btn btn-primary']);
}
?>
&emsp;
<?php
if(Yii::$app->user->can('article/hsz')){
    echo \yii\bootstrap\Html::a('回收站',['article/hsz'],['class'=>'btn btn-info']);
}
?>


<table class="table table-bordered table-responsive" style="margin-top: 10px">
    <tr>
        <th>ID</th>
        <th>文章名称</th>
        <th>文章简介</th>
        <th>文章分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($articles as $article):?>
    <tr>
        <td><?=$article->id?></td>
        <td><?=$article->name?></td>
        <td><?=$article->intro?></td>
        <td><?=$article->articleCategory->name?></td>
        <td><?=$article->sort?></td>
        <td><?=\backend\models\Article::getStatus()[$article->status]?></td>
        <td><?=date('Y-m-d',$article->create_time)?></td>
        <td>
            <?php
                if(Yii::$app->user->can('article/look')){
                    echo \yii\bootstrap\Html::a('查看',['article/look','id'=>$article->id],['class'=>'btn btn-primary']);
                }
            ?>
            &emsp;
            <?php
                if(Yii::$app->user->can('article/edit')){
                    echo \yii\bootstrap\Html::a('修改',['article/edit','id'=>$article->id],['class'=>'btn btn-warning']);
                }
            ?>
            &emsp;
            <?php
                 if(Yii::$app->user->can('article/delete')){
                     echo \yii\bootstrap\Html::a('删除',['article/delete','id'=>$article->id],['class'=>'btn btn-danger']);
                 }
            ?>
        </td>
    </tr>
    <?php endforeach;?>
</table>

<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$page,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页']);
