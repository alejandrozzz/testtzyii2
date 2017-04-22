<?php

use yii\db\Migration;

/**
 * Handles the creation of table `meal_ingredient`.
 */
class m170422_111230_create_meal_ingredient_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('meal_ingredient', [
            'id' => $this->primaryKey(),
            'meal_id' => $this->integer()->notNull(),
            'ingredient_id' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('meal_ingredient');
    }
}
