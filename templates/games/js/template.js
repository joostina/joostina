
	
$(document).ready(function(){

	
	$('a.checklevel').click(function(){
		$.notifyBar({
			cls: 'error',
			html: 'Доступно на уровне: '+ this.type
		});	
		return false;		
	})

	
	
    
	$('.menu_inside li').click(function(){
        
		if($(this).hasClass('menu_inside_active')){
			return false;
		}
		var $_id = this.id;
            
		$('.menu_inside li').removeClass('menu_inside_active');
		$('li#'+$_id).addClass('menu_inside_active');
             
		$('.menu_inside_submenu .active_ul').fadeOut(400, function () {
            
			$(this).removeClass('active_ul');
                                  
			$('.menu_inside_submenu ul.'+$_id).fadeIn(400, function () {
				$('.menu_inside_submenu ul.'+$_id).addClass('active_ul');
			});
			$('div.menu_inside_submenu').scrollLeft(0);
		});
            
		return false;
	})
        
	$("a.thumb").easyTooltip();
	$("#sites_scroller a").easyTooltip();
	$(".mainmenu a").easyTooltip();
	$("a.s_title").easyTooltip();
	$(".button_soc").easyTooltip({
		tooltipId: "gamepage_tooltips"
	});
	$("#cur_rating").easyTooltip({
		tooltipId: "gamerating_tooltips"
	});
        
	$( ".image_slider" ).accessNews({
		headline : "Всего",
		speed : "normal",
		slideBy : 2
	});
	
	$( ".image_slider_wallpapers" ).accessNews({
		headline : "Всего",
		speed : "normal",
		slideBy : 2
	});
	
	$("a.about_manager").colorbox({width:"500px", inline:true, href:"#gamemanager_about", opacity: 0.3});
	$("a.button_send_email").colorbox({width:"550px", inline:true, href:"#contact_form", opacity: 0.3});
	$("a#button_award").colorbox({width:"550px", inline:true, href:"#awards_form", opacity: 0.3});
	$("a.view_award").colorbox({width:"550px", inline:true, href: $(this).attr('href'), opacity: 0.3});
	/*
	$( ".accordeon" ).accordion({
		autoHeight: false
	});
*/

	if( typeof(_p3000_show)!='undefined' ){
		$('#paginator').paginator({
			pagesTotal: _p3000_tp,
			pagesSpan:10,
			pageCurrent: p3000_cp,
			baseUrl: _p3000_bu
		});
	}

	$('a.get_user').cluetip({
		width:            172,      // The width of the clueTip
		cluetipClass: 'jtip',
		arrows: true,
		dropShadow: false,
		hoverIntent: false,
		sticky: true,
		mouseOutClose: true,
		closePosition: 'title',
		activation:  'click',
		closeText: '<img src="/template/images/elements/cross_small.png" />'
	});

	$(".search_input").autocomplete({
		source: _live_site + "/ajax.index.php?option=com_search&task=autocomplete",
		minLength: 2,
		select: function(event, ui) {
		// автоматический редирект на результаты поиска
		//document.location = '/search/'+this.value;
		}

	});

	 $.tablesorter.defaults.widgets = ['zebra']; 
	$('.tablesorter').length ? $('.tablesorter').tablesorter( {
		cssHeader:'sortheader',
	    textExtraction: {
	        1: function(o) {
	            return $('h2 a',o).text();
	        }
	    }

	} ) : null;

	$('.user_action').each(function(i,e){
		var obj = $(this);
		if(obj.attr('user_id') == _current_uid){
			obj.after( $('<a>')
				.attr({
					href: _live_site + '/add/topics/'+obj.attr('topic_id'),
					title: "Редактирование"
				})
				.html('<img src="'+_live_site + '/templates/games/images/buttons/pen.png">')
				.addClass('userEdit')
				);
		};
	});
    
    
	/*------------------------------------------------------------------------Активные фильтры: BEGIN*/

	$("ul.with_filters li").click(function () {
		if($(this).hasClass('menu_inside_submenu_active')){
			remove_from_filtr($(this));
		}
		else{
			add_in_filtr($(this));
		}
		return false;
	});

    
	function remove_from_filtr(el){
        
		if($(el).hasClass('first')){
			return false;
		}
        
		//Снимаем признак активности
		$(el).removeClass('menu_inside_submenu_active');
         
		//Удаляем фильтр из поля
		var field = $(el).parent('ul').attr('name');
		$("#f_"+field).val('');
         
		//Удаляем фильтр из видимых
		var filtr_id = 'in_filtr_'+field;
		$('#'+filtr_id).remove();
         
		//Делаем активным первый пункт "Все"
		$(el).parent('ul').find('li.first').addClass('menu_inside_submenu_active');
        
		if ($('ul.current_filter_ul li').length <1){
			$('ul.current_filter_ul').remove();
			$('.filtr_actions').html('Фильтры не заданы. <a href="'+_live_site+'/games/">Сбросить все параметры</a>');
		}
	}
    
    
	//Удаление фильтра из списка фильтров
	$("a.del_filter").live('click', function () {
        
		//Очищаем поле
		var field = $(this).attr('name');
		$("#f_"+field).val('');
        
		//Сбрасываем признак активности пункта
		$('ul.'+field+' li').removeClass('menu_inside_submenu_active');
		$('ul.'+field+' li.first').addClass('menu_inside_submenu_active');
        
		//Удаляем фильтр из видимых
		var filtr_id = 'in_filtr_'+field;
		$('#'+filtr_id).remove();
         
		//Если нет выбранных фильтров - убираем ul и выводим надпись
		if ($('ul.current_filter_ul li').length <1){
			$('ul.current_filter_ul').remove();
			$('.filtr_actions').html('Фильтры не заданы. <a href="'+_live_site+'/games/">Сбросить все параметры</a>');
		}

		// сразу выведем количество подходящих игрdel_filter
		on_filter_submit();
		return false;
	});
 
 
	//Прокрутка в области алфавитной навигации
	$(function(){
		var div2 = $('div.with_scroll'),
		ul2 = $('ul.with_scroll'),
		ulPadding = 20;
		var divWidth = div2.width();
		div2.css({
			overflow: 'hidden'
		});
		var lastLi = ul2.find('li:last-child');
		div2.mousemove(function(e){
			var ulWidth = lastLi[0].offsetLeft + lastLi.outerWidth() + ulPadding;
			var left = (e.pageX - div2.offset().left) * (ulWidth-divWidth) / divWidth;
			div2.scrollLeft(left);
		});
		div2.scrollLeft(0);
	});
    
	$(function(){
		var div = $('div.with_scroll2'),
		ul = $('ul.with_scroll2'),
		ulPadding = 20;
		var divWidth = div.width();
		div.css({
			overflow: 'hidden'
		});
		var lastLi = ul.find('li:last-child');
		div.mousemove(function(e){
			var ulWidth = lastLi[0].offsetLeft + lastLi.outerWidth() + ulPadding;
			var left = (e.pageX - div.offset().left) * (ulWidth-divWidth) / divWidth;
			div.scrollLeft(left);
		});
		div.scrollLeft(0);
	});
    
	//ссылка, перенаправляюющая на сформированную сылку фильтра
	$('#filter_submit').live('click', function(){
		document.location = $('#result_href').val();
		return false;
	})
	/*------------------------------------------------------------------------Активные фильтры: END*/

	$("a.game_versions_a").toggle(
		function () {
			$(this).addClass('active');
			$(this).text('Все версии материала (скрыть)');
			$('.game_versions_list').slideDown();

		},
		function () {
			$(this).removeClass('active');
			$(this).text('Все версии материала (показать)');
			$('.game_versions_list').slideUp();
		}
		);


	/*------------------------------------------------------------------------Юзерпанель: BEGIN*/
	if($.cookie("mp_userpanel")== 1){
		$("a.user_panel_up_down").removeClass('inactive');
		$("a.user_panel_up_down").addClass('active');
		$(".user_panel_wrap").removeClass('inactive');

		$('.user_panel_wrap').css('width','100%' );
		$("a.user_panel_up_down.active").attr('title', 'Свернуть панель');
	}
	else{
		$("a.user_panel_up_down").addClass('active');
	}
    
	$("a.user_panel_up_down.active").toggle(
		function () {
			$(this).removeClass('active');
			$(this).addClass('inactive');
			$('.user_panel').fadeOut("fast");
			$('.user_panel_wrap').animate( {
				width:"18px"
			});
			$(this).attr('title', 'Развернуть панель');
			$.cookie("mp_userpanel", -1, {
				expires: 7,
				path: '/'
			});

		},
		function () {
			$(this).removeClass('inactive');
			$(this).addClass('active');
			$('.user_panel').fadeIn("fast");
			$('.user_panel_wrap').animate( {
				width:"100%"
			});
			$(this).attr('title', 'Свернуть панель');
			$.cookie("mp_userpanel", 1, {
				expires: 7,
				path: '/'
			});
		}
		);
      
	$("a.user_panel_up_down.inactive").toggle(
		function () {
			$(this).removeClass('inactive');
			$(this).addClass('active');
			$('.user_panel').fadeIn("fast");
			$('.user_panel_wrap').animate( {
				width:"100%"
			});
			$(this).attr('title', 'Свернуть панель');
			$.cookie("mp_userpanel", 1, {
				expires: 7,
				path: '/'
			});

		},
		function () {
			$(this).removeClass('active');
			$(this).addClass('inactive');
			$('.user_panel').fadeOut("fast");
			$('.user_panel_wrap').animate( {
				width:"18px"
			});
			$(this).attr('title', 'Развернуть панель');
			$.cookie("mp_userpanel", -1, {
				expires: 7,
				path: '/'
			});
		}
		);
	/*------------------------------------------------------------------------Юзерпанель: END*/

	// голосование за топики
	$('a.rater_topic').click( function(){
		_el = $(this);
		var obj_id = $(this).attr('obj_id');
		$.ajax({
			url: _live_site + "/ajax.index.php",
			type: 'post',
			data:{
				obj_option: 'topic',
				obj_id: obj_id,
				task : 'topic',
				option: 'com_vote',
				ball: _el.hasClass('rater_minus_1') ? -1 : 1
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
						$('span.obj_'+obj_id).html( data.counter );
						_el.addClass('active');
					}
				}
			}
		});

		return false;
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


	// голосование за пользователя - плюс
	$('a.karma_plus_1').click( function(){
		_el = $(this);
		var obj_id = $(this).attr('obj_id');
		// за себя голосовать нельзя
		if( obj_id==_current_uid ){
			$.notifyBar({
				cls: "error",
				html: "За себя голосовать нельзя нельзя"
			});
			return false;
		};

		$.ajax({
			url: _live_site + "/ajax.index.php",
			type: 'post',
			data:{
				obj_option: 'user',
				obj_id: obj_id,
				task : 'user',
				option: 'com_vote',
				ball: 1
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
						$('span.obj_'+obj_id + ' big').html( data.counter );
						$('span.obj_'+obj_id + ' small').html( data.voters_count );
						_el.addClass('active');
						$('big#user_cur_rating').html('Рейтинг: ' + data.full_rate);
					}
				}
			}
		});

		return false;
	});

	// голосование за пользователя - минус
	$('a.karma_minus_1').click( function(){
		_el = $(this);
		var obj_id = $(this).attr('obj_id');
		// за себя голосовать нельзя
		if( obj_id==_current_uid ){
			$.notifyBar({
				cls: "error",
				html: "За себя голосовать нельзя нельзя"
			});
			return false;
		};

		$.ajax({
			url: _live_site + "/ajax.index.php",
			type: 'post',
			data:{
				obj_option: 'user',
				obj_id: obj_id,
				task : 'user',
				option: 'com_vote',
				ball: -1
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
						$('span.obj_'+obj_id + ' big').html( data.counter );
						$('span.obj_'+obj_id + ' small').html( data.voters_count );
						_el.addClass('active');
						$('big#user_cur_rating').html('Рейтинг: ' + data.full_rate);
					}
				}
			}
		});

		return false;
	});

	// кнопка сравнения выбранных версий
	$('#compare_button').click( function(){
		document.location='/games/diff/'+$('#current_game_id').val()+':'+$('#game_old').val()+':'+$('#game_new').val() ;
		return false;
	} ) ;
    
    
	// ----------------затемнение списка выбора игр при выборе категории, не подразумевающей привязку игры
	/*  $('#game_id').fadeTo(700, 0.6, function(){
        $('#game_id').attr('disabled', 'disabled');  
        $('label[for="'+$(this).attr('id')+'"]').fadeTo(700, 0.5);  
    })    
    $('#type_id').change( function(){
        if( !in_array($("#type_id option:selected").val(), stop_game)  ){
            $('#game_id').fadeTo(700, 1, function(){
                $('#game_id').removeAttr('disabled');  
                $('label[for="game_id"]').fadeTo(700, 1);  
            }) 
        }
        else{
            $('#game_id').fadeTo(700, 0.6, function(){
                $('#game_id').attr('disabled', 'disabled');
                $('label[for="game_id"]').fadeTo(700, 0.5);  
            })      
        }
    } ) ;  */
    
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
    
	$(".tabs").tabs();
     
	$("#SliderSingle").slider(
	{
		from: 0,
		to: 100,
		step: 1,
		round: 0,

		dimension: '%',
		skin: "round_plastic",
		onstatechange: function(){
			$('#game_rate_user_action span').text($('#SliderSingle').val());
		},
		callback : function(){
            
		}
	}
	);
    
	$('#game_rate_graph i.v').animate({
		width : $('#game_rate_graph').attr('title')+'%'
	},
	1500
	)
                        
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
    
	//Они загружаются САМИ! Миракл!
	//Загрузка аватара
	if(typeof _upload_avatar != 'undefined'){
		var uploader = new plupload.Uploader({
			runtimes : 'flash,html5,html4,gears',
			browse_button : 'pickfiles',
			max_file_size : '3mb',
			url : _live_site + '/ajax.index.php?option=com_users&task=uploadavatar',
			flash_swf_url : '/media/swf/plupload.flash.swf',
			multi_selection: true,
			filters : [
			{
				title : "Изображения",
				extensions : "jpg,gif,png,jpeg"
			}
			]
		});
        
		uploader.bind('FilesAdded', function(up, files) {
			setTimeout( function(){
				up.start();
				//console.log('FilesAdded');
				$('#process').addClass('in_progress');
			},200);
		});
        
        
        
		uploader.bind('Error', function(up, err) {
			$('#filelist').append("<span class='error'>Ошибка: " + err.code +
				", " + err.message +
				(err.file ? ", Файл: " + err.file.name : "") +
				"</span>"
				);
		});

		uploader.bind('FileUploaded', function(up, files, res) {
			var dateob = new Date();
			var avatar = $.parseJSON(res.response).avatar;
			$('#useravatar').attr('src', _live_site + avatar + 'avatar.png?'+dateob.getTime() );
			$('#user_avatar_from_mod').attr('src', _live_site + avatar + 'avatar.png?'+dateob.getTime() );
			$('#process').removeClass('in_progress');
			$.notifyBar({
				html: "Аватар успешно заменён"
			});
		});
        
		uploader.init();
       
	}
    
	//Загрузка изображений для анонсов
	if(typeof _upload_image != 'undefined'){
		var uploader2 = new plupload.Uploader({
			runtimes : 'flash,html5,html4',
			browse_button : 'pickimage',
			max_file_size : '3mb',
			url : _live_site + '/ajax.index.php?option=com_add&task=upload_for_anons',
			flash_swf_url : '/media/swf/plupload.flash.swf',
			multi_selection: false,
			filters : [
			{
				title : "Изображения",
				extensions : "jpg,gif,png,jpeg"
			}
			]
		});
        
		uploader2.bind('FilesAdded', function(up, files) {
			setTimeout( function(){
				up.start();
				$('#process_image').addClass('in_progress');
				$.each(files, function(i, file) {
					$('#filelist2').html(
						'<div id="' + file.id + '">' + 'Картинка: ' + file.name  + '</div>'
						);
				});
			},200);

		});

		uploader2.bind('UploadProgress', function(up, file) {
			$('#' + file.id + " b").html(file.percent + "%");
		});

		uploader2.bind('FileUploaded', function(up, files, res) {
			var ret_data = $.parseJSON(res.response);
			$('#image').attr('src', _live_site + ret_data.image + '/image.png' );
			$('#anons_image_id').val( ret_data.file_id );
			$('#process_image').removeClass('in_progress');
			$.notifyBar({
				html: "Изображение успешно загружено"
			});
		});

		uploader2.init();
	}
    
	//Загрузка файлов к топикам
	if(typeof _upload_file != 'undefined'){
		var uploader = new plupload.Uploader({
			runtimes : 'flash,html5,gears, html4',
			browse_button : 'pickfiles',
			max_file_size : '250mb',
			url : _live_site + '/ajax.index.php?option=com_add&task=uploadfile',
			flash_swf_url : '/media/swf/plupload.flash.swf',
			multi_selection: false
		});

		uploader.bind('FilesAdded', function(up, files) {
			setTimeout( function(){
				up.start();
				$('#process_file').addClass('in_progress');
			},200);

		});

		uploader.bind('UploadProgress', function(up, file) {
			$('#' + file.id + " b").html(file.percent + "%");
		});

		uploader.bind('FileUploaded', function(up, files, res) {
			var ret_data = $.parseJSON(res.response);
			$('#filelist').html( 'Загружен файл: ' + ret_data.file_name);
			$('#file_id').val( ret_data.file_id );
			$('#process_file').removeClass('in_progress');
			$.notifyBar({
				html: "Файл успешно загружен"
			});
		});

		uploader.init();
	}
    
	//Загрузка изображения в галерею
	if(typeof _upload_gallery_img != 'undefined'){
        
		var uploader = new plupload.Uploader({
			runtimes : 'flash,html5,gears, html4',
			browse_button : 'pickfiles',
			max_file_size : '10mb',
			url : _live_site + '/ajax.index.php?option=com_add&task=uploadimage',
			flash_swf_url : '/media/swf/plupload.flash.swf',
			multi_selection: false,
			filters : [
			{
				title : "Изображения",
				extensions : "jpg,jpeg,gif,png"
			}
			]
		});

		uploader.bind('FilesAdded', function(up, files) {
			setTimeout( function(){
				up.start();
				$('#process_image').addClass('in_progress');
			},200);

		});

		uploader.bind('UploadProgress', function(up, file) {
			$('#' + file.id + " b").html(file.percent + "%");
		});

		uploader.bind('FileUploaded', function(up, files, res) {
			var ret_data = $.parseJSON(res.response);
			$('#image').attr('src', _live_site + ret_data.image + '/image.png' );
			$('#file_id').val( ret_data.file_id );
			$('#process_image').removeClass('in_progress');
			$.notifyBar({
				html: "Изображение успешно загружено"
			});
			if($('#this_is_wall').val() == 1){
				$('#process_image').addClass('in_progress');
				$('#addimages_allsize').show(1500, function(){ $(this).html('Создаём копии в разных разрешениях...'); });
				
				$.ajax({
					url: _live_site + "/ajax.index.php",
					type: 'post',
					data:{
						option: 'com_add',
						task : 'createwalls',
						file_id: ret_data.file_id
					},
					dataType: 'json',
					success: function( data ){
						if(!data){
							$.notifyBar({
								cls: "error",
								html: "Ошибка при создании копий изображения"
							});
						}else{
							$('#addimages_allsize').html(data.message);
							$('#process_image').removeClass('in_progress');
						}
					}
				});			
				
			}
		});

		uploader.init();
	}
    
	//Загрузка изображения к игре
	if(typeof _upload_image_for_game != 'undefined'){
		var uploader = new plupload.Uploader({
			runtimes : 'flash,html5,gears, html4',
			browse_button : 'pickfiles',
			max_file_size : '5mb',
			url : _live_site + '/ajax.index.php?option=com_games&task=uploadimage',
			flash_swf_url : '/media/swf/plupload.flash.swf',
			multi_selection: false,
			filters : [
			{
				title : "Изображения",
				extensions : "jpg,gif,png,jpeg"
			}
			]
		});

		uploader.bind('FilesAdded', function(up, files) {
			setTimeout( function(){
				up.start();
				$('#process_image').addClass('in_progress');
			},200);
		});

		uploader.bind('UploadProgress', function(up, file) {
			$('#' + file.id + " b").html(file.percent + "%");
		});

		uploader.bind('FileUploaded', function(up, files, res) {
			var ret_data = $.parseJSON(res.response);
			$('#gameimage').attr('src', _live_site + ret_data.image + '/game.png' );
			$('#image_id').val( ret_data.file_id );
			$('#process_image').removeClass('in_progress');
			$.notifyBar({
				html: "Изображение игры успешно загружено"
			});
		});

		uploader.init();
	}
     
	//-----------------------------------------------------------Выставление оценки игрушке
	$('#rate_this_game').click( function(){
		var obj_id = $(this).attr('obj_id');
		$.ajax({
			url: _live_site + "/ajax.index.php",
			type: 'post',
			data:{
				obj_option: 'game',
				obj_id: obj_id,
				task : 'game',
				option: 'com_vote',
				ball: $('#SliderSingle').val()
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
						$('#cur_rating strong').html( data.average+'%' );
						//Скрываем область голосования
						$('.my_vote_area_before_vote').hide("slow");
                        
						//Выводим поставленную оценку
						$('#game_rate_user span').html( $('#SliderSingle').val() );
                        
						//Заполняем прогрессбар текущим значением рейтинга игрушки
						$('#game_rate_graph').attr('title', data.average);
						$('#game_rate_graph i.v').animate({
							width : $('#game_rate_graph').attr('title')+'%'
						},
						1500
						);
                        
						//Показываем область с результатом
						$('.my_vote_area_after_vote').slideUp(1000, function(){
							$(this).removeClass('hidden');
						});
					}
				}
			}
		});

		return false;
	});

	// назначение сомтрителя
	$('#change_game_manager').click(function(){
		$.ajax({
			url: _live_site + "/ajax.index.php",
			type: 'post',
			data:{
				option: 'com_games',
				task : 'change_manager',
				user_id: $('#manager_id').val(),
				game_id: $('#current_game_id').val()
			},
			dataType: 'json',
			success: function( data ){
				if(!data){
					$.notifyBar({
						cls: "error",
						html: "Что-то пошло не так, совсем не так"
					});
				}else{
					$.notifyBar({
						html: data.message
					});
				}
			}
		});

		return false;
	});

});

