<?php

use yii\db\Migration;

class m141002_093710_fix_default extends Migration
{

    public function up()
    {
        $this->alterColumn('poll', 'allow_multiple', "smallint(6) NOT NULL DEFAULT 0");
    }

    public function down()
    {
        echo "m141002_093710_fix_default does not support migration down.\n";
        return false;
    }

    /*
      // Use safeUp/safeDown to do migration with transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}
