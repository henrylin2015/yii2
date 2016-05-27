<?php
namespace frontend\controllers;

use Yii;
use frontend\models\EntryForm;
use frontend\controllers\BaseController;
use common\libs\Tools;
use common\models\Country;
/**
 * Site controller
 */
class SiteController extends BaseController
{
    public function actionIndex($mes="hello"){
        Tools::test();
        return $this->render("index",['mes'=>$mes]);
    }
    public function actionCountry(){
        $country = Yii::$container->get('\common\models\Country');
        //echo "<pre>";
        // var_dump($country->all('name'));
        //var_dump($country->find_one('US'));
        //var_dump($country->find_pages('name'));
        $model = $country->find_pages('name',3);
        //echo "<pre>";
        //var_dump($model);
        //exit();
        return $this->render('country',['model'=>$model]);
    }
    public function actionEntry(){
        $entryForm = new EntryForm;
        //validate
        if($entryForm->load(Yii::$app->request->post()) && $entryForm->validate()){
            echo "OK";
            return;
        }
        return $this->render('entry',['model'=>$entryForm]);
    }
    public function actionText(){
        echo "test....";
        return;
    }
    /***
     * sent email
     */
    public function actionEmail(){
        //没有用模板文件
        //$mail= Yii::$app->mailer->compose();
        //加载模板文件
        $mail = Yii::$app->mailer->compose("email",['to'=>'henry']);
        $mail->setTo('lin_hailing@sina.com');
        $mail->setSubject("邮件测试");
        //$mail->setTextBody('zheshisha ');   //发布纯文字文本
        //$mail->setHtmlBody("<br>问我我我我我");    //发布可以带html标签的文本
        if($mail->send()){
            echo "success";
            return;
        }
        echo "failse";
    }
}
