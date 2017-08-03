<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170728_022534_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->notNull()->comment('菜单名称'),
            'url'=>$this->string()->notNull()->comment('路由'),
            'sort'=>$this->integer()->notNull()->comment('排序'),
            'parent_id'=>$this->integer()->notNull()->comment('父ID')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
