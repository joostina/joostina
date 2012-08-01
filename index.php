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
$sysstart = TRUE ? microtime(true) : null;

// рассчет памяти
function_exists('memory_get_usage') ? define('_JOOS_MEM_USAGE', memory_get_usage()) : null;

// подключение главного файла - ядра системы
require_once __DIR__ . '/core/joostina.php';

try {

	$controller = joosController::instance();
	$controller->run();
	
    echo joosController::render();

    echo !JDEBUG ? : joosController::debug($sysstart);

} catch (Exception $e) {

    echo $e;
}
