<?php

/**
 * Extrafields - Компонент для управления дополнительными полями
 * Фронтенд-контроллер
 *
 * @version 1.0
 * @package Joostina.Components
 * @subpackage Extrafields
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class actionsNews extends joosController {

	/**
	 * Cтартовый метод, запускается до вызова основного метода контроллера
	 */
	public static function on_start($active_task) {

		//Хлебные крошки
		joosBreadcrumbs::instance()
				->add('Дополнительные поля', $active_task == 'index' ? false : joosRoute::href('extrafields'));

		//Метаинформация страницы
		joosMetainfo::set_meta('extrafields', '', '', array('title' => 'Дополнительные поля'));
	}

	/**
	 * Главная страница компонента
	 */
	public static function index() {

		$page = isset(self::$param['page']) ? self::$param['page'] : 0;

		// формируем объект
		$extrafields = new Extrafields();

		// число записей
		$count = $extrafields->count();

		// подключаем библиотеку постраничной навигации
		joosLoader::lib('pager', 'utils');
		$pager = new Pager(joosRoute::href('extrafields'), $count, 5);
		$pager->paginate($page);

		// опубликованные записи
		$items = $extrafields->get_list(
						array('select' => '*',
							'offset' => $pager->offset,
							'limit' => $pager->limit,
							'order' => 'id DESC', // сначала последние
						)
		);

		return array(
			'items' => $items,
			'pager' => $pager
		);
	}

	/**
	 * Просмотр записи
	 */
	public static function view() {

		// номер просматриваемой записи
		$id = self::$param['id'];

		// формируем и загружаем просматриваемую запись
		$item = new Extrafields;
		$item->load($id) ? null : self::error404();

		// одно из вышеобозначенных действий зафиксировало ошибку, прекращаем работу
		if (self::$error) {
			return;
		}

		//Метаинформация страницы
		joosMetainfo::set_meta('extrafields', 'item', $item->id, array('title' => $item->title));

		return array(
			'item' => $item
		);
	}

	/**
	 * Создание/редактирование записи
	 */
	/**
	 * Просмотр по категориям
	 */
}