<?php

use yii\db\Migration;

/**
 * Class m170816_103228_create_table_ingredients
 */
class m170816_103228_create_table_ingredients extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('ingredients',[
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'status' => $this->boolean()->defaultValue(false)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('ingredients');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170816_103228_create_table_ingredients cannot be reverted.\n";

        return false;
    }
    */
}
