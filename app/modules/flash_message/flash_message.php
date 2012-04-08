<?php defined( '_JOOS_CORE' ) or die();

/**
 * Модуль информацинного сообщения
 *
 * @version    1.0
 * @package   Core\Modules
 * @author     JoostinaTeam
 * @copyright  (C) 2007-2012 Joostina Team
 *
 **/
if ( ( $message = joosFlashMessage::get() ) ) {
	echo '<div class="alert">' . $message . '</div>';
}