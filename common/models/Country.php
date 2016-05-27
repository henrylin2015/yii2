<?php
namespace common\models;

use yii\db\ActiveRecord;
use yii\data\Pagination;
class Country extends ActiveRecord{
    public function all($order){
        return $this->find()->orderby($order)->all();
    }
    public function find_one($id){
        return $this->findOne($id);
    }
    public function find_pages($order,$pageSize = 5){
        $query = $this->find();
        $pagination = new Pagination([
            'defaultPageSize' => $pageSize,
            'totalCount' => $query->count(),
        ]);

        $infos = $query->orderBy($order)
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return ['all'=>$infos,'pagelinks'=>$pagination];
    }
}