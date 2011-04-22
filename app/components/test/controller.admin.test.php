<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Test  - Компонент для тестирования нового функционала
 * Контроллер панели управления
 *
 * @version 1.0
 * @package Joostina.Components.Controllers
 * @subpackage Test     
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminTest {

	public static function index() {

		echo $date = _CURRENT_SERVER_TIME;

		echo '<br />';

		$cache = new joosCache();

		if ($v = $cache->get('asd')) {
			echo $v;
		} else {
			$cache->set('asd', $date);
		}
	}

}