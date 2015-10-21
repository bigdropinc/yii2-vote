<?php
/**
 * Created by PhpStorm.
 * User: bigdrop
 * Date: 19.10.15
 * Time: 17:35
 */

namespace shirase\vote\models;

use yii\db\ActiveRecord;

class Vote extends ActiveRecord{
    public static function tableName(){
        return '{{%vote}}';
    }
}