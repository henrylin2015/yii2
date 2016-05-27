<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
/***
 * @description:这个类是所有类的父类，每一个类执行之前都要执行这个类的方法
 * @author:henry
 * @create:2016年05月27日10:22:55
 */
class BaseController extends Controller{
    public function init(){
        //echo "init ...<br>";
        //public $layout = false;
        //$this->layout = false;
    }
}