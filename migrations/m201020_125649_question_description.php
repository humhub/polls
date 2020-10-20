<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m201020_125649_question_description
 */
class m201020_125649_question_description extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('poll', 'question', 'description');
        $this->addColumn('poll', 'question', Schema::TYPE_STRING . ' AFTER id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('poll', 'question');
        $this->renameColumn('poll', 'description','question');
    }
}
