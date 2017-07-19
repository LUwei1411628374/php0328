<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-19
 * Time: 18:24
 */
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($models,'name')->textInput(['readonly'=>'true']);
echo $form->field($model,'content')->textarea(['readonly'=>'true']);

\yii\bootstrap\ActiveForm::end();