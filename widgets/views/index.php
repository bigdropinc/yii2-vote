<?php
/**
 * Created by PhpStorm.
 * User: bigdrop
 * Date: 16.10.15
 * Time: 16:15
 */
use yii\helpers\Url;
use Yii;
$script = <<<JS
function vote(options){
    var self = this;
    for(option in options){this[option] = options[option];}

    var init = function(){
        console.log(self);
        $(self.like_button).on('click',function(){
            return self.like(self.model,self.id);
        });
        $(self.dislike_button).on('click',function(){
            return self.dislike(self.model,self.id);
        });
        return self;
    }
    var call = function(name,params){
        if(typeof self[name] === 'function'){
            return self[name].apply(self,params);
        }
        return false;
    };
    var hasSuccess = function(){
        if(typeof self.ajax_options['success'] === 'function');
    };
    var hasError = function(){
        return (typeof self.ajax_options['error'] === 'function');
    };

    this.action = 'like';
    this.action_path = (typeof this.action_path == 'undefined')?'':this.action_path.replace(/(\/*)?$/,'/');
    this.like_action = (typeof this.like_action == 'undefined')?'like':this.like_action;
    this.dislike_action = (typeof this.dislike_action == 'undefined')?'dislike':this.dislike_action;
    this.cancelable = (typeof this.cancelable == 'undefined')?false:this.cancelable;
    this.ajax_options = (typeof this.ajax_options == 'undefined')?{}:this.ajax_options;

    this.like = function(model, id){
        self.action = 'like';
        self.send(self.like_action,model,id);
        return false;
    };
    this.dislike = function(model,id){
        self.action = 'dislike';
        self.send(self.dislike_action,model,id);
        return false;
    };
    this.checkAction = function(action){return ((action=='like')||(action=='dislike'));};

    this.send = function(action, _model, _id){
        var options = {
            url:this.action_path.concat(action),
            method:"POST",
            data:{model:_model,id:_id},
            dataType:"json",
        };
        for(var i in self.ajax_options){
            options[i] = self.ajax_options[i];
        }
        options['success'] = function(data,status,request){
            call(self.action.concat('Success'),[data,status,request]);
            return hasSuccess()?self.ajax_options['success'](data,status,request):false;
        };
        options['error'] = function(request,status,error){
            call(self.action.concat('Error'),[request,status,error]);
            return hasSuccess()?self.ajax_options['error'](request,status,error):false;
        };
        $.ajax(options);
    };

    return init();
}

    //jQuery(LikeWidget).trigger('boom');
JS;
$this->registerJs($script);
?>
<section id="<?=$widgetId?>">
    <div class="meta-holder">
        <span class="meta text">Was this helpful?</span>
        <a href="<?=$likeUrl?>" id="<?=$widgetId?>-like" class="up"><i class="font-icon icon-thumbs-up-alt">&#xe809;</i></a>
        <a href="<?=$dislikeUrl?>" id="<?=$widgetId?>-dislike" class="down"><i class="font-icon icon-thumbs-down-alt">&#xe80a;</i></a>
        <span class="meta counter">0 out of 0 found this helpful</span>
    </div>
</section>
