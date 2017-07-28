<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property integer $sort
 * @property integer $parent_id
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    //获取菜单
    public static function getMenus()
    {
        $menu = [0=>'顶级菜单'];
        $menus = ArrayHelper::map(self::find()->where(['parent_id'=>0])->asArray()->all(),'id','name');

        return ArrayHelper::merge($menu,$menus);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','sort', 'parent_id'], 'required'],
           // [['name','url'],'unique'],
            [['sort', 'parent_id'], 'integer'],
            [['name', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '菜单名称',
            'url' => '路由',
            'sort' => '排序',
            'parent_id' => '上级菜单',
        ];
    }

    public function getChildren()
    {
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}
