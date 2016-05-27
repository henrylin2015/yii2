<?php
namespace backend\models;
use yii\rbac\DbManager;
class Rbac extends DbManager{
        public function createItem($item){
                if(empty($item->name) || $this->getOneItem($item->name) !== null)
                        return false;
                return $this->addItem($item) ? true : false;
        }
        public function getOneItem($name){
                return $this->getItem($name);
        }
        public function updateOneItem($name, $item){
                return $this->updateItem($name, $item);
        }
        public function deleteOneItem($name){
                if($item = $this->getOneItem($name))
                        return $this->removeItem($item);
                else
                        return false;
        }
}