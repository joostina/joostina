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

//joosMainframe::addClass('mosAdminMenus');
joosLoader::lib('html');

//Грузим модули для админки
joosModule::modules_for_backend();

function admin_body() {
	echo $GLOBALS['_MOS_OPTION']['buffer'];
}

/* вывод информационного поля */

function joost_info($msg) {
	return '<div class="message">' . $msg . '</div>';
}

function ajax_acl_error() {
	echo json_encode(array('error' => 'acl'));
}

class joosCoreAdmin {

	public static function init_session_admin($option, $task) {

		// logout check
		if ($option == 'logout') {
			$database = database::instance();

			// обновление записи последнего посещения панели управления в базе данных
			if (isset($_SESSION['session_user_id']) && $_SESSION['session_user_id'] != '') {
				$query = "UPDATE #__users SET lastvisitDate = " . $database->quote(_CURRENT_SERVER_TIME) . " WHERE id = " . (int) $_SESSION['session_user_id'];
				$database->set_query($query)->query();
			}

			// delete db session record corresponding to currently logged in user
			if (isset($_SESSION['session_id']) && $_SESSION['session_id'] != '') {
				$query = "DELETE FROM #__session WHERE session_id = " . $database->quote($_SESSION['session_id']);
				$database->set_query($query)->query();
			}

			// destroy PHP session
			session_destroy();

			// return to site homepage
			mosRedirect('index.php');
			exit();
		}

		// check if session name corresponds to correct format
		if (session_name() != md5(JPATH_SITE)) {
			mosRedirect(JPATH_SITE . '/' . JADMIN_BASE . '/');
			exit();
		}

		// restore some session variables
		$my = new User();
		$my->id = joosRequest::int('session_user_id', 0, $_SESSION);
		$my->username = joosRequest::session('session_USER');
		$my->groupname = joosRequest::session('session_groupname');
		$my->gid = joosRequest::int('session_gid', 0, $_SESSION);
		$my->params = joosRequest::session('session_user_params');
		$my->bad_auth_count = joosRequest::int('session_user_params', 0, $_SESSION);

		$session_id = joosRequest::session('session_id');
		$logintime = joosRequest::session('session_logintime');

		if ($session_id != session_id()) {
			// session id does not correspond to required session format
			mosRedirect(JPATH_SITE . '/' . JADMIN_BASE . '/', _YOU_NEED_TO_AUTH);
		}

		// check to see if session id corresponds with correct format
		if ($session_id == md5($my->id . $my->username . $my->groupname . $logintime)) {

			// if task action is to `save` or `apply` complete action before doing session checks.
			if ($task != 'save' && $task != 'apply') {

				$database = database::instance();

				$session_life_admin = joosConfig::get2('session', 'life_admin');

				// purge expired admin sessions only
				$past = time() - $session_life_admin;
				$query = "DELETE FROM #__session WHERE time < '" . (int) $past . "' AND guest = 1 AND gid = 0 AND userid <> 0";
				$database->set_query($query)->query();

				// update session timestamp
				$query = "UPDATE #__session SET time = " . $database->quote(time()) . " WHERE session_id = " . $database->quote($session_id);
				$database->set_query($query)->query();

				// set garbage cleaning timeout
				self::set_session_garbage_clean($session_life_admin);

				// check against db record of session
				$query = "SELECT COUNT( session_id ) FROM #__session WHERE session_id = " . $database->quote($session_id) . " AND username = " . $database->quote($my->username) . " AND userid = " . intval($my->id);
				$count = $database->set_query($query)->load_result();

				// если в таблице нет информации о текущей сессии - она устарела
				if ($count == 0) {
					setcookie(md5(JPATH_SITE));
					// TODO тут можно сделать нормальную запоминалку последней активной страницы, и разных данных с неё. И записывать всё это как параметры пользователя в JSON
					mosRedirect(JPATH_SITE . '/' . JADMIN_BASE . '/', _ADMIN_SESSION_ENDED);
				} else {
					// load variables into session, used to help secure /popups/ functionality
					$_SESSION['option'] = $option;
					$_SESSION['task'] = $task;
				}
			}
		} elseif ($session_id == '') {
			// no session_id as user has not attempted to login, or session.auto_start is switched on
			if (ini_get('session.auto_start') || !ini_get('session.use_cookies')) {
				mosRedirect(JPATH_SITE, _YOU_NEED_TO_AUTH_AND_FIX_PHP_INI);
			} else {
				mosRedirect(JPATH_SITE, _YOU_NEED_TO_AUTH);
			}
		} else {
			mosRedirect(JPATH_SITE, _WRONG_USER_SESSION);
			exit();
		}

		return $my;
	}

