<?php
namespace backend\controllers;

use yii\web\Controller;
use yii\db\Query;
use yii\data\ActiveDataProvider;
class RbacController extends Controller{
    /**
     * 角色/权限列表
     * @param number $type
     * @return Ambigous <string, string>
     */
    public function actionItemindex($type = 1){
        $auth = \Yii::$app->getAuthManager();
        $dataProvider = new ActiveDataProvider([
            'query' => (new Query())->from($auth->itemTable)
            ->where(['type'=>$type])
            ->orderBy(['created_at'=>SORT_DESC]),
            'pagination' => ['pageSize' => 8],
        ]);
        return $this->render('itemIndex',['dataProvider'=>$dataProvider,'type'=>$type]);
    }
    /**
     * 添加角色/权限
     * @param unknown $type
     * @return Ambigous <string, string>
     */
    public function actionCreateItem($type){
        $item = new \yii\rbac\Item();
        if($item->name === null  && ($data = \Yii::$app->request->post())){
            $item->name = $data['name'];
            $item->description = $data['description'];
            $item->ruleName = $data['ruleName'];
            $item->data = $data['data'];
            $item->type = $type;
            $rbac = \Yii::$container->get('\app\models\Rbac');
            if($rbac->createItem($item))
            return $this->success(['view-item','name'=>$item->name]);
            else
            return $this->error('数据插入失败！');
        }
        return $this->render('_itemform',['model'=>$item,'type'=>$type]);
    }
    /**
     * 更新一个 角色/权限
     * @param unknown $name
     * @return Ambigous <\backend\controllers\Ambigous, string, string>|Ambigous <string, string>
     */
    public function actionUpdateItem($name){
        $rbac = \Yii::$container->get('\app\models\Rbac');
        if(empty($item = $rbac->getOneItem($name)))
        return $this->error('不存在的项目');
        if($data = \Yii::$app->request->post()){
            $name = $item->name;
            $item->name = $data['name'];
            $item->description = $data['description'];
            $item->ruleName = $data['ruleName'];
            $item->data = $data['data'];
            if($rbac->updateOneItem($name,$item))
            return $this->success(['view-item','name'=>$item->name]);
            else
            return $this->error('数据修改失败！');
        }
        return $this->render('_itemform',['model'=>$item,'type'=>$item->type]);
    }
    /**
     * 删除一个角色 权限
     * @param unknown $name
     * @return Ambigous <\backend\controllers\Ambigous, string, string>
     */
    public function actionDeleteItem($name){
        $rbac = \Yii::$container->get('\app\models\Rbac');
        //使用了MyISAM引擎，因此不支持级联删除，所以不允许有子节点的情况下删除父节点
        if((new Query())->from($rbac->itemChildTable)->andWhere(['parent'=>$name])->orWhere(['child'=>$name])->count()>0)
        return $this->error('该项目下还有子节点，请先删除子节点再删除该节点'); 
        if($rbac->deleteOneItem($name))
        return $this->success(['itemindex']);
        else
        return $this->error('删除失败！');
    }
    /**
     * 查看信息 角色  权限
     * @param unknown $name
     * @return Ambigous <string, string>
     */
    public function actionViewItem($name){
        $rbac = \Yii::$container->get('\app\models\Rbac');
        $item = $rbac->getOneItem($name);
        if($item !== null)
        return $this->render('itemView',['model'=>$item]);
        else
        $this->goBack();
    }
    /**
     * Ajax判断角色 权限是否存在主键冲突
     * @return string
     */
    public function actionAjaxIshasItem(){
        $data = \Yii::$app->request->post();
        $rbac = \Yii::$container->get('\app\models\Rbac');
        $result = $rbac->getOneItem($data['name']);
        if($data['name'] == $data['newRecord'])
        return json_encode($result === null ? false:true);
        else
        return json_encode($result === null ? true:false);
    }
    /**
     * 子节点列表
     * @return Ambigous <string, string>
     */
    public function actionItemChildindex(){
        $auth = \Yii::$app->getAuthManager();
        $dataProvider = new ActiveDataProvider([
            'query' => (new Query())->from($auth->itemChildTable)
            ->orderBy(['parent'=>SORT_DESC]),
        ]);
        return $this->render('itemChildIndex',['dataProvider'=>$dataProvider]);
    }
    /**
     * 添加子节点  可以为角色 权限添加子节点  （不能将一个权限作为角色的子节点 ）
     */
    public function actionCreateItemChild(){
        if($data = \Yii::$app->request->post()){
            $rbac = \Yii::$container->get('\app\models\Rbac');
            if(empty($data['parent']) || empty($data['childs']) || !($parent = $rbac->getOneItem($data['parent']))) 
            return $this->error('父节点或子节点信息错误！');
            $errors = 0;
            foreach ($data['childs'] as $v){
                if(!($child = $rbac->getOneItem($v)))
                return $this->error('不存在的子节点！');
                try {
                    $rbac->addChild($parent,$child);
                }catch (\Exception $e){
                    $errors++;
                }
            }
            if($errors)
            return $this->success(['item-childindex'],$errors.'条信息没有插入成功');
            else
            return $this->success(['item-childindex']);
        }

        return $this->render('_itemChildForm',['model'=>['parent'=>'','child'=>'']]);
    }
    /**
     * 删除子节点
     * @param unknown $parent
     * @param unknown $child
     * @return Ambigous <\backend\controllers\Ambigous, string, string>
     */
    public function actionDeleteItemChild($parent,$child){
        $rbac = \Yii::$container->get('\app\models\Rbac');
        if(empty($parent) || empty($child) || !($parent = $rbac->getOneItem($parent)) || !($child = $rbac->getOneItem($child)))
        return $this->error('错误的参数');
        return $rbac->removeChild($parent, $child) ?  $this->success(['item-childindex']) : $this->error('删除失败');
    }
    /**
     * Ajax请求所有的项目
     */
    public function actionAjaxGetAllitem(){
        $auth = \Yii::$app->getAuthManager();
        $items = (new Query)->select(['name','description','type'])->from($auth->itemTable)->orderBy(['type'=>SORT_ASC])->all($auth->db);
        return $this->renderAjax('itemViewAll',['data'=>$items]);
    }
    /**
     * 用户权限列表
     */
    public function actionAssignmentIndex(){
        $auth = \Yii::$app->getAuthManager();
        return $this->render('assignmentIndex',['data'=>(new Query())->from($auth->assignmentTable)->orderBy(['user_id'=>SORT_DESC])->all()]);
    }
    /**
     * 为用户分配角色
     */
    public function actionCreateAssignment(){
        $rbac = \Yii::$container->get('\app\models\Rbac');
        if(($data = \Yii::$app->request->post()) && !empty($data['user_id']) && !empty($data['item_name'])){
            $item = $rbac->getOneItem($data['item_name']);
            $role = new \yii\rbac\Role();
            if(empty($item))
            return $this->error('错误的传入参数');
            $role->name = $item->name;
            if($rbac->assign($role,$data['user_id']))
            return $this->success(['assignment-index']);
            else
            return $this->error('分配权限失败');
        }
        $arr = array_combine((new Query())->select(['name'])->from($rbac->itemTable)->where(['type'=>1])->orderBy(['name'=>SORT_DESC])->column(), (new Query())->select(['description'])->from($rbac->itemTable)->where(['type'=>1])->orderBy(['name'=>SORT_DESC])->column());
        return $this->render('_assignment',['roles'=>$arr]);
    }
    /**
     * 删除用户角色
     * @param unknown $user_id
     * @param unknown $item_name
     */
    public function actionDeleteAssignment($user_id,$item_name){
        $rbac = \Yii::$container->get('\app\models\Rbac');
        $item = $rbac->getOneItem($item_name);
        if($rbac->revoke($item, $user_id))
        return $this->success(['assignment-index']);
        else
        return $this->error('删除失败');
    }
}