<?php defined('_JOOS_CORE') or exit();

/**
 * Работа с файлами
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Mail
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosMail {

	/**
	 * Очень упрощённая функция базовой отправки сообщения на email
	 *
	 * @tutorial joosMail::simply('admin@examle.com','Hello!','From Russia!');
	 *
	 * @param string $to      email получателя
	 * @param string $title   заголовк сообщения
	 * @param string $message текст сообщения
	 * @return bool
	 */
	public static function simply($to, $title, $message) {

		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=utf-8\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "X-MSMail-Priority: Normal\n";
		$headers .= "X-Mailer: JoostinaCoreMail\n";
		$headers .= sprintf("From: JoostinaCore <%s>\n", joosConfig::get2('mail', 'system_email'));

		return (bool)mail($to, $title, $message, $headers);
	}

}
