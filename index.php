<?php

/**
 * Frontend - точка входа
 *
 * @package   Joostina.Core
 * @author    JoostinaTeam
 * @copyright (C) 2007-2011 Joostina Team
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
// Установка флага родительского файла
define('_JOOS_CORE', 1);

// рассчет памяти
function_exists('memory_get_usage') ? define('_MEM_USAGE_START', memory_get_usage()) : null;

$sysstart = TRUE ? microtime(true) : null;

// подключение главного файла - ядра системы
require_once ( __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'joostina.php' );
require_once ( JPATH_BASE . DS . 'core' . DS . 'front.root.php' );

// запускаем контроллер
joosController::run();

ob_start();
// загрузка файла шаблона
require_once ( JPATH_BASE . '/app/templates/' . JTEMPLATE . '/index.php' );
joosDocument::$data['html_body'] = ob_get_clean();

echo $html_body = joosDocument::$data['html_body'];

/* эта часть из далёкого будующего
  $hash = md5($html_body);

  header("Etag: \"" . $hash . "\"");

  if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) == '"' . $hash . '"') {
  joosRequest::send_headers_by_code(304);
  header('modelContent-Length: 0');
  header('X-Powered-By: 304');
  die();
  } else {
  header('X-Powered-By: 200');
  echo $html_body;
  }
 */

// вывод лога отладки
if (JDEBUG) {

	// подсчет израсходованной памяти
	if (defined('_MEM_USAGE_START')) {
		$mem_usage = ( memory_get_usage() - _MEM_USAGE_START );
		$mem_usage = joosFile::convert_size($mem_usage);
	} else {
		$mem_usage = 'недоступно';
	}

	// подсчет времени генерации страницы
	joosDebug::add_top(sprintf('Завтрачено <b>времени</b>: %s, <b>памяти</b> %s ', round(( microtime(true) - $sysstart), 5), $mem_usage));

	// вывод итогового лога отлатчика
	joosDebug::get();
}
