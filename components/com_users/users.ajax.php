<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

class actionsUsers {

    public static function index() {
        
    }

    public static function uploadavatar() {
        mosMainFrame::addLib('plupload');
        $file = Plupload::upload('original_avatar', 'avatars', User::current()->id, false);

        mosMainFrame::addLib('images');
        $avatar = dirname($file['basename']);

        // создаём превьюшки для всех нужных мест
        Thumbnail::output($file['basename'], $avatar . '/avatar.png', array('width' => 100, 'height' => 100));
        Thumbnail::output($file['basename'], $avatar . '/avatar_25x25.png', array('width' => 25, 'height' => 25));
        Thumbnail::output($file['basename'], $avatar . '/avatar_30x30.png', array('width' => 30, 'height' => 30));
        Thumbnail::output($file['basename'], $avatar . '/avatar_45x45.png', array('width' => 45, 'height' => 45));
        Thumbnail::output($file['basename'], $avatar . '/avatar_100x100.png', array('width' => 100, 'height' => 100));
        Thumbnail::output($file['basename'], $avatar . '/avatar_200x200.png', array('width' => 200, 'height' => 200));

        echo json_encode(array('avatar' => $file['location']));
    }

    public static function uploadfiles() {
        mosMainFrame::addLib('plupload');
        Plupload::upload();
    }
    
    public static function send_email() {
    	
    	$user_id = mosGetParam($_POST, 'user_id', 0);
    	$subject = mosGetParam($_POST, 'subject', '');
    	$text = strip_tags(trim(mosGetParam($_POST, 'text', '')));
		
		if(!User::current()->id){
			return json_encode(array('message' => 'Сначала авторизуйтесь'));	
		}
		
		if(!$user_id){
			return json_encode(array('message' => 'Не понятно, кому отправлять письмо'));		
		}		

		$message = '<strong>Пользователь сайта Megaplay.ru отправил Вам сообщение:</strong><br/>';
		if( $subject == '' || $text  == '' ){
			return json_encode(array('message' => 'Не хватает данных для отправки'));	
		}
		$message .= $text;
		
		$message .= '<br/><br/><strong>Для просмотра информации об отправителе, перейдите в его профиль:</strong><br/>';		
		$recipient = new User;
		$recipient->load($user_id); $recipient->user_id = $recipient->id;
		$message .= '<a href="'.User::profile_link($recipient).'">'.User::profile_link($recipient).'</a>';
	
	
		if(mosMail(
				JConfig::getInstance()->config_mailfrom, //от кого - email
				'ПользовательMegaplay.ru', //от кого  - имя
				$recipient->email, //кому - email
				$subject, //тема
				$message, //сообщение
				1
			)
		){
			return json_encode(array('message' => 'Сообщение успешно отправлено'));
		}else {
			return json_encode(array('message' => 'Не удалось отправить сообщение'));
		}  	
	    	
	        
    }

}
