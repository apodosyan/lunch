<?php

use yii\db\Migration;

/**
 * Class m170816_103250_create_table_dishes
 */
class m170816_103250_create_table_dishes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('dishes',[
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'type' => $this->string(100)->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('dishes');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170816_103250_create_table_dishes cannot be reverted.\n";

        return false;
    }
    */
}
