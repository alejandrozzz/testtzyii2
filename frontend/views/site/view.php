<?php use yii\helpers\Html; ?>
    <h1><?php echo 'Блюдо: '.$meal->name;?></h1>
    <h2>Ингредиенты:</h2>
<?php
foreach ($meal->getIngredients() as $ingredient){
    echo \backend\models\Ingredient::getIngredient($ingredient) . '<br>';
}?>