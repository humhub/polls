<?php

use yii\db\Schema;
use yii\db\Migration;

class m160218_132531_close_and_anonymous_poll extends Migration
{
    public function up()
    {
        
        $this->addColumn('poll', 'is_random', Schema::TYPE_BOOLEAN. ' DEFAULT 0');
        $this->addColumn('poll', 'closed', Schema::TYPE_BOOLEAN. ' DEFAULT 0');
        $this->addColumn('poll', 'anonymous', Schema::TYPE_BOOLEAN. ' DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('poll', 'is_random');
        $this->dropColumn('poll', 'closed');
        $this->dropColumn('poll', 'anonymous');
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
