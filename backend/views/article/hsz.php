
<?=\yii\bootstrap\Html::a('返回列表',['article/index'],['class'=>'btn btn-info'])?>
<table class="table table-bordered">
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
            <td><?=\backend\models\Article::getStatus(false)[$article->status]?></td>

            <td><?=date('Y-m-d',$article->create_time)?></td>
            <td>
                <?=\yii\bootstrap\Html::a('查看',['article/look','id'=>$article->id],['class'=>'btn btn-primary'])?>
                <?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$article->id],['class'=>'btn btn-warning'])?>
                <?=\yii\bootstrap\Html::a('删除',['article/delete','id'=>$article->id],['class'=>'btn btn-danger'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>

<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$page,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页']);
