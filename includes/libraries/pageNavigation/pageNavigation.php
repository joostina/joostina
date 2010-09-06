<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или LICENSE.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл COPYRIGHT.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

class mosPageNav {

	private $limitstart;
	private $limit;
	private $total;

	public function mosPageNav($total,$limitstart,$limit) {
		$this->total     = (int)$total;
		$this->limitstart = (int) max($limitstart,0);
		$this->limit     = (int) max($limit,0);
	}

	public function getLimitBox($link) {
		$limits = array();
		for($i = 5; $i <= 30; $i += 5) {
			$limits[] = mosHTML::makeOption($i);
		}
		$limits[] = mosHTML::makeOption('50');
		$limits[] = mosHTML::makeOption('100');
		$limits[] = mosHTML::makeOption('150');
		$limits[] = mosHTML::makeOption('5000',_PN_ALL);

		$link = $link."&amp;limit=' + this.options[selectedIndex].value + '&amp;limitstart=".$this->limitstart;
		$link = sefRelToAbs($link);

		return mosHTML::selectList($limits,'limit','class="inputbox" size="1" onchange="document.location.href=\''.$link.'\';"','value','text',$this->limit);
	}

	public function writeLimitBox($link) {
		echo mosPageNav::getLimitBox($link);
	}

	public function writePagesCounter() {
		$txt = '';
		$from_result = $this->limitstart + 1;
		if($this->limitstart + $this->limit < $this->total) {
			$to_result = $this->limitstart + $this->limit;
		} else {
			$to_result = $this->total;
		}
		if($this->total > 0) {
			$txt .= _PN_RESULTS." $from_result - $to_result "._PN_OF." $this->total";
		}
		return $to_result ? $txt : '';
	}

	public function writeLeafsCounter() {
		$txt = '';
		$page = ceil(($this->limitstart + 1) / $this->limit);
		if($this->total > 0) {
			$total_pages = ceil($this->total / $this->limit);
			$txt .= _PN_PAGE." $page "._PN_OF." $total_pages";
		}
		return $txt;
	}

	public function writePagesLinks($link) {

		$txt = '<div class="pagenavigation"><ul>';

		$displayed_pages = 10;
		$total_pages = $this->limit?ceil($this->total / $this->limit):0;

		// скрываем навигатор по страницам если их меньше 2х.
		if($total_pages<2) return;

		$this_page = $this->limit?ceil(($this->limitstart + 1) / $this->limit):1;
		$start_loop = (floor(($this_page - 1) / $displayed_pages))* $displayed_pages +1;
		if($start_loop + $displayed_pages - 1 < $total_pages) {
			$stop_loop = $start_loop + $displayed_pages - 1;
		} else {
			$stop_loop = $total_pages;
		}

		$link .= '&amp;limit='.$this->limit;

		if(!defined('_PN_LT') || !defined('_PN_RT')) {
			DEFINE('_PN_LT','&larr;');
			DEFINE('_PN_RT','&rarr;');
		}

		$pnSpace = '';
		if(_PN_LT || _PN_RT) $pnSpace = "&nbsp;";

		if($this_page > 1) {

			$page = ($this_page - 2)* $this->limit;

			$txt .= '<li class="first_page"><a href="'.sefRelToAbs("$link&amp;limitstart=0").'" class="pagenav" title="'._PN_START.'">'._PN_START.'</a></li> ';
			$txt .= '<li class="back"><a href="'.sefRelToAbs("$link&amp;limitstart=$page").'" class="pagenav" title="'._PN_PREVIOUS.'">'._PN_LT.$pnSpace._PN_PREVIOUS.'</a></li> ';
		} else {
			$txt .= '<li class="first_page"><span class="pagenav">'._PN_START.'</span></li> ';
			$txt .= '<li class="back"><span class="pagenav">'._PN_LT.$pnSpace._PN_PREVIOUS.'</span></li> ';
		}

		for($i = $start_loop; $i <= $stop_loop; $i++) {
			$page = ($i - 1)* $this->limit;
			if($i == $this_page) {
				$txt .= '<li><span class="pagenav">'.$i.'</span></li>';
			} else {
				$txt .= '<li><a href="'.sefRelToAbs($link.'&amp;limitstart='.$page).'" class="pagenav"><strong>'.$i.'</strong></a></li>';
			}
		}

		if($this_page < $total_pages) {

			$page = $this_page* $this->limit;
			$end_page = ($total_pages - 1)* $this->limit;

			$txt .= '<li class="next"><a href="'.sefRelToAbs($link.'&amp;limitstart='.$page).' " class="pagenav" title="'._PN_NEXT.'">'._PN_NEXT.$pnSpace._PN_RT.'</a> </li>';
			$txt .= '<li class="last_page"><a href="'.sefRelToAbs($link.'&amp;limitstart='.$end_page).' " class="pagenav" title="'._PN_END.'">'._PN_END.'</a></li>';
		} else {
			$txt .= '<li class="next"><span class="pagenav">'._PN_NEXT.$pnSpace._PN_RT.'</span></li> ';
			$txt .= '<li class="last_page"><span class="pagenav">'._PN_END.$pnSpace._PN_RT.'</span></li>';
		}
		return $txt.'</ul></div>';
	}
}