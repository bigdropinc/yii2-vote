<?php
/**
 * Created by PhpStorm.
 * User: bigdrop
 * Date: 16.10.15
 * Time: 16:15
 */
use yii\helpers\Url;
use Yii;

?>
<section id="<?=$widgetId?>">
    <div class="meta-holder">
        <span class="meta text">Was this helpful?</span>
        <a href="<?=$likeUrl?>" id="<?=$widgetId?>-like" class="up"><i class="font-icon icon-thumbs-up-alt">&#xe809;</i></a>
        <a href="<?=$dislikeUrl?>" id="<?=$widgetId?>-dislike" class="down"><i class="font-icon icon-thumbs-down-alt">&#xe80a;</i></a>
        <span class="meta counter">0 out of 0 found this helpful</span>
    </div>
</section>
