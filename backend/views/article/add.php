<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-19
 * Time: 17:06
 */
use \kucha\ueditor\UEditor;
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'article_category_id')->dropDownList(\backend\models\Article::getCategory());
echo $form->field($model,'sort')->textInput(['type'=>'number']);
//echo $form->field($models,'content')->textarea();
echo $form->field($models,'content')->widget('kucha\ueditor\UEditor',[]);


echo $form->field($model,'status',['inline'=>1])->radioList(\backend\models\Article::getStatus());

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();