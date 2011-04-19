<?php

/**
 * Ядро
 *
 * @package Joostina.Core
 * @author JoostinaTeam
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version SVN: $Id: joostina.php 238 2011-03-13 13:24:57Z LeeHarvey $
 * Иинформация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

// Обработчик ошибок
require JPATH_BASE . DS . 'core' . DS . 'exception.php';
// Автозагрузчик
require JPATH_BASE . DS . 'core' . DS . 'libraries' . DS . 'autoloader.php';
joosAutoloader::init();
// предстартовые конфигурации
require JPATH_BASE . DS . 'app' . DS . 'bootstrap.php';
// ядро
require JPATH_BASE . DS . 'core' . DS . 'extraroute.php';

// роутер
require_once (JPATH_BASE . DS . 'app' . DS . 'route.php');


/* работа с пользователями  */
joosLoader::admin_model('modules');

/**
 * Корневой клас ядра Joostina!
 * @package Joostina
 */
class joosMainframe {

	private static $instance;
	/**
	 *  * @var object An object of path variables */
	private $_path;
	public static $is_admin = false;

	/**
	 * Инициализация ядра
	 * @param boolen $is_admin - инициализация в пространстве панели управления
	 */
	protected function __construct($is_admin = false) {

		if ($is_admin) {
			// указываем параметр работы в админ-панели напрямую
			self::$is_admin = true;
			define('JTEMPLATE', joosConfig::get('template_admin'));
			//joosConfig::set('admin_icons_path', sprintf('%s/%s/templates/%s/media/images/ico/', JPATH_SITE, JADMIN_BASE, JTEMPLATE));
			$option = joosRequest::param('option');
			$this->_setAdminPaths($option);

			// это что бы в админке запоминались фильтры, последние страницы   и прочие вкусняшки
			joosSession::init_user_state();
		} else {
			define('JTEMPLATE', joosConfig::get('template'));
		}
	}

	/**
	 * Получение прямой ссылки на объект ядра
	 * @param boolen $is_admin - инициализация ядра в пространстве панели управления
	 * @return joosMainframe - объект ядра
	 */
	public static function instance($is_admin = false) {

		JDEBUG ? joosDebug::inc('joosMainframe::instance()') : null;

		if (self::$instance === null) {
			self::$instance = new self($is_admin);
		}

		return self::$instance;
	}

	function set($property, $value = null) {
		$this->$property = $value;
	}

	function get($property, $default = null) {
		return isset($this->$property) ? $this->$property : $default;
	}

	/**
	 * Установка переменных окружения для путей
	 * @param string $name - название переменной пути
	 * @param string $path  - непосредственно сам путь
	 */
	public function setPath($name, $path) {
		if (is_file($path)) {
			$this->_path->$name = $path;
		}
	}

	private function _setAdminPaths($option, $basePath = JPATH_BASE) {
		$option = strtolower($option);

		$this->_path = new stdClass();

		// security check to disable use of `/`, `\\` and `:` in $options variable
		if (strpos($option, '/') !== false || strpos($option, '\\') !== false || strpos($option, ':') !== false) {
			mosErrorAlert(_ACCESS_DENIED);
			return;
		}

		$prefix = substr($option, 0, 4);
		if ($prefix != 'mod_') {
			// ensure backward compatibility with existing links
			$name = $option;
			$option = $option;
		} else {
			$name = substr($option, 4);
		}

		$template = JTEMPLATE;

		//TODO Здесь какой-то ужысс
		// components
		if (file_exists("$basePath/app/templates/$template/components/$name.html.php")) {
			$this->_path->front = "$basePath/app/components/$option/$name.php";
			$this->_path->front_html = "$basePath/app/templates/$template/components/$name.html.php";
		} elseif (file_exists("$basePath/app/components/$option/$name.php")) {
			$this->_path->front = "$basePath/app/components/$option/$name.php";
			$this->_path->front_html = "$basePath/capp/omponents/$option/$name.html.php";
		}

		if (file_exists("$basePath/app/components/$option/admin.$name.php")) {
			$this->_path->admin = "$basePath/app/components/$option/admin.$name.php";
			$this->_path->admin_html = "$basePath/app/components/$option/admin.$name.html.php";
		}

		if (file_exists("$basePath/app/components/$option/toolbar.$name.php")) {
			$this->_path->toolbar = "$basePath/app/components/$option/toolbar.$name.php";
			$this->_path->toolbar_html = "$basePath/app/components/$option/toolbar.$name.html.php";
			$this->_path->toolbar_default = "$basePath/app/core/toolbar.html.php";
		}

		if (file_exists("$basePath/app/components/$option/$name.class.php")) {
			$this->_path->class = "$basePath/app/components/$option/$name.class.php";
		} elseif (file_exists("$basePath/app/components/$option/$name.class.php")) {
			$this->_path->class = "$basePath/app/components/$option/$name.class.php";
		} elseif (file_exists("$basePath/core/$name.php")) {
			$this->_path->class = "$basePath/core/$name.php";
		}

		if ($prefix == 'mod_' && file_exists("$basePath/app/modules/$option.php")) {
			$this->_path->admin = "$basePath/app/modules/$option.php";
			$this->_path->admin_html = "$basePath/app/modules/mod_$name.html.php";
		} elseif (file_exists("$basePath/app/components/$option/admin.$name.php")) {
			$this->_path->admin = "$basePath/app/components/$option/admin.$name.php";
			$this->_path->admin_html = "$basePath/app/components/$option/admin.$name.html.php";
		} else {
			$this->_path->admin = "$basePath/app/components/admin/admin.admin.php";
			$this->_path->admin_html = "$basePath/app/components/admin/admin.admin.html.php";
		}
	}

