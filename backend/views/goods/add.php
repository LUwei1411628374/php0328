<?php
/**
 * Created by PhpStorm.
 * User: Administrator
* Date: 2017-07-22
* Time: 14:33
*/
use yii\web\JsExpression;
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'logo')->hiddenInput();

//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 80,
        'height' => 30,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    //console.log(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将图片的地址赋值给logo字段
        $("#goods-logo").val(data.fileUrl);
        //将上传成功的图片回显
        $("#img").attr('src',data.fileUrl);
    }
}
EOF
        ),
    ]
]);
echo \yii\bootstrap\Html::img($model->logo?$model->logo:false,['id'=>'img','height'=>50]);
echo $form->field($model,'goods_category_id')->hiddenInput();
echo '<div >
    <ul id="treeDemo" class="ztree" style="width: 500px;height: 120px;"></ul>
</div>';
echo $form->field($model,'brand_id')->dropDownList(\backend\models\Goods::getBrand(),['prompt'=>'=请选择品牌=']);
echo $form->field($model,'market_price');
echo $form->field($model,'shop_price');
echo $form->field($model,'stock');
echo $form->field($model,'is_on_sale',['inline'=>1])->radioList(\backend\models\Goods::$sale);

echo $form->field($model,'sort')->textInput(['type'=>'number']);
echo $form->field($models,'content')->widget('kucha\ueditor\UEditor',[]);


echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

//调用视图的方法加载静态资源
//加载css文件
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerCssFile('@web/zTree/css/demo.css');
//加载js文件
//'depends'=>\yii\web\JqueryAsset::className()  设置依赖JqueryAsset
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',
    ['depends'=>\yii\web\JqueryAsset::className()]);
/*$categories[] = ['id'=>0,'goods_category_id'=>0,'name'=>'顶级分类','open'=>1];*/
$nodes = \yii\helpers\Json::encode(\backend\models\GoodsCategory::getZtreeNodes());
$nodeId = $model->goods_category_id;
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback: {
		        onClick: function(event, treeId, treeNode){
		            //console.log(treeNode.id);
		            //将当期选中的分类的id，赋值给parent_id隐藏域
		            $("#goods-goods_category_id").val(treeNode.id);
		        }
	        }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$nodes};
  
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        zTreeObj.expandAll(true);//展开全部节点
        
        //获取节点
        var node = zTreeObj.getNodeByParam("id", "{$nodeId}", null);
        //选中节点
        zTreeObj.selectNode(node);
        //触发选中事件
        
JS
));




