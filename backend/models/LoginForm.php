<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-24
 * Time: 18:38
 */
namespace backend\models;
use backend\models\User;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password_hash;
    public $code;
    public $rememberMe;

    public function rules()
    {
        return [
          [['username','password_hash'],'required'],
            ['code','captcha','captchaAction'=>'user/captcha'],
            ['rememberMe','boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
          'username'=>'用户名',
            'password_hash'=>'密码',
            'code'=>'验证码',
            'rememberMe'=>'保存登录'
        ];
    }

    public function login(){

        $admin = \backend\models\User::findOne(['username'=>$this->username]);

        if($admin){
            if(\Yii::$app->security->validatePassword($this->password_hash,$admin->password_hash)){

                //密码正确.可以登录
                //自动登录
                $log = $this->rememberMe?24*3600:0;
                \backend\models\User::updateAll(['last_login_time'=>time(),'last_login_ip'=>ip2long(\Yii::$app->request->userIP)],['id'=>$admin->id]);
                //2 登录(保存用户信息到session)
                \Yii::$app->user->login($admin,$log);
                return true;
            }else{

                //密码错误.提示错误信息
                $this->addError('password_hash','密码错误');

            }

        }else{
            //用户不存在,提示 用户不存在 错误信息
            $this->addError('username','用户名不存在');
        }
        return false;
    }
}