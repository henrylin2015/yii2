<?php
use yii\helpers\Html;
use yii\widgets\linkPager;
?>
<h1>Countries</h1>
<ul>
  <?php foreach($model['all'] as $v): ?>
    <li>
      <?=Html::encode("{$v->name} ({$v->code})");?>
      <?=Html::encode("{$v->population}");?>
    </li>
  <?php endforeach; ?>
  <?= LinkPager::widget(['pagination' => $model['pagelinks']]) ?>
</ul>