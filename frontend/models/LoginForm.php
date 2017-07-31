<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-30
 * Time: 11:07
 */
namespace frontend\models;

use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $code;
    public $rememberMe;


    public function rules()
    {
        return [
            [['username','password'],'required'],
            ['code','captcha','captchaAction'=>'member/captcha'],
            ['rememberMe','boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'code'=>'验证码',
            'rememberMe'=>'保存登录'
        ];
    }

}