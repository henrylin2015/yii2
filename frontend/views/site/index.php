<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
$this->title = 'My Yii Application';
?>
<?= $mes; ?>
<?= Html::encode($mes);?>
<br>
<?= Yii::t('app','name1'); ?>
<br>
<?= Yii::t('app','test'); ?>
<br>
<?=Yii::$app->language; ?>
<br>
<?= Yii::t('app','ccc'); ?>