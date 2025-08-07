<?php

use humhub\components\Migration;

class m250731_075103_fix_question extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('poll', 'question', $this->string()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250731_075103_fix_question cannot be reverted.\n";

        return false;
    }
}
