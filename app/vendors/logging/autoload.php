<?php

defined('_JOOS_CORE') or die;

class joosLoggingAutoloader {

	public static function register()
	{
		spl_autoload_register(array(__CLASS__, 'autoload'), true, true);
	}

	public static function autoload($class_name)
	{
		$monolog_path	= JPATH_BASE.DS.'app'.DS.'vendors'.DS.'logging';
		$path_parts		= explode("\\", $class_name);
		$class_path		= $monolog_path.DS.implode(DS, $path_parts).'.php';

		if ( ! is_file($class_path))
			return;

		require_once $class_path;
	}

}