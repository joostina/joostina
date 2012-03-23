<?php

/**
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class joosModuleAdmin {

	public static function load_by_name($module_name) {
		$module_file = JPATH_BASE_APP . DS . 'modules' . DS . $module_name . DS . $module_name . '.php';

		if (is_file($module_file)) {
			require_once $module_file;
		} else {
			throw new joosException('Файл :file_name для модуля :module_name не найден',
					array(
						':module_name' => $module_name,
						':file_name' => $module_file
					)
			);
		}
	}

	public static function view($module_name, $template_view = 'default') {
		return JPATH_BASE_APP . DS . 'modules' . DS . $module_name . DS . 'views' . DS . $template_view . '.php';
	}

	public static function render($module_name, array $params = array()) {

		$template_view = isset($params['template']) ? $params['template'] : 'default';

		extract($params, EXTR_OVERWRITE);

		require_once self::view($module_name, $template_view);
	}

}

/**
 * Расширение ядра для работы панели управления
 * @category core
 * @category admin_cp
 */
class joosCoreAdmin extends joosCore {

	/**
	 * Скрытая инстанция текущего авторизованного пользователя
	 *
	 * @var User
	 */
	private static $user = false;

	public static function start() {

		// стартуем сессию с названием из конфигурации
		session_name(JADMIN_SESSION_NAME);
		session_start();

		joosCore::admin();

		// это что бы в админке запоминались фильтры, последние страницы и прочие вкусняшки
		joosSession::init_user_state();
	}

	public static function user() {
		return self::$user;
	}

