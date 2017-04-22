<?php

use yii\db\Migration;

/**
 * Handles the creation of table `meals`.
 */
class m170422_102301_create_meals_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('meals', [
            'meal_id' => $this->primaryKey(),
            'name' => $this->string(32),
            ]);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('meals');
    }
}
