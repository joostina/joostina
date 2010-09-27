<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_JOOS_CORE') or die();

global $database;
global $mosConfig_lang;

include_once(JPATH_BASE.DS . 'language' . DS.$mosConfig_lang.DS . 'system.php');


$adminOffline = false;

if (!defined('_INSTALL_CHECK')) {
	session_name(md5(JPATH_SITE));
	session_start();

	require_once(JPATH_BASE . '/components/com_users/users.class.php');
	if (class_exists('User') && $database != null) {
		// восстановление некоторых переменных сессии
		$admin = new User($database);
		$admin->id = intval(mosGetParam($_SESSION, 'session_user_id', ''));
		$admin->username = strval(mosGetParam($_SESSION, 'session_USER', ''));
		$admin->groupname = strval(mosGetParam($_SESSION, 'session_groupname', ''));
		$session_id = mosGetParam($_SESSION, 'session_id', '');
		$logintime = mosGetParam($_SESSION, 'session_logintime', '');

		// проверка наличия строки сессии в базе данных
		if ($session_id == md5($admin->id.$admin->username.$admin->groupname.$logintime)) {
			$query = "SELECT* FROM #__session WHERE session_id = " . $database->Quote($session_id) . " AND username = " . $database->Quote($admin->username) . "\n AND userid = " . intval($admin->id);
			$database->setQuery($query);
			if (!$result = $database->query()) {
				echo $database->stderr();
			}

			if ($database->getNumRows($result) == 1) {
				define('_ADMIN_OFFLINE', 1);
			}
		}
	}
}

$config = Jconfig::getInstance();

if (!defined('_ADMIN_OFFLINE') || defined('_INSTALL_CHECK')) {
	// xml prolog
	echo '<?xml version="1.0" encoding="UTF-8"?' . '>';
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $config->config_sitename; ?> - <?php echo _SITE_OFFLINE; ?></title>
        <style type="text/css">
            @import url(<?php echo JPATH_SITE; ?>/administrator/templates/joostfree/css/admin_login.css);
        </style>
        <link rel="stylesheet" href="<?php echo JPATH_SITE; ?>/templates/css/offline.css" type="text/css" />
        <link rel="shortcut icon" href="<?php echo JPATH_SITE ?>/images/favicon.ico" />
        <meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
    </head>
    <body>
        <div id="joo">
            <img src="<?php echo JPATH_SITE;?>/administrator/templates/joostfree/images/logo_130.png" alt="Joostina!" />
        </div>
        <div id="ctr1" align="center">
            <p>&nbsp;</p><p>&nbsp;</p>
            <table width="550" align="center" class="outline">
                <tr>
                    <td align="center">
                        <h1><?php echo $config->config_sitename; ?></h1>
                    </td>
                </tr>
					<?php if ($config->config_offline == 1) { ?>
                <tr>
                    <td width="39%" align="center">
                        <b><?php echo $config->config_offline_message; ?></b>
                    </td>
                </tr>
						<?php } elseif(isset($mosSystemError)) { ?>
                <tr>
                    <td width="39%" align="center">
                        <b><?php echo $config->config_error_message; ?></b>
                        <br />
                        <span class="err"><?php echo defined('_SYSERR' . $mosSystemError) ? constant('_SYSERR' . $mosSystemError) : $mosSystemError; ?></span>
                    </td>
                </tr>
						<?php } else { ?>
                <tr>
                    <td width="39%" align="center"><b><?php echo _INSTALL_WARN; ?></b></td>
                </tr>
						<?php } ?>
            </table>
        </div>
        <div id="break"></div>
        <div id="footer_off" align="center"><div align="center"><?php echo coreVersion::$URL; ?></div></div>
    </body>
</html>
	<?php
	exit(0);
}