<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m170802_080527_create_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cart', [
            'id' => $this->primaryKey(),
//            id	primaryKey
            'goods_id'=>$this->integer()->notNull()->comment('商品id'),
//            goods_id	int	商品id
            'amount'=>$this->integer()->notNull()->comment('商品数量'),
//            amount	int	商品数量
            'member_id'=>$this->integer()->notNull()->comment('用户id')
//            member_id	int	用户id
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cart');
    }
}
