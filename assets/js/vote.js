/**
 * Created by bigdrop on 19.10.15.
 */
function vote(options){
    var self = this;
    for(option in options){this[option] = options[option];}

    var init = function(){
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
    var setStatus = function(){
        if(self.action==='like'){
            self.liked = true;
            self.disliked = false;
        }else if(self.action==='dislike'){
            self.liked = false;
            self.disliked = true;
        }
    }

    this.action = 'like';
    this.action_path = (typeof this.action_path == 'undefined')?'':this.action_path.replace(/(\/*)?$/,'/');
    this.like_action = (typeof this.like_action == 'undefined')?'like':this.like_action;
    this.dislike_action = (typeof this.dislike_action == 'undefined')?'dislike':this.dislike_action;
    this.cancelable = (typeof this.cancelable == 'undefined')?false:this.cancelable;
    this.liked = (typeof this.liked == 'undefined')?false:this.liked;
    this.disliked = (typeof this.disliked == 'undefined')?false:this.disliked;
    this.ajax_options = (typeof this.ajax_options == 'undefined')?{}:this.ajax_options;

    this.like = function(model, id){
        self.action = 'like';
        if(!this.liked || this.cancelable) {
            self.send(self.like_action, model, id);
        }
        return false;
    };
    this.dislike = function(model,id){
        self.action = 'dislike';
        if(!this.disliked || this.cancelable) {
            self.send(self.dislike_action, model, id);
        }
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
            setStatus();
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