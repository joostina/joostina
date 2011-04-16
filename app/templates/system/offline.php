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

// xml prolog
echo '<?xml version="1.0" encoding="UTF-8"?' . '>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo _SITE_OFFLINE; ?></title>
    <style type="text/css">
        @import url(<?php echo JPATH_SITE; ?>/administrator/templates/joostfree/css/admin_login.css);
    </style>
    <link rel="stylesheet" href="<?php echo JPATH_SITE; ?>/templates/css/offline.css" type="text/css"/>
    <link rel="shortcut icon" href="<?php echo JPATH_SITE ?>/images/favicon.ico"/>
    <meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>"/>
</head>
<body>
<div id="joo">
    <img src="<?php echo JPATH_SITE; ?>/administrator/templates/joostfree/images/logo_130.png" alt="Joostina!"/>
</div>
<div id="ctr1" align="center">
    <p>&nbsp;</p>

    <p>&nbsp;</p>

    <h1>Что - то явно не так</h1>
</div>
<div id="break"></div>
<div id="footer_off" align="center">
    <div align="center"><?php echo joosVersion::$URL; ?></div>
</div>
</body>
</html>
<?php
exit(0);