<?php defined('_JOOS_CORE') or die();

/**
 * Компонент управления независимыми страницами
 * Контроллер сайта
 *
 * @version    1.0
 * @package    Components\Pages
 * @subpackage Controllers\Site
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsPages extends joosController {

	public static function index() {

		$page = new modelPages();
		$pages = $page->get_list(array('where' => 'state = 1'));

		joosDocument::instance()
				->set_page_title('Тынц');

		return array('task' => 'view', 'pages' => $pages);
	}

	public static function view() {

		$slug = self::$param['page_name'];

		$page = new modelPages;
		$page->slug = $slug;
		$page->find() ? null : joosPages::page404();

		// одно из вышеобозначенных действий зафиксировало ошибку, прекращаем работу
		if (self::$error) {
			return false;
		}

        joosDocument::instance()
      				->set_page_title($page->title)
      				->add_meta_tag('description', $page->meta_description)
      				->add_meta_tag('keywords', $page->meta_keywords)
      				->seo_tag('yandex-vf1', md5(time())) // формируем тэг для поисковой машины Yandex.ru ( пример )
      				->seo_tag('rating', false); // тэг rating - скрываем

		joosBreadcrumbs::instance()
				->add($page->title);

		// если для текущего действия аквирован счетчик хитов - то обновим его
		joosHit::add('pages', $page->id, 'view');

		// передаём параметры записи и категории в которой находится запись для оформления
		return array('page' => $page);
	}

}