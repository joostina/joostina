<?php

/**
 * Pages - компонент независимых страниц
 * Аякс-контроллер
 *
 * @version 1.0
 * @package Components
 * @subpackage Pages
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 *
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class actionsAjaxPages {

	public static function statuschanger() {
		return joosAutoAdmin::autoajax();
	}

}