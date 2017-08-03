<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_goods`.
 */
class m170803_035808_create_order_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_goods', [
            'id' => $this->primaryKey(),
//            id	primaryKey
//            order_id	int	订单id
        'order_id'=>$this->integer()->notNull()->comment('订单id'),
//            goods_id	int	商品id
        'goods_id'=>$this->integer()->notNull()->comment('商品id'),
//            goods_name	varchar(255)	商品名称
        'goods_name'=>$this->string(255)->notNull()->comment('商品名称'),
//            logo	varchar(255)	图片
        'logo'=>$this->string(255)->notNull()->comment('图片'),
//            price	decimal	价格
        'price'=>$this->decimal()->notNull()->comment('价格'),
//            amount	int	数量
        'amount'=>$this->integer()->comment('数量'),
//            total	decimal	小计
        'total'=>$this->decimal()->notNull()->comment('小计')
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_goods');
    }
}
