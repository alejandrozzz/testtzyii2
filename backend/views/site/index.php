<?php use yii\helpers\Html; ?>

<?php echo Html::a('Create New Meal', array('site/create'), array('class' => 'btn btn-primary pull-right')); ?>
<?php echo Html::a('Create New Ingredient', array('site/createingredient'), array('class' => 'btn btn-primary pull-right')); ?>
<div class="clearfix"></div>
<hr />
<h2>Meals: </h2>
<table class="table table-striped table-hover">
    <tr>
        <td>#</td>
        <td>Title</td>
        <td>Options</td>
    </tr>
    <?php foreach ($data as $meal): ?>
        <tr>
            <td>
                <?php echo Html::a($meal->meal_id, array('site/view', 'id'=>$meal->meal_id)); ?>
            </td>
            <td><?php echo Html::a($meal->name, array('site/view', 'id'=>$meal->meal_id)); ?></td>
<!--            <td>--><?php //echo $meal->created; ?><!--</td>-->
<!--            <td>--><?php //echo $meal->updated; ?><!--</td>-->
            <td>
                <?php echo Html::a('Edit', array('site/update', 'id'=>$meal->meal_id), array('class'=>'icon icon-edit')); ?>
                <?php echo Html::a('Remove', array('site/delete', 'id'=>$meal->meal_id), array('class'=>'icon icon-trash')); ?>
            </td>
        </tr>
    <?php endforeach; ?>

</table>
<h2>Ingredients: </h2>
<table class="table table-striped table-hover">
    <tr>
        <td>#</td>
        <td>Title</td>
        <td>Hidden</td>
        <td>Options</td>
    </tr>
    <?php foreach ($ingredients as $meal): ?>
        <tr>
            <td>
                <?php echo Html::a($meal->id, array('site/viewingredient', 'id'=>$meal->id)); ?>
            </td>
            <td><?php echo Html::a($meal->name, array('site/viewingredient', 'id'=>$meal->id)); ?></td>
            <td>
            <td><?php echo $meal->hidden; ?></td>
            <td>
                <?php echo Html::a('Edit', array('site/updateingredient', 'id'=>$meal->id), array('class'=>'icon icon-edit')); ?>
                <?php echo Html::a('Remove', array('site/deleteingredient', 'id'=>$meal->id), array('class'=>'icon icon-trash')); ?>
                <?php echo Html::a('Hide', array('site/hideingredient', 'id'=>$meal->id), array('class'=>'icon icon-trash')); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
