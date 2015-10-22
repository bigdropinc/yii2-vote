<?php
/**
 * Created by PhpStorm.
 * User: bigdrop
 * Date: 21.10.15
 * Time: 14:13
 */
namespace shirase\vote\helpers;

class VoteHelper{
    public static function countLike($model,$model_id,$encode=true,$type=1,$class='shirase\vote\models\Vote',$modelField='model',$modelIdField='model_id',$typeField='type'){
        return $class::find()->where([
            $modelField => $encode?crc32($model):$model,
            $modelIdField => $model_id,
            $typeField => $type,
        ])->count();
    }
    public static function countDislike($model,$model_id,$encode=true,$type=-1,$class='shirase\vote\models\Vote',$modelField='model',$modelIdField='model_id',$typeField='type'){
        return $class::find()->where([
            $modelField => $encode?crc32($model):$model,
            $modelIdField => $model_id,
            $typeField => $type,
        ])->count();
    }
    public static function countVotes($model,$model_id,$encode=true,$plusState=1,$minusState=-1,$class='shirase\vote\models\Vote',$modelField='model',$modelIdField='model_id',$typeField='type'){
        return $class::find()->select([
            'total'=>'count(*)',
            'plus'=>"count(IF($typeField=$plusState,1,NULL))",
            'minus'=>"count(IF($typeField=$minusState,1,NULL))"
        ])->where([
            $modelField => $encode?crc32($model):$model,
            $modelIdField => $model_id,
            $typeField => [$plusState,$minusState],
        ])->groupBy($modelField)->asArray()->all();
    }
}