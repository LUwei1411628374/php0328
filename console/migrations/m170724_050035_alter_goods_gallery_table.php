<?php

use yii\db\Migration;

class m170724_050035_alter_goods_gallery_table extends Migration
{
    public function safeUp()
    {
        $this->renameColumn('goods_gallery','good_id','goods_id');

    }

    public function safeDown()
    {
        echo "m170724_050035_alter_goods_gallery_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170724_050035_alter_goods_gallery_table cannot be reverted.\n";

        return false;
    }
    */
}
