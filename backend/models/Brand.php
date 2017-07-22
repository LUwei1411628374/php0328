<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
    public $imgFile;

    public static function getStatusOptions($hidden_del=true){
        $options=[
            -1=>'删除',0=>'隐藏',1=>'正常'
        ];
        if($hidden_del){
            unset($options['-1']);
        }
        return $options;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'intro','status'], 'required'],
            [['logo'], 'string', 'max' => 255],


            //['logo','file','extensions'=>['gif','png','jpg'],'maxSize'=>1024*1024*1],
        ];

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '品牌名称',
            'intro' => '简介',

            'sort' => '排序',
            'status' => '状态',
            'logo'=>'LOGO'
        ];
    }
}
