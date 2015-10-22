<?php
/**
 * Created by PhpStorm.
 * User: bigdrop
 * Date: 19.10.15
 * Time: 17:35
 */

namespace shirase\vote\models;

use Yii;

/**
 * This is the model class for table "vote".
 *
 * @property integer $user_id
 * @property integer $model_id
 * @property string $model
 * @property integer $state
 * @property string $ip
 * @property string $created_at
 * @property string $updated_at
 */
class Vote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vote}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'model_id', 'model', 'type'], 'required'],
            [['user_id', 'model_id', 'type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['model'], 'string', 'max' => 32],
            [['ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'model_id' => 'Model ID',
            'model' => 'Model',
            'type' => 'Type',
            'ip' => 'Ip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}