	/**
	 * Получение пути окружения
	 * @param string $varname - название переменной
	 * @param string $option - название компонента дял которого получается переменные окружения
	 * @return string путь
	 */
	public function getPath($varname, $option = '') {

		if ($option) {
			$temp = $this->_path;
			$this->_setAdminPaths($option, JPATH_BASE);
		} else {
			$temp = '';
		}

		$result = null;
		if (isset($this->_path->$varname)) {
			$result = $this->_path->$varname;
		} else {
			switch ($varname) {
				case 'xml':
					//$name = substr($option, 4);
					$path = JPATH_BASE . DS . JADMIN_BASE . "/components/$option/$name.xml";
					if (file_exists($path)) {
						$result = $path;
					} else {
						$path = JPATH_BASE . "/components/$option/$name.xml";
						$result = file_exists($path) ? $path : $result;
					}
					break;

				case 'mod0_xml':
					// Site modules
					if ($option == '') {
						$path = JPATH_BASE . '/modules/mod_custom/mod_custom.xml';
					} else {
						$path = JPATH_BASE . "/modules/$option/$option.xml";
					}
					$result = file_exists($path) ? $path :
							$result;
					break;

				case 'mod1_xml':
					// admin modules
					if ($option == '') {
						$path = JPATH_BASE . DS . JADMIN_BASE . '/modules/mod_custom/mod_custom.xml';
					} else {
						$path = JPATH_BASE . DS . JADMIN_BASE . "/modules/$option/$option.xml";
					}
					$result = file_exists($path) ? $path :
							$result;
					break;

				case 'menu_xml':
					$path = JPATH_BASE . DS . JADMIN_BASE . "/components/menus/$option/$option.xml";
					$result = file_exists($path) ? $path :
							$result;
					break;
			}
		}
		if ($option) {
			$this->_path = $temp;
		}
		return $result;
	}

	/**
	 * Проверка на работу в режиме сайта или в режиме панели управления
	 * @return boolean результат проверки. TRUE если панель управления, FALSE если режим сайта
	 */
	public static function is_admin() {
		return self::$is_admin;
	}

}

/**
 * Класс работы со страницой выдаваемой в браузер
 * @package Joostina
 * @subpackage Document
 */
class joosDocument {

	private static $instance;
	private static $title_separator = ' - ';
	public static $page_body;
	public static $data = array(
		'title' => array(),
		'meta' => array(),
		'custom' => array(), //JS-файлы
		'js_files' => array(), //Исполняемый код, подключаемый ПОСЛЕ js-файлов
		'js_code' => array(),
		'js_onready' => array(),
		'css' => array(),
		'header' => array(),
		'pathway' => array(),
		'pagetitle' => false,
		'page_body' => false,
		'html_body' => false,
		'footer' => array(),
	);
	public static $config = array(
		'favicon' => true, 'seotag' => true,
	);
	public static $seotag = array(
		'distribution' => 'global',
		'rating' => 'General',
		'document-state' => 'Dynamic',
		'documentType' => 'WebDocument',
		'audience' => 'all',
		'revisit' => '5 days',
		'revisit-after' => '5 days',
		'allow-search' => 'yes',
		'language' => 'russian',
		'robots' => 'index, follow',
	);
	// время кэширования страницы браузером, в секундах
	public static $cache_header_time = false;

