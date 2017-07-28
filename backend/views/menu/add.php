<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-28
 * Time: 10:42
 */
$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'name')->textInput(['prompt'=>'=请填写菜单名称=']);

echo $form->field($model,'url')->dropDownList
(\yii\helpers\ArrayHelper::map(Yii::$app->authManager->getPermissions(),'name','name'),['prompt'=>'=请选择路由=']);

echo $form->field($model,'parent_id')->dropDownList(\backend\models\Menu::getMenus(),['prompt'=>'=请选择菜单=']);

echo $form->field($model,'sort')->textInput(['type'=>'number']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();