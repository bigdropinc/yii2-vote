/**
 * Created by bigdrop on 19.10.15.
 */
function vote(options){
    var self = this;

    this.action = 'like';
	this.action_path = null;
    this.like_action = 'like';
    this.like_button = '#vote_like_button';
    this.like_view = '#vote_like_view';
    this.dislike_action = 'dislike';
    this.dislike_button = '#vote_dislike_button';
    this.dislike_view = '#vote_dislike_view';
    this.total_view = '#vote_total_view';
    this.cancelable = false;
    this.liked = false;
    this.disliked = false;
    this.ajax_options = {};
    this.concatUrl = function(path, action){
		if(typeof path == 'string'){
			return path.replace(/(\/*)?$/,'/').concat(action);
		}
		return action;
	}
    this.beforeLike = function(){
    	return true;
    }
    this.beforeDislike = function(){
    	return true;
    }
    this.setViews = function(count){
        $(self.total_view).text(count.total);
        $(self.like_view).text(count.plus);
        $(self.dislike_view).text(count.minus);
    }
    this.likeSuccess = function(data){
        $(self.like_button).addClass('active');
        $(self.dislike_button).removeClass('active');
        self.setViews(data.votes);
    }
    this.dislikeSuccess = function dislikeSuccess(data){
    	$(self.dislike_button).addClass('active');
    	$(self.like_button).removeClass('active');
        self.setViews(data.votes);
    }
    var init = function(){
    	
    	for(option in options){self[option] = options[option];}
        
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
        return (typeof self.ajax_options['success'] === 'function');
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

    this.like = function(model, id){
    	if(self.beforeLike(id)){
	        self.action = 'like';
	        if(!this.liked || this.cancelable) {
	            self.send(self.like_action, model, id);
	        }
    	}
        return false;
    };
    this.dislike = function(model,id){
    	if(self.beforeDislike(id)){
	        self.action = 'dislike';
	        if(!this.disliked || this.cancelable) {
	            self.send(self.dislike_action, model, id);
	        }
    	}
        return false;
    };
    this.checkAction = function(action){return ((action=='like')||(action=='dislike'));};

    this.send = function(action, _model, _id){
        var options = {
            url:self.concatUrl(this.action_path,action),
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