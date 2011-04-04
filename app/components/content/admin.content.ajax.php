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

joosLoader::lib('joiadmin', 'system');
JoiAdmin::dispatch_ajax();

// передаём управление полётом в автоматический Ajax - обработчик
JoiAdmin::autoajax();

class actionsAjaxContent {

	private static $implode_model = true;

	public static function on_start() {
		joosLoader::lib('forms');
		require joosCore::path('content', 'admin_class');
	}

	public static function index() {

	}

	public static function image_uploader() {
	
		joosLoader::lib('valumsfileuploader', 'files');
		joosLoader::lib('images');
		
		$image_id = joosRequest::request('image_id', 0);
		$image_id = $image_id ? $image_id : false;

		$counter = joosRequest::request('counter', 1);

		//Загружаем оригинальное изображение (original.png)
		$file = ValumsfileUploader::upload('original', 'content', $image_id, false);

		//Путь к оригинальному изображению
		$img = dirname($file['basename']);
		
		//Настройки ресайза изображений
		$params_from_cat = json_decode(Categories::get_params_by_category(joosRequest::request('category_id', 0), 'content'), true);

		$thumbs_params = array();

		if($params_from_cat['item_image_size_big']){
			$thumbs_params['big'] =  explode('x', $params_from_cat['item_image_size_big']);
		}

		if($params_from_cat['item_image_size_medium']){
			$thumbs_params['medium'] =  explode('x', $params_from_cat['item_image_size_medium']);
		}

		if($params_from_cat['item_image_size_thumb']){
			$thumbs_params['thumb'] = explode('x', $params_from_cat['item_image_size_thumb']);
		}

		Thumbnail::create_thumbs($file['basename'], $img, $thumbs_params, 'jpg', 70);

		echo json_encode(array('location' => $file['location'], 'file_id' => $file['file_id'], 'livename' => $file['livename'],'success'=>true));
		return;
	}

	public static function add_pic(){

		$id = joosRequest::request('id', 0);
		$cat_id = joosRequest::request('category_id', 0);
		$counter = joosRequest::request('counter', 0);

		$item = new Content();
		$item->load($id);

		$content = adminContent::get_uploader($item, array(), $counter);
		$js = adminContent::get_js_code_for_uploader(0, $counter);

		echo json_encode(array('content' => $content, 'js' => $js, 'success'=>true));
		return;
	}
	
	public static function slug_generator(){
		
		joosLoader::admin_model('categories');
		
		$cats = new Categories();

		$id = joosRequest::request('id', 0);
		$title = joosRequest::request('title', '');
		$cat_name = joosRequest::request('cat_name', '');
		$cat_id = joosRequest::request('cat_id', 0);
		
		if(!$title){
			echo json_encode(array('error'=>'Введите заголовок статьи'));	
			return;
		}	
			
		$cat = clone $cats;
		$cat->load($cat_id);
		
		$segments = array();
					
		//Если это корневая категория
		if($cat->parent_id == 1){
			//просто подставляем существующую ссылку или формируем ссылку из названия
			$segments[] = $cat->slug ? $cat->slug.'/' : Text::str_to_url($cat->name).'/';	
		}
		//иначе - построим путь c учётом выбранного родителя
		else{
			$path = $cats->getPathFromRoot($cat_id);	
			unset($path[1]);			
			
			$_repeat = '';
			//добавляем все ссылки категорий в массив
			foreach($path as $id=>$_cat){
				$_slug = $_cat['slug'] ? $_cat['slug'].'/'  : Text::str_to_url($_cat['name']).'/';
				$_slug = str_replace($_repeat, '', $_slug);	
				$segments[] = $_slug;	
				$_repeat .= $_slug;
			}
		}
			
		//последним сегментом будет название самой статьи
		$segments[] = Text::str_to_url($title);	
		
		//объединяем в строку
		$slug = implode('', $segments);	
		
		//возвращаем
		echo json_encode(array('slug'=>$slug));
		
		return;
	}	

}