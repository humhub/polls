<?php

use humhub\modules\polls\models\Poll;
use humhub\components\Migration;

class m150709_151858_namespace extends Migration
{
    public function up()
    {
        $this->renameClass('Poll', Poll::class);

        $table = $this->db->getTableSchema('activity');
        $columnsContentCreated = ['class' => 'humhub\modules\content\activities\ContentCreated'];
        $columnsNewVote = ['class' => 'humhub\modules\polls\activities\NewVote'];
        if (isset($table->columns['module'])) {
            $columnsContentCreated['module'] = 'content';
            $columnsNewVote['module'] = 'polls';
        }

        $this->update('activity', $columnsContentCreated, ['class' => 'PollCreated']);
        $this->update('activity', $columnsNewVote, ['class' => 'PollAnswered']);
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
