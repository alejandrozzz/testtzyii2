<?php

/* @var $this yii\web\View */
$this->title = 'My Meals Application';
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<div class="site-index">
    <?php
    $form = ActiveForm::begin([
    'id' => 'meal-form',
    'enableClientValidation'=>false,
    'validateOnSubmit' => true, // this is redundant because it's true by default
    ]);
    ?>

    <?= $form->field($model, 'ingredients')->checkBoxList(ArrayHelper::map(\backend\models\Ingredient::find()->all(), 'id', 'name')) ?>
    <?php ActiveForm::end(); ?>

    <?php $this->registerJsFile(Yii::getAlias('@frontend'). '/web/js/scripts.js',['position' => \yii\web\View::POS_END]); ?>
     <div class="clearfix"></div>
    <hr />
    <h2>Meals: </h2>
    <table class="table table-striped table-hover">
        <tr>
            <td>#</td>
            <td>Title</td>
        </tr>



    </table>

</div>
<?php
$this->registerJs(

    "
    $(\"#meal-ingredients :checkbox\").click(function () {
        if ($(\"#meal-ingredients :checkbox:checked\").length >= 5) {
            $(\"#meal-ingredients :checkbox:not(:checked)\").prop(\"disabled\", \"disabled\");
        } else {
            $(\"#meal-ingredients :checkbox\").prop(\"disabled\", \"\");
        }
        if ($(\"#meal-ingredients :checkbox:checked\").length < 2) {
            //alert('Выберите больше ингредиентов');
        } else {
        
    var allVals = [];
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
                $('table').empty().append('<tr><td>#</td><td>Title</td></tr>');
                
                    if (data.code != 300){
                        $(JSON.parse(data.search)).each(function(){
                            $('table').append('<tr><td>'+$(this)[0].meal_id+'</td><td></td></tr>');
                        })
                    }
                 else {
                    $('table').append('<tr><td></td><td>Ничего не найдено.</td></tr>')
                }}
            });}
    })"
);
?>