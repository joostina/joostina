<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

Jacl::isDeny('config','view') ? mosRedirect('index2.php?', _NOT_AUTH) : null;


require_once($mainframe->getPath('admin_html'));

switch ($task) {

	case 'apply':
	case 'save':
		Jacl::isDeny('config','save') ? mosRedirect('index2.php?', _NOT_AUTH) : null;
		js_menu_cache_clear();
		saveconfig($task);
		break;

	case 'cancel':
		mosRedirect('index2.php');
		break;

	default:
		showconfig($option);
		break;
}

/**
 * Show the configuration edit form
 * @param string The URL option
 */
function showconfig($option) {
	global $mosConfig_editor, $mosConfig_cache_handler;

	$database = database::getInstance();


	$row = JConfig::getInstance();
	$row->bindGlobals();

	// compile list of the languages
	$langs = array();
	$lists = array();
	// -- Языки --
	if ($handle = opendir(JPATH_BASE . '/language/')) {
		$i = 0;
		while (false !== ($file = readdir($handle))) {
			if ( is_dir( JPATH_BASE.DS.'language'.DS.$file) && $file!='.svn' && $file != "." && $file != "..") {
				$langs[] = mosHTML::makeOption($file);
			}
		}
	}

	// сортировка списка языков
	sort($langs);
	reset($langs);

	// НАСТРОЙКИ САЙТА
	$lists['offline'] = mosHTML::yesnoRadioList('config_offline', 'class="inputbox"', $row->config_offline);

	$listLimit = array(
		mosHTML::makeOption(5, 5),
		mosHTML::makeOption(10, 10),
		mosHTML::makeOption(15, 15),
		mosHTML::makeOption(20, 20),
		mosHTML::makeOption(25, 25),
		mosHTML::makeOption(30, 30),
		mosHTML::makeOption(50, 50),
		mosHTML::makeOption(100, 100),
		mosHTML::makeOption(150, 150),
	);

	$lists['list_limit'] = mosHTML::selectList($listLimit, 'config_list_limit', 'class="inputbox" size="1"', 'value', 'text', ($row->config_list_limit ? $row->config_list_limit : 50));

	$lists['frontend_login'] = mosHTML::yesnoRadioList('config_frontend_login', 'class="inputbox"', $row->config_frontend_login);

	// отключение ведения сессий подсчета числа пользователей на сайте
	$lists['session_front'] = mosHTML::yesnoRadioList('config_no_session_front', 'class="inputbox"', $row->config_no_session_front);
	// отключение тега Generator
	$lists['generator_off'] = mosHTML::yesnoRadioList('config_generator_off', 'class="inputbox"', $row->config_generator_off);
	// получаем список шаблонов. Код получен из модуля выбора шаблона
	$titlelength = 20;
	$template_path = JPATH_BASE.DS . 'templates';
	$templatefolder = @dir($template_path);
	$darray = array();
	$darray[] = mosHTML::makeOption('...', _O_OTHER); // параметр по умолчанию - позволяет использовать стандартный способ определения шаблона
	if ($templatefolder) {
		while ($templatefile = $templatefolder->read()) {
			if ($templatefile != 'system' && $templatefile != "." && $templatefile != ".." && $templatefile != ".svn" && $templatefile != "css" && is_dir("$template_path/$templatefile")) {
				if (strlen($templatefile) > $titlelength) {
					$templatename = substr($templatefile, 0, $titlelength - 3);
					$templatename .= "...";
				} else {
					$templatename = $templatefile;
				}
				$darray[] = mosHTML::makeOption($templatefile, $templatename);
			}
		}
		$templatefolder->close();
	}
	sort($darray);
	$lists['one_template'] = mosHTML::selectList($darray, 'config_one_template', "class=\"inputbox\" ", 'value', 'text', $row->config_one_template);
	// время генерации страницы
	$lists['config_time_generate'] = mosHTML::yesnoRadioList('config_time_generate', 'class="inputbox"', $row->config_time_generate);
	//индексация страницы печати
	$lists['index_print'] = mosHTML::yesnoRadioList('config_index_print', 'class="inputbox"', $row->config_index_print);
	// расширенные теги индексации
	$lists['index_tag'] = mosHTML::yesnoRadioList('config_index_tag', 'class="inputbox"', $row->config_index_tag);
	// ежесуточная оптимизация таблиц бд
	$lists['optimizetables'] = mosHTML::yesnoRadioList('config_optimizetables', 'class="inputbox"', $row->config_optimizetables);
	// кэширование меню панели управления
	$lists['adm_menu_cache'] = mosHTML::yesnoRadioList('config_adm_menu_cache', 'class="inputbox"', $row->config_adm_menu_cache);
	// управление captcha
	$lists['captcha'] = mosHTML::yesnoRadioList('config_captcha', 'class="inputbox"', $row->config_captcha);
	// формат времени
	$date_help = array(
		mosHTML::makeOption('%d.%m.%Y ' . _COM_CONFIG_YEAR . ' %H:%M', strftime('%d.%m.%Y ' . _COM_CONFIG_YEAR . ' %H:%M')),
		mosHTML::makeOption('%d:%m:%Y ' . _COM_CONFIG_YEAR . ' %H:%M', strftime('%d:%m:%Y ' . _COM_CONFIG_YEAR . ' %H:%M')),
		mosHTML::makeOption('%d-%m-%Y ' . _COM_CONFIG_YEAR . ' %H-%M', strftime('%d-%m-%Y ' . _COM_CONFIG_YEAR . ' %H-%M')),
		mosHTML::makeOption('%d/%m/%Y ' . _COM_CONFIG_YEAR . ' %H/%M', strftime('%d/%m/%Y ' . _COM_CONFIG_YEAR . ' %H/%M')),
		mosHTML::makeOption('%d/%m/%Y %H/%M', strftime('%d/%m/%Y %H/%M')),
		mosHTML::makeOption('%d/%m/%Y', strftime('%d/%m/%Y')),
		mosHTML::makeOption('%d:%m:%Y', strftime('%d:%m:%Y')),
		mosHTML::makeOption('%d.%m.%Y', strftime('%d.%m.%Y')),
		mosHTML::makeOption('%d/%m/%Y ' . _COM_CONFIG_YEAR, strftime('%d/%m/%Y ' . _COM_CONFIG_YEAR)),
		mosHTML::makeOption('%d:%m:%Y ' . _COM_CONFIG_YEAR, strftime('%d:%m:%Y ' . _COM_CONFIG_YEAR)),
		mosHTML::makeOption('%d.%m.%Y ' . _COM_CONFIG_YEAR, strftime('%d.%m.%Y ' . _COM_CONFIG_YEAR)),
		mosHTML::makeOption('%H/%M', strftime('%H/%M')),
		mosHTML::makeOption('%H:%M', strftime('%H:%M')),
		mosHTML::makeOption('%H ' . _COM_CONFIG_HOURS . '%M ' . _COM_CONFIG_MONTH, strftime('%H ' . _COM_CONFIG_HOURS . ' %M ' . _COM_CONFIG_MONTH)),
		mosHTML::makeOption('%A %d/%m/%Y ' . _COM_CONFIG_YEAR . ' %H/%M', strftime('%A %d/%m/%Y ' . _COM_CONFIG_YEAR . ' %H/%M')),
		mosHTML::makeOption('%d %B %Y', strftime('%d %B %Y'))
	);
	$lists['form_date_help'] = mosHTML::selectList($date_help, 'config_form_date_h', 'class="inputbox" size="1" onchange="adminForm.config_form_date.value=this.value;"', 'value', 'text', $row->config_form_date);
	// полный формат даты и времени
	$lists['form_date_full_help'] = mosHTML::selectList($date_help, 'config_form_date_full_h', 'class="inputbox" size="1" onchange="adminForm.config_form_date_full.value=this.value;"', 'value', 'text', $row->config_form_date_full);
	// поддержка работы на младших версиях MySQL
	$lists['config_pathway_clean'] = mosHTML::yesnoRadioList('config_pathway_clean', 'class="inputbox"', $row->config_pathway_clean);
	// отключение удаления сессий в панели управления
	$lists['config_admin_autologout'] = mosHTML::yesnoRadioList('config_admin_autologout', 'class="inputbox"', $row->config_admin_autologout);
	// отключение favicon
	$lists['config_disable_favicon'] = mosHTML::yesnoRadioList('config_disable_favicon', 'class="inputbox"', $row->config_disable_favicon);
	// использование расширенного отладчика на фронте
	$lists['config_front_debug'] = mosHTML::yesnoRadioList('config_front_debug', 'class="inputbox"', $row->config_front_debug);
		// автоматическая авторизация после подтверждения регистрации
	$lists['config_auto_activ_login'] = mosHTML::yesnoRadioList('config_auto_activ_login', 'class="inputbox"', $row->config_auto_activ_login);
	// оптимизация функции кэширования
	$lists['config_cache_opt'] = mosHTML::yesnoRadioList('config_cache_opt', 'class="inputbox"', $row->config_cache_opt);
	// DEBUG - ОТЛАДКА
	$lists['debug'] = mosHTML::yesnoRadioList('config_debug', 'class="inputbox"', $row->config_debug);

	// НАСТРОЙКИ СЕРВЕРА
	$lists['gzip'] = mosHTML::yesnoRadioList('config_gzip', 'class="inputbox"', $row->config_gzip);

	$session = array(
		mosHTML::makeOption(0, _SECURITY_LEVEL3),
		mosHTML::makeOption(1, _SECURITY_LEVEL2),
		mosHTML::makeOption(2, _SECURITY_LEVEL1)
	);
	$lists['session_type'] = mosHTML::selectList($session, 'config_session_type', 'class="inputbox" size="1"', 'value', 'text', $row->config_session_type);

	$errors = array(
		mosHTML::makeOption( -1, _COM_CONFIG_ERROR_SYSTEM),
		mosHTML::makeOption(0, _COM_CONFIG_ERROR_HIDE),
		mosHTML::makeOption(E_ERROR | E_WARNING | E_PARSE, _COM_CONFIG_ERROR_TINY),
		mosHTML::makeOption(E_ALL, _COM_CONFIG_ERROR_ALL),
		mosHTML::makeOption(E_ALL & ~ E_NOTICE, _COM_CONFIG_ERROR_PARANOIDAL),
	);

	$lists['error_reporting'] = mosHTML::selectList($errors, 'config_error_reporting', 'class="inputbox" size="1"', 'value', 'text', $row->config_error_reporting);

	$lists['admin_expired'] = mosHTML::yesnoRadioList('config_admin_expired', 'class="inputbox"', $row->config_admin_expired);

	// НАСТРОЙКИ ЛОКАЛИ СТРАНЫ
	$lists['lang'] = mosHTML::selectList($langs, 'config_lang', 'class="inputbox" size="1"', 'value', 'text', $row->config_lang);

	$timeoffset = array(
		mosHTML::makeOption( - 12, _TIME_OFFSET_M_12),
		mosHTML::makeOption( - 11, _TIME_OFFSET_M_11),
		mosHTML::makeOption( - 10, _TIME_OFFSET_M_10),
		mosHTML::makeOption( - 9.5, _TIME_OFFSET_M_9_5),
		mosHTML::makeOption( - 9, _TIME_OFFSET_M_9),
		mosHTML::makeOption( - 8, _TIME_OFFSET_M_8),
		mosHTML::makeOption( - 7, _TIME_OFFSET_M_7),
		mosHTML::makeOption( - 6, _TIME_OFFSET_M_6),
		mosHTML::makeOption( - 5, _TIME_OFFSET_M_5),
		mosHTML::makeOption( - 4, _TIME_OFFSET_M_4),
		mosHTML::makeOption( - 3.5, _TIME_OFFSET_M_3_5),
		mosHTML::makeOption( - 3, _TIME_OFFSET_M_3),
		mosHTML::makeOption( - 2, _TIME_OFFSET_M_2),
		mosHTML::makeOption( - 1, _TIME_OFFSET_M_1),
		mosHTML::makeOption(0, _TIME_OFFSET_M_0),
		mosHTML::makeOption(1, _TIME_OFFSET_P_1),
		mosHTML::makeOption(2, _TIME_OFFSET_P_2),
		mosHTML::makeOption(3, _TIME_OFFSET_P_3),
		mosHTML::makeOption(3.5, _TIME_OFFSET_P_3_5),
		mosHTML::makeOption(4, _TIME_OFFSET_P_4),
		mosHTML::makeOption(4.5, _TIME_OFFSET_P_4_5),
		mosHTML::makeOption(5, _TIME_OFFSET_P_5),
		mosHTML::makeOption(5.5, _TIME_OFFSET_P_5_5),
		mosHTML::makeOption(5.75, _TIME_OFFSET_P_5_75),
		mosHTML::makeOption(6, _TIME_OFFSET_P_6),
		mosHTML::makeOption(6.30, _TIME_OFFSET_P_6_5),
		mosHTML::makeOption(7, _TIME_OFFSET_P_7),
		mosHTML::makeOption(8, _TIME_OFFSET_P_8),
		mosHTML::makeOption(8.75, _TIME_OFFSET_P_8_75),
		mosHTML::makeOption(9, _TIME_OFFSET_P_9),
		mosHTML::makeOption(9.5, _TIME_OFFSET_P_9_5),
		mosHTML::makeOption(10, _TIME_OFFSET_P_10),
		mosHTML::makeOption(10.5, _TIME_OFFSET_P_10_5),
		mosHTML::makeOption(11, _TIME_OFFSET_P_11),
		mosHTML::makeOption(11.30, _TIME_OFFSET_P_11_5),
		mosHTML::makeOption(12, _TIME_OFFSET_P_12),
		mosHTML::makeOption(12.75, _TIME_OFFSET_P_12_75),
		mosHTML::makeOption(13, _TIME_OFFSET_P_13),
		mosHTML::makeOption(14, _TIME_OFFSET_P_14), );

	$lists['offset'] = mosHTML::selectList($timeoffset, 'config_offset_user', 'class="inputbox" size="1"', 'value', 'text', $row->config_offset_user);

	$feed_timeoffset = array(
		mosHTML::makeOption('-12:00', _TIME_OFFSET_M_12),
		mosHTML::makeOption('-11:00', _TIME_OFFSET_M_11),
		mosHTML::makeOption('-10:00', _TIME_OFFSET_M_10),
		mosHTML::makeOption('-09:30', _TIME_OFFSET_M_9_5),
		mosHTML::makeOption('-09:00', _TIME_OFFSET_M_9),
		mosHTML::makeOption('-08:00', _TIME_OFFSET_M_8),
		mosHTML::makeOption('-07:00', _TIME_OFFSET_M_7),
		mosHTML::makeOption('-06:00', _TIME_OFFSET_M_6),
		mosHTML::makeOption('-05:00', _TIME_OFFSET_M_5),
		mosHTML::makeOption('-04:00', _TIME_OFFSET_M_4),
		mosHTML::makeOption('-03:30', _TIME_OFFSET_M_3_5),
		mosHTML::makeOption('-03:00', _TIME_OFFSET_M_3),
		mosHTML::makeOption('-02:00', _TIME_OFFSET_M_2),
		mosHTML::makeOption('-01:00', _TIME_OFFSET_M_1),
		mosHTML::makeOption('00:00', _TIME_OFFSET_M_0),
		mosHTML::makeOption('+01:00', _TIME_OFFSET_P_1),
		mosHTML::makeOption('+02:00', _TIME_OFFSET_P_2),
		mosHTML::makeOption('+03:00', _TIME_OFFSET_P_3),
		mosHTML::makeOption('+03:30', _TIME_OFFSET_P_3_5),
		mosHTML::makeOption('+04:00', _TIME_OFFSET_P_4),
		mosHTML::makeOption('+04:30', _TIME_OFFSET_P_4_5),
		mosHTML::makeOption('+05:00', _TIME_OFFSET_P_5),
		mosHTML::makeOption('+05:30', _TIME_OFFSET_P_5_5),
		mosHTML::makeOption('+05:45', _TIME_OFFSET_P_5_75),
		mosHTML::makeOption('+06:00', _TIME_OFFSET_P_6),
		mosHTML::makeOption('+06:30', _TIME_OFFSET_P_6_5),
		mosHTML::makeOption('+07:00', _TIME_OFFSET_P_7),
		mosHTML::makeOption('+08:00', _TIME_OFFSET_P_8),
		mosHTML::makeOption('+08:45', _TIME_OFFSET_P_8_75),
		mosHTML::makeOption('+09:00', _TIME_OFFSET_P_9),
		mosHTML::makeOption('+09:30', _TIME_OFFSET_P_9_5),
		mosHTML::makeOption('+10:00', _TIME_OFFSET_P_10),
		mosHTML::makeOption('+10:30', _TIME_OFFSET_P_10_5),
		mosHTML::makeOption('+11:00', _TIME_OFFSET_P_11),
		mosHTML::makeOption('+11:30', _TIME_OFFSET_P_11_5),
		mosHTML::makeOption('+12:00', _TIME_OFFSET_P_12),
		mosHTML::makeOption('+12:45', _TIME_OFFSET_P_12_75),
		mosHTML::makeOption('+13:00', _TIME_OFFSET_P_13),
		mosHTML::makeOption('+14:00', _TIME_OFFSET_P_14)
	);
	$lists['feed_timeoffset'] = mosHTML::selectList($feed_timeoffset, 'config_feed_timeoffset', 'class="inputbox" size="1"', 'value', 'text', $row->config_feed_timeoffset);

// НАСТРОЙКИ ПОЧТЫ
	$mailer = array(
		mosHTML::makeOption('mail', _PHP_MAIL_FUNCTION),
		mosHTML::makeOption('sendmail', 'Sendmail'),
		mosHTML::makeOption('smtp', _SMTP_SERVER)
	);
	$lists['mailer'] = mosHTML::selectList($mailer, 'config_mailer', 'class="inputbox" size="1"', 'value', 'text', $row->config_mailer);
	$lists['smtpauth'] = mosHTML::yesnoRadioList('config_smtpauth', 'class="inputbox"', $row->config_smtpauth);


	// НАСТРОЙКИ КЭША
	$lists['caching'] = mosHTML::yesnoRadioList('config_caching', 'class="inputbox"', $row->config_caching);

// НАСТРОЙКИ ПОЛЬЗОВАТЕЛЕЙ
	$lists['frontend_userparams'] = mosHTML::yesnoRadioList('config_frontend_userparams', 'class="inputbox"', $row->config_frontend_userparams);
	$lists['allowUserRegistration'] = mosHTML::yesnoRadioList('config_allowUserRegistration', 'class="inputbox"', $row->config_allowUserRegistration);

// НАСТРОЙКИ META-ДАННЫХ
	$lists['MetaAuthor'] = mosHTML::yesnoRadioList('config_MetaAuthor', 'class="inputbox"', $row->config_MetaAuthor);
	$lists['MetaTitle'] = mosHTML::yesnoRadioList('config_MetaTitle', 'class="inputbox"', $row->config_MetaTitle);

// НАСТРОЙКИ SEO
	$lists['sef'] = mosHTML::yesnoRadioList('config_sef', 'class="inputbox" onclick="javascript: if (document.adminForm.config_sef[1].checked) { alert(\'' . _C_CONFIG_HTACCESS_RENAME . '\') }"', $row->config_sef);
	$lists['pagetitles'] = mosHTML::yesnoRadioList('config_pagetitles', 'class="inputbox"', $row->config_pagetitles);

	$pagetitles_first = array(
		mosHTML::makeOption(0, _COM_CONFIG_SEO_TYPE_1),
		mosHTML::makeOption(1, _COM_CONFIG_SEO_TYPE_2),
		mosHTML::makeOption(2, _COM_CONFIG_SEO_TYPE_3),
		mosHTML::makeOption(3, _COM_CONFIG_SEO_TYPE_4),
	);
	$lists['pagetitles_first'] = mosHTML::selectList($pagetitles_first, 'config_pagetitles_first', 'class="inputbox" size="1"', 'value', 'text', $row->config_pagetitles_first);

	$lists['mtage_base'] = mosHTML::yesnoRadioList('config_mtage_base', 'class="inputbox"', $row->config_mtage_base);
	$lists['config_custom_print'] = mosHTML::yesnoRadioList('config_custom_print', 'class="inputbox"', $row->config_custom_print);
	$lists['tpreview'] = mosHTML::yesnoRadioList('config_disable_tpreview', 'class="inputbox"', $row->config_disable_tpreview);

	$locales = array(
		mosHTML::makeOption('ru_RU.utf8', 'ru_RU.utf8'),
		mosHTML::makeOption('russian', 'russian (windows)'),
		mosHTML::makeOption('english', 'english (for windows)'),
	);
	$lists['locale'] = mosHTML::selectList($locales, 'config_locale', 'class="selectbox" size="1" dir="ltr"', 'value', 'text', $row->config_locale);

	// включение кода безопасности для доступа к панели управления
	$lists['config_enable_admin_secure_code'] = mosHTML::yesnoRadioList('config_enable_admin_secure_code', 'class="inputbox"', $row->config_enable_admin_secure_code);

	// режим редиректа при включенном коде безопасноти
	$redirect_r = array(
		mosHTML::makeOption(0, 'index.php'),
		mosHTML::makeOption(1, _ADMIN_REDIRECT_PAGE)
	);
	$lists['config_admin_redirect_options'] = mosHTML::RadioList($redirect_r, 'config_admin_redirect_options', 'class="inputbox"', $row->config_admin_redirect_options, 'value', 'text');

	// обработчики кэширования
	$cache_handler = array();
	$cache_handler[] = mosHTML::makeOption('file', 'file');
	if (function_exists('eaccelerator_get'))	$cache_handler[] = mosHTML::makeOption('eaccelerator', 'eAccelerator');
	if (extension_loaded('apc'))		$cache_handler[] = mosHTML::makeOption('apc', 'APC');
	if (class_exists('Memcache'))			$cache_handler[] = mosHTML::makeOption('memcache', 'Memcache');
	if (function_exists('xcache_set'))		$cache_handler[] = mosHTML::makeOption('xcache', 'Xcache');

	?>
<script>
	function showHideMemCacheSettings()
	{
		if(document.getElementById("config_cache_handler").value != "memcache")
		{
			document.getElementById("memcache_persist").style.display = "none";
			document.getElementById("memcache_compress").style.display = "none";
			document.getElementById("memcache_server").style.display = "none";
		}
		else
		{
			document.getElementById("memcache_persist").style.display = "";
			document.getElementById("memcache_compress").style.display = "";
			document.getElementById("memcache_server").style.display = "";
		}
	}
</script>

	<?php
	// оработчик кэширования
	$lists['cache_handler'] = mosHTML::selectList($cache_handler, 'config_cache_handler', 'class="inputbox" id="config_cache_handler" onchange="showHideMemCacheSettings();" ', 'value', 'text', $row->config_cache_handler);

	if (!empty($row->config_memcache_settings) && !is_array($row->config_memcache_settings)) {
		$row->config_memcache_settings = unserialize(stripslashes($row->config_memcache_settings));
	}
	$lists['memcache_persist'] = mosHTML::yesnoRadioList('config_memcache_persistent', 'class="inputbox"', $row->config_memcache_persistent);
	$lists['memcache_compress'] = mosHTML::yesnoRadioList('config_memcache_compression', 'class="inputbox"', $row->config_memcache_compression);

	// список шаблонов панели управления
	$titlelength = 20;
	$admin_template_path = JPATH_BASE.DS . 'administrator' . DS . 'templates';
	$templatefolder = @dir($admin_template_path);

	$admin_templates = array();
	$admin_templates[] = mosHTML::makeOption('...', _O_OTHER); // параметр по умолчанию - позволяет использовать стандартный способ определения шаблона
	if ($templatefolder) {
		while ($templatefile = $templatefolder->read()) {
			if ($templatefile != "." && $templatefile != ".." && $templatefile != ".svn" && is_dir($admin_template_path.DS.$templatefile)) {
				if (strlen($templatefile) > $titlelength) {
					$templatename = substr($templatefile, 0, $titlelength - 3);
					$templatename .= "...";
				} else {
					$templatename = $templatefile;
				}
				$admin_templates[] = mosHTML::makeOption($templatefile, $templatename);
			}
		}
		$templatefolder->close();
	}
	sort($admin_templates);
	$lists['config_admin_template'] = mosHTML::selectList($admin_templates, 'config_admin_template', 'class="inputbox" ', 'value', 'text', $row->config_admin_template);

	// блокировка компонентов
	$lists['components_access'] = mosHTML::yesnoRadioList('config_components_access', 'class="inputbox"', $row->config_components_access);

	HTML_config::showconfig($row, $lists, $option);
}

