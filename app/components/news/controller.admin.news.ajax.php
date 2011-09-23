<?php

/**
 * News - компонент новостей
 * Аякс-контроллер админ-панели
 *
 * @version 1.0
 * @package Components
 * @subpackage News
 * @author JoostinaTeam <info@joostina.ru>
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 *
 * */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class actionsAjaxAdminNews {

	public static function statuschanger() {
		joosAutoadmin::autoajax();
	}

	public static function image_uploader() {

		joosLoader::lib('valumsfileuploader', 'files');

		$image_id = joosRequest::request('image_id', 0);
		$image_id = $image_id ? $image_id : false;

		//Загружаем оригинальное изображение (original.png)
		$file = ValumsfileUploader::upload('original', 'news', $image_id, false);

		//Путь к оригинальному изображению
		$img = dirname($file['basename']);

		//Настройки ресайза изображений
		$params = new joosParams;
		$params->load_params('news', 'default');

		//массив с настройками размеров изображений
		//Размеры изображений задаются по следующим правилам:
		/*
		  ' '          - превью не создаётся
		  '500'        - ширина превью (учитывается только ширина, новая высота будет определена автоматически)
		  '500х200'    - ширина x высота (точные размеры изображения, лишнее отрезается)
		  '0x200'      - высота превью (превью будет создано точно по высоте, ширина определится автоматически)
		 */
		$thumbs_params = array();

		if ($params->get('item_image_size_big')) {
			$thumbs_params['big'] = explode('x', $params->get('item_image_size_big'));
		}

		if ($params->get('item_image_size_medium')) {
			$thumbs_params['medium'] = explode('x', $params->get('item_image_size_medium'));
		}

		if ($params->get('item_image_size_thumb')) {
			$thumbs_params['thumb'] = explode('x', $params->get('item_image_size_thumb'));
		}

		Thumbnail::create_thumbs($file['basename'], $img.'/thumb.jpg', $thumbs_params, $params->get('item_image_ext', 'jpg'), $params->get('item_image_quality', 70));

		echo json_encode(array('location' => $file['location'], 'file_id' => $file['file_id'], 'livename' => $file['livename'], 'success' => true));
	}

}