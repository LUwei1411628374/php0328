<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m170729_034536_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
//            id	primaryKey
            'username'=>$this->string(50)->notNull()->comment('用户名'),
//            username	varchar(50)	用户名
            'auth_key'=>$this->string(32)->notNull()->comment('密钥'),
//            auth_key	varchar(32)
            'password_hash'=>$this->string(100)->notNull()->comment('密码'),
//            password_hash	varchar(100)	密码（密文）
            'email'=>$this->string(100)->notNull()->comment('邮箱'),
//            email	varchar(100)	邮箱
            'tel'=>$this->string()->notNull()->comment('电话'),
//            tel	char(11)	电话
            'last_login_time'=>$this->integer()->notNull()->comment('最后登录时间'),
//            last_login_time	int	最后登录时间
            'last_login_ip'=>$this->integer()->notNull()->comment('最后登录ip'),
//            last_login_ip	int	最后登录ip
            'status'=>$this->integer(1)->notNull()->comment('状态'),
//            status	int(1)	状态（1正常，0删除）
            'created_at'=>$this->integer()->notNull()->comment('添加时间'),
//            created_at	int	添加时间
            'updated_at'=>$this->integer()->notNull()->comment('修改时间')
//            updated_at	int	修改时间
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
