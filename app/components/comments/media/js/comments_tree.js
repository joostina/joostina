$(document).ready(function(){
	
	$('.comment_reply').live('click', function(){
		$(this).after($('#comment_form'));
		$href = $(this).attr('comment');
		$('#parent_id').val($href.split('#')[1]);
		return false;
	});
	
	function clear_comment_form(){
		$('#comments_addform #comment_input').val('');
		$('#comments_addform #parent_id').val('0');
	}
	
	function comment_form_back(){
		//Возвращаем форму на место				
		$('#first_comment_wrap').append($('#comment_form'));
	}
	
	$('#comment_back').live('click', function(){
		clear_comment_form();
		comment_form_back();
		return false;
	});	
	
	//Перемещение к родительскому комментарию
	$.localScroll();
	
	//Выводим только что добавленный комментарий
	function print_comment(comment_data, parent_id){
		
		$.ajax({
			url: _live_site+"/ajax/",
			type: 'post',
			dataType:'html',
			data:{
				option: 'comments',
				task : 'print_comment',
				comment_data: comment_data
			},
			cache:false,
			success: function(data){				
				if(parent_id > 0){
					//Если уровень уже существует - добавляем к нему новый коммент
					if($('#comments-list-'+parent_id).length){
						$('#comments-list-'+ parent_id).append(data);
					}
					//Иначе - оборачиваем в дополнительный враппер
					else{
						$('#comment-item-'+ parent_id).after('<div class="comments-list" id="comments-list-'+ parent_id +'">' + data + '</div>');
					}
				}
				else{
					if($('#comments-list-0').length){
						$('#comments-list-0').append(data);
					}else{
						$('.comments').append('<div class="comments-list" id="comments-list-0">' + data + '</div>');
					}
						
				}
				clear_comment_form();
				comment_form_back();
			}
		});		

	}

	$('.comment_button').live('click', function(){
		var parent_id = $('#parent_id').val();
		$.ajax({
			url: _live_site + "/ajax/",
			type: 'post',
			data:{
				obj_option: _comments_objoption,
				obj_id: _comments_objid,
				task : 'add_comment',
				option: 'comments',
				comment_text: $('#comment_input').val(),
				parent_id: parent_id,
				current_href: location.href
			},
			dataType: 'json',
			success: function( data ){
				if(!data){
					$.notifyBar({
						cls: "error",
						html: "Что-то пошло не так( Попробуйте оставить комментарий чуть позже"
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
					print_comment(data, parent_id);
				}
			}
		});

		return false;
	})
});



