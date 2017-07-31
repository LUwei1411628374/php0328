<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $name
 * @property string $city
 * @property string $address
 * @property integer $tel
 * @property integer $status
 * @property string $area
 * @property integer $member_id
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $province;//省
    public $center;//市
    public $area;//区

    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'province','center','area','address', 'tel'], 'required'],
            [['tel'], 'integer'],
            ['status','safe'],
            ['tel','string','max'=>11],
            [['name'], 'string', 'max' => 10],
            [['city', 'address'], 'string', 'max' => 100],
            [['area'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '收货人',
            'city' => '所在地区',
            'address' => '详细地址',
            'tel' => '收货人电话',
            'status' => '设置为默认地址',
            'area' => '省，市，区',
            'member_id' => 'Member ID',
        ];
    }
//根据id查询省市区的名字
    public static function getName($id){
        $name=Location::find()->select('name')->where(['id'=>$id])->one();
        return $name;
    }

}
