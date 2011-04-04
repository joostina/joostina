<?php
/**
 *
 **/
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class actionsSitemap {

	/**
	 * Название обрабатываемой модели
	 * @var joosDBModel модель
	 */
	public static $model = 'adminSitemap';
	
	/**
	 * Подменю
	 */	
	public static $submenu = array();
	
	/**
	 * Тулбары
	 */	
	public static  $toolbars = array();
	
	
	/**
	 * Выполняется сразу после запуска контроллера
	 */
	public static function on_start() {
		
		joosLoader::admin_model('sitemap');

	}	
			

	/**
	 * Список объектов
	 * 
	 * @param string $option
	 */
	public static function index($option) {

	}
	

	public static function generate_xml($option){
		
		require_once (JPATH_BASE . DS . 'includes' . DS . 'route.php');
		
		$map = Sitemap::get_map(true);
		$map->xml_output();
		
		joosRoute::redirect('index2.php', 'Карта сайта обновлена');
	
	}


}