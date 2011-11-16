<?php

/**
 * modelPages - компонент независимых страниц
 * Аякс-контроллер
 *
 * @version    1.0
 * @package    Components
 * @subpackage modelPages
 * @author     JoostinaTeam
 * @copyright  (C) 2008-2010 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 *
 */
// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

class actionsAjaxAdminPages {

	public static function status_change() {
		return joosAutoadmin::autoajax();
	}

	/**
	 * Генерация ссылки на страницу
	 *
	 * @return json string
	 */
	public static function slug_generator() {

		$title = joosRequest::post( 'title' , '' );

		// формируем из введённого заголовка страницы валидный UTL-адрес
		$slug = joosText::str_to_url( $title );

		echo json_encode( array ( 'slug' => $slug ) );
		return;
	}

}