	private function __construct() {

	}

	/**
	 *
	 * @return joosDocument
	 */
	public static function instance() {
		if (self::$instance === null) {
			self::$instance = new self;
			self::$data['title'] = array(joosConfig::get2('info', 'title'));
		}
		return self::$instance;
	}

	public static function get_data($name) {
		return isset(self::$data[$name]) ? self::$data[$name] : false;
	}

	/**
	 * Полностью заменяет заголовок страницы на переданный
	 *
	 * @param string $title Заголовок страницы
	 * @param string $pagetitle Название страницы
	 * @return joosDocument
	 */
	public function set_page_title($title = '', $pagetitle = '') {

		// title страницы
		$title = $title ? $title : joosConfig::get2('info', 'title');
		self::$data['title'] = array($title);

		// название страницы, не title!
		self::$data['pagetitle'] = $pagetitle ? $pagetitle : $title;

		return $this;
	}

	/**
	 * Добавляет строку в массив с фрагментами заголовка
	 *
	 * @param string $title Фрагмент заголовка страницы
	 * @return joosDocument
	 */
	public function add_title($title = '') {
		self::$data['title'][] = $title;
	}

	/**
	 * Возвращает заголовок страницы, собранный из фрагментов, отсортированных в обратном порядке
	 * @return string Заголовок
	 */
	public static function get_title() {

		//TODO: может, можно что-то поизящнее придумать?
		$title = array_reverse(self::$data['title']);

		return implode(' / ', $title);
	}

	public function add_meta_tag($name, $content) {
		$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
		$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
		self::$data['meta'][] = array($name, $content);

		return $this;
	}

	public function append_meta_tag($name, $content) {
		$n = count(self::$data['meta']);
		for ($i = 0; $i < $n; $i++) {
			if (self::$data['meta'][$i][0] == $name) {
				$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
				if ($content != '' & self::$data['meta'][$i][1] == "") {
					self::$data['meta'][$i][1] .= ' ' . $content;
				}
				;
				return;
			}
		}

		$this->add_meta_tag($name, $content);
	}

	function prepend_meta_tag($name, $content) {
		$name = joosString::trim(htmlspecialchars($name, ENT_QUOTES, 'UTF-8'));
		$n = count(self::$data['meta']);
		for ($i = 0; $i < $n; $i++) {
			if (self::$data['meta'][$i][0] == $name) {
				$content = joosString::trim(htmlspecialchars($content, ENT_QUOTES, 'UTF-8'));
				self::$data['meta'][$i][1] = $content . self::$data['meta'][$i][1];
				return;
			}
		}
		self::instance()->add_meta_tag($name, $content);
	}

	function add_custom_head_tag($html) {
		self::$data['custom'][] = trim($html);

		return $this;
	}

	function add_custom_footer_tag($html) {
		self::$data['custom'][] = trim($html);

		return $this;
	}

	public function get_head() {

		$head = array();
		$head[] = isset(self::$data['title']) ? "\t" . '<title>' . self::get_title() . '</title>' : false;

		foreach (self::$data['meta'] as $meta) {
			$head[] = '<meta name="' . $meta[0] . '" content="' . $meta[1] . '" />';
		}

		foreach (self::$data['custom'] as $html) {
			$head[] = $html;
		}

		return implode("\n\t", $head) . "\n";
	}

	/**
	 * Подключение JS файла
	 * @param string $path полный путь до файла
	 * @param array $params массив дополнительных параметров подключения файла
	 * @return joosDocument
	 */
	public function add_js_file($path, $params = array('first' => false)) {

		if (isset($params['first']) && $params['first'] == true) {
			array_unshift(self::$data['js_files'], $path);
		} else {
			self::$data['js_files'][] = $path;
		}

		/**
		  @var $this self */
		return $this;
	}

	public function add_js_code($code) {
		self::$data['js_code'][] = $code;
		return $this;
	}

	public function add_js_vars($code) {
		self::$data['js_vars'][] = $code;
		return $this;
	}

