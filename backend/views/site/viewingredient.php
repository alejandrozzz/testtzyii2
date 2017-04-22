<?php use yii\helpers\Html; ?>
    <div class="pull-right btn-group">
        <?php echo Html::a('Update', array('site/updatingredient', 'id' => $ingredient->id), array('class' => 'btn btn-primary')); ?>
        <?php echo Html::a('Delete', array('site/deleteingredient', 'id' => $ingredient->id), array('class' => 'btn btn-danger')); ?>
    </div>

    <h1><?php echo 'Ингредиент: '.$ingredient->name;?></h1>