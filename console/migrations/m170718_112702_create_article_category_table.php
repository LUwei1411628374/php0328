<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m170718_112702_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            // id	primaryKey
            'name'=>$this->string(50)->notNull()->comment('文章名称'),
            //name	varchar(50)	名称
            'intro'=>$this->text()->notNull()->comment('简介'),
            //intro	text	简介
            'sort'=>$this->integer(11)->notNull()->comment('排序'),
            //sort	int(11)	排序
            'status'=>$this->integer(2)->notNull()->comment('状态')
            //status	int(2)	状态(-1删除 0隐藏 1正常)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
