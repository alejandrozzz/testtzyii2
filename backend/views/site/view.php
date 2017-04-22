<?php use yii\helpers\Html; ?>
<div class="pull-right btn-group">
    <?php echo Html::a('Update', array('site/update', 'id' => $meal->meal_id), array('class' => 'btn btn-primary')); ?>
    <?php echo Html::a('Delete', array('site/delete', 'id' => $meal->meal_id), array('class' => 'btn btn-danger')); ?>
</div>

<h1><?php echo 'Блюдо: '.$meal->name;?></h1>
    <h2>Ингредиенты:</h2>
<?php
    foreach ($meal->getIngredients() as $ingredient){
        echo \backend\models\Ingredient::getIngredient($ingredient) . '<br>';
    }?>