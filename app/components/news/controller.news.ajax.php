<?php

/**
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

class actionsAjaxNews extends joosController {

	public static function index() {

		joosLoader::lib( 'valumsfileuploader' , 'files' );
		joosLoader::lib( 'images' );

		$image_id = joosRequest::request( 'image_id' , 0 );
		$image_id = $image_id ? $image_id : false;

		//Загружаем оригинальное изображение (original.png)
		$file = ValumsfileUploader::upload( 'original' , 'news' , $image_id , false );

		//Путь к оригинальному изображению
		$img = dirname( $file['basename'] );

		//Большая картинка
		Thumbnail::output( $file['basename'] , $img . '/big.png' , array ( 'width'  => 555 ,
		                                                                   'height' => 555 ,
		                                                                   'method' => THUMBNAIL_METHOD_SCALE_MIN ) );

		//Средняя картинка
		Thumbnail::output( $file['basename'] , $img . '/medium.png' , array ( 'width'  => 300 ,
		                                                                      'height' => 300 ,
		                                                                      'method' => THUMBNAIL_METHOD_SCALE_MIN ) );

		//Превью
		//Сначала уменьшаем
		Thumbnail::output( $file['basename'] , $img . '/thumb.png' , array ( 'width'  => 158 ,
		                                                                     'height' => 108 ,
		                                                                     'method' => THUMBNAIL_METHOD_SCALE_MIN ) );
		//а потом обрезаем
		Thumbnail::output( $img . '/thumb.png' , $img . '/thumb.png' , array ( 'width'  => 158 ,
		                                                                       'height' => 108 ,
		                                                                       'method' => THUMBNAIL_METHOD_CROP ) );

		echo json_encode( array ( 'location' => $file['location'] ,
		                          'file_id'  => $file['file_id'] ,
		                          'livename' => $file['livename'] ,
		                          'success'  => true ) );
	}

}