/**
 * Сохранение конфигурации
 */
function saveconfig($task) {
	global $mosConfig_password, $mosConfig_session_type;
	josSpoofCheck();

	$database = database::getInstance();

	$row = JConfig::getInstance();
	if (!$row->bind($_POST)) {
		mosRedirect('index2.php', $row->getError());
	}

	// if Session Authentication Type changed, delete all old Frontend sessions only - which used old Authentication Type
	if ($mosConfig_session_type != $row->config_session_type) {
		$past = time();
		$query = "DELETE FROM #__session WHERE time < " . $database->Quote($past) . " AND ( ( guest = 1 AND userid = 0 ) OR ( guest = 0 AND gid > 0 ) )";
		$database->setQuery($query);
		$database->query();
	}

	$server_time = date('O') / 100;
	$offset = $_POST['config_offset_user'] - $server_time;
	$row->config_offset = $offset;

	//override any possible database password change
	$row->config_password = $mosConfig_password;

	// handling of special characters
	$row->config_sitename = htmlspecialchars($row->config_sitename, ENT_QUOTES);

	// handling of quotes (double and single) and amp characters
	// htmlspecialchars not used to preserve ability to insert other html characters
	$row->config_offline_message = ampReplace($row->config_offline_message);
	$row->config_offline_message = str_replace('"', '&quot;', $row->config_offline_message);
	$row->config_offline_message = str_replace("'", '&#039;', $row->config_offline_message);

	// handling of quotes (double and single) and amp characters
	// htmlspecialchars not used to preserve ability to insert other html characters
	$row->config_error_message = ampReplace($row->config_error_message);
	$row->config_error_message = str_replace('"', '&quot;', $row->config_error_message);
	$row->config_error_message = str_replace("'", '&#039;', $row->config_error_message);

	// ключ кэша
	$row->config_cache_key = time();

	$config = "<?php \n";

	$config .= $row->getVarText();
	$config .= "setlocale (LC_TIME, \$mosConfig_locale);\n";
	$config .= '?>';

	$fname = JPATH_BASE . '/configuration.php';

	$enable_write = intval(mosGetParam($_POST, 'enable_write', 0));
	$oldperms = fileperms($fname);
	if ($enable_write) {
		@chmod($fname, $oldperms | 0222);
	}

	if ($fp = fopen($fname, 'w')) {
		fputs($fp, $config, strlen($config));
		fclose($fp);
		if ($enable_write) {
			@chmod($fname, $oldperms);
		} else {
			if (mosGetParam($_POST, 'disable_write', 0)) @chmod($fname, $oldperms & 0777555);
		} // if

		$msg = _CONFIGURATION_IS_UPDATED;

		// apply file and directory permissions if requested by user
		$applyFilePerms = mosGetParam($_POST, 'applyFilePerms', 0) && $row->config_fileperms != '';
		$applyDirPerms = mosGetParam($_POST, 'applyDirPerms', 0) && $row->config_dirperms != '';
		if ($applyFilePerms || $applyDirPerms) {
			$mosrootfiles = array(JADMIN_BASE, 'cache', 'components', 'images', 'language', 'plugins', 'media', 'modules', 'templates', 'configuration.php');
			$filemode = null;
			if ($applyFilePerms) {
				$filemode = octdec($row->config_fileperms);
			}
			$dirmode = null;
			if ($applyDirPerms) {
				$dirmode = octdec($row->config_dirperms);
			}
			mosMainFrame::addLib('files');
			foreach ($mosrootfiles as $file) {
				mosChmodRecursive(JPATH_BASE . '/' . $file, $filemode, $dirmode);
			}
		} // if

		switch ($task) {
			case 'apply':
				mosRedirect('index2.php?option=com_config&hidemainmenu=1', $msg);
				break;
			case 'save':
			default:
				mosRedirect('index2.php', $msg);
				break;
		}
	} else {
		if ($enable_write) {
			@chmod($fname, $oldperms);
		}
		mosRedirect('index2.php', _CANNOT_OPEN_CONF_FILE);
	}
}