// эти функции вызываются из вне, поэтому должны быть дотсупны в общем пространстве имён
function add_in_filtr(el){

	$(el).parent('ul').find('li').removeClass('menu_inside_submenu_active');
	$(el).addClass('menu_inside_submenu_active');

	//определяем поле, в которое нужно поместить значение
	var field = $(el).parent('ul').attr('name');
	//определяем значение фильтра
	var value = $(el).attr('id');

	//Задаём ID элемента списка фильтров
	var filtr_id = 'in_filtr_'+field;

	//Помещаем значение фильтра в поле формы
	$("#f_"+field).val(value);

	if($(el).hasClass('first')){
		$("#f_"+field).val('');
	}

	//Формируем HTML для видимого фильтра
	var filtr_el =  '<strong>' + $(el).parent('ul').attr('title')+':</strong> ' + $(el).text() +
	'<a name="'+field+'" class="del_circle del_filter" href="#" title="Удалить из фильтра">&nbsp;</a>';

	//Если нет враппера <ul> для фильтров - создаём его
	if($('.current_filter_ul').length){

		//Если в списке фильтров уже присутствует фильтр текущей группы - удалим его
		if($('#'+filtr_id).length){
			$('#'+filtr_id).remove();
			if($(el).hasClass('first')){
				$("#f_"+field).val('');
				if ($('ul.current_filter_ul li').length <1){
					$('ul.current_filter_ul').remove();
					$('.filtr_actions').html('Фильтры не заданы. <a href="'+_live_site+'/games/">Сбросить все параметры</a>');
				}
				return false;
			}
		};

		//Добавляем новый фильтр
		$('.current_filter_ul').append(
			'<li id="'+filtr_id+'">' + filtr_el + '</li>'
			);
	}
	else{
		//Создаём
		$('.filtr_actions').html(
			'<a class="filter_submit" id="filter_submit" href="javascript:void(0)"><span id="filter_results"></span>Применить</a><ul class="current_filter_ul">'+
			'<li id="'+filtr_id+'">' + filtr_el + '</li>'+
			'</ul>');
	}

	// сразу выведем количество подходящих игрdel_filter
	on_filter_submit();
	return false;
}


function on_filter_submit(){
	$.ajax({
		url: _live_site + "/ajax.index.php?option=com_games&task=activ_filter",
		type: 'post',
		data:$('form#games_filter').serialize(),
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
					$('#filter_results').html( ' ('+ data.message + ') ' );
					$('#result_href').val( data.href );
				}
			}
		}
	});
}
