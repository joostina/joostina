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

class mosTabs {

	private $useCookies = 0;
	private static $loaded = false;

	public function mosTabs($useCookies=false, $xhtml = 0) {

		/* запрет повторного включения css и js файлов в документ */
		if (self::$loaded == false) {
			self::$loaded = true;

			$js_file = JPATH_SITE . '/media/js/tabs.js';
			$css_file = JPATH_SITE . '/media/js/tabs/tabpane.css';

			if ($xhtml) {
				Jdocument::getInstance()
						->addJS($js_file)
						->addCSS($css_file);
			} else {
				echo JHTML::css_file($css_file) . "\n\t";
				echo JHTML::js_file($js_file) . "\n\t";
			}
			$this->useCookies = $useCookies;
		}
	}

	public function startPane($id) {
		echo '<div class="tab-page" id="' . $id . '">';
		echo '<script type="text/javascript">var tabPane1 = new WebFXTabPane( document.getElementById( "' . $id . '" ), ' . $this->useCookies . ' )</script>';
	}

	public function endPane() {
		echo '</div>';
	}

	public function startTab($tabText, $paneid) {
		echo '<div class="tab-page" id="' . $paneid . '">';
		echo '<h2 class="tab">' . $tabText . '</h2>';
		echo '<script type="text/javascript">tabPane1.addTabPage( document.getElementById( "' . $paneid . '" ) );</script>';
	}

	public function endTab() {
		echo '</div>';
	}

}