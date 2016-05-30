<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

class WelcomeController extends Controller{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionUpdate(){
        echo "welcome update";
    }
    public function actionAdd(){
        echo "welcome add";
    }
    public function actionDel(){
        echo "welcome del";
    }
}