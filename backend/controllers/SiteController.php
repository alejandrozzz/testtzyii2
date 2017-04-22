<?php
namespace backend\controllers;

use backend\models\Ingredient;
use backend\models\Meal;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        //see captcha and error added here, this fixes the issue
                        'actions' => ['create', 'view' ,'createingredient', 'viewingredient', 'update', 'delete', 'updateingredient', 'deleteingredient', 'hideingredient'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $meals = new Meal();
        $data = $meals->find()->all();
        $ingredients = new Ingredient();
        $ingredients = $ingredients->find()->all();
        echo $this->render('index', array(
            'data' => $data,
            'ingredients' => $ingredients
        ));
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionView($id=NULL)
    {
        if ($id === NULL)
            throw new HttpException(404, 'Not Found');

        $meal = Meal::find()->where(['meal_id' => $id])->one();

        if ($meal === NULL)
            throw new HttpException(404, 'Document Does Not Exist');

        echo $this->render('view', array(
            'meal' => $meal
        ));
    }

    public function actionCreate()
    {
        $model = new Meal;
        if (isset($_POST['Meal']))
        {
            $model->name = $_POST['Meal']['name'];
            if (is_array($_POST['Meal']['ingredients']))
                $model->setIngredients($_POST['Meal']['ingredients']);
            else $model->setIngredients([]);

            if ($model->save())
                Yii::$app->response->redirect(array('site/view', 'id' => $model->meal_id));
        }

        echo $this->render('create', array(
            'model' => $model
        ));
    }

    public function actionCreateingredient()
    {
        $model = new Ingredient();
        if (isset($_POST['Ingredient']))
        {
            $model->name = $_POST['Ingredient']['name'];

            //$model->content = $_POST['Post']['content'];

            if ($model->save())
                Yii::$app->response->redirect(array('site/viewIngredient', 'id' => $model->id));
        }

        echo $this->render('createIngredient', array(
            'model' => $model
        ));
    }

    public function actionViewingredient($id=NULL)
    {
        if ($id === NULL)
            throw new HttpException(404, 'Not Found');

        $ingredient = Ingredient::find()->where(['id' => $id])->one();

        if ($ingredient === NULL)
            throw new HttpException(404, 'Document Does Not Exist');

        echo $this->render('viewingredient', array(
            'ingredient' => $ingredient
        ));
    }

    public function actionUpdate($id=NULL)
    {
        if ($id === NULL)
            throw new HttpException(404, 'Not Found');

        $model = Meal::find()->where(array('meal_id' => $id))->one();

        if ($model === NULL)
            throw new HttpException(404, 'Document Does Not Exist');

        if (isset($_POST['Meal']))
        {
            $model->name = $_POST['Meal']['name'];
            if (is_array($_POST['Meal']['ingredients']))
            $model->setIngredients($_POST['Meal']['ingredients']);
            else $model->setIngredients([]);

            if ($model->save())
                Yii::$app->response->redirect(array('site/view', 'id' => $model->meal_id));
        }

        echo $this->render('create', array(
            'model' => $model
        ));
    }

    public function actionDelete($id=NULL)
    {
        if ($id === NULL)
        {
            Yii::$app->session->setFlash('MealDeletedError');
            Yii::$app->getResponse()->redirect(array('site/index'));
        }

        $meal = Meal::findOne($id);


        if ($meal === NULL)
        {
            Yii::$app->session->setFlash('MealDeletedError');
            Yii::$app->getResponse()->redirect(array('site/index'));
        }

        $meal->delete();

        Yii::$app->session->setFlash('MealDeleted');
        Yii::$app->getResponse()->redirect(array('site/index'));
    }

    public function actionUpdateingredient($id=NULL)
    {
        if ($id === NULL)
            throw new HttpException(404, 'Not Found');

        $model = Ingredient::find()->where(array('id' => $id))->one();

        if ($model === NULL)
            throw new HttpException(404, 'Document Does Not Exist');

        if (isset($_POST['Ingredient']))
        {
            $model->name = $_POST['Ingredient']['name'];

            if ($model->save())
                Yii::$app->response->redirect(array('site/viewingredient', 'id' => $model->id));
        }

        echo $this->render('createingredient', array(
            'model' => $model
        ));
    }

    public function actionDeleteingredient($id=NULL)
    {
        if ($id === NULL)
        {
            Yii::$app->session->setFlash('IngredientDeletedError');
            Yii::$app->getResponse()->redirect(array('site/index'));
        }

        $ingredient = Ingredient::findOne($id);


        if ($ingredient === NULL)
        {
            Yii::$app->session->setFlash('IngredientDeletedError');
            Yii::$app->getResponse()->redirect(array('site/index'));
        }

        $ingredient->delete();

        Yii::$app->session->setFlash('IngredientDeleted');
        Yii::$app->getResponse()->redirect(array('site/index'));
    }

    public function actionHideingredient($id=NULL)
    {
        if ($id === NULL)
            throw new HttpException(404, 'Not Found');

        $model = Ingredient::find()->where(array('id' => $id))->one();

        if ($model === NULL)
            throw new HttpException(404, 'Document Does Not Exist');

            $model->hidden = 1;


            if ($model->save())
                Yii::$app->response->redirect(array('site/createingredient', 'id' => $model->id));


        echo $this->render('createingredient', array(
            'model' => $model
        ));
    }
}
