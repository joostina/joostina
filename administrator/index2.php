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

// live_site
define('JPATH_SITE', $mosConfig_live_site );

// подключаем ядро
require_once (JPATH_BASE .DS. 'includes'.DS.'joostina.php');
// подключаем расширенные административные функции
require_once (JPATH_BASE_ADMIN.DS.'includes'.DS.'admin.php');
// класс работы с визуальным редактором
require_once (JPATH_BASE .DS. 'includes'.DS.'editor.php');

database::getInstance();

// работа с сессиями начинается до создания главного объекта взаимодействия с ядром
session_name(md5(JPATH_SITE));
session_start();

header('Content-type: text/html; charset=UTF-8');

// получение основных параметров
$option		= strval(strtolower(mosGetParam($_REQUEST,'option','')));
$task		= strval(mosGetParam($_REQUEST,'task',''));
$no_html	= (int)mosGetParam($_REQUEST,'no_html',0);
$id	=		(int)mosGetParam($_REQUEST,'id',0);

// mainframe - основная рабочая среда API, осуществляет взаимодействие с 'ядром'
$mainframe = mosMainFrame::getInstance(true);

require_once($mainframe->getLangFile());
require_once($mainframe->getLangFile('administrator'));

// запуск сессий панели управления
$my = $mainframe->initSessionAdmin($option,$task);

// класс работы с правами пользователей
mosMainFrame::addLib('acl');
// загружаем набор прав для панели управления
Jacl::init_admipanel();
Jacl::isAllowed( 'adminpanel' ) ? null : mosRedirect( JPATH_SITE, 'В доступе отказано' ) ;


// страница панели управления по умолчанию
$option = $_REQUEST['option'] = ($option == '') ? 'com_admin' : $option;

ob_start();
if($path = $mainframe->getPath('admin')) {
    //Подключаем язык компонента
    if($mainframe->getLangFile($option)) {
        include_once($mainframe->getLangFile($option));
    }
    require_once ($path);
} else {
    ?><img src="<?php echo JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE;?>/images/ico/error.png" border="0" alt="Joostina!" /><?php
}

$_MOS_OPTION['buffer'] = ob_get_contents();
ob_end_clean();

ob_start();

// начало вывода html
if($no_html == 0) {
    // загрузка файла шаблона
    if(!file_exists(JPATH_BASE .DS.JADMIN_BASE.DS.'templates'.DS. JTEMPLATE .DS.'index.php')) {
        echo _TEMPLATE_NOT_FOUND.': '.JTEMPLATE;
    } else {
        //Подключаем язык шаблона
        if($mainframe->getLangFile('tmpl_'.JTEMPLATE)) {
            include_once($mainframe->getLangFile('tmpl_'.JTEMPLATE));
        }
        require_once (JPATH_BASE . DS.JADMIN_BASE.DS.'templates' .DS. JTEMPLATE .DS.'index.php');
    }
} else {
    mosMainBody_Admin();
}

// информация отладки, число запросов в БД
JDEBUG ? jd_get() : null;

// восстановление сессий
if($task == 'save' || $task == 'apply' || $task == 'save_and_new' ) {
    $mainframe->initSessionAdmin($option,'');
}

ob_end_flush();