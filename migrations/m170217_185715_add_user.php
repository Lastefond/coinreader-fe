<?php

use yii\db\Migration;

class m170217_185715_add_user extends Migration
{
    public function up()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(),
            'last_name' => $this->string(),
            'created_at' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
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
