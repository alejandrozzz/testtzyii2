<?php
namespace frontend\controllers;

use backend\models\MealIngredient;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use backend\models\Meal;
use backend\models\Ingredient;

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
                'only' => ['logout', 'signup', 'create', 'ajax'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'create', 'ajax'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'ajax' => ['post'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $meals = new Meal();
        $model = new Meal();
        $meals = $meals->find()->all();
        echo $this->render('index', array(
            'meals' => $meals,
            'model' => $model
        ));
        //return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
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
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
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

    public function actionAjax()
    {

        $model = new MealIngredient();
        if (Yii::$app->request->isAjax)
        {
            $data = (Yii::$app->request->post());
//            var_dump($data);
//            die();
            $ingredients = json_decode($data['ingredients']);

            //$search = $model::find()->select(['ingredient_id'])->addGroupBy('ingredient_id')->where(array('ingredient_id'))->all();
            $tmp1 = '';
            $tmp2 = '';
            foreach ($ingredients as $ingr){
                $tmp1 .= 'meal_ingredient.ingredient_id = '.(int)htmlspecialchars($ingr).' OR ';
                $tmp2 .= 'tmp.ingredient_id = '.(int)htmlspecialchars($ingr).' AND ';

            }
            $tmp1 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $tmp1);
            $tmp1 = str_replace('&#039;', '', $tmp1);
            $tmp2 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $tmp2);
            $tmp2 = str_replace('&#039;', '', $tmp2);
            $sql = "CREATE TEMPORARY TABLE IF NOT EXISTS tmp AS (SELECT DISTINCT * FROM meal_ingredient where $tmp1)";
             $query = Yii::$app->db->createCommand($sql)->execute();
            $sql = "SELECT DISTINCT meal_ingredient.meal_id as meal_id from meal_ingredient inner JOIN tmp on meal_ingredient.ingredient_id = tmp.ingredient_id and meal_ingredient.meal_id = tmp.meal_id group by meal_id having count(tmp.ingredient_id) = ".count($ingredients);
            //$sql = "SELECT DISTINCT tmp.meal_id as meal_id from tmp where $tmp2";
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $query = Yii::$app->db->createCommand($sql)->queryAll();
//            if (count($query))
//
//            return [
//                'search' => json_encode($query ),
//                'code' => 100,
//            ];
            //Yii::$app->db->createCommand('SELECT 0 INTO @x; ');

            $sql = "SELECT DISTINCT meal_ingredient.meal_id as meal_id, meal_ingredient.ingredient_id from meal_ingredient inner JOIN tmp on meal_ingredient.ingredient_id = tmp.ingredient_id and meal_ingredient.meal_id = tmp.meal_id group by meal_id having meal_ingredient.ingredient_id NOT in (select id from ingredients where hidden = 1) order by count(tmp.ingredient_id)";
            $query = Yii::$app->db->createCommand($sql)->queryAll();

            if (count($query)>=2){
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'search' => json_encode($query ),
                    'code' => 200,
                ];}
            else {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'search' => json_encode([]),
                    'code' => 300,
                ];
            }
        }


    }
}
