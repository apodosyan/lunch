<?php

use yii\db\Migration;

/**
 * Class m170816_103302_create_table_dishes_ingredients
 */
class m170816_103302_create_table_dishes_ingredients extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('dishes_ingredients',[
            'id' => $this->primaryKey(),
            'dish_id' =>$this->integer(11)->notNull(),
            'ingredient_id' => $this->integer(11)->notNull(),
        ]);
        $this->createIndex(
            'idx-d-i-dish_id',
            'dishes_ingredients',
            'dish_id'
        );

        // add foreign key for table `dishes`
        $this->addForeignKey(
            'fk-d-i-dish_id',
            'dishes_ingredients',
            'dish_id',
            'dishes',
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-d-i-ingredient_id',
            'dishes_ingredients',
            'ingredient_id'
        );

        // add foreign key for table `ingredients`
        $this->addForeignKey(
            'fk-d-i-ingredient_id',
            'dishes_ingredients',
            'ingredient_id',
            'ingredients',
            'id',
            'CASCADE'
        );

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        // drops foreign key for table `dishes`
        $this->dropForeignKey(
            'fk-d-i-dish_id',
            'dishes_ingredients'
        );

        // drops index for column `dish_id`
        $this->dropIndex(
            'idx-d-i-dish_id',
            'dishes_ingredients'
        );

        // drops foreign key for table `ingredients`
        $this->dropForeignKey(
            'fk-d-i-ingredient_id',
            'dishes_ingredients'
        );

        // drops index for column `ingredient_id`
        $this->dropIndex(
            'fk-d-i-ingredient_id',
            'dishes_ingredients'
        );

        $this->dropTable('dishes_ingredients');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170816_103302_create_table_dishes_ingredients cannot be reverted.\n";

        return false;
    }
    */
}
