<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

use yii\db\Migration;
use yii\db\Schema;

class m180327_125017_show_result_after_close extends Migration
{
    public function safeUp()
    {
        $this->addColumn('poll', 'show_result_after_close', Schema::TYPE_BOOLEAN. ' DEFAULT 0');
    }

    public function safeDown()
    {
        echo "m180327_125017_show_result_after_close cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180327_125017_show_result_after_close cannot be reverted.\n";

        return false;
    }
    */
}
