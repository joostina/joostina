<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

?>

<div style="display: none;"><div id="contact_form">

<script type="text/javascript">
		$(document).ready(function() {
			
			$("#contactform").validate({
				errorContainer: ".errors_ajax",
				errorLabelContainer: ".errors_ajax",
				wrapper: "span"
			});		
			
			$('#send_button').click(function(){                    
				if ($("#contactform").valid()){
					$.ajax({
						url: _live_site + "/ajax.index.php",
						type: 'post',
						data:{
							option: 'com_users',
							task : 'send_email',
							user_id: <?php echo $user->id?>,
							subject: $('#subject').val(),
							text: $('#text').val()
						},
						dataType: 'json',
						success: function( data ){
							if(!data){
								$('#resp').html('Что-то пошло не так');
							}else{
								$('#resp').html(data.message);
							}
						}
					});
				}
				return false;				
			});
		});
	</script> 
         
        <form id="contactform" name="contactform"  method="post" action="" class="ajaxForm">
        
    		<label>Заголовок сообщения</label><br/>
    		<input type="text" class="inputbox required" size="50" title="Пожалуйста, введите тему сообщения" id="subject" name="subject"/>
    		
    		<br/><br/>
    		
    		<label>Текст сообщения</label><br/>
    		<textarea rows="6" cols="50" id="text" name="text" class="inputbox required" title="Пожалуйста, введите текст сообщения"></textarea>
    		
    		<br/><br/>
    		
    		<div id="resp"></div>
    		<button type="submit" id="send_button">Отправить</button>

            <input type="hidden" value="id" name="<?php echo $user->id?>"/>
    
    </form>
    
    
    <div class="errors_ajax" style="display: none; "></div>
    
    
</div></div>