	public static function set_session_garbage_clean($session_life_admin) {
		if (!defined('_JOS_GARBAGECLEAN')) {
			define('_JOS_GARBAGECLEAN', 1);

			$garbage_timeout = $session_life_admin + 600;
			@ini_set('session.gc_maxlifetime', $garbage_timeout);
		}
	}

}

/**
 * Page navigation support class
 * @package Joostina
 */
class mosPageNav {

	public $limitstart;
	public $limit;
	public $total;

	function mosPageNav($total, $limitstart, $limit) {
		$this->total = (int) $total;
		$this->limitstart = (int) max($limitstart, 0);
		$this->limit = (int) max($limit, 1);
		if ($this->limit > $this->total) {
			$this->limitstart = 0;
		}
		if (($this->limit - 1) * $this->limitstart > $this->total) {
			$this->limitstart -= $this->limitstart % $this->limit;
		}
	}

	function getLimitBox() {

		// если элементов нет - то и селектор-ограничитель показывать незачем
		if ($this->total == 0) {
			return '';
		}

		$limits = array();
		for ($i = 5; $i <= 30; $i += 5) {
			$limits[] = html::make_option("$i");
		}
		$limits[] = html::make_option('50');
		$limits[] = html::make_option('100');
		$limits[] = html::make_option('150');
		$limits[] = html::make_option('5000', _PN_ALL);
		// build the html select list
		$html = ' ' . _PN_DISPLAY_NR . ' ';
		$html .= html::selectList($limits, 'limit', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->limit);
		$html .= "\n<input type=\"hidden\" name=\"limitstart\" value=\"$this->limitstart\" />";
		return $html;
	}

	function writeLimitBox() {
		echo mosPageNav::getLimitBox();
	}

	function writePagesCounter() {
		echo $this->getPagesCounter();
	}

	function getPagesCounter() {
		$html = '';
		$from_result = $this->limitstart + 1;
		if ($this->limitstart + $this->limit < $this->total) {
			$to_result = $this->limitstart + $this->limit;
		} else {
			$to_result = $this->total;
		}
		if ($this->total > 0) {
			$html .= "\n" . _NAV_SHOW . " " . $from_result . " - " . $to_result . " " . _NAV_SHOW_FROM . " " . $this->total;
		} else {
			$html .= "\n" . _NO_ITEMS;
		}
		return '' . $html;
	}

	function writePagesLinks() {
		echo $this->getPagesLinks();
	}

	function getPagesLinks() {
		$html = '';
		$displayed_pages = 10;
		$total_pages = ceil($this->total / $this->limit);
		// скрываем навигатор по страницам если их меньше 2х.
		if ($total_pages < 2)
			return;
		$this_page = ceil(($this->limitstart + 1) / $this->limit);
		$start_loop = (floor(($this_page - 1) / $displayed_pages)) * $displayed_pages + 1;
		if ($start_loop + $displayed_pages - 1 < $total_pages) {
			$stop_loop = $start_loop + $displayed_pages - 1;
		} else {
			$stop_loop = $total_pages;
		}

		if ($this_page > 1) {
			$page = ($this_page - 2) * $this->limit;

			$html .= "\n<a href=\"#prev\" id=\"pagenav_prev\" class=\"pagenav\" onclick=\"javascript: document.adminForm.limitstart.value=$page; document.adminForm.submit();return false;\">&larr;&nbsp;" . _PN_PREVIOUS . "</a>";
		} else {

			$html .= "\n<span  id=\"pagenav_prev\" class=\"pagenav\">&larr;&nbsp;" . _PN_PREVIOUS . "</span>";
		}

		for ($i = $start_loop; $i <= $stop_loop; $i++) {
			$page = ($i - 1) * $this->limit;
			if ($i == $this_page) {
				$html .= "\n<span id=\"pagenav_current\" class=\"pagenav\"> $i </span>";
			} else {
				$html .= "\n<a href=\"#$i\" class=\"pagenav\" onclick=\"javascript: document.adminForm.limitstart.value=$page; document.adminForm.submit();return false;\">$i</a>";
			}
		}

		if ($this_page < $total_pages) {
			$page = $this_page * $this->limit;
			$end_page = ($total_pages - 1) * $this->limit;
			$html .= "\n<a href=\"#next\"  id=\"pagenav_next\" class=\"pagenav\" onclick=\"javascript: document.adminForm.limitstart.value=$page; document.adminForm.submit();return false;\"> " . _PN_NEXT . "&nbsp;&rarr;</a>";
		} else {
			$html .= "\n<span id=\"pagenav_next\"  class=\"pagenav\">" . _PN_NEXT . "&nbsp;&rarr;</span>";
		}
		return $html;
	}

