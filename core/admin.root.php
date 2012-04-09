<?php defined('_JOOS_CORE') or die();

/**
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */


class joosModuleAdmin {

	public static function load_by_name($module_name) {
		$module_file = JPATH_BASE_APP . DS . 'modules' . DS . $module_name . DS . $module_name . '.php';

		if ( joosFile::exists($module_file)) {
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

		joosCore::set_admin_mode();

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

		$session_id = joosRequest::session('session_id');
		$logintime = joosRequest::session('session_logintime');

		if ($session_id != session_id()) {
			joosRoute::redirect(JPATH_SITE_ADMIN, __('Вы не авторизованы'));
		}

		// check to see if session id corresponds with correct format
		if ($session_id == md5($my->id . $my->user_name . $logintime)) {

			$task = joosRequest::param('task');
			if ($task != 'save' && $task != 'apply') {

				$database = joosDatabase::instance();

				$session_life_admin = joosConfig::get2('session', 'life_admin');

				// purge expired admin sessions only
				$past = time() - $session_life_admin;
				$query = "DELETE FROM #__users_session WHERE time < '" . (int) $past . "' AND guest = 1 AND user_id <> 0";
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
			ini_set('session.gc_maxlifetime', $garbage_timeout);
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
		$html .= joosHtml::selectList($limits, 'limit', 'class="js-limit" size="1"', 'value', 'text', $this->limit);
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
			$html .= "\n" . $from_result . "-" . $to_result . " " . __('из') . " " . $this->total;
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

		$html = '<ul>';
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

			$html .= "<li><a href=\"#prev\" class=\"js-pagenav\" data-page=\"$page\">&larr;</a></li>";
		} else {

			$html .= "<li class=\"disabled\"><a href=\"#\"  class=\"pagenav\">&larr;</a></li>";
		}

		for ($i = $start_loop; $i <= $stop_loop; $i++) {
			$page = ( $i - 1 ) * $this->limit;
			if ($i == $this_page) {
				$html .= "<li class=\"active\"><a href=\"#\" class=\"pagenav\"> $i </a></li>";
			} else {
				$html .= "<li><a href=\"#$i\" class=\"js-pagenav\"  data-page=\"$page\">$i</a></li>";
			}
		}

		if ($this_page < $total_pages) {
			$page = $this_page * $this->limit;
			//$end_page = ($total_pages - 1) * $this->limit;
			$html .= "<li><a href=\"#next\"  class=\"js-pagenav\"  data-page=\"$page\">&rarr;</a></li>";
		} else {
			$html .= "<li class=\"disabled\"><a href=\"#\" class=\"pagenav\">&rarr;</a></li>";
		}
		return $html.'</ul>';
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

/**
 * Базовый контроллер работы панели управления Joostina CMS
 * @package    Joostina
 * @subpackage Controller
 *
 * //  extends joosController
 * 
 */
class joosAdminController{

    /**
     * Пункты подменю компеонента
     * 
     * @var array
     */
    protected static $submenu = array();
    
    /**
     * Текущий активный пункт меню
     * 
     * @var string
     */
    protected static $active_menu = 'default';


    public static function get_submenu(){

        return static::$submenu;
    }

    public static function action_before() {
        
        $menu = joosRequest::request('menu', false);

        if ($menu && isset(static::$submenu[$menu])) {

            static::$active_menu = $menu;
        } else {
            
            $menu = static::$active_menu;
        }

        static::$submenu[$menu]['active'] = true;

        if (isset(static::$submenu[$menu]['model'])) {
            joosAutoadmin::set_active_model_name(static::$submenu[$menu]['model']);
        }

        joosAutoadmin::set_active_menu_name($menu);
    }

    public static function index() {

        $obj = joosAutoadmin::get_active_model_obj();
        $obj_count = joosAutoadmin::get_count($obj);

        $pagenav = joosAutoadmin::pagenav($obj_count);

        $param = array(
            'offset' => $pagenav->limitstart,
            'limit' => $pagenav->limit,
            'order' => 'id DESC'
        );
        $obj_list = joosAutoadmin::get_list($obj, $param);

        $fields_list= isset(static::$submenu[static::$active_menu]['fields'])
            ? static::$submenu[static::$active_menu]['fields']
            : array('id', 'title', 'state');
        
        // передаём информацию о объекте и настройки полей в формирование представления
        joosAutoadmin::listing($obj, $obj_list, $pagenav, $fields_list);
    }

    public static function create() {
        static::edit();
    }

    public static function edit() {
        
        $id = joosRequest::get('id',0);
        
        $obj_data = joosAutoadmin::get_active_model_obj();
        $id > 0 ? $obj_data->load($id) : null;

        joosAutoadmin::edit($obj_data, $obj_data);
    }

    public static function save( $redirect = 0 ) {

        joosCSRF::check_code();

        $obj_data = joosAutoadmin::get_active_model_obj();
        $obj_data->save($_POST);

        $option = joosRequest::param('option');
        
        switch ($redirect) {
            default:
            case 0: // просто сохранение
                joosRoute::redirect('index2.php?option=' . $option . '&menu=' . static::$active_menu, 'Всё ок!');
                break;

            case 1: // применить
                joosRoute::redirect('index2.php?option=' . $option . '&menu=' . static::$active_menu . '&task=edit&id=' . $obj_data->id, 'Всё ок, редактируем дальше');
                break;

            case 2: // сохранить и добавить новое
                joosRoute::redirect('index2.php?option=' . $option . '&menu=' . static::$active_menu . '&task=create', 'Всё ок, создаём новое');
                break;
        }
    }

    public static function apply() {
        
        return static::save(1);
    }

    public static function save_and_new() {
        
        return static::save(2);
    }

    public static function remove() {
        
        joosCSRF::check_code();

        // идентификаторы удаляемых объектов
        $cid = (array) joosRequest::array_param('cid');
        $option = joosRequest::param('option');
        
        $obj_data =  joosAutoadmin::get_active_model_obj();
        $obj_data->delete_array($cid, 'id') ? joosRoute::redirect('index2.php?option=' . $option . '&menu=' . static::$active_menu, 'Удалено успешно!') : joosRoute::redirect('index2.php?option=' . $option . '&menu=' . static::$active_menu, 'Ошибка удаления');
    }

    public static function publish(){
        self::publish_unpublish(1);
    }

    public static function unpublish(){
        self::publish_unpublish(0);
    }

    /**
     * Смена статуса (поля 'state')
     */
    public static function publish_unpublish($state = 1){

        joosCSRF::check_code();

        $cid = (array) joosRequest::array_param('cid');
        $option = joosRequest::param('option');

        $obj_data =  joosAutoadmin::get_active_model_obj();
        $obj_data->set_state_group($cid, $state)
                ? joosRoute::redirect('index2.php?option=' . $option . '&menu=' . static::$active_menu, 'Выполнено успешно')
                : joosRoute::redirect('index2.php?option=' . $option . '&menu=' . static::$active_menu, 'Ошибка смены статуса');
    }
    
}

/**
 * Базовый ajax контроллер работы панели управления Joostina CMS
 * @package    Joostina
 * @subpackage Controller
 *
 */
class joosAdminControllerAjax extends joosAdminController{

    /**
     * Смена статуса (поля 'state')
     */
    public static function set_state(){

        $obj_id = joosRequest::int('obj_id', 0, $_POST);
     	$obj_state = joosRequest::post('obj_state');
        $obj_model = joosRequest::post('obj_model');

        if (!$obj_model || !class_exists($obj_model)) {
            return array('type' => 'error');
        }

        $new_state = ($obj_state == 1 ? 0 : 1);

        $obj = new $obj_model;
        $obj->load($obj_id);

        if(! $obj->change_state('state')){
            return array('type' => 'error');
        }

        return array(
            'type' => 'success',
            'message' => 'Статус изменён',
            'new_state' => $new_state,
            'new_title' => $new_state == 1 ? 'Активно' : 'Не активно',
            'new_class' => $new_state == 1 ? 'icon-ok' : 'icon-remove'
        );

    }

    /**
     * Загрузка изображений для текстов материалов (через визуальный редактор)
     * Грузятся в /attachments/images_embedded
    */
    public static function upload_images_embedded(){
        joosLoader::lib('upload', 'upload');
        $upload_result = joosUpload::easy_upload(
            'file',
            JPATH_BASE . '/app/attachments/images_embedded/',
            array('new_name' => date('YmdHis'))
        );
        echo '<img src="'.$upload_result['file'].'" />';
    }

    /**
     * Загрузка файлов для текстов материалов (через визуальный редактор)
     * Грузятся в /attachments/files_embedded
    */
    public static function upload_files_embedded(){
        joosLoader::lib('upload', 'upload');
        $upload_result = joosUpload::easy_upload(
            'file',
            JPATH_BASE . '/app/attachments/files_embedded/',
            array('new_name' => date('YmdHis'))
        );
        echo '<a href="'.$upload_result['file'].'" class="redactor_file_link redactor_file_ico_'.$upload_result['file_info']['ext'].'">'.$upload_result['name'].'</a>';
    }

    /**
     * Смена статуса публикации объекта
     * 
     * @static
     * @return bool
     */
    public static function status_change() {
        return joosAutoadmin::autoajax();
    }
    
}

class joosAdminView {
    
    private static $component_params = array(
        'component_title'=>'',
        'submenu'=>array(),
        'component_header'=>'',
        'current_model'=>''
    );

    private static $listing_elements = array(
        'table_headers' => ''
    );

    public static function  set_param($name, $value){
        self::$component_params[$name] = $value;
    }

    public static function  set_listing_param($name, $value){
        self::$listing_elements[$name] = $value;
    }
    
    public static function get_component_title(){
        return self::$component_params['component_title'];
    }

    public static function get_component_header(){
        return self::$component_params['component_header'];
    }
    
    public static function get_submenu(){

        $options = joosAutoadmin::get_option();
        
        foreach (self::$component_params['submenu'] as $menu_name => &$href) {
            $href['href'] = isset( $href['href'] ) ? $href['href'] : sprintf('index2.php?option=%s&menu=%s',$options,$menu_name);
        }
        
        return self::$component_params['submenu'];
    }

    public static function get_current_model(){
        return self::$component_params['current_model'];
    }

    public static function get_listing_param($name){
        return self::$listing_elements[$name];
    }

}

class joosAdminViewToolbarListing{
    
}


class joosAdminViewToolbarEdit{

}