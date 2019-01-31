<?php

use yii\db\Schema;
use yii\db\Migration;

class m190130_145553_poll_debate extends Migration
{
    public function up()
    {
        $this->addColumn('poll', 'debate', Schema::TYPE_BOOLEAN. ' DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('poll', 'debate');
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
