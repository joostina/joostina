<?php defined( '_JOOS_CORE' ) or exit();

/**
 * Custom - модуль для вывода пользовательского содержимого (задается в админке)
 * Основной исполняемый файл
 *
 * Доступны следующие переменные:
 *         $module
 *         $params
 *         $object_data
 *
 * @version    1.0
 * @package   Core\Modules
 * @author     JoostinaTeam
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    see license.txt
 *
 **/



//Подключение вспомогательной библиотеки
require_once joosCore::path( 'custom' , 'module_helper' );
require $module->template_path;	