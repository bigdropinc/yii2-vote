<?php
/**
 * Created by PhpStorm.
 * User: bigdrop
 * Date: 21.10.15
 * Time: 14:13
 */
namespace shirase\vote\helpers;
use yii\helpers\ArrayHelper;

class VoteHelper{
    public static function countLike($model,$model_id,$_options=[]){
    	$options = array_merge([
    			'encode'=>true,
    			'plusState'=>1,
    			'minusState'=>-1,
    			'class'=>'shirase\vote\models\Vote',
    			'modelField'=>'model',
    			'modelIdField'=>'model_id',
    			'typeField'=>'type'
    	],$_options);
    	extract($options,EXTR_SKIP);
    	return $class::find()->where([
            $modelField => $encode?crc32($model):$model,
            $modelIdField => $model_id,
            $typeField => $type,
        ])->count();
    }
    public static function countDislike($model,$model_id,$_options=[]){
    	$options = array_merge([
    			'encode'=>true,
    			'plusState'=>1,
    			'minusState'=>-1,
    			'class'=>'shirase\vote\models\Vote',
    			'modelField'=>'model',
    			'modelIdField'=>'model_id',
    			'typeField'=>'type'
    	],$_options);
    	extract($options,EXTR_SKIP);
    	return $class::find()->where([
            $modelField => $encode?crc32($model):$model,
            $modelIdField => $model_id,
            $typeField => $type,
        ])->count();
    }
    public static function emptyVotes(){
    	return [
            'total'=>	0,
            'plus'=>	0,
            'Isplus'=>	0,
            'minus'=>	0,
            'Isminus'=>	0,
        ];
    }
    public static function countVotes($model,$model_id,$_options=[]){
    	$options = array_merge([
    			'encode'=>true,
    			'plusState'=>1,
    			'minusState'=>-1,
    			'class'=>'shirase\vote\models\Vote',
    			'modelField'=>'model',
    			'modelIdField'=>'model_id',
    			'typeField'=>'type',
    			'user_id'=>\Yii::$app->user->id,
    			'user_ip'=>\Yii::$app->request->userIP,
    	],$_options);
    	extract($options,EXTR_SKIP);
        return $class::find()->select([
            'total'=>'count(*)',
            'plus'=>"count(IF($typeField=$plusState,1,NULL))",
            'Isplus'=>"count(IF(($typeField=$plusState AND (user_id='$user_id')),1,NULL))",
            'minus'=>"count(IF($typeField=$minusState,1,NULL))",
            'Isminus'=>"count(IF(($typeField=$minusState AND (user_id='$user_id')),1,NULL))",
        ])->where([
            $modelField => $encode?crc32($model):$model,
            $modelIdField => $model_id,
            $typeField => [$plusState,$minusState],
        ])->groupBy($modelField)->asArray()->one()?:static::emptyVotes();
    }
}