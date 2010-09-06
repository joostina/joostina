<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// Установка флага родительского файла
define('_VALID_MOS',1);
// разделитель каталогов
define('DS', DIRECTORY_SEPARATOR );
// корень файлов
define('JPATH_BASE', dirname(dirname(__FILE__)) );
// корень файлов админкиы
define('JPATH_BASE_ADMIN', dirname(__FILE__) );

require_once (JPATH_BASE.DS.'configuration.php');

// для совместимости
$mosConfig_absolute_path = JPATH_BASE;

// live_site
define('JPATH_SITE', $mosConfig_live_site );

// подключаем ядро
require_once (JPATH_BASE .DS. 'includes'.DS.'joostina.php');
// подключаем расширенные административные функции
require_once (JPATH_BASE_ADMIN.DS.'includes'.DS.'admin.php');

// создаём сессии
session_name(md5($mosConfig_live_site));
session_start();

header("Content-type: text/html; charset=utf-8");
header ("Cache-Control: no-cache, must-revalidate ");

$option		= strval(strtolower(mosGetParam($_REQUEST,'option','')));
$task		= strval(mosGetParam($_REQUEST,'task',''));

// mainframe - основная рабочая среда API, осуществляет взаимодействие с 'ядром'
$mainframe = mosMainFrame::getInstance(true);

require_once($mainframe->getLangFile());
require_once($mainframe->getLangFile('administrator'));

$my = $mainframe->initSessionAdmin($option,$task);

// класс работы с правами пользователей
mosMainFrame::addLib('acl');
Jacl::init_admipanel();

if( Jacl::isDeny('adminpanel') ){
	echo json_encode( array('error'=>'acl') );
}

if(!$my->id) {
	die('error-my');
}

$commponent = str_replace('com_','',$option);

initGzip();
// файл обработки Ajax запрсоов конкртеного компонента
$file_com = JPATH_BASE_ADMIN.DS.'components'.DS.$option.DS.'admin.'.$commponent.'.ajax.php';
// проверяем, какой файл необходимо подключить, данные берутся из пришедшего GET запроса
if(file_exists($file_com)) {
	//Подключаем язык компонента
	if($mainframe->getLangFile($option)) {
		include($mainframe->getLangFile($option));
	}
	include_once ($file_com);
} else {
	die('error-inc-component');
}

doGzip();