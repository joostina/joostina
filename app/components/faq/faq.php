<?php
/**

 *
 **/
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class actionsFaq extends joosController {

	/**
	 * Cтартовый метод, запускается до вызова основного метода контроллера
	 */
	public static function on_start($active_task) {
		
		//Модель компонента
		joosLoader::model('faq');
		
		//Хлебные крошки
		Jbreadcrumbs::instance()
				->add('Вопрос-ответ', $active_task == 'index' ? false : joosRoute::href('faq'));
				
		//Метаинформация страницы
		Metainfo::set_meta('faq', '', '', array('title'=>'Вопрос-ответ'));
	}

	/**
	 * Главная страница компонента
	 */
	public static function index() {

		$page = isset(self::$param['page']) ? self::$param['page'] : 0;

		// формируем объект
		$faq = new Faq();

		// число записей
		$count = $faq->count('WHERE state = 1');

		// подключаем библиотеку постраничной навигации
		joosLoader::lib('pager', 'utils');
		$pager = new Pager(joosRoute::href('faq'), $count, 2);
		$pager->paginate($page);

		// опубликованные записи
		$items = $faq->get_list(
			array(	'select' => '*',
					'where' => 'state = 1',
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

	public static function archive() {

		$page = isset(self::$param['page']) ? self::$param['page'] : 0;
		$year = isset(self::$param['year']) ? self::$param['year'] : date('Y');

		// формируем объект записей блога
		$faq = new Faq();

		$params = new Params;
		$params->group = 'faq';
		$params->subgroup = 'default';
		$params->find();
		$params = json_decode($params->data, true);
		$years = explode(',', $params['archive_eyars']);

		$where = 'YEAR(created_at)='.$year;

		// число записей
		$count = $faq->count('WHERE state = 1 AND '.$where);

		// подключаем библиотеку постраничной навигации
		joosLoader::lib('pager', 'utils');
		$pager = new Pager(joosRoute::href('faq_archive_year', array('year'=>$year)), $count, 2, 5);
		$pager->paginate($page);

		// опубликованные записи блога
		$items = $faq->get_list(
			array(
				'select' => '*',
				'where' => 'state = 1 AND '.$where,
				'offset' => $pager->offset,
				'limit' => $pager->limit,
				'order' => 'id DESC', // сначала последние
			)
		);

		//Хлебные крошки
		Jbreadcrumbs::instance()->add('Архив вопросов');
		JoosDocument::instance()->add_title('Архив вопросов');

		return array(
			'items' => $items,
			'years' => $years,
			'year' => $year,
			'pager' => $pager
		);
	}



	public static function send_question() {

		if(!$_POST){
			joosRoute::redirect(joosRoute::href('faq'));
		}

		$faq = new Faq();
		$faq->save($_POST);

		return  joosRoute::redirect(joosRoute::href('faq'), 'Сообщение отправлено');
	}

}