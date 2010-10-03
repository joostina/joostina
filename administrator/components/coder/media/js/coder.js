$(document).ready(function() {

	// выбор значка для кнопки
	$('#coder_form input').live('click', function(){
		//alert( $(this).val() );

		$.ajax({
			url: "ajax.index.php?option=coder",
			type: "POST", 
			cache: false,
			data: $('#coder_form').serialize() ,
			success: function(html){
				$("#coder_results").html(html);
			}
		});

	});

	// выделение всего текста при клике на текст модели
	$('.coder_model_area').live('click', function(){
		$(this).select();
		});

});