<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Address extends ActiveRecord{
    public function rules()
    {
        return[
            [['name','province','city','area','address','tel'],'required'],
            ['status','safe'],
        ];
    }
    public function attributeLabels()
    {
        return[
            'name'=>'收货人',
            'province'=>'所在省',
            'city'=>'所在城市',
            'area'=>'所在地区',
            'address'=>'详细地址',
            'tel'=>'手机号',
            'status'=>'设置为默认地址',
        ];
    }
    //根据id获取省市区的名字
    public static function getName($id){
        $name=Location::find()->select('name')->where(['id'=>$id])->one();
        return $name;
    }
}