<?php
/**
 * Created by PhpStorm.
 * User: bigdrop
 * Date: 19.10.15
 * Time: 15:03
 */

namespace shirase\vote\actions;

use shirase\vote\helpers\VoteHelper;
use yii\base\Action;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;

class VoteAction extends Action{
    public $model = 'shirase\vote\models\Like';
    public $cancelable = false;
    public $type = 1;
    public $action;

    public function behaviors(){
        return [
            'verbs'=>[
                'class'=>VerbFilter::className(),
                'actions'=>[
                    '*'=>['post'],
                ]
            ]
        ];
    }

    public function run(){
        if(!Yii::$app->request->isAjax){
            return Yii::$app->response->redirect(['/site/error']);
        }
        if(Yii::$app->user->isGuest){
            return Json::encode(['AccessError'=>'Authorization needed']);
        }
        if(is_callable($this->action)){
            return $this->action();
        }
        $estimatedModel = Yii::$app->request->post('model');
        $estimatedModel_id = Yii::$app->request->post('id');
        $options = [
            'model'=>$estimatedModel,
            'model_id'=>$estimatedModel_id,
            'user_id'=>Yii::$app->user->id,
            'ip'=>Yii::$app->request->userIP,
            'type'=>$this->type,
        ];
        $model = new $this->model($options);
        $like = $model::findOne([
            'model'=>$model->model,
            'model_id'=>$model->model_id,
            'user_id'=>$model->user_id,
            'type'=>[$model->type,$model->type*(-1)],
        ]);
        if($like){
            if($like->type == $this->type) {
                if($this->cancelable) {
                    if ($like->delete()) {
                        return Json::encode([
                            'cancel' => 'canceled',
                            'votes'=>VoteHelper::countVotes($model->model,$model->model_id,false)]);
                    } else {
                        return Json::encode([
                            'error' => 'Canceling failed',
                            'votes'=>VoteHelper::countVotes($model->model,$model->model_id,false)]);
                    }
                }else{
                    return Json::encode([
                        'double' => 'Doppelganger',
                        'votes'=>VoteHelper::countVotes($model->model,$model->model_id,false)
                    ]);
                }
            }elseif($like->type == ($this->type * (-1))){
                if(!$like->delete()){
                    return Json::encode([
                        'error'=>'Vote failed',
                        'votes'=>VoteHelper::countVotes($model->model,$model->model_id,false)
                    ]);
                }
            }
        }
        if(!$model->save()){
            return Json::encode([
                'error'=>'Vote saving failed',
                'votes'=>VoteHelper::countVotes($model->model,$model->model_id,false)
            ]);
        }

        return Json::encode([
            'success'=>true,
            'votes'=>VoteHelper::countVotes($model->model,$model->model_id,false)
        ]);
    }
}