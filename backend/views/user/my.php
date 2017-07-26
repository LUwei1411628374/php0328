<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-25
 * Time: 16:32
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo  $form->field($model,'old_password');
echo  $form->field($model,'new_password');
echo  $form->field($model,'re_password');
echo \yii\bootstrap\Html::submitButton('确认修改',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();