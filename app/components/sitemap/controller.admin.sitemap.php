<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Компонент генерации карты сайта
 * Контроллер панели управления
 *
 * @version    1.0
 * @package    Controllers
 * @subpackage Sitemap
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminSitemap  extends joosAdminController{

	/**
	 * Название обрабатываемой модели
	 *
	 * @var joosModel модель
	 */
	public static $model = 'modelAdminSitemap';
	/**
	 * Подменю
	 */
	public static $submenu = array ();
	/**
	 * Тулбары
	 */
	public static $toolbars = array ();

	/**
	 * Список объектов
	 *
	 * @param string $option
	 */
	public static function index( $option ) {

	}

	public static function generate_xml( $option ) {

		require_once ( JPATH_BASE . DS . 'includes' . DS . 'route.php' );

		$map = midelSitemap::get_map( true );
		$map->xml_output();

		joosRoute::redirect( 'index2.php' , 'Карта сайта обновлена' );
	}

}