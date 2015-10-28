<?php
namespace shirase\vote;
use yii\base\Module;

class VoteModule extends Module{
	
	public $model;
	public $primaryField = 'id';
	public $viewPath = 'index';
	public $enableView = true;
	public $voteModel = 'shirase\vote\models\Like';
	public $vote;
	public $modelField = 'model';
	public $modelIdField = 'model_id';
	public $typeField = 'type';
	public $userIdField = 'user_id';
	public $cancelable = false;
	public $guestErrorMessage;
	public $onGuest;
	public $actionPath;
	public $ajaxOptions=[];
	public $likeAction = 'like';
	public $dislikeAction = 'dislike';
	public $likeButton;
	public $dislikeButton;
	public $clientOptions = [];
	public $clientVar;
	
	public function init(){
		parent::init();
	}
}