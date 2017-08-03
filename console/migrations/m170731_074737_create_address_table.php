<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170731_074737_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            //            `name` varchar(50) DEFAULT NULL COMMENT '收货人',
            'name'=>$this->string()->notNull()->comment('收货人'),

//          `province` varchar(11) DEFAULT NULL COMMENT '省',
            'province'=>$this->string()->notNull()->comment('省'),
//          `city` varchar(11) DEFAULT NULL COMMENT '城市',
            'city'=>$this->string()->notNull()->comment('城市'),
//          `area` varchar(11) DEFAULT NULL COMMENT '区县',
            'area'=>$this->string()->notNull()->comment('区县'),
//          `address` varchar(255) DEFAULT NULL COMMENT '详细地址',
            'address'=>$this->string()->notNull()->comment('详细地址'),
//          `tel` varchar(11) DEFAULT NULL COMMENT '手机',
            'tel'=>$this->string()->notNull()->comment('手机'),
//          `status` int(11) DEFAULT NULL COMMENT '状态',
            'status'=>$this->integer()->notNull()->comment('状态'),
            'member_id'=>$this->integer()->notNull()->comment('用户ID')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
