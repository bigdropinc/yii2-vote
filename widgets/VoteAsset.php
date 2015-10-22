<?php
/**
 * Created by PhpStorm.
 * User: bigdrop
 * Date: 19.10.15
 * Time: 14:22
 */
namespace shirase\vote\widgets;

use yii\web\AssetBundle;

class VoteAsset extends AssetBundle{
    public $sourcePath = '@vendor/shirase/yii2-vote/assets';
    public $basePath = '@vendor/shirase/yii2-vote/assets';

    public $css = [
        'css/vote.css',
        '//fonts.googleapis.com/css?family=Open+Sans:300',
        '//fonts.googleapis.com/css?family=Montserrat',
    ];
    public $js = [
        'js/vote.js',
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}