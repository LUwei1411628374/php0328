

<?=\yii\bootstrap\Html::a('返回列表',['article-category/index'],['class'=>'btn btn-primary'])?>
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
                <td><?=\backend\models\ArticleCategory::getArticleOptions($hidden=-1)['$article->status']?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('还原',['article-category/update','id'=>$article->id],['class'=>'btn btn-warning'])?>
                    <?=\yii\bootstrap\Html::a('删除',['article-category/deletes','id'=>$article->id],['class'=>'btn btn-danger'])?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$page,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页']);