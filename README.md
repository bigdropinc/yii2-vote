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

Once the extension is installed, simply use it in your code by  :

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

Include VoteAction in your controller:

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

```php
<?= \\shirase\likedislike\AutoloadExample::widget(); ?>```