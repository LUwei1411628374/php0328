<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-25
 * Time: 16:34
 */
namespace backend\models;

use yii\base\Model;

class MyForm extends Model
{
    public $old_password;
    public $new_password;
    public $re_password;

    public function rules()
    {
        return [
          [['old_password','new_password','re_password'],'required'],
            ['re_password', 'compare', 'compareAttribute' => 'new_password','message' => '两次密码输入不一致']
        ];
    }

    public function attributeLabels()
    {
        return [
          'old_password'=>'旧密码',
            'new_password'=>'新密码',
            're_password'=>'确认密码'
        ];
    }
}