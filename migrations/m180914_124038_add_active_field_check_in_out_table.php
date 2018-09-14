<?php

use yii\db\Migration;

/**
 * Class m180914_124038_add_active_field_check_in_out_table
 */
class m180914_124038_add_active_field_check_in_out_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('check_in_out', 'active','boolean DEFAULT 1');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('check_in_out', 'active');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180914_124038_add_active_field_check_in_out_table cannot be reverted.\n";

        return false;
    }
    */
}
