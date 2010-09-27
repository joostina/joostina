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

class jqueryTabs {
	private $id;
	private $counter;
	private $tabs_headers = array();
	private $tabs_body = array();

	public function  __construct(){
		mosCommonHTML::loadJqueryUI();
		mosCommonHTML::loadJqueryUICSS();

		$this->counter = 0;
	}

	public function startPane($id) {
		$this->id = $id;
		echo '<div class="tab-page" id="'.$id.'">';
	}

	public function endPane() {
		echo '<ul><li>';
		echo implode('</li><li>', $this->tabs_headers);
		echo '</li></ul>';
		echo implode('', $this->tabs_body);
		echo '</div>';
		?><script type="text/javascript">$(function() { $("#<?php echo $this->id ?>").tabs(); });</script><?php
	}

	public function startTab($tabText,$paneid) {
		++$this->counter;
		$this->tabs_headers[$this->counter] = $tabText;
		ob_start();
		echo '<div class="tab-page" id="tabs-'.$this->counter.'">';
	}

	public function endTab() {
		echo '</div>';
		$this->tabs_body[$this->counter] = ob_get_contents();
		ob_end_clean();
	}
}