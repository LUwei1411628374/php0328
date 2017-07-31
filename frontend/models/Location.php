<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-07-30
 * Time: 20:17
 */
namespace frontend\models;

use yii\db\ActiveRecord;
use yii\helpers\Json;

class Location extends ActiveRecord
{
    //查询城市
    public static function getProvince($id){
        $rows = self::find()->where(['parent_id'=>$id])->asArray()->all();
        return Json::encode($rows);
    }
}