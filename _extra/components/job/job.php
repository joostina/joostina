<?php
/**
 * Job - Компонент вакансий
 * Фронтенд-контроллер
 *
 * @version 1.0
 * @package Joostina.Components
 * @subpackage Job
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 **/
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class actionsJob extends joosController {

	/**
	 * Cтартовый метод, запускается до вызова основного метода контроллера
	 */
	public static function on_start($active_task) {
		
		//Модель компонента
		joosLoader::model('job');
		
		//Хлебные крошки
		Jbreadcrumbs::instance()
				->add('Вакансии', $active_task == 'index' ? false : joosRoute::href('job'));
				
		//Метаинформация страницы
		Metainfo::set_meta('job', '', '', array('title'=>'Вакансии'));
	}

	/**
	 * Главная страница компонента
	 */
	public static function index() {

		$page = isset(self::$param['page']) ? self::$param['page'] : 0;

		// формируем объект
		$job = new Job();

		// число записей
		$count = $job->count();

		// подключаем библиотеку постраничной навигации
		joosLoader::lib('pager', 'utils');
		$pager = new Pager(joosRoute::href('job'), $count, 5);
		$pager->paginate($page);

		// опубликованные записи
		$items = $job->get_list(
			array(	'select' => '*',
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
		$item = new Job;
		$item->load($id) ? null : self::error404();

		// одно из вышеобозначенных действий зафиксировало ошибку, прекращаем работу
		if (self::$error) {
			return;
		}

		//Метаинформация страницы
		Metainfo::set_meta('job', 'item', $item->id, array('title'=>$item->title));

		return array(
			'item' => $item
		);
	}
	
	public static function send_response() {

		if(!$_POST){
			mosRedirect(joosRoute::href('job'));
		}

		//Прикреплённый файл
		$file_path = '';
		if(joosRequest::file('qqfile')){
			joosLoader::lib('valumsfileuploader', 'files');
			$file = ValumsfileUploader::upload('resume', 'job', false, false);
			$file_path = JPATH_SITE . $file['livename'];

			$_POST['resume'] = $file_path;
		}
		else{
			return mosRedirect(joosRoute::href('job'), 'Необходимо прикрепить резюме');
		}

		$job_resp = new JobResponses();
		$job_resp->save($_POST);

		//для отправки сообщения на почту
		joosLoader::lib('mail', 'utils');
		$fields = array(
			'usermail' => 'Email',
			'username' => 'Имя',
			'message' => 'Сообщение'
		);
		$recipient = joosConfig::get2('mail', 'from');
		$subject = 'Ответ на вакансию с сайта ' . JPATH_SITE;
		$body = '';
		foreach($fields as $key => $label){
			$body .= $label . ': ' . joosRequest::post($key). "\n";
		}
		if($file_path){
			$body .= 'Прикреплённый файл: '.$file_path;
		}
		//оправляем письмо
		$r = mosMail(joosRequest::post('useremail'), joosRequest::post('username'), $recipient, $subject, $body);
		
		return $r ? mosRedirect(joosRoute::href('job'), 'Сообщение отправлено') : mosRedirect(joosRoute::href('job'), 'Ошибка при отправке');

	}
	 
	 
	/**
	 * Просмотр по категориям
	 */		 

}