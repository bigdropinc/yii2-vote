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
    public $model = 'shirase\vote\models\Vote';
    public $allowGuests = false;
    public $cancelable = false;
    public $type = 1;
    public $action;

    public function run(){
        if(!Yii::$app->request->isAjax){
            return Yii::$app->response->redirect(['/site/error']);
        }
        if(Yii::$app->user->isGuest && !$this->allowGuests){
            return Json::encode([
            		'status'=>'AccessError',
            		'message'=>'Authorization needed',
            		'votes'=>[]
            ]);
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
        if(!$model->validate()){
        	return Json::encode([
        			'status' => 'Error',
        			'message' => 'Data validation failed',
        			'errors' => $model->getErrors(),
        			'votes'=>VoteHelper::countVotes($model->model,$model->model_id,['encode'=>false])]);
        }
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
                            'status' => 'cancel',
                        	'message' => 'canceled',
                            'votes'=>VoteHelper::countVotes($model->model,$model->model_id,['encode'=>false])]);
                    } else {
                        return Json::encode([
                            'status' => 'error',
                        	'message' => 'Canceling failed',
                            'votes'=>VoteHelper::countVotes($model->model,$model->model_id,['encode'=>false])]);
                    }
                }else{
                    return Json::encode([
                        'status' => 'double',
                    	'message' => 'Doppelganger',
                        'votes'=>VoteHelper::countVotes($model->model,$model->model_id,['encode'=>false])
                    ]);
                }
            }elseif($like->type == ($this->type * (-1))){
                if(!$like->delete()){
                    return Json::encode([
                        'status' => 'error',
                    	'message' => 'Vote failed',
                        'votes'=>VoteHelper::countVotes($model->model,$model->model_id,['encode'=>false])
                    ]);
                }
            }
        }
        if(!$model->save()){
            return Json::encode([
                'status' => 'error',
            	'message'=>'Vote saving failed',
                'votes'=>VoteHelper::countVotes($model->model,$model->model_id,['encode'=>false])
            ]);
        }

        return Json::encode([
            'status' => 'success',
        	'message'=>true,
            'votes'=>VoteHelper::countVotes($model->model,$model->model_id,['encode'=>false])
        ]);
    }
}