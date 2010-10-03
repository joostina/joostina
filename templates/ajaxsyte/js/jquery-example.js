$(document).ready(function(){
	jQuery.listen("click", "a", function(){
		$.ajax({
			url: $(this).attr("href"),
			beforeSend: function(){
				$("#process").css("display","inline")
				.text("Отправляю ajax-запрос");
			},
			success: function(answ){
				$("#process").text("Ответ получен")
				.fadeOut(3000);
				$("#div1").append(answ);
			}
		});
	});
});