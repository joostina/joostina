<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

// name - имя поля формы $_FILES['name']
joosUpload::upload( 'name' , array ( // новое имя для файла, если не указано - очищается и обезопасивается оригинальное
	'file_name'   => 'new_file_name' ,
	// новое расширение файла, не указано - используется оригинальное
	'file_ext'    => 'new_file_ext' ,
	// размещение, если не указано - то в общие аттачи, указано - по правилам
	'location'    => 'custom_location' ,
	// разешённые типы файлов
	'allow_types' => 'images|docs|arhive' ,
	// запещённые типы файлов
	'deny_types'  => 'executable|scripts' , ) );

class joosUpload {

	private static $types = array ( 'images'  => array ( 'jpg' , 'jpeg' , 'png' , 'gif' ) ,
	                                'docs'    => array ( 'doc' , 'docx' , 'odf' , 'ppt' ) ,
		/* ... */
	                                'scripts' => array ( 'php' , 'js' , 'bat' ) );

	public static function upload_form(){

	}

	public static function upload() {

	}

}
