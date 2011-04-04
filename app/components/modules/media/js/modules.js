$(document).ready(function(){
	
	
		//Копирование позиции
		var for_copy = $('.fields:first');		
		$('.module_page_copy').live('click', function(){
			
			//Сколько наборов полей сейчас присутствует в форме
			var _count = $('#fields_count').val();
			
			//Номер набора полей, выступающего доннором
			var _curr_number = for_copy.attr('title');
			//Порядковый номер, который нужно будет присвоить клону
			var _new_number = Number(_count);
			
			//создали клона
			var new_fieldset = for_copy.clone();
			
			//корректируем
			new_fieldset.attr("title", _new_number);
			new_fieldset.find('input[name="pages['+_curr_number+'][controller]"]').attr('name', 'pages['+_new_number+'][controller]').attr('value', ''); 
			new_fieldset.find('input[name="pages['+_curr_number+'][method]"]').attr('name', 'pages['+_new_number+'][method]').attr('value', ''); 
			new_fieldset.find('input[name="pages['+_curr_number+'][rule]"]').attr('name', 'pages['+_new_number+'][rule]').attr('value', ''); 
			
			//Выводим
			$('#modules_pages').append(new_fieldset);  
			
			//наращиваем счетчик полей
			$('#fields_count').val(Number(_count) + 1);	
		})
		
		//Удаление позиции
		$('.module_page_del').live('click', function(){			
			var _count =  $('#modules_pages').children('div.fields').length;
			console.log(_count);
			if(_count>1){
				$(this).parent().parent().remove();	
				//уменьшаем значение  счетчика полей
				$('#fields_count').val(Number(_count) - 1);		
			}	
		});	
	

});	