<?php
/**
 * Frontend - точка входа
 *
 * @package   Core
 * @author    JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
// Установка флага родительского файла
define('_JOOS_CORE', 1);

// рассчет времени работы
define('JOOS_START', microtime(true));

// рассчет памяти
function_exists('memory_get_usage') ? define('JOOS_MEMORY_START', memory_get_usage()) : null;

define('JPATH_BASE', __DIR__);

// предстартовые конфигурации
require JPATH_BASE  . '/app/bootstrap.php';

// подключение главного файла - ядра системы
require_once JPATH_BASE . '/core/joostina.php';

try {

	joosCore::instance()
		->init()
		->route();

	
	echo joosController::instance()
		->run()
		->render();
	


    echo !JDEBUG ? : joosController::show_debug();

} catch (Exception $e) {

    echo $e;
}
