<h1><?=$model->scenario == \backend\models\PermissionForm::SCENARIO_ADD?'添加':'修改'?>权限</h1>
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-26
 * Time: 14:28
 */

$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'name')->textInput(['readonly'=>$model->scenario!=\backend\models\PermissionForm::SCENARIO_ADD]);
echo $form->field($model,'description');

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn  btn-info']);

\yii\bootstrap\ActiveForm::end();