<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_category`.
 */
class m170720_014258_create_goods_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_category', [
            'id' => $this->primaryKey(),
//                id	primaryKey
            'tree'=>$this->integer()->notNull()->comment('树ID'),
//                tree	int()	树id
            'lft'=>$this->integer()->notNull()->comment('左值'),
//                lft	int()	左值
            'rgt'=>$this->integer()->notNull()->comment('右值'),
//                rgt	int()	右值
            'depth'=>$this->integer()->notNull()->comment('层级'),

//                depth	int()	层级
            'name'=>$this->string()->notNull()->comment('名称'),
//                name	varchar(50)	名称
            'parent_id'=>$this->integer()->notNull()->comment('上级分类ID'),
//                parent_id	int()	上级分类id
            'intro'=>$this->text()->notNull()->comment('简介')
//                intro	text()	简介
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_category');
    }
}
