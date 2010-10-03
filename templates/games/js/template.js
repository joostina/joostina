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
/* это для аякс-сайтов
	$('a:internal').live("click",function(){
		$.ajax({
			dataType: 'json',
			url: $(this).attr("href"),
			beforeSend: function(){
			//$("#process").css("display","inline")
			//.text("Отправляю ajax-запрос");
			},
			success: function(answ){
				//$("#process").text("Ответ получен")
				//.fadeOut(3000);
				//$("#div1").append(answ);
				//console.log( answ );
				$.each(answ,  function(ttt,aaa){
					$(ttt).html(aaa);
				} );
			}
		});
		return false;
	});
*/
// http://jquerylist.ru/tutorials/extending-jquerys-selector-capabilities.html - селектор для выборки внешних ссылок, расширеннй для внутренних
$.extend($.expr[':'],{
   external: function(a,i,m) {
      if(!a.href) {return false;}
      return a.hostname && a.hostname !== window.location.hostname;
   },
   internal: function(a,i,m) {
      if(!a.href) {return false;}
      return a.hostname && a.hostname == window.location.hostname;
   }
});


});

/*

 			extractHash: function (state) {
				var Ajaxy = $.Ajaxy;

				// Strip urls
				var state = Ajaxy.extractState(state);

				// Extract the anchor
				var hash = state.match(/^([^#?]*)/)||'';
				if ( hash && hash.length||false === 2 ) {
					hash = hash[1]||'';
				}

				// Return hash
				return hash;
			},

			extractAnchor: function (state) {
				var Ajaxy = $.Ajaxy;

				// Strip urls
				var	state = Ajaxy.extractState(state),
					anchor_param_name = Ajaxy.options.anchor_param_name;

				// Extract the anchor
				var anchor = state.replace(/[^#]+#/g,'#').match(/#+([^#\?]*)/)||'';
				if ( anchor && anchor.length||false === 2 ) {
					anchor = anchor[1]||'';
				}

				// Check
				if ( anchor === state ) {
					anchor = '';
				}

				// Check
				if ( !anchor ) {
					// Extract anchor from QueryString
					var anchor = state.match(RegExp(anchor_param_name+'=([a-zA-Z0-9-_]+)')) || '';
					if ( anchor && anchor.length||false === 2 ) {
						anchor = anchor[1]||'';
					}
				}

				// Return anchor
				return anchor;
			},

setTimeout(History.hashchangeLoader, 200);

			setHash: function ( hash ) {
				var History = $.History;

				// Prepare hash
				hash = History.extractHash(hash);

				// Write hash
				if ( typeof window.location.hash !== 'undefined' ) {
					if ( window.location.hash !== hash ) {
						window.location.hash = hash;
					}
				} else if ( location.hash !== hash ) {
					location.hash = hash;
				}

				// Done
				return hash;
			},

			getHash: function ( ) {
				var History = $.History;

				// Get the hash
				var hash = History.extractHash(window.location.hash || location.hash);

				// Return the hash
				return hash;
			},

			extractHash: function ( url ) {
				// Extract the hash
				var hash = url
					.replace(/^[^#]*#/, '')
					.replace(/^#+|#+$/, '')
					;

				// Return hash
				return hash;
			},

 */