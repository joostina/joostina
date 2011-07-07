$(function() {
	//Генератор ссылки на страницу
	$('#pages_slug_generator').live('click', function() {

		// объект по которому производится клик
		var _obj = $(this);

		$.ajax({
			url: 'ajax.index.php?option=pages&task=slug_generator',
			type: 'post',
			data:{
				title:    $('#title').val()
			},
			dataType: 'json',
			success: function(data) {
				if (data.error) {
					alert(data.error);
					return;
				}
				$('#slug').val(data.slug);
			}
		});
	});
});