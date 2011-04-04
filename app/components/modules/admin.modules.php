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

joosLoader::admin_model('modules');
joosLoader::admin_view('modules');

/**
 * Содержимое
 */
class actionsModules {

	/**
	 * Название обрабатываемой модели
	 * @var joosDBModel модель
	 */
	public static $model = 'Modules';

	/**
	 * Список объектов
	 */
	public static function index($option) {
		$obj = new self::$model;
		
		$obj_count = $obj->count('WHERE client_id = 0');

		$pagenav = JoiAdmin::pagenav($obj_count, $option);

		$param = array(
			'where' => 'client_id = 0',
			'offset' => $pagenav->limitstart,
			'limit' => $pagenav->limit,
			'order' => 'position, ordering'
		);
		$obj_list = JoiAdmin::get_list($obj, $param);

		// передаём данные в представление
		thisHTML::index($obj, $obj_list, $pagenav);
	}

	/**
	 * Редактирование
	 */
	public static function create($option) {
		self::edit($option, 0);
	}

	/**
	 * Редактирование объекта
	 * @param integer $id - номер редактируемого объекта
	 */
	public static function edit($option, $id) {
		$obj_data = new self::$model;
		$id > 0 ? $obj_data->load($id) : null;
		
		//Прицепляем дополнительные параметры конкретного модуля
		$obj_data->params  = json_decode($obj_data->params, true);

		thisHTML::edit($obj_data, $obj_data);
	}

	/**
	 * Сохранение отредактированного или созданного объекта
	 */
	public static function save($option, $redirect = 0) {

		joosSpoof::check_code();

		$obj_data = new self::$model;
		
		$_POST['params'] = json_encode($_POST['params']);
		
		$result = $obj_data->save($_POST);
		
		
		//Настройка прав
		if(isset($_POST['access'])){
			
			joosLoader::admin_model('access');
			$access = new Access;	
			
			foreach($_POST['access'] as $action=>$new_access){
				$access->section = 'Module';
				$access->subsection = $obj_data->id;
				$access->action = $action;
				
				$access->find();
				
				$access->accsess = json_encode($new_access);	
				$access->store();
			}			
		}
		
		
		//Привязка модуля к страницам сайта	
		$pages = new ModulesPages;
		if($_POST['pages']){
			$_pages = $_POST['pages'];
			
			//Удалим старые записи
			$pages->delete_list(array('where'=>'moduleid = '.$obj_data->id));
			
			foreach($_pages as $page){				
				
				if($page['controller']){
					$pages->moduleid = $obj_data->id;
					$pages->controller = $page['controller'];
					$pages->method = $page['method'];
					$pages->rule = $page['rule'];
					
					//сохраняем
					$pages->store();	
					//сбрасываем
					$pages->reset();		
					
				}
			}//endforeach				
		}

		if ($result == false) {
			echo 'Ошибочка: ' . database::instance()->get_error_msg();
			return;
		}

		switch ($redirect) {
			default:
			case 0: // просто сохранение
				return joosRoute::redirect('index2.php?option=' . $option . '&model=' . self::$model, 'Всё ок!');
				break;

			case 1: // применить
				return joosRoute::redirect('index2.php?option=' . $option . '&model=' . self::$model . '&task=edit&id=' . $obj_data->id, 'Всё ок, редактируем дальше');
				break;

			case 2: // сохранить и добавить новое
				return joosRoute::redirect('index2.php?option=' . $option . '&model=' . self::$model . '&task=create', 'Всё ок, создаём новое');
				break;
		}
	}

	public static function apply($option) {
		return self::save($option, 1);
	}

	public static function save_and_new($option) {
		return self::save($option, 2);
	}

	/**
	 * Удаление одного или группы объектов
	 */
	public static function remove($option) {
		joosSpoof::check_code();

		// идентификаторы удаляемых объектов
		$cid = (array) joosRequest::array_param('cid');

		$obj_data = new self::$model;
		$obj_data->delete_array($cid, 'id') ? joosRoute::redirect('index2.php?option=' . $option, 'Удалено успешно!') : joosRoute::redirect('index2.php?option=' . $option, 'Ошибка удаления');
	}

}