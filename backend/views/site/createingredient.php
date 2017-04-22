<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'ingredient-form',
        'enableClientValidation'=>false,
        'validateOnSubmit' => true,
    ]);
    echo $form->field($model, 'name')->textInput(array('class' => 'span8'));
    ?>
    <div class="form-actions">
        <?php echo Html::submitButton('Submit', null, null, array('class' => 'btn btn-primary')); ?>
    </div>
<?php ActiveForm::end(); ?>