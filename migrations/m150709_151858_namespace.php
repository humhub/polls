<?php

use yii\db\Schema;
use humhub\components\Migration;

class m150709_151858_namespace extends Migration
{

    public function up()
    {
        $this->renameClass('Poll', humhub\modules\polls\models\Poll::className());
        $this->update('activity', ['class' => 'humhub\modules\content\activities\ContentCreated', 'module' => 'content'], ['class' => 'PollCreated']);
        $this->update('activity', ['class' => 'humhub\modules\polls\activities\NewVote', 'module' => 'polls'], ['class' => 'PollAnswered']);
    }

    public function down()
    {
        echo "m150709_151858_namespace cannot be reverted.\n";

        return false;
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
