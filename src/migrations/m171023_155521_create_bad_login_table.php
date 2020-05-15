<?php

use yii\db\Migration;

class m171023_155521_create_bad_login_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('bad_login', [
            'id' => $this->primaryKey(),
            'key' => $this->string()->notNull(),
            'amount' => $this->integer(2)->defaultValue(1),
            'reset_at' => $this->timestamp(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ]);

        $this->createIndex('bad_login_key_index', 'bad_login', 'key');
        $this->createIndex('bad_login_amount_index', 'bad_login', 'amount');
        $this->createIndex('bad_login_reset_at_index', 'bad_login', 'reset_at');
    }

    public function safeDown()
    {
        $this->dropTable('bad_login');
    }
}
