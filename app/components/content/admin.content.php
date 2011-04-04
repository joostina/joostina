<?php
/**
 * Content - компонент управления контентом
 * Контроллер админ-панели
 *
 * @version 1.0
 * @package Components
 * @subpackage Content
 * @author JoostinaTeam <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 *
 **/
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class actionsContent{
	
	/**
	 * Название обрабатываемой модели
	 * @var joosDBModel модель
	 */
	public static $model = 'adminContent';	
		
	/**
	 * Подменю
	 */	
	public static $submenu = array(
		'content_all' => array(
			'name' => 'Все статьи',
			'href' => 'index2.php?option=content',
			'active' => false
		),
		'categories' => array(
			'name' => 'Категории',
			'href' => 'index2.php?option=categories&group=content',
			'active' => false
		),
		'params' => array(
			'name' => 'Настройки',
			'href' => 'index2.php?option=params&group=content',
			'active' => false
		),
		'extrafields' => array(
			'name' => 'Дополнительные поля',
			'href' => 'index2.php?option=extrafields&group=content',
			'active' => false
		),
	);
		
			
	/**
	 * Выполняется сразу после запуска контроллера
	 */
	public static function on_start() {
		joosLoader::admin_model('content');
		joosDocument::instance()->add_js_file(JPATH_SITE . '/app/components/content/media/js/content.admin.js');
		joosDocument::instance()->add_css(JPATH_SITE . '/app/components/content/media/css/content.admin.css');
	}


	/**
	 * Список объектов
	 * 
	 * @param string $option
	 */	
	public static function index($option) {	
		ob_start();	mosMenuBar::copy();	$add = ob_get_contents(); ob_end_clean();	
		JoiAdminToolbar::add_button($add);	
				
		self::$submenu['content_all']['active'] = true;
		
		$obj = new self::$model;
		
		$where = '';
		
		//Если сработал фильтр по категориям
		$catid = joosRequest::request('category_id', 0);
		if($catid  > 0){
			$category = new Categories('content');
			$category->load($catid);
			$ids = implode(', ', array_keys($category->get_branch($category->lft, $category->rgt, $catid, true)));
			
			$where = $ids ? 'i.category_id IN('.$ids.')' : 'i.category_id = '. $catid;
		}		
		
		if($where){
			$where_for_count = str_replace('i.', '', $where);
			$obj_count =  $obj->count('WHERE ' . $where_for_count);	
		}
		else{
			$obj_count =  $obj->count();		
		}
		
		
		$pagenav = JoiAdmin::pagenav($obj_count, $option);

		$param = array(
			'select' => 'i.*, c.name AS catname',
			'offset' => $pagenav->limitstart,
			'limit' => $pagenav->limit,
			'order' => 'i.category_id,  i.ordering ',
			'join' => 'LEFT JOIN #__categories AS c ON (c.id = i.category_id)',
			'pseudonim' => 'i'
		);
		
		if($where){
			$param['where'] = $where;
		}
		
		
		$obj_list = $obj->get_list($param);
		
		
        // массив названий элементов для отображения в таблице списка
        $fields_list = array( 'id', 'title', 'slug', 'category_id', 'ordering', 'state');
        // передаём информацию о объекте и настройки полей в формирование представления
        JoiAdmin::listing( $obj, $obj_list, $pagenav, $fields_list, 'category_id' );		

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
		$obj_data->params = Params::get_params('content', 'item', $obj_data->id);

		//Мета-информация
		$obj_data->metainfo = Metainfo::get_meta('content', 'item', $obj_data->id);

		JoiAdmin::edit($obj_data, $obj_data);
	}

	
	/**
	 * Сохранение информации
	 * 
	 * @param string $option
	 * @param integer $redirect
	 */
	public static function save_this($option, $redirect = 0) {

		joosSpoof::check_code();

		$obj = new self::$model;
		
		$result = $obj->save($_POST);
		
		if(isset($_POST['params'])){
			$params = new Params;		
			$params->save_params($_POST['params'], 'content', 'item', $obj->id);			
		}

		//Сохранение мета-информации
		Metainfo::add_meta($_POST['metainfo'], 'content', 'item', $obj->id);

		//Сохранение данных дополнительных полей
		if(isset($_POST['extra_fields'])){
			$ef = new ExtrafieldsData();
			$ef->save_data($_POST['extra_fields'], $obj->id);
		}
		

		if ($result == false) {
			echo 'Ошибочка: ' . database::instance()->get_error_msg();
			return;
		}

		switch ($redirect) {
			default:
			case 0: // просто сохранение
				return joosRoute::redirect('index2.php?option=content&model=' . self::$model, 'Всё ок!');
				break;

			case 1: // применить
				return joosRoute::redirect('index2.php?option=' . $option . '&model=' . self::$model . '&task=edit&id=' . $obj->id, 'Всё ок, редактируем дальше');
				break;

			case 2: // сохранить и добавить новое
				return joosRoute::redirect('index2.php?option=' . $option . '&model=' . self::$model . '&task=create', 'Всё ок, создаём новое');
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

		//Удаление данных дополнительных полей
		$ef_data = new ExtrafieldsData();
        
        if($obj_data->delete_array($cid, 'id')){
	        $ef_data->delete_array($cid, 'obj_id');
            joosRoute::redirect('index2.php?option=' . $option, 'Удалено успешно!');
        } 
        else{
            joosRoute::redirect('index2.php?option=' . $option, 'Ошибка удаления');
        }  
	}
	
	/**
	 * Копирование объекта или группы объектов, возврат на главную
	 * 
	 * @param string $option
	 * @return
	 */
	public static function copy($option) {
		joosSpoof::check_code();

		//идентификаторы удаляемых объектов
		$cid = (array) joosRequest::array_param('cid');

		$obj_data = new self::$model;
        
        if($obj_data->copy_array($cid, 'id')){
            joosRoute::redirect('index2.php?option=' . $option, 'Скопировано успешно!');
        } 
        else{
            joosRoute::redirect('index2.php?option=' . $option, 'Ошибка копирования');
        }  
	}				

}