<?php defined('_JOOS_CORE') or exit;


/**
 * Компонент генерации карты сайта
 * Контроллер панели управления
 *
 * @version    1.0
 * @package    Components\Sitemap
 * @subpackage Controllers\Admin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminSitemap  extends joosAdminController{

    /**
     * Список объектов
     * 
     * @static
     * @return array|void
     */
	public static function index( ) {
        return array();
	}

	public static function generate_xml( ) {
		
		// это что бы роутер не жаловался
		joosRoute::init();

		$map = modelSitemap::get_map( true );
		$map->xml_output();


		joosRoute::redirect( 'index2.php' , 'Карта сайта обновлена' );
	}

}