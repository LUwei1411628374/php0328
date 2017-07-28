<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-24
 * Time: 14:59
 */


$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'username');
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'email');
if(!$model->isNewRecord){
    echo $form->field($model,'status',['inline'=>1])->radioList(\backend\models\User::$status_option);
}

echo $form->field($model,'rules',['inline'=>1])->checkboxList(\yii\helpers\ArrayHelper::map(Yii::$app->authManager->getRoles(),'name','description'));

echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),
    ['captchaAction'=>'user/captcha',
        'template'=>'<div class="row"><div class="col-lg-1">{image}</div><div class="col-lg-1">{input}</div></div>'])->label('验证码');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();