$(document).ready(function(){

	$(".search_input").autocomplete({
		source: _live_site + "/ajax.index.php?option=com_search&task=autocomplete",
		minLength: 2,
		select: function(event, ui) {
		// автоматический редирект на результаты поиска
		//document.location = '/search/'+this.value;
		}

	});

	// ----------------------------------------------------------------голосование за комментарии
	$('.comment_rater').click( function(){
		_el = $(this);
		var obj_id = $(this)[0].rel.split('#')[1];
		$.ajax({
			url: _live_site + "/ajax.index.php",
			type: 'post',
			data:{
				obj_option: 'comment',
				obj_id: obj_id,
				task : 'comment',
				option: 'com_vote',
				ball: _el.hasClass('vote_minus') ? -1 : 1
			},
			dataType: 'json',
			success: function( data ){
				if(!data){
					$.notifyBar({
						cls: "error",
						html: "Что-то пошло не так, совсем не так"
					});
					return false;
				}else if(data.error){
					$.notifyBar({
						cls: "error",
						html: data.error
					});
					return false;
				}
				else{
					if(data.state == 'error' ){
						$.notifyBar({
							cls: 'error',
							html: data.message
						});
					}else{
						$('li#mark_'+obj_id+' span').html( data.counter );
						_el.addClass('active');
						_el.parent('li.buttons').addClass('unactive');
					}
				}
			}
		});

		return false;
	});
    
	function in_array(needle, haystack, strict) {
		var found = false, key, strict = !!strict;
		for (key in haystack) {
			if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
				found = true;
				break;
			}
		}
		return found;
	}
                            
	//Магия валидации
	if(typeof _validation_form != 'undefined'){
		var validator = $(_validation_form).validate({
			rules: _validation_rules,
			messages: _validation_messages,
			errorContainer: ".errors_ajax",
			errorLabelContainer: ".errors_ajax",
			wrapper: "li",
			focusInvalid: false
		});
	}

});