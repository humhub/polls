<?php

use yii\db\Schema;
use yii\db\Migration;

class m190131_142300_poll_state extends Migration
{
    public function up()
    {
        $this->addColumn('poll', 'state', Schema::TYPE_BOOLEAN. ' DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('poll', 'state');
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
