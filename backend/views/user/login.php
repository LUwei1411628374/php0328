<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-24
 * Time: 18:17
 */
$form = \yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'username');
    echo $form->field($model,'password_hash');
echo $form->field($model,'rememberMe')->checkbox();
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),
    ['captchaAction'=>'user/captcha',
        'template'=>'<div class="row"><div class="col-lg-1">{image}</div><div class="col-lg-2">{input}</div></div>'])->label('验证码');
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();