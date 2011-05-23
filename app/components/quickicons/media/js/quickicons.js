$(document).ready(function() {

	// выбор значка для кнопки
	$('div#quickicons_icons img').live('click', function() {
		var $img = $(this);
		$('#new_quickicons_icon').attr('src', $img.attr('src'));
		$('#quickicons_icon_value').val($img.attr('alt'));
	});
	/* PRO
     $('div#quickicons_icons img').live('hover', function(){
     var $img = $(this);
     $('#new_quickicons_icon').attr('src',$img.attr('src'));
     $('#quickicons_icon_value').val( $img.attr('alt') );
     });
     */

	// помошник формирования ссылки для кнопки
	$('#quickicons_href_helper').change(function() {
		$('#href').val('index2.php?option=' + $(this).val());
	});

});