	public function add_css($path, $params = array('media' => 'all')) {
		self::$data['css'][] = array($path, $params);

		return $this;
	}

	public function seo_tag($name, $value) {
		self::$seotag[$name] = $value;

		return $this;
	}

	public static function javascript() {

		$result = '';
		$result .= _JSCSS_CACHE ? self::js_files_cache() : self::js_files();
		echo $result .= self::js_code();

		//return $result . "\n";
	}

	public static function js_files() {
		$result = array();

		foreach (self::$data['js_files'] as $js_file) {
			// если включена отладка - то будет добавлять антикеш к имени файла
			$result[] = joosHTML::js_file($js_file . (JDEBUG ? '?' . time() : false));
		}

		return implode("\n\t", $result) . "\n";
	}

	public static function js_code() {

		if (joosRequest::is_ajax()) {
			$result = self::$data['js_code'];
		} else {
			$c = array();
			foreach (self::$data['js_code'] as $js_code) {
				//$result[] = JHTML::js_code($js_code);
				$c[] = $js_code . ";\n";
			}
			$result = joosHTML::js_code(implode("", $c));
		}
		return $result;
	}

	public static function stylesheet() {
		$result = array();

		foreach (self::$data['css'] as $css_file) {
			// если включена отладка - то будет добавлять онтикеш к имени файла
			$result[] = joosHTML::css_file($css_file[0] . (JDEBUG ? '?' . time() : JFILE_ANTICACHE), $css_file[1]['media']);
		}

		return implode("\n\t", $result) . "\n";
	}

	public static function head() {

		$jdocument = self::instance();

		$meta = joosDocument::get_data('meta');
		$n = count($meta);

		$description = $keywords = false;

		for ($i = 0; $i < $n; $i++) {
			if ($meta[$i][0] == 'keywords') {
				$_meta_keys_index = $i;
				$keywords = $meta[$i][1];
			} else {
				if ($meta[$i][0] == 'description') {
					$_meta_desc_index = $i;
					$description = $meta[$i][1];
				}
			}
		}

		$description ? null : $jdocument->append_meta_tag('description', joosConfig::get2('info', 'description'));
		$keywords ? null : $jdocument->append_meta_tag('keywords', joosConfig::get2('info', 'keywords'));

		if (joosDocument::$config['seotag'] == true) {
			foreach (self::$seotag as $key => $value) {
				$value != false ? $jdocument->add_meta_tag($key, $value) : null;
			}
		}

		echo $jdocument->get_head();


		// favourites icon
		if (self::$config['favicon'] == true) {
			$icon = JPATH_SITE . '/media/favicon.ico?v=2';
			echo "\t" . '<link rel="shortcut icon" href="' . $icon . '" />' . "\n\t";
		}
	}

	public static function body() {
		echo self::$data['page_body'];
	}

	public static function footer_data() {
		return implode("\n", self::$data['footer']);
	}

	public static function head_data() {
		return implode("\n", self::$data['header']);
	}

	public static function header() {
		if (!headers_sent()) {
			if (self::$cache_header_time) {
				header_remove('Pragma');
				header('Cache-Control: max-age=' . self::$cache_header_time);
				header('Expires: ' . gmdate('r', time() + self::$cache_header_time));
			} else {
				header('Pragma: no-cache');
				header('Cache-Control: no-cache, must-revalidate');
			}
			header('X-Powered-By: Joostina CMS');
			joosRequest::is_ajax() ? header('Content-type: application/json') : header('Content-type: text/html; charset=UTF-8');
			joosRequest::is_ajax() ? ob_start("ob_gzhandler") : null;
		}
	}

}

/**
 * Главное ядро Joostina CMS
 * @package Joostina
 * @subpackage Core
 */
class joosCore {

