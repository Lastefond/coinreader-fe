<?php

use yii\db\Migration;

class m170217_185837_create_coin_log extends Migration
{
    public function up()
    {
        $this->createTable('{{%coin_log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'coin_value' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%coin_log}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