	function getListFooter() {
		$html = '<div class="adminpaginator">';
		$html .= '<div class="adminpaginator_pages_counter"><span class="ap-pagescount">' . $this->getPagesCounter() . '</span>' . $this->getLimitBox() . '</div><div class="ap-pages">' . $this->getPagesLinks() . '</div>';
		$html .= '</div>';
		return $html;
	}

	function rowNumber($i) {
		return $i + 1 + $this->limitstart;
	}

	function orderUpIcon($i, $condition = true, $task = 'orderup', $alt = _PN_MOVE_TOP) {
		if (($i > 0 || ($i + $this->limitstart > 0)) && $condition) {
			return '<a href="#reorder" onClick="return listItemTask(\'cb' . $i . '\',\'' . $task . '\')" title="' . $alt . '"><img src="' . joosConfig::get('admin_icons_path') . 'uparrow.png" width="12" height="12" border="0" alt="' . $alt . '" /></a>';
		} else {
			return '&nbsp;';
		}
	}

	function orderDownIcon($i, $n, $condition = true, $task = 'orderdown', $alt =_PN_MOVE_DOWN) {
		if (($i < $n - 1 || $i + $this->limitstart < $this->total - 1) && $condition) {
			return '<a href="#reorder" onClick="return listItemTask(\'cb' . $i . '\',\'' . $task . '\')" title="' . $alt . '"><img src="' . joosConfig::get('admin_icons_path') . 'downarrow.png" width="12" height="12" border="0" alt="' . $alt . '" /></a>';
		} else {
			return '&nbsp;';
		}
	}

	function orderUpIcon2($id, $order) {
		if ($order == 0) {
			$img = 'uparrow.png';
			$show = true;
		} else
		if ($order < 0) {
			$img = 'uparrow.png';
			$show = true;
		} else {
			$img = 'uparrow.png';
			$show = true;
		}

		if ($show) {
			$output = '<a href="#ordering" onClick="listItemTask(\'cb' . $id . '\',\'orderup\')" title="' . _NAV_ORDER_UP . '">';
			$output .= '<img src="' . joosConfig::get('admin_icons_path') . $img . '" width="12" height="12" border="0" alt="' . _NAV_ORDER_UP . '" title="' . _NAV_ORDER_UP . '" /></a>';

			return $output;
		} else {
			return '&nbsp;';
		}
	}

	function orderDownIcon2($id, $order) {

		if ($order == 0) {
			$img = 'downarrow.png';
			$show = true;
		} elseif ($order < 0) {
			$img = 'downarrow.png';
			$show = true;
		} else {
			$img = 'downarrow.png';
			$show = true;
		}

		if ($show) {
			$output = '<a href="#ordering" onClick="listItemTask(\'cb' . $id . '\',\'orderdown\')" title="' . _NAV_ORDER_DOWN . '">';
			$output .= '<img src="' . joosConfig::get('admin_icons_path') . $img . '" width="12" height="12" border="0" alt="' . _NAV_ORDER_DOWN . '" title="' . _NAV_ORDER_DOWN . '" /></a>';

			return $output;
		} else {
			return '&nbsp;';
		}
	}

}