<?php

/**
 * Nette Framework Requirements Checker.
 *
 * This script will check if your system meets the requirements for running Nette Framework.
 *
 * This file is part of the Nette Framework.
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 */
/**
 * Check PHP configuration.
 */
foreach (array('function_exists', 'version_compare', 'extension_loaded', 'ini_get') as $function) {
	if (!function_exists($function)) {
		die(__("Ошибка: функция <b>$function</b> не поддерживается настройками сервера, дальшейшее определение соответсвия сервкера невозможно."));
	}
}

function __($s) {
	return $s;
}

define('CHECKER_VERSION', '2.m1');

$tests[] = array(
	'title' => __('Веб сервер'),
	'message' => $_SERVER['SERVER_SOFTWARE'],
);

$tests[] = array(
	'title' => __('Версия PHP'),
	'required' => TRUE,
	'passed' => version_compare(PHP_VERSION, '5.3.0', '>='),
	'message' => PHP_VERSION,
	'description' => __('Установленная версия PHP слишком старая.')
);

$tests[] = array(
	'title' => __('Лимит памяти'),
	'message' => ini_get('memory_limit'),
);

$tests['ha'] = array(
	'title' => '.htaccess file protection',
	'required' => FALSE,
	'description' => 'File protection by <code>.htaccess</code> is optional. If it is absent, you must be careful to put files into document_root folder.',
	'script' => "var el = document.getElementById('resha');\nel.className = typeof checkerScript == 'undefined' ? 'passed' : 'warning';\nel.parentNode.removeChild(el.nextSibling.nodeType === 1 ? el.nextSibling : el.nextSibling.nextSibling);",
);

$tests[] = array(
	'title' => __('Функция ini_set'),
	'required' => FALSE,
	'passed' => function_exists('ini_set'),
	'description' => __('Функция <code>ini_set()</code> недоступна. Это означает что Joostina будет работать лишь в ограниченных условиях настроек сервера.'),
);

$tests[] = array(
	'title' => __('Функция контроля ошибок error_reporting'),
	'required' => TRUE,
	'passed' => function_exists('error_reporting'),
	'description' => __('Функция <code>error_reporting()</code> недоступна. Joostina CMS не сможет работать.'),
);

$tests[] = array(
	'title' => __('Регистрация глобальных переменных'),
	'required' => TRUE,
	'passed' => !iniFlag('register_globals'),
	'message' => __('Отключено'),
	'errorMessage' => __('Включено'),
	'description' => __('Параметр <code>register_globals</code> в положении включено. Для нормальной и бозопасной работы регистрацию глоабльных переменных необходимо отключить'),
);

$tests[] = array(
	'title' => __('Режим совместимости Zend.ze1_compatibility_mode'),
	'required' => TRUE,
	'passed' => !iniFlag('zend.ze1_compatibility_mode'),
	'message' => __('Отключено'),
	'errorMessage' => __('Включено'),
	'description' => 'Configuration directive <code>zend.ze1_compatibility_mode</code> is enabled. Nette Framework requires this to be disabled.',
);

$tests[] = array(
	'title' => __('Порядок обработки переменных'),
	'required' => TRUE,
	'passed' => strpos(ini_get('variables_order'), 'G') !== FALSE && strpos(ini_get('variables_order'), 'P') !== FALSE && strpos(ini_get('variables_order'), 'C') !== FALSE,
	'description' => __('Конфигурация <code>variables_order</code> не подходит.'),
);

$tests[] = array(
	'title' => __('Автостарт сессий'),
	'required' => TRUE,
	'passed' => session_id() === '' && !defined('SID'),
	'message' => __('Отключено'),
	'errorMessage' => __('Включено'),
	'description' => 'Включен автостарт сессий. Joostina CMS умеет сама более оптимально работать с сессиями.',
);

$tests[] = array(
	'title' => __('Расширение PCRE'),
	'required' => TRUE,
	'passed' => extension_loaded('pcre') && @preg_match('/pcre/u', 'pcre'),
	'message' => ('Доступно и проверено'),
	'errorMessage' => __('Отключено, либо не поддерживает UTF-8'),
	'description' => __('Расширение PCRE должно быть доступно и поддерживать UTF-8.'),
);

/*
  $tests[] = array(
  'title' => 'PDO extension',
  'required' => FALSE,
  'passed' => $pdo = extension_loaded('pdo') && PDO::getAvailableDrivers(),
  'message' => $pdo ? 'Available drivers: ' . implode(' ', PDO::getAvailableDrivers()) : NULL,
  'description' => 'PDO extension or PDO drivers are absent. You will not be able to use <code>Nette\Database</code>.',
  );
 */

$tests[] = array(
	'title' => __('Поддержка mbstring'),
	'required' => FALSE,
	'passed' => extension_loaded('mbstring'),
	'description' => __('Поддержка mbstring является ключевым местом работы системы.'),
);

