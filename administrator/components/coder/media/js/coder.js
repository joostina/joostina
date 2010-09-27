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

});