	/**
	 * Вычисление пути расположений файлов
	 * @static
	 * @param string $name название объекта
	 * @param string $type тип объекта, компонент, модуль
	 * @param string $cat категория ( для библиотек )
	 * @return bool|string
	 */
	public static function path($name, $type, $cat = '') {

		(JDEBUG && $name != 'debug') ? joosDebug::inc(sprintf('joosCore::%s - <b>%s</b>', $type, $name)) : null;

		switch ($type) {
			case 'controller':
				$file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'controller.' . $name . '.php';
				break;

			case 'admin_controller':
				$file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'controller.admin.' . $name . '.php';
				break;

			case 'ajax_controller':
				$file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'controller.' . $name . '.ajax.php';
				break;

			case 'class':
				$file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'models' . DS . 'model.' . $name . '.php';
				break;

			case 'admin_class':
				$file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'models' . DS . 'model.admin.' . $name . '.php';
				break;

			case 'html':
				$file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . $name . '.html.php';
				break;

			case 'admin_html':
				$file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'admin.' . $name . '.html.php';
				break;

			case 'admin_template_html':
				$file = JPATH_BASE . DS . 'app' . DS . 'templates' . DS . JTEMPLATE . DS . 'html' . DS . $name . '.php';
				break;

			case 'admin_view':
				$file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $type . DS . 'admin_views' . DS . $cat . DS . $name . '.php';
				break;

			case 'lang':
				$file = JPATH_BASE . DS . 'app' . DS . 'language' . DS . JLANG . DS . $name . '.php';
				break;

			case 'module_helper':
				$file = JPATH_BASE . DS . 'app' . DS . 'modules' . DS . $name . DS . 'helper.' . $name . '.php';
				break;

			case 'module_admin_helper':
				$file = JPATH_BASE . DS . 'app' . DS . 'modules' . DS . $name . DS . 'helper.' . $name . '.php';
				break;

			case 'lib':
				$file = JPATH_BASE . DS . 'core' . DS . 'vendors' . DS . $name . DS . $name . '.php';
				break;

			case 'lib-cat':
				$file = JPATH_BASE . DS . 'core' . DS . 'vendors' . DS . $cat . DS . $name . DS . $name . '.php';
				break;

			case 'core_class':
				$file = JPATH_BASE . DS . 'core' . DS . 'classes' . DS . $name . '.class.php';
				break;

			default:
				break;
		}

		return $file;
	}

}

/**
 * Класс подключения файлов
 * @package Joostina
 * @subpackage Loader
 */
class joosLoader {

	public static function model($name) {
		// TODO разрешить после полной натсройки автозагрузчика
		//require_once joosCore::path($name, 'class');
	}

	public static function admin_model($name) {
		// TODO разрешить после полной натсройки автозагрузчика
		//require_once joosCore::path($name, 'admin_class');
	}

	public static function view($name) {
		require_once joosCore::path($name, 'html');
	}

	public static function admin_view($name) {
		require_once joosCore::path($name, 'admin_html');
	}

	public static function admin_template_view($name) {
		require_once joosCore::path($name, 'admin_template_html');
	}

	public static function controller($name) {
		require_once joosCore::path($name, 'controller');
	}

	public static function admin_controller($name) {
		require_once joosCore::path($name, 'admin_controller');
	}

	public static function core_class($name) {
		require_once joosCore::path($name, 'core_class');
	}

	/**
	 * Прямое подключение внешних библиотек
	 * @param string $name название библиотеки
	 * @param string $category  подкаталог расположения библиотеки
	 */
	public static function lib($name, $vendor = false) {
		require_once $vendor ? joosCore::path($name, 'lib-cat', $vendor) : joosCore::path($name, 'lib');
	}

	public static function lang($name) {
		$file = joosCore::path($name, 'lang');

		// для языковый файлов такая вот жусткач штуковина
		if (is_file($file)) {
			require_once $file;
		} else {
			!JDEBUG ? : joosDebug::add(sprintf(('Отсутствует файл языка для %s'), $name));
		}
	}

}

/**
 * Базовый контроллер Joostina CMS
 * @package Joostina
 * @subpackage Contlroller
 */
class joosController {

	public static $activroute;
	public static $controller;
	public static $task;
	public static $param;
	public static $error = false;
	private static $jsondata = array('extradata' => array());

	public static function init() {
		//
		joosDocument::header();
		// инициализируем соединение с базой
		//joosDatabase::instance();
	}

