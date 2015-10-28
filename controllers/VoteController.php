<?php
namespace shirase\controllers;
use yii\web\Controller;
use shirase\vote\actions\VoteAction;
use shirase\vote\models\Vote;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class VoteController extends Controller{
	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),
						'only' => ['like', 'dislike'],
						'rules' => [
							[
								'actions' => ['like','dislike'],
								'allow' => true,
								'roles' => ['@'],
							],
						],
				],
				'verbs' => [
						'class' => VerbFilter::className(),
						'actions' => [
								'like' => ['post'],
								'dislike' => ['post'],
						],
				],
		];
	}
	public function actions()
	{
		return [
				'like'=>[ 'class'=>VoteAction::className(), 'model'=>Vote::className(), ],
				'dislike'=>[ 'class'=>VoteAction::className(), 'model'=>Vote::className(), 'type'=>-1, ],
		];
	}
}