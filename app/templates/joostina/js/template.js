$(function(){

	
	$('a[rel="external"], a[rel="newtab"]').click(function(){
		this.target = "_blank";
	});

	
	$('.switcher').click(function(){
		var _el_switcher = $(this);		
		var rel = _el_switcher.attr('rel');
		var txt = _el_switcher.text();
		var _block_switcher = $('.switcher-block');
		_el_switcher.attr('rel', txt);
		_el_switcher.text(rel);
		_el_switcher.toggleClass('g-active');
		_block_switcher.slideToggle('fast');		
		return false;	
	});
	
	// -------------------------------------------------------------------------Показываем/скрываем форму логина-регистрации
	$('.m-auto_enter span').live('click', function(){
		var _rel = $(this).attr('rel');
		$('.m-auto_formwrap').removeClass('g-active');
		$('#'+_rel).toggleClass('g-active');
	})
	$('.m-auto_formwrap .close').live('click', function(){
		$('.m-auto_formwrap').removeClass('g-active');	
	})
});