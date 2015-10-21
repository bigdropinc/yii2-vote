<?php
/**
 * Created by PhpStorm.
 * User: bigdrop
 * Date: 21.10.15
 * Time: 14:13
 */
namespace shirase\vote\helpers;

class VoteHelper{
    public static function countLike($model,$model_id,$encode=true,$state=1,$class='shirase\vote\models\Vote',$modelField='model',$modelIdField='model_id',$stateField='state'){
        return $class::find()->where([
            $modelField => $encode?crc32($model):$model,
            $modelIdField => $model_id,
            $stateField => $state,
        ])->count();
    }
    public static function countDislike($model,$model_id,$encode=true,$state=-1,$class='shirase\vote\models\Vote',$modelField='model',$modelIdField='model_id',$stateField='state'){
        return $class::find()->where([
            $modelField => $encode?crc32($model):$model,
            $modelIdField => $model_id,
            $stateField => $state,
        ])->count();
    }
    public static function countVotes($model,$model_id,$encode=true,$plusState=1,$minusState=-1,$class='shirase\vote\models\Vote',$modelField='model',$modelIdField='model_id',$stateField='state'){
        return $class::find()->select([
            'total'=>'count(*)',
            'plus'=>"count(IF($stateField=$plusState,1,NULL))",
            'minus'=>"count(IF($stateField=$minusState,1,NULL))"
        ])->where([
            $modelField => $encode?crc32($model):$model,
            $modelIdField => $model_id,
            $stateField => [$plusState,$minusState],
        ])->groupBy($modelField)->asArray()->all();
    }
}