<?php

use yii\db\Migration;

/**
 * Handles the creation of table `ingredients_add_hidden`.
 */
class m170422_193326_create_ingredients_add_hidden_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('ingredients','hidden','integer NOT NULL DEFAULT 0');
    }
}
