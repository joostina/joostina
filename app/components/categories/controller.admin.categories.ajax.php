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

class actionsAjaxCategories {

	private static $implode_model = true;

	public static function index() {
		
	}

	public static function image_uploader() {

		joosLoader::lib('valumsfileuploader', 'files');
		joosLoader::lib('images');

		$image_id = joosRequest::request('image_id', 0);
		$image_id = $image_id ? $image_id : false;

		//Загружаем оригинальное изображение (original.png)
		$file = ValumsfileUploader::upload('original', 'categories', $image_id, false);

		//Путь к оригинальному изображению
		$img = dirname($file['basename']);

		Thumbnail::output($file['basename'], $img . '/thumb.jpg', array('resize' => 0, 'quality' => 80, 'type' => IMAGETYPE_JPEG));

		//Большая картинка
		//Thumbnail::output($file['basename'], $img . '/big.png', array('width' => 555, 'height' => 555, 'method' => THUMBNAIL_METHOD_SCALE_MIN));
		//Средняя картинка
		//Thumbnail::output($file['basename'], $img . '/medium.png', array('width' => 300, 'height' => 300, 'method' => THUMBNAIL_METHOD_SCALE_MIN));
		//Превью
		//Сначала уменьшаем
		//Thumbnail::output($file['basename'], $img . '/thumb.png', array('width' => 200, 'height' => 200, 'method' => THUMBNAIL_METHOD_SCALE_MIN));
		//а потом обрезаем
		//Thumbnail::output($img . '/thumb.png', $img . '/thumb.png', array('width' => 200, 'height' => 200, 'method' => THUMBNAIL_METHOD_CROP));

		echo json_encode(array('location' => $file['location'], 'file_id' => $file['file_id'], 'livename' => $file['livename'], 'success' => true));
	}

	public static function slug_generator() {

		$cats = new Categories();

		$cat_id = joosRequest::request('cat_id', 0);
		$cat_name = joosRequest::request('cat_name', '');
		$parent_id = joosRequest::request('parent_id', 0);

		if (!$cat_name) {
			echo json_encode(array('error' => 'Введите имя категории'));
			return;
		}

		//Если  в качестве родителя выбран корень
		if ($parent_id == 1) {
			//просто транслитерируем название категории
			$slug = joosText::str_to_url($cat_name);
		}

		//иначе - построим путь c учётом выбранного родителя
		else {

			$segments = array();

			$path = $cats->get_path_from_root($parent_id);
			unset($path[1]);

			$_repeat = '';
			foreach ($path as $id => $_cat) {
				$_slug = $_cat['slug'] ? $_cat['slug'] . '/' : joosText::str_to_url($_cat['name'] . '/');
				$_slug = str_replace($_repeat, '', $_slug);
				$segments[] = $_slug;
				$_repeat .= $_slug;
			}

			$segments[] = joosText::str_to_url($cat_name);
			$slug = implode('', $segments);
		}

		echo json_encode(array('slug' => $slug));
		return;
	}

	public static function add_pic() {

		$id = joosRequest::request('id', 0);
		$counter = joosRequest::request('counter', 0);

		$item = new CategoriesDetails();
		$item->cat_id = $id;
		$item->find();

		$content = CategoriesDetails::get_uploader($item, array(), $counter);
		$js = CategoriesDetails::get_js_code_for_uploader(0, $counter);

		echo json_encode(array('content' => $content, 'js' => $js, 'success' => true));
		return;
	}

}