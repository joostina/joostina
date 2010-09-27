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
/*
 * Компонент - главная страница
 * Позволяет использовать 2 типа страниц:
 * 1. Компонент - выбирается любой доступный компонент системы и настройки для него
 * 2. Страница модулей - позволяет расположить конструкцию составленную из произвольных модулей по специально подготовленному макету
 */

class actionsMainpage extends Jcontroller {

	public static function index() {
		require_once 'views/modules/view_0.php';
	}

}