	/**
	 * Автоматическое определение и запуск метода действия
	 */
	public static function run() {

		$class = 'actions' . ucfirst(self::$controller);

		JDEBUG ? joosDebug::add($class . '::' . self::$task) : null;

		$path = joosCore::path(self::$controller, 'controller');

		if (!is_file($path) || self::$activroute == '404') {
			return self::error404();
		} else {
			require_once ($path);
		}

		if (method_exists($class, self::$task)) {

			// в контроллере можно прописать общие действия необходимые при любых действиях контроллера - они будут вызваны первыми, например подключение моделей, скриптов и т.д.
			method_exists($class, 'on_start') ? call_user_func_array($class . '::on_start', array(self::$task)) : null;

			$results = call_user_func_array($class . '::' . self::$task, array());
			if (is_array($results)) {
				self::views($results, self::$controller, self::$task);
			} elseif (is_string($results)) {
				echo $results;
			}
		} else {
			//  в контроллере нет запрашиваемого метода
			return self::error404();
		}
	}

	/**
	 * Автоматическое определение и запуск метода действия для Аякс-азпросов
	 */
	public static function ajax_run() {

		$class = 'actions' . ucfirst(self::$controller);

		JDEBUG ? joosDebug::add($class . '::' . self::$task) : null;

		$path = joosCore::path(self::$controller, 'ajax_controller');

		if (!is_file($path) || self::$activroute == '404') {
			return self::error404();
		} else {
			require_once ($path);
		}

		if (method_exists($class, self::$task)) {

			// в контроллере можно прописать общие действия необходимые при любых действиях контроллера - они будут вызваны первыми, например подключение моделей, скриптов и т.д.
			method_exists($class, 'on_start') ? call_user_func_array($class . '::on_start', array(self::$task)) : null;

			$results = call_user_func_array($class . '::' . self::$task, array());
		} else {
			//  в контроллере нет запрашиваемого метода
			return self::ajax_error404();
		}
		if (is_array($results)) {
			self::views($results, self::$controller, self::$task);
		} elseif (is_string($results)) {
			echo $results;
		}
	}

	public static function set_json_data(array $add_to_json) {
		self::$jsondata['extradata'] += $add_to_json;
	}

	private static function views(array $params, $option, $task) {

		//Это для того, чтобы просто пропускать аяксовые запросы через основной контроллер
		if (!joosRequest::is_ajax()) {
			//Готовим модули к выдаче: выбираем модули, которые нужны для текущей страницы
			self::prepare_modules_for_current_page($params, $option, $task);
		}

		if (joosRequest::is_ajax()) {
			ob_start();
			self::as_html($params, $option, $task);
			$jsob_body = ob_get_contents();
			ob_end_clean();

			$json = self::$jsondata;
			$json += array(joosConfig::get2('ajax', 'component_dom', '#component') => $jsob_body,);

			$json['extradata']['title'] = joosDocument::get_title();
			$json += joosModule::load_for_ajax();

			//$json += isset($params['core::extradata']) ? array('extradata' => $params['core::extradata']) : array();
			$json['extradata'] += isset($params['core::extradata']) ? $params['core::extradata'] : array();

			$js_code = joosDocument::instance()->js_code();
			if ($js_code) {
				$json['extradata']['js_code'] = $js_code;
			}


			echo json_encode($json);
			exit();
		}

		(isset($params['as_json']) && $params['as_json'] == true) ? self::as_json($params) : self::as_html($params, $option, $task);
	}

	private static function as_html(array $params, $option, $task) {
		$template = isset($params['template']) ? $params['template'] : 'default';
		$views = isset($params['task']) ? $params['task'] : $task;
		;
		extract($params, EXTR_OVERWRITE);
		$viewfile = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $option . DS . 'views' . DS . $views . DS . $template . '.php';

		unset($params, $option, $task);

		is_file($viewfile) ? require ($viewfile) : null;
	}

	private static function as_json(array $params) {
		unset($params['as_json']);

		echo json_encode($params);
		exit();
	}

	/**
	 * joosController::prepare_modules_for_current_page()
	 * Формирует массив модулей, необходимых для вывода на данной странице
	 *
	 * @param array $params Массив параметров, передаваемый из контроллера компонента
	 * @param atring $option
	 * @param string $task
	 * @return void
	 */
	private static function prepare_modules_for_current_page(array $params, $option, $task) {

		joosModule::modules_by_page($option, $task, $params);

		unset($params);
	}

