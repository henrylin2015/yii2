<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div style="width:100%;margin:0 5%;">
  <div class="list-group col-md-10">
    <?php echo Html::beginForm(['create-item-child'],'post');?>
    <div class="list-group-item">
      <?php echo Html::label('父节点：',null,['class'=>'col-md-3']).Html::textInput('parent',$model['parent'],['id'=>'parent-item-value']);?>
      <span class="glyphicon glyphicon-plus btn" id="parent-node"></span>
    </div>
    <div class="bg-success" id="parent-node-contents"></div>
    <div class="list-group-item">
      <?php echo Html::label('子节点：',null,['class'=>'col-md-3'])?>
      <span class="glyphicon glyphicon-plus btn" id="child-node"></span>
    </div>
    <div id="child-node-contents"></div>
    <div class="list-group-item">
      <?php echo Html::button(empty($model['parent'])? '添加信息':'修改信息',['type'=>'submit','class'=>'btn btn-success col-md-offset-4']);?>
      <input type="button" value="返回上一页" onclick="history.go(-1);" class="btn btn-default" />
    </div>
        <?php echo Html::endForm();?>
  </div>
</div>
<script>
 <?php $this->beginBlock('GET_ITEM_NODE');?>
 $('#parent-node').click(function(){
   if($('#parent-node-contents').html() == ''){
     $.ajax({
       url:'<?php echo Url::toRoute(['ajax-get-allitem'])?>',
       type:'get',
       success:function(data){
         $('#parent-node-contents').html(data);
         $('#parent-node').removeClass('glyphicon glyphicon-plus');
         $('#parent-node').addClass('glyphicon glyphicon-minus');
         //为每个子节点绑定事件
         $('#parent-node-contents table tr td').click(function(){
           $('#parent-item-value').val($(this).find('strong').html());
           $(this).siblings().removeClass('bg-info');
           $(this).addClass('bg-info');
         }).css('cursor','pointer');
       }
     });
   }else{
     if($('#parent-node-contents').css('display') == 'none'){
       $('#parent-node-contents').css('display','');
       $('#parent-node').removeClass('glyphicon glyphicon-plus');
       $('#parent-node').addClass('glyphicon glyphicon-minus');
     }else{
       $('#parent-node-contents').css('display','none');
       $('#parent-node').removeClass('glyphicon glyphicon-minus');
       $('#parent-node').addClass('glyphicon glyphicon-plus');
     }
   }
 });

 $('#child-node').click(function(){
   if($('#child-node-contents').html() == ''){
     if($('#parent-node-contents').html() == '')
       return alert('请先选择父节点');
     $('#child-node').removeClass('glyphicon glyphicon-plus');
     $('#child-node').addClass('glyphicon glyphicon-minus');
     $('#child-node-contents').html($('#parent-node-contents').html());
     //取消所有绑定事件
     $('#child-node-contents table tr td').unbind("click").css('cursor','pointer').removeClass('bg-info');
     //添加CHECK BOX
     $('#child-node-contents table tr td span').before('<input type="checkbox" name="childs[]" class="child-items" value="" />  ');
     //绑定点击事件
     $('#child-node-contents table tr td').click(function(){
       var childValueObj = $(this).find('.child-items');
       if(childValueObj.val() == '')
         childValueObj.val($(this).find('strong').html());
       if(childValueObj.prop('checked')){
         childValueObj.prop('checked',false)
           $(this).removeClass('bg-info');
       }else{
         //判断添加关系
         if($('#parent-item-value').val() == childValueObj.val())
           return alert('不能为自己添加子节点');
         if($("#parent-node-contents table tr td strong:contains('"+$('#parent-item-value').val()+"')").attr('type') == 2 && $(this).find('strong').attr('type') == 1)
           return alert('不能将角色作为权限的子节点');
         childValueObj.prop('checked',true)
           $(this).addClass('bg-info');
       }
     });
   }else{
     if($('#child-node-contents').css('display') == 'none'){
       $('#child-node-contents').css('display','');
       $('#child-node').removeClass('glyphicon glyphicon-plus');
       $('#child-node').addClass('glyphicon glyphicon-minus');
     }else{
       $('#child-node-contents').css('display','none');
       $('#child-node').removeClass('glyphicon glyphicon-minus');
       $('#child-node').addClass('glyphicon glyphicon-plus');
     }
   }
 });
 <?php $this->endBlock();
 $this->registerJs($this->blocks['GET_ITEM_NODE'],\yii\web\View::POS_END);
 ?>
</script>