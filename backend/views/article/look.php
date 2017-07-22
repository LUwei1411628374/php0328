<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-19
 * Time: 18:24
 */
?>
<!--/*$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($models,'name')->textInput(['readonly'=>'true']);
echo $form->field($model,'content')->textarea(['readonly'=>'true']);

\yii\bootstrap\ActiveForm::end();*/-->


<table>
    <tr>
        <td>文章名称</td>
        <td>文章内容</td>
    </tr>

    <tr>
        <td><?=$models->name?></td>
        <td><?=$model->content?></td>
    </tr>
</table>


