<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

joosDocument::instance()->add_css(JPATH_SITE . '/components/sitemap/media/css/sitemap.css');

?><h1>Карта сайта</h1><?php

foreach ($map->nodes as $space => $nodes) {
	echo '<div class="mapspace space-' . $space . '">';
	echo '<ul>';
	foreach ($nodes as $node) {
		echo '<li>'.$node . '</li>';
	}
	echo '</ul>';
	echo '</div>';
}