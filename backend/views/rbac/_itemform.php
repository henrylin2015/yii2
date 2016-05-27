<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div style="width:100%;margin:0 25%;">
  <div class="list-group col-md-6">
    <?php echo empty($model->name) ? Html::beginForm([ 'create-item','type'=>$type],'post') :
      Html::beginForm(['update-item','name'=>$model->name],'post');?>
    <div class="list-group-item"><?php echo empty($model->name) ? '添加':'修改';?><?php echo $type==1?'角色':'权限';?></div>
    <div class="list-group-item"><?php echo Html::label('名称：',null,['class'=>'col-md-3']).Html::textInput('description',$model->description);?></div>
    <div class="list-group-item">
      <?php echo Html::label('标 识：',null,['class'=>'col-md-3']).Html::textInput('name',$model->name,['id'=>'itemName']);?>
      <div id="item-name-error" class="col-md-offset-3"></div>
    </div>
    <div class="list-group-item"><?php echo Html::label('规则名称：',null,['class'=>'col-md-3']).Html::textInput('ruleName',$model->ruleName);?></div>
    <div class="list-group-item"><?php echo Html::label('数据：',null,['class'=>'col-md-3']).Html::textInput('data',$model->data);?></div>
    <div class="list-group-item">
      <?php echo Html::button(empty($model->name)? '添加信息':'修改信息',['type'=>'submit','class'=>'btn btn-success col-md-offset-4']);?>
      <input type="button" value="返回上一页" onclick="history.go(-1);" class="btn btn-default" />
    </div>
        <?php echo Html::endForm();?>
  </div>
</div>
<script>
 <?php $this->beginBlock('JS_END');?>

 $('#itemName').blur(function(){
   if($('#itemName').val() == ''){
     alert('标识必须填写');
     return;
   }
   $.ajax({
     url:'<?php echo Url::to(['ajax-ishas-item']);?>',
     data:{'name':$('#itemName').val(),'newRecord':'<?php echo empty($model->name) ? '':$model->name; ?>'},
     dataType:'json',
     type:'post',
     success: function(result){
       if(result)
         $('#item-name-error').css('color','green').html('该标识可以使用！');
       else
         $('#item-name-error').css('color','red').html('该标识已使用，请修改标识！');
     }
   });
 });
 <?php
 $this->endBlock();
 $this->registerJs($this->blocks['JS_END'],\yii\web\view::POS_END);
 ?>
</script>