<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class actionsExts extends joosController {

	public static function on_start($active_task) {
		joosLoader::model('exts');
		Jbreadcrumbs::instance()
				->add('Расширения', $active_task == 'index' ? false : joosRoute::href('extensions'));
	}

	public static function index() {

		$page = isset(self::$param['page']) ? self::$param['page'] : 0;

		$exts_obj = new Exts;
		$count = $exts_obj->count();

		// подключаем библиотеку постраничной навигации
		joosLoader::lib('pager', 'utils');
		$pager = new Pager(joosRoute::href('extensions'), $count, 5);
		$pager->paginate($page);

		$exts_items = $exts_obj->get_list(array(
					'select' => '*',
					'offset' => $pager->offset,
					'limit' => $pager->limit,
					'order' => 'id DESC', // сначала последние
						), array('extensions')
		);

		return array(
			'exts_items' => $exts_items,
			'pager' => $pager,
		);
	}

	public static function by_type() {
		// текущая страница
		$page = isset(self::$param['page']) ? self::$param['page'] : 0;
		// slug-название категории которой принадлежит текущая запись
		$type_name = self::$param['type'];

		$ext_types = Exts::get_types_slug_array();
		isset($ext_types[$type_name]) ? null : self::error404();

		$type_id = $ext_types[$type_name];
		
		$exts_obj = new Exts;
		$count = $exts_obj->count('WHERE state=1 AND type_id=' . $type_id);

		// подключаем библиотеку постраничной навигации
		joosLoader::lib('pager', 'utils');
		$pager = new Pager(joosRoute::href('extensions_type', array('type' => $type_name)), $count, 5, 6);
		$pager->paginate($page);

		// опубликованные записи блога
		$exts_items = $exts_obj->get_list(
						array(
					'select' => "ext.*, u.username, u.id as user_id, comm.counter AS comments_count",
					'join' => "AS ext"
					. "\nINNER JOIN #__users AS u ON u.id=ext.user_id"
					. "\nLEFT JOIN #__comments_counter AS comm ON (comm.obj_option = 'Exts' AND comm.obj_id = ext.id)"
					,'where' => 'ext.state=1 AND ext.type_id=' . $type_id,
					'offset' => $pager->offset,
					'limit' => $pager->limit,
					'order' => 'id DESC', // сначала последние
						)
		);
		
		$type_name = Exts::get_types_title();
		
		Jbreadcrumbs::instance()
				->add($type_name[$type_id]);

		return array(
			'views'=>'index',
			'exts_items' => $exts_items,
			'pager' => $pager,
		);
	}

	public static function view() {
		// номер просматриваемой записи
		$exts_slug = self::$param['name'];

		// формируем и загружаем просматриваемую запись
		$exts_obj = new Exts;
		$exts_obj->slug = $exts_slug;
		($exts_obj->find() && $exts_obj->state==1 )  ? null : self::error404();

		// устанавливаем заголовок страницы
		joosDocument::instance()
				->set_page_title($exts_obj->title);

		$ext_types = Exts::get_types();
		
		Jbreadcrumbs::instance()
				->add($ext_types[$exts_obj->type_id]['title'], joosRoute::href('extensions_type',array('type'=>$ext_types[$exts_obj->type_id]['slug'])))
				->add($exts_obj->title);

		// передаём параметры записи и категории в которой находится запись для оформления
		return array(
			'exts_obj' => $exts_obj,
		);
	}

}