	public static function error404() {

		// если обработка идёт через Ajax
		if (joosRequest::is_ajax()) {

			$json = self::$jsondata;
			$json += array(joosConfig::get2('ajax', 'component_dom', '#component') => _NOT_EXIST,);
			echo json_encode($json);
			exit();
		}

		header('HTTP/1.0 404 Not Found');

		if (!joosConfig::get('404_page')) {
			echo _NOT_EXIST;
		} else {
			require_once (JPATH_BASE . '/app/templates/system/404.php');
			exit(404);
		}

		self::$error = 404;

		return;
	}

	public static function ajax_error404() {
		header('HTTP/1.0 404 Not Found');
		echo _NOT_EXIST;

		self::$error = 404;

		return;
	}

	/**
	 * Статичный запуск проитзвольной задачи из произвольного контроллера
	 * @param string $controller название контроллера
	 * @param string $task выполняемая задача
	 * @param array $params массив парамеьтров передаваемых задаче
	 */
	public static function static_run($controller, $task, array $params = array()) {

		self::$controller = $controller;
		self::$task = $task;
		self::$param = $params;
		self::$activroute = 'staticrun';

		self::run();
	}

	/**
	 * Подключение шаблона
	 * @param string $controller название контроллера
	 * @param string $task выполняемая задача
	 * @param array $params массив параметров, которые могут переданы в шаблон
	 */
	public static function get_view($controller, $task, $template = 'default', $params = array()) {
		extract($params, EXTR_OVERWRITE);
		$viewfile = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $controller . DS . 'views' . DS . $task . DS . $template . '.php';
		is_file($viewfile) ? require ($viewfile) : null;
	}

}

/**
 * Класс работы с датами
 * @package Joostina
 * @subpackage Date
 */
class joosDate {

	public static function format($date, $format = '', $offset = null) {

		if ($date == '0000-00-00 00:00:00') {
			return $date;
		}

		if ($format == '') {
			$format = _DATE_FORMAT_LC;
		}

		if (is_null($offset)) {
			$config_offset = joosConfig::get2('locale', 'offset');
			$offset = $config_offset;
		}

		return strftime($format, strtotime($date) + ($offset * 60 * 60));
	}

	public static function current($format = "") {

		$config_offset = joosConfig::get2('locale', 'offset');

		if ($format == '') {
			$format = _DATE_FORMAT_LC;
		}

		return strftime($format, time() + ($config_offset * 60 * 60));
	}

}

// общий класс работы с модулями ( на фронте )
class joosModule extends Modules {

	private static $data = array();
	private static $_object_data = array();

	public static function get_data() {
		return self::$data;
	}

	public static function add_array(array $modules) {
		self::$data += $modules;
	}

	/**
	 * Загрузка ВСЕХ модулей для текущей страницы
	 */
	public static function modules_by_page($controller, $method, $object_data = array()) {
		$modules_pages = new ModulesPages;

		$modules = $modules_pages->get_list(array('select' => "mp.*,m.*", 'join' =>
					'AS mp INNER JOIN #__modules AS m ON ( m.id = mp.moduleid AND m.state = 1 AND m.client_id = 0 )', 'where' =>
					'mp.controller = "all" OR mp.controller = "' . $controller . '"', 'order' => 'm.position, m.ordering',));


		$by_position = array();
		$by_name = array();
		$by_id = array();

		foreach ($modules as $module) {
			if ($module->controller == 'all' || (!$module->method || ($module->method == $method))) {
				$by_position[$module->position][$module->id] = $module;
				$by_name[$module->module] = $module;
				$by_id[$module->id] = $module;
			}
		}

		self::$data += $by_position;
		self::$data += $by_name;
		self::$data += $by_id;

		self::$_object_data = $object_data;
	}

	/**
	 * Загрузка модулей админпанели
	 */
	public static function modules_for_backend() {
		$modules = new Modules;

		$modules = $modules->get_list(array('select' => "*", 'where' => 'state = 1 AND client_id = 1', 'order' => 'position, ordering',));


		$by_position = array();
		$by_name = array();
		$by_id = array();

		foreach ($modules as $module) {
			$by_position[$module->position][$module->id] = $module;
			$by_name[$module->module] = $module;
			$by_id[$module->id] = $module;
		}

		self::$data += $by_position;
		self::$data += $by_name;
		self::$data += $by_id;
	}

	/**
	 * Загрузка ВСЕХ модулей определённой позиции
	 * @param string $name название позиции
	 */
	public static function load_by_position($name) {
		if (self::in_position($name)) {
			foreach (self::$data[$name] as $position_name => $module) {
				self::module($module);
			}
		}
	}

