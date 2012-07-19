<?php defined('_JOOS_CORE') or exit();

/**
 * Компонент генерации карты сайта
 * Контроллер сайта
 *
 * @version    1.0
 * @package    Components\Sitemap
 * @subpackage Controllers\Site
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsSitemap extends joosController {

	public static function action_before($active_task) {
		//Хлебные крошки
		joosBreadcrumbs::instance()->add('Карта сайта');

	}

	public static function index() {

		$map = modelSitemap::get_map();

		return array('map' => $map);
	}

}