<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-26
 * Time: 14:08
 */
namespace backend\models;
use yii\base\Model;

class PermissionForm extends Model
{
   public $name;
   public $description;
//定义 场景
   const SCENARIO_ADD = 'Add';

   public function rules()
   {
       return [
         [['name','description'],'required'],
           ['name','validateName','on'=>self::SCENARIO_ADD]
       ];
   }

   public function attributeLabels()
   {
       return [
         'name'=>'路由',
           'description'=>'描述',
       ];
   }
//自定义验证规则
   public function validateName(){
       $authManage = \Yii::$app->authManager;
       if($authManage->getPermission($this->name)){
            $this->addError('name','权限已存在');
       }
   }
}