	public static function load_by_name($name, $add_params = array()) {
		if (isset(self::$data[$name])) {
			self::module(self::$data[$name], $add_params);
		}
	}

	public static function load_by_id($id, $add_params = array()) {
		if (isset(self::$data[$id])) {
			self::module(self::$data[$id], $add_params);
		}
	}

	/**
	 * Получение числа модулей расположенных в определённой позиции
	 * @param string $name название позиции
	 * @return int число модулей в выбранной позиции
	 */
	public static function count_by_position($name) {
		return isset(self::$data[$name]) ? count(self::$data[$name]) : false;
	}

	/**
	 * Проверка наличия модулей определённой позиции
	 * @param string $name название позиции
	 * @return bool наличие модулей в выбранной позиции
	 */
	public static function in_position($name) {
		return isset(self::$data[$name]);
	}

	/**
	 * Подключение (вывод) модуля в тело страницы
	 * @var module stdClass Объект модуля
	 */
	public static function module($module = null, $add_params = array()) {

		if (!$module) {
			return;
		}

		//Определяем имя главного исполняемого файла модуля
		$name = $module->module ? $module->module : 'custom';
		$file = JPATH_BASE . DS . 'app' . DS . 'modules' . DS . $name . DS . $name . '.php';

		//Пытаемся сразу определить шаблон для вывода
		$module->template_path = self::module_template($module);


		//Разворачиваем параметры модуля
		$params = json_decode($module->params, true);
		if ($add_params) {
			$params = array_merge($params, $add_params);
		}

		$object_data = self::$_object_data;

		//Подключаем модуль
		is_file($file) ? require ($file) : null;
	}

	/**
	 * Определение имени шаблона для вывода
	 * @var name str Имя модуля
	 * @var params array Массив параметров
	 */
	private static function module_template($module) {
		$_tpl = $module->template ? $module->template : 'default';
		$name = $module->module ? $module->module : 'custom';
		$_tpl_file = JPATH_BASE . DS . 'app' . DS . 'modules' . DS . $name . DS . 'views' . DS . $_tpl . '.php';
		return is_file($_tpl_file) ? $_tpl_file : null;
	}

	/**
	 * Загрузка всех активированных модулей для аякс-представления
	 */
	public static function load_for_ajax() {
		$modules = array();
		foreach (self::$data as $position => $data) {
			if (self::in_position($position)) {
				ob_start();

				foreach (self::$data[$position] as $position_name => $position_params) {
					$position_params = is_array($position_params) ? $position_params : array();
					self::module($position_name, $position_params, array('hide_frame' => true));
				}

				$key = '#jpos_' . $position;
				$modules[$key] = ob_get_contents();
				ob_end_clean();
			}
		}
		return $modules;
	}

}

// заглушка для дальнейшей локализации, все строки просто обрамляем __('текст')
function __($t) {
	return $t;
}

/**
 * Utility function to return a value from a named array or a specified default
 * @param array A named array
 * @param string The key to search for
 * @param mixed The default value to give if no key found
 * @param int An options mask: _MOS_NOTRIM prevents trim, _MOS_ALLOWHTML allows safe html, _MOS_ALLOWRAW allows raw input
 */
//define("_NOTRIM", 0x0001);
//define("_ALLOWHTML", 0x0002);

function __mosGetParam(&$arr, $name, $def = null, $mask = 0) {

	$return = null;
	if (isset($arr[$name])) {
		$return = $arr[$name];

		if (is_string($return)) {
			$return = (!($mask & _NOTRIM)) ? trim($return) : $return;

			$return = (!($mask & _ALLOWHTML)) ? joosInputFilter::instance()->process($return) : $return;
		}

		return $return;
	} else {
		return $def;
	}
}

function mosErrorAlert($text, $action = 'window.history.go(-1);', $mode = 1) {
	$text = nl2br($text);
	$text = addslashes($text);
	$text = strip_tags($text);

	switch ($mode) {
		case 2:
			echo "<script>$action</script> \n";
			break;

		case 1:
		default:
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />";
			echo "<script>alert('$text'); $action</script> \n";
			break;
	}

	exit;
}

// отладка определённой переменной
function _xdump($var, $text = '<pre>') {
	echo '<pre>';
	print_r($var);
	echo "</pre>\n";
}
