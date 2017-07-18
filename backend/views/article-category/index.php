

<?=\yii\bootstrap\Html::a('添加文章',['article-category/add'],['class'=>'btn btn-primary'])?>
<?=\yii\bootstrap\Html::a('回收站',['article-category/hsz'],['class'=>'btn btn-warning'])?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>文章名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($articles as $article):?>
    <tr>
        <td><?=$article->id?></td>
        <td><?=$article->name?></td>
        <td><?=$article->intro?></td>
        <td><?=$article->sort?></td>
        <td><?=\backend\models\ArticleCategory::getArticleOptions()[$article->status]?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['article-category/edit','id'=>$article->id],['class'=>'btn btn-warning'])?>
            <?=\yii\bootstrap\Html::a('删除',['article-category/delete','id'=>$article->id],['class'=>'btn btn-danger'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$page,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页']);