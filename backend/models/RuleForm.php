<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-26
 * Time: 16:28
 */
namespace backend\models;

use yii\base\Model;

class RuleForm extends Model
{
    public $name;
    public $description;
    public $permissions=[];

    const SCENARIO_RULEADD = 'RuleAdd';

    public function rules()
    {
        return [
          [['name','description'],'required'],
            ['permissions','safe'],
            ['name','validateName','on'=>self::SCENARIO_RULEADD]
        ];
    }

    public function attributeLabels()
    {
        return [
          'name'=>'角色名',
            'description'=>'角色描述',
            'permissions'=>'角色权限'
        ];
    }
// 自定义角色规则
    public function validateName(){
        $authManager = \Yii::$app->authManager;
        if($authManager->getRole($this->name)){
            $this->addError('name','角色名已存在');
        }
    }
}