<?php

use yii\db\Migration;

class m131030_122743_longer_questions extends Migration
{

    public function up()
    {
        $this->alterColumn('poll', 'question', 'TEXT NOT NULL');
    }

    public function down()
    {
        echo "m131030_122743_longer_questions does not support migration down.\n";
        return false;
    }

}
