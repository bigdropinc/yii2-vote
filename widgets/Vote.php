<?php
namespace shirase\vote\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\JsExpression;

class Vote extends Widget{
	protected $id;
	public $model;
	public $voteModel = 'shirase\vote\models\Like';
	public $vote;
	public $modelField = 'model';
	public $modelIdField = 'model_id';
	public $stateField = 'state';
	public $userIdField = 'user_id';
	public $cancelable = false;
	public $guestErrorMessage;
	public $actionPath;
	public $ajaxOptions=[];
	public $likeAction = 'like';
	public $dislikeAction = 'dislike';
	public $likeButton;
	public $dislikeButton;
	public $primaryField = 'id';
	public $clientOptions = [];
	public $clientVar;

	public function init(){
		$this->id = self::getId(true);
		$this->likeButton = ($this->likeButton)?:'#'.$this->id.'-like';
		$this->dislikeButton = ($this->dislikeButton)?:'#'.$this->id.'-dislike';
	}
	public function clientWidget($var = null){
		$begin = '';
		if($var !== null){
			$begin = "var $var = ";
		}
		$options = ArrayHelper::merge([
			'action_path'=>$this->actionPath,
			'ajax_options'=>$this->ajaxOptions,
			'like_button'=>$this->likeButton,
			'dislike_button'=>$this->dislikeButton,
			'cancelable'=>$this->cancelable,
			'model'=>crc32(get_class($this->model)),
			'id'=>$this->model[$this->primaryField],
		],$this->clientOptions);
		$options = Json::encode($options);
		$script = new JsExpression($begin."(new vote({$options}));");

		return $script;
	}
	public function run(){
		if(\Yii::$app->user->isGuest){
			return $this->guestErrorMessage;
		}
		VoteAsset::register($this->view);
		$this->view->registerJs($this->clientWidget($this->clientVar));
		return $this->render('index',[
			'model'=>$this->model,
			'widgetId'=>$this->id,
			'likeUrl'=>Url::to($this->actionPath.$this->likeAction),
			'dislikeUrl'=>Url::to($this->actionPath.$this->dislikeAction),
		]);
	}
}