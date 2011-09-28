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

class actionsAjaxBlog extends joosController {

	public static function index() {

		joosLoader::lib( 'valumsfileuploader' , 'files' );
		joosLoader::lib( 'images' );

		//Загружаем оригинальное изображение (original.png)
		$file = ValumsfileUploader::upload( 'original' , 'blogs' , false , false );

		//Путь к оригинальному изображению
		$img      = dirname( $file['basename'] );

		$img_size = getimagesize( $file['basename'] );
		if ( $img_size[0] < 200 && $img_size[1] < 200 ) {
			echo json_encode( array ( 'error' => 'Слишком маленькое изображение' ) );
			return;
		}

		//картинка, коорую выводим в теле статьи
		Thumbnail::output( $file['basename'] , $img . '/image.png' , array ( 'width'      => 555 ,
		                                                                     'height'     => 555 ,
		                                                                     'method'     => THUMBNAIL_METHOD_SCALE_MAX ,
		                                                                     'check_size' => 1 ) );

		//Сначала уменьшаем
		Thumbnail::output( $file['basename'] , $img . '/image_200x200.png' , array ( 'width'  => 200 ,
		                                                                             'height' => 200 ) );
		//а потом обрезаем
		Thumbnail::output( $img . '/image_200x200.png' , $img . '/image_100x100.png' , array ( 'width'  => 100 ,
		                                                                                       'height' => 100 ,
		                                                                                       'method' => THUMBNAIL_METHOD_CROP ) );

		echo json_encode( array ( 'location' => $file['location'] ,
		                          'file_id'  => $file['file_id'] ,
		                          'livename' => $file['livename'] ,
		                          'success'  => true ) );
	}

}