$tests[] = array(
	'title' => __('Переопределение системных функций через mbstring'),
	'required' => TRUE,
	'passed' => !extension_loaded('mbstring') || !(mb_get_info('func_overload') & 2),
	'message' => __('Выключено'),
	'errorMessage' => __('Включено'),
	'description' => __('Активно переопределение системных функций. Для нормальной работы переопределение должно быть выключенно.'),
);

$tests[] = array(
	'title' => __('Магические кавычки (Magic Quotes)'),
	'required' => FALSE,
	'passed' => !iniFlag('magic_quotes_gpc') && !iniFlag('magic_quotes_runtime'),
	'message' => __('Отключено'),
	'errorMessage' => __('Включено'),
	'description' => __('Магический ковычки <code>magic_quotes_gpc</code> и <code>magic_quotes_runtime</code> активны. Это функции не рекомендуются, Joostina CMS будет их отключать.'),
);

$tests[] = array(
	'title' => __('Расширение для кеширования Memcache'),
	'required' => FALSE,
	'passed' => extension_loaded('memcache'),
	'description' => __('Максимально быстро система работает используя кеширование Memcache.')
);

$tests[] = array(
	'title' => __('Работса с графикой GD '),
	'required' => TRUE,
	'passed' => extension_loaded('gd'),
	'description' => __('Системе необходима поддержка работы с изображениями.'),
);

$tests[] = array(
	'title' => __('Расширения GD'),
	'required' => FALSE,
	'passed' => extension_loaded('gd') && GD_BUNDLED,
	'description' => __('Часть функций работы с изображениями будет недоступна.'),
);

$tests[] = array(
	'title' => __('Расширение Fileinfo или mime_content_type()'),
	'required' => FALSE,
	'passed' => extension_loaded('fileinfo') || function_exists('mime_content_type'),
	'description' => __('Эти расширения необходимы для оптимлаьной работы с типами файлов и данными о них.'),
);

$tests[] = array(
	'title' => __('Параметры HTTP_HOST или SERVER_NAME'),
	'required' => TRUE,
	'passed' => isset($_SERVER["HTTP_HOST"]) || isset($_SERVER["SERVER_NAME"]),
	'message' => __('Present'),
	'errorMessage' => __('Absent'),
	'description' => __('Either <code>$_SERVER["HTTP_HOST"]</code> or <code>$_SERVER["SERVER_NAME"]</code> must be available for resolving host name.'),
);

$tests[] = array(
	'title' => __('Параметры REQUEST_URI или ORIG_PATH_INFO'),
	'required' => TRUE,
	'passed' => isset($_SERVER["REQUEST_URI"]) || isset($_SERVER["ORIG_PATH_INFO"]),
	'message' => __('Present'),
	'errorMessage' => __('Absent'),
	'description' => __('Either <code>$_SERVER["REQUEST_URI"]</code> or <code>$_SERVER["ORIG_PATH_INFO"]</code> must be available for resolving request URL.'),
);

$tests[] = array(
	'title' => __('Параметры SCRIPT_FILENAME, SCRIPT_NAME, PHP_SELF'),
	'required' => TRUE,
	'passed' => isset($_SERVER["SCRIPT_FILENAME"], $_SERVER["SCRIPT_NAME"], $_SERVER["PHP_SELF"]),
	'message' => __('Present'),
	'errorMessage' => __('Absent'),
	'description' => __('<code>$_SERVER["SCRIPT_FILENAME"]</code> and <code>$_SERVER["SCRIPT_NAME"]</code> and <code>$_SERVER["PHP_SELF"]</code> must be available for resolving script file path.'),
);

$tests[] = array(
	'title' => __('Параметры SERVER_ADDR или LOCAL_ADDR'),
	'required' => TRUE,
	'passed' => isset($_SERVER["SERVER_ADDR"]) || isset($_SERVER["LOCAL_ADDR"]),
	'message' => __('Present'),
	'errorMessage' => __('Absent'),
	'description' => __('<code>$_SERVER["SERVER_ADDR"]</code> or <code>$_SERVER["LOCAL_ADDR"]</code> must be available for detecting development / production mode.'),
);

paint($tests);

/**
 * Paints checker.
 * @param  array
 * @return void
 */
function paint($requirements) {
	$errors = $warnings = FALSE;

	foreach ($requirements as $id => $requirement) {
		$requirements[$id] = $requirement = (object) $requirement;
		if (isset($requirement->passed) && !$requirement->passed) {
			if ($requirement->required) {
				$errors = TRUE;
			} else {
				$warnings = TRUE;
			}
		}
	}

	require __DIR__ . '/assets/checker.phtml';
}

/**
 * Gets the boolean value of a configuration option.
 * @param  string  configuration option name
 * @return bool
 */
function iniFlag($var) {
	$status = strtolower(ini_get($var));
	return $status === 'on' || $status === 'true' || $status === 'yes' || (int) $status;
}