	public static function init_user() {

		$option = joosRequest::param('option');

		// logout check
		if ($option == 'logout') {
			$database = joosDatabase::instance();

			// обновление записи последнего посещения панели управления в базе данных
			if (isset($_SESSION['session_user_id']) && $_SESSION['session_user_id'] != '') {
				$query = "UPDATE #__users SET lastvisit_date = " . $database->quote(JCURRENT_SERVER_TIME) . " WHERE id = " . (int) $_SESSION['session_user_id'];
				$database->set_query($query)->query();
			}

			// delete db session record corresponding to currently logged in user
			if (isset($_SESSION['session_id']) && $_SESSION['session_id'] != '') {
				$query = "DELETE FROM #__users_session WHERE session_id = " . $database->quote($_SESSION['session_id']);
				$database->set_query($query)->query();
			}

			session_destroy();
			joosRoute::redirect('index.php');
		}

		if (session_name() != JADMIN_SESSION_NAME) {
			joosRoute::redirect(JPATH_SITE_ADMIN, 'Ошибка сессии');
		}

		$my = new modelUsers();
		$my->id = joosRequest::int('session_user_id', 0, $_SESSION);
		$my->user_name = joosRequest::session('session_user_name');
		$my->group_name = joosRequest::session('session_group_name');
		$my->group_id = joosRequest::int('session_group_id', 0, $_SESSION);

		$session_id = joosRequest::session('session_id');
		$logintime = joosRequest::session('session_logintime');

		if ($session_id != session_id()) {
			joosRoute::redirect(JPATH_SITE_ADMIN, __('Вы не авторизованы'));
		}

		// check to see if session id corresponds with correct format
		if ($session_id == md5($my->id . $my->user_name . $my->group_name . $logintime)) {

			$task = joosRequest::param('task');
			if ($task != 'save' && $task != 'apply') {

				$database = joosDatabase::instance();

				$session_life_admin = joosConfig::get2('session', 'life_admin');

				// purge expired admin sessions only
				$past = time() - $session_life_admin;
				$query = "DELETE FROM #__users_session WHERE time < '" . (int) $past . "' AND guest = 1 AND group_id = 0 AND user_id <> 0";
				$database->set_query($query)->query();

				// update session timestamp
				$query = "UPDATE #__users_session SET time = " . $database->quote(time()) . " WHERE session_id = " . $database->quote($session_id);
				$database->set_query($query)->query();

				// set garbage cleaning timeout
				self::set_session_garbage_clean($session_life_admin);

				// check against db record of session
				$query = "SELECT COUNT( session_id ) FROM #__users_session WHERE session_id = " . $database->quote($session_id) . " AND user_name = " . $database->quote($my->user_name) . " AND user_id = " . intval($my->id);
				$count = $database->set_query($query)->load_result();

				// если в таблице нет информации о текущей сессии - она устарела
				if ($count == 0) {
					setcookie(JADMIN_SESSION_NAME);
					// TODO тут можно сделать нормальную запоминалку последней активной страницы, и разных данных с неё. И записывать всё это как параметры пользователя в JSON
					joosRoute::redirect(JPATH_SITE_ADMIN, __('Вы не авторизованы'));
				}
			}
		} elseif ($session_id == '') {
			joosRoute::redirect(JPATH_SITE, __('Вы не авторизованы'));
		} else {
			joosRoute::redirect(JPATH_SITE, __('Вы не авторизованы'));
			exit();
		}

		self::$user = $my;
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
 * Постраничная навигация для панели управления
 * @category core
 * @category admin_cp
 * @package  Joostina
 */
class joosAdminPagenator {

	public $limitstart;
	public $limit;
	public $total;

	function joosAdminPagenator($total, $limitstart, $limit) {
		$this->total = (int) $total;
		$this->limitstart = (int) max($limitstart, 0);
		$this->limit = (int) max($limit, 1);
		if ($this->limit > $this->total) {
			$this->limitstart = 0;
		}
		if (( $this->limit - 1 ) * $this->limitstart > $this->total) {
			$this->limitstart -= $this->limitstart % $this->limit;
		}
	}

	function get_limit_box() {

		// если элементов нет - то и селектор-ограничитель показывать незачем
		if ($this->total == 0) {
			return '';
		}

		$limits = array();
		for ($i = 5; $i <= 30; $i += 5) {
			$limits[] = joosHtml::make_option("$i");
		}

		$limits[] = joosHtml::make_option('50');
		$limits[] = joosHtml::make_option('100');
		$limits[] = joosHtml::make_option('150');
		$limits[] = joosHtml::make_option('50000', __('-Всё-'));
		// build the html select list
		$html = ' ' . __('Отображать') . ' ';
		$html .= joosHtml::selectList($limits, 'limit', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->limit);
		$html .= "\n<input type=\"hidden\" name=\"limitstart\" value=\"$this->limitstart\" />";
		return $html;
	}

	function write_limit_box() {
		echo joosAdminPagenator::get_limit_box();
	}

	function write_pages_counter() {
		echo $this->get_pages_counter();
	}

	function get_pages_counter() {
		$html = '';
		$from_result = $this->limitstart + 1;
		if ($this->limitstart + $this->limit < $this->total) {
			$to_result = $this->limitstart + $this->limit;
		} else {
			$to_result = $this->total;
		}
		if ($this->total > 0) {
			$html .= "\n" . __('Показано') . " " . $from_result . " - " . $to_result . " " . __('из') . " " . $this->total;
		} else {
			$html .= "\n" . __('Записи не найдены');
		}
		return '' . $html;
	}

	function write_pages_links() {
		echo $this->get_pages_links();
	}

	function get_pages_links() {

		$total_pages = ceil($this->total / $this->limit);
		// скрываем навигатор по страницам если их меньше 2х.
		if ($total_pages < 2) {
			return '';
		}

		$html = '';
		$displayed_pages = 10;

		$this_page = ceil(( $this->limitstart + 1 ) / $this->limit);
		$start_loop = ( floor(( $this_page - 1 ) / $displayed_pages) ) * $displayed_pages + 1;
		if ($start_loop + $displayed_pages - 1 < $total_pages) {
			$stop_loop = $start_loop + $displayed_pages - 1;
		} else {
			$stop_loop = $total_pages;
		}

		if ($this_page > 1) {
			$page = ( $this_page - 2 ) * $this->limit;

			$html .= "\n<a href=\"#prev\" id=\"pagenav_prev\" class=\"pagenav\" onclick=\"javascript: document.adminForm.limitstart.value=$page; document.adminForm.submit();return false;\">&larr;&nbsp;" . __('Предыдущая') . "</a>";
		} else {

			$html .= "\n<span  id=\"pagenav_prev\" class=\"pagenav\">&larr;&nbsp;" . __('Предыдущая') . "</span>";
		}

		for ($i = $start_loop; $i <= $stop_loop; $i++) {
			$page = ( $i - 1 ) * $this->limit;
			if ($i == $this_page) {
				$html .= "\n<span id=\"pagenav_current\" class=\"pagenav\"> $i </span>";
			} else {
				$html .= "\n<a href=\"#$i\" class=\"pagenav\" onclick=\"javascript: document.adminForm.limitstart.value=$page; document.adminForm.submit();return false;\">$i</a>";
			}
		}

		if ($this_page < $total_pages) {
			$page = $this_page * $this->limit;
			//$end_page = ($total_pages - 1) * $this->limit;
			$html .= "\n<a href=\"#next\"  id=\"pagenav_next\" class=\"pagenav\" onclick=\"javascript: document.adminForm.limitstart.value=$page; document.adminForm.submit();return false;\"> " . __('Следующая') . "&nbsp;&rarr;</a>";
		} else {
			$html .= "\n<span id=\"pagenav_next\"  class=\"pagenav\">" . __('Следующая') . "&nbsp;&rarr;</span>";
		}
		return $html;
	}

	function get_list_footer() {
		$html = '<div class="adminpaginator">';
		$html .= '<div class="adminpaginator_pages_counter"><span class="ap-pagescount">' . $this->get_pages_counter() . '</span>' . $this->get_limit_box() . '</div><div class="ap-pages">' . $this->get_pages_links() . '</div>';
		$html .= '</div>';
		return $html;
	}

	function row_number($i) {
		return $i + 1 + $this->limitstart;
	}

	function order_up_icon($i, $condition = true, $task = 'orderup', $alt = _PN_MOVE_TOP) {
		if (( $i > 0 || ( $i + $this->limitstart > 0 ) ) && $condition) {
			return '<a href="#reorder" onClick="return listItemTask(\'cb' . $i . '\',\'' . $task . '\')" title="' . $alt . '"><img src="' . joosConfig::get('admin_icons_path') . 'uparrow.png" width="12" height="12" border="0" alt="' . $alt . '" /></a>';
		} else {
			return '&nbsp;';
		}
	}

	function order_down_icon($i, $n, $condition = true, $task = 'orderdown', $alt = _PN_MOVE_DOWN) {
		if (( $i < $n - 1 || $i + $this->limitstart < $this->total - 1 ) && $condition) {
			return '<a href="#reorder" onClick="return listItemTask(\'cb' . $i . '\',\'' . $task . '\')" title="' . $alt . '"><img src="' . joosConfig::get('admin_icons_path') . 'downarrow.png" width="12" height="12" border="0" alt="' . $alt . '" /></a>';
		} else {
			return '&nbsp;';
		}
	}

	function order_up_icon2($id, $order) {
		if ($order == 0) {
			$img = 'uparrow.png';
			$show = true;
		} else if ($order < 0) {
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

	function order_down_icon2($id, $order) {

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


class joosAdminController extends joosController{

}

class joosAdminControllerAjax extends joosAdminController{

}