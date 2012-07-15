<?php
/**
 * Страница вывода ошибки работы с базой данных
 *
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined( '_JOOS_CORE' ) or exit();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo joosConfig::get2( 'info' , 'title' ); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <META name="robots" content="noindex,nofollow" />
    <link rel="stylesheet" href="<?php echo JPATH_SITE ?>/app/templates/system/media/css/app.css">
</head>
<body>
<h2><?php echo joosConfig::get2( 'info' , 'title' ); ?></h2>
<!-- <?php echo joosFilter::htmlspecialchars($message) ?> -->
</body>
</html>
