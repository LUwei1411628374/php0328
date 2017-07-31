<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $article_category_id
 * @property integer $sort
 * @property integer $status
 * @property integer $create_time
 */
class Article extends \yii\db\ActiveRecord
{
    public function getArticleCategory(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }

    public static function getStatus($hidden_del=true){
        $status=[
            -1=>'删除',0=>'隐藏',1=>'正常'
        ];
        if($hidden_del){
            unset($status['-1']);
        }
        return $status;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'intro', 'article_category_id', 'sort', 'status'], 'required'],
            [['intro'], 'string'],
            [['article_category_id', 'sort', 'status', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '文章名称',
            'intro' => '文章简介',
            'article_category_id' => '文章分类id',
            'sort' => '排序',
            'status' => '状态',
            'create_time' => '创建时间',
        ];
    }

    public static function getCategory(){
            $rows=ArticleCategory::find()->all();
            $tmp=[];
            foreach ($rows  as $row){
                $tmp[$row->id]=$row->name;
            }
            return $tmp;
    }

}
