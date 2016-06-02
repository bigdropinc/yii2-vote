likedislike
===========
Like and dislike module widget

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist shirase/yii2-vote "*"
```

or add

```
"shirase/yii2-vote": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Create the table votes using needed type for user and model ID's:
```sql
CREATE TABLE vote(
	user_id BIGINT NOT NULL,
	model_id BIGINT NOT NULL,
	model VARCHAR(32) NOT NULL,
	type TINYINT(1) NOT NULL,
	ip VARCHAR(15) DEFAULT NULL,
	created_at DATETIME NOT NULL,
	updated_at DATETIME NOT NULL,
	PRIMARY KEY (user_id, model_id, model, type)
);
```
Or use predefined migration in MODULE/migrations folder.

```
php yii migrate --migrationPath=@vendor/shirase/yii2-vote/migrations
```

Include VoteAction in your controller:

```php
public function actions()
{
    return [
        'like'=>[
            'class'=>shirase\vote\actions\VoteAction::className(),
            'model'=>shirase\vote\models\Vote::className(),
        ],
        'dislike'=>[
            'class'=>shirase\vote\actions\VoteAction::className(),
            'model'=>shirase\vote\models\Vote::className(),
            'type'=>-1,
        ],
    ];
}
```

VoteAction parameter:
---------------------
	"model" - class name of vote activeRecord. Default value: "shirase\vote\models\Like"
	"type" - type of operation that will be executed ( 1 => like, -1 => dislike). Default value: 1
	"action" - anonymous function that will be called instead of action.
	"allowGuests" - allow action for guest users. Default value: false
	
By default client script of voteWidget send "POST" with two parameters for voteAction:

	"model" - class name for voted model. By default value encoded with crc32.
	"id" - ID for voted model.

By default VoteAction get next parameters from application:

	"user_id" - get from Yii::$app->user->id
	"ip" - get from Yii::$app->request->userIP

Using widget:
-------------

Once the extension is installed, simply use it in your code by  :

```php
shirase\vote\widgets\Vote::widget([
        'model' => $model, //*Obligatory parameter. Object for Like/Dislike.
        'primaryField' => 'id', //Name of primary key for model
        'actionPath'=>'/controllerName/', //Path to controller for like/dislike action. E.g. '/site/' for action '/site/like'
        'ajaxOptions'=>[
            //ajax attributes
            'url'=>"http://custom.url",
            'method'=>'POST',
            'data'=>['custom'=>'data'],
            'dataType'=>'HTML',
            'etc'=>'etc',
        ],
        'clientOptions'=>[
            //widget attributes
            'likeError'=>JsExpression("alert('like success');"); //method on ajax like error
            'likeSuccess'=>JsExpression("alert('like error');"); //method on ajax like success
            'dislikeSuccess'=>JsExpression("alert('dislike success');"); //method on ajax dislike success
            'dislikeError'=>JsExpression("alert('dislike error');"); //method on ajax dislike error
        ],
        'voteModel' => MyLikeModel::className(), //ActiveRecord class of table for storing vote data.
        'vote' => $myLikeModel, //Vote table object
        'modelField' => 'modelFieldForLikeModel', //Name of field for Vote table in wich store model identifier
        'modelIdField' => 'modelIdFieldForLikeModel', //Name of field for Vote table in wich store model primary key
        'userIdField' => 'userIdFieldForLikeModel', //Name of field for Vote table in wich store user identifier
        'typeField' => 'typeFieldForLikeModel', //Name of field for Vote table in wich store vote type
        'guestErrorMessage' => "You are guest, go away!", //Message displaying instead of widget for guest users
        'cancelable'=>true, //Is user able to cancel their like
    ]);
```
[![Yii2](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](http://www.yiiframework.com/)
