
<form action="">
    <div class="input-group col-md-3 pull-right" style="margin-top:0px positon:relative">
        <input type="text" name="keywords" class="form-control" placeholder="请输入字段名"  / >
        <span class="input-group-btn">
               <button class="btn btn-info btn-search">查找</button>
            </span>
    </div>
</form>

<?= \yii\bootstrap\Html::a('添加',['brand/add'],['class'=>'btn btn-primary'])?>
<?= \yii\bootstrap\Html::a('回收站',['brand/hsz'],['class'=>'btn btn-warning'])?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>品牌</th>
        <th>简介</th>
        <th>LOGO</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($brands as $brand):?>
    <tr>
        <td><?=$brand->id?></td>
        <td><?=$brand->name?></td>
        <td><?=$brand->intro?></td>
        <td><?=\yii\bootstrap\Html::img($brand->logo,['height'=>50])?></td>
        <td><?=$brand->sort?></td>
        <td><?=\backend\models\Brand::getStatusOptions(false)[$brand->status]?></td>
        <td>
            <?= \yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand->id],['class'=>'btn btn-warning'])?>
            <?= \yii\bootstrap\Html::a('删除',['brand/delete','id'=>$brand->id],['class'=>'btn btn-danger'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$page,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页']);



