<?php
/**
 * Pages - компонент независимых страниц
 * Контроллер
 *
 * @version 1.0
 * @package Components
 * @subpackage Pages
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 *
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Содержимое
 */
class actionsPages {

	/**
	 * Название обрабатываемой модели
	 * @var joosDBModel модель
	 */
	public static $model = 'adminPages';
    
	/**
	 * Подменю
	 */	
	public static $submenu = array(
		'pages' => array(
			'name' => 'Все страницы',
			'href' => 'index2.php?option=pages',
			'active' => false
		),
		/*'params' => array(
			'name' => 'Настройки',
			'href' => 'index2.php?option=params&group=pages',
			'active' => false
		),*/
		'metainfo' => array(
			'name' => 'Метаданные по-умолчанию',
			'href' => 'index2.php?option=metainfo&group=pages',
			'active' => false
		),
	);
	
	/**
	 * Тулбары
	 */	
	public static  $toolbars = array();
    
    
	/**
	 * Выполняется сразу после запуска контроллера
	 */
	public static function on_start() {
        joosLoader::admin_model('pages');
	}

	/**
	 * Список объектов
	 * 
	 * @param string $option
	 */
	public static function index($option) {
		
		//установка подменю
		self::$submenu['pages']['active'] = true;
		
		$obj = new self::$model;
		
		//количество записей
		$obj_count = JoiAdmin::get_count($obj);

		//инициализируем постраничную навигацию
		$pagenav = JoiAdmin::pagenav($obj_count, $option);

		//параметры запроса на получение списка записей
		$param = array(
			'offset' => $pagenav->limitstart,
			'limit' => $pagenav->limit,
			'order' => 'id DESC'
		);
        
		//получаем массив объектов
		$obj_list = JoiAdmin::get_list($obj, $param);

        // массив названий элементов для отображения в таблице списка
        $fields_list = array( 'id', 'title','slug','state');
        // передаём информацию о объекте и настройки полей в формирование представления
        JoiAdmin::listing( $obj, $obj_list, $pagenav, $fields_list );
	}
	

	/**
	 * Создание объекта
	 * 
	 * @param string $option
	 */
	public static function create($option) {
		self::edit($option, 0);
	}

	/**
	 * Редактирование объекта
	 * 
	 * @param string $option
	 * @param integer $id - номер редактируемого объекта
	 */
	public static function edit($option, $id) {
		
		$obj_data = new self::$model;
		
		$id > 0 ? $obj_data->load($id) : null;
			
		//Параметры
		$obj_data->params = Params::get_params('pages', 'item', $obj_data->id);
		
		//Мета-информация
		$obj_data->metainfo = Metainfo::get_meta('pages', 'item', $obj_data->id);

        // передаём данные в формирование представления
        JoiAdmin::edit($obj_data, $obj_data);
	}

	/**
	 * Сохранение информации
	 * 
	 * @param string $option
	 * @param integer $redirect
	 */
	private static function save_this($option, $redirect = 0){
		
		joosSpoof::check_code();

		$obj_data = new self::$model;
		
		//сохраняем основные данные
		$result = $obj_data->save($_POST);
		
		//Сохранение параметров
		if(isset($_POST['params'])){
			$params = new Params;
			$params->save_params($_POST['params'], 'pages', 'item', $obj_data->id);
		}

		
		//Сохранение мета-информации
		Metainfo::add_meta($_POST['metainfo'], 'pages', 'item', $obj_data->id);			

		if ($result == false) {
			echo 'Ошибочка: ' . database::instance()->get_error_msg();
			return;
		}
		
		switch ($redirect) {
			default:
			case 0: // просто сохранение
				return mosRedirect('index2.php?option=' . $option . '&model=' . self::$model, 'Всё ок!');
				break;

			case 1: // применить
				return mosRedirect('index2.php?option=' . $option . '&model=' . self::$model . '&task=edit&id=' . $obj_data->id, 'Всё ок, редактируем дальше');
				break;

			case 2: // сохранить и добавить новое
				return mosRedirect('index2.php?option=' . $option . '&model=' . self::$model . '&task=create', 'Всё ок, создаём новое');
				break;
		}		
				
	}

	/**
	 * Сохранение отредактированного или созданного объекта 
	 * и перенаправление на главную страницу компонента
	 * 
	 * @param string $option
	 */
	public static function save($option) {		
		self::save_this($option);
	}
	

	/**
	 * Сохраняем и возвращаем на форму редактирования
	 * 
	 * @param string $option
	 */
	public static function apply($option) {
		return self::save_this($option, 1);
	}

	/**
	 * Сохраняем и направляем на форму создания нового объекта
	 * 
	 * @param mixed $option
	 */
	public static function save_and_new($option) {
		return self::save_this($option, 2);
	}


	/**
	 * Удаление объекта или группы объектов, возврат на главную
	 * 
	 * @param string $option
	 * @return
	 */
	public static function remove($option) {
		joosSpoof::check_code();

		//идентификаторы удаляемых объектов
		$cid = (array) joosRequest::array_param('cid');

		$obj_data = new self::$model;
        
        if($obj_data->delete_array($cid, 'id')){
            mosRedirect('index2.php?option=' . $option, 'Удалено успешно!');
        } 
        else{
            mosRedirect('index2.php?option=' . $option, 'Ошибка удаления');  
        }  
	}

}