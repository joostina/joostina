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

// подключение главного файла - ядра системы
require_once __DIR__ . '/core/joostina.php';

try {

	echo joosController::instance()
		->init()
		->route()
		->run()
		->render();

    echo !JDEBUG ? : joosController::debug();

} catch (Exception $e) {

    echo $e;
}
