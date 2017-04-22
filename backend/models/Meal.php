<?php
namespace backend\models;

use yii\helpers\ArrayHelper;
class Meal extends \yii\db\ActiveRecord
{
    protected $ingredients = [];

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'meals';
    }

    /**
     * @return array primary key of the table
     **/
    public static function primaryKey()
    {
        return array('meal_id');
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'created' => 'Created',
            'updated' => 'Updated',
        );
    }

    public function setIngredients(array $ingredients)
    {
        $this->ingredients = $ingredients;
    }
    public function getIngredients()
    {
        if (!count($this->ingredients))
            return $this->ingredients
                = ArrayHelper::getColumn( $this->getIngredientMeals(), 'ingredient_id');
        else
            return $this->ingredients;
    }
    public function afterSave($insert, $changedAttributes)
    {
        $this->updateIngredients();
        parent::afterSave($insert, $changedAttributes);
    }

    protected function updateIngredients()
    {
        MealIngredient::deleteAll(array('meal_id' => $this->meal_id));
        if (is_array($this->ingredients))
            foreach ($this->ingredients as $id) {
                $mealIngredient = new MealIngredient();
                $mealIngredient->meal_id = $this->meal_id;
                $mealIngredient->ingredient_id = $id;
                $mealIngredient->save();
            }
    }

    public function getIngredientMeals(){
        return MealIngredient::findAll(array('meal_id' => $this->meal_id));
    }
}