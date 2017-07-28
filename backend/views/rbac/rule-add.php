<h1><?=$model->scenario == \backend\models\RuleForm::SCENARIO_RULEADD?'添加':'修改 '?>角色</h1>

<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-26
 * Time: 16:53
 */
$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'name')->textInput(['readonly'=>$model->scenario!=\backend\models\RuleForm::SCENARIO_RULEADD]);
echo $form->field($model,'description');
echo $form->field($model,'permissions',['inline'=>1])->checkboxList(\yii\helpers\ArrayHelper::map(Yii::$app->authManager->getPermissions(),'name','description'));
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();