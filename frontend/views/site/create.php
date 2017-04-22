<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<?php
$form = ActiveForm::begin([
    'id' => 'meal-form',
    'enableClientValidation'=>false,
    'validateOnSubmit' => true, // this is redundant because it's true by default
]);

echo $form->field($model, 'name')->textInput(array('class' => 'span8')); ?>
    <div class="form-actions">
        <?php echo Html::submitButton('Submit', null, null, array('class' => 'btn btn-primary')); ?>
    </div>
<?= $form->field($model, 'ingredients')->checkBoxList(ArrayHelper::map(\backend\models\Ingredient::find()->all(), 'id', 'name')) ?>
<?php ActiveForm::end(); ?>

<?php $this->registerJsFile(Yii::getAlias('@frontend'). '/web/js/scripts.js',['position' => \yii\web\View::POS_END]); ?>

<?php
$this->registerJs(

    "var allVals = [];
            $('#meal-ingredients :checked').each(function() {
                allVals.push($(this).val());
            });

            $.ajax({
                url: '". \Yii::$app->getUrlManager()->createUrl('/site/ajax/') . "',
                type: 'post',
                data: {
                    ingredients: JSON.stringify(allVals),
                    _csrf : '" .Yii::$app->request->getCsrfToken() . "'
                },
                success: function (data) {
                    console.log(data.search);
                }
            });"
);
?>