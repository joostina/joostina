<?php defined('_JOOS_CORE') or exit;


/**
 * Класс работы со страницой выдаваемой в браузер
 * @package    Joostina
 * @subpackage Document
 */
class joosDocument
{
	private static $instance;
	public static $page_body;
	public static $data = array('title' => array(), 'meta' => array(), 'custom' => array(), //JS-файлы
		'js_files' => array(), //Исполняемый код, подключаемый ПОСЛЕ js-файлов
		'js_code' => array(), 'js_onready' => array(), 'css' => array(), 'header' => array(), 'pathway' => array(), 'pagetitle' => false, 'page_body' => false, 'html_body' => false, 'footer' => array(),);
	public static $config = array('favicon' => true, 'seotag' => true,);
	public static $seotag = array('distribution' => 'global', 'rating' => 'General', 'document-state' => 'Dynamic', 'documentType' => 'WebDocument', 'audience' => 'all', 'revisit' => '5 days', 'revisit-after' => '5 days', 'allow-search' => 'yes', 'language' => 'russian', 'robots' => 'index, follow');
	// время кэширования страницы браузером, в секундах
	public static $cache_header_time = false;

	private function __construct()
	{
	}

	/**
	 *
	 * @return joosDocument
	 */
	public static function instance()
	{
		if (self::$instance === null) {
			self::$instance = new self;
			self::$data['title'] = array(joosConfig::get2('info', 'title'));
		}

		return self::$instance;
	}

	public static function get_data($name)
	{
		return isset(self::$data[$name]) ? self::$data[$name] : false;
	}

	public static function set_body($body)
	{
		self::$data['page_body'] = $body;
	}

	public static function get_body()
	{
		return self::$data['page_body'];
	}

	/**
	 * Полностью заменяет заголовок страницы на переданный
	 *
	 * @param string $title     Заголовок страницы
	 * @param string $pagetitle Название страницы
	 *
	 * @return joosDocument
	 */
	public function set_page_title($title = '', $pagetitle = '')
	{
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
	 *
	 * @return joosDocument
	 */
	public function add_title($title = '')
	{
		self::$data['title'][] = $title;
	}

	/**
	 * Возвращает заголовок страницы, собранный из фрагментов, отсортированных в обратном порядке
	 * @return string Заголовок
	 */
	public static function get_title()
	{
		$title = array_reverse(self::$data['title']);

		return implode(' / ', $title);
	}

	public function add_meta_tag($name, $content)
	{
		$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
		$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
		self::$data['meta'][] = array($name, $content);

		return $this;
	}

	public function append_meta_tag($name, $content)
	{
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

	public function prepend_meta_tag($name, $content)
	{
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

	public function add_custom_head_tag($html)
	{
		self::$data['custom'][] = trim($html);

		return $this;
	}

	public function add_custom_footer_tag($html)
	{
		self::$data['custom'][] = trim($html);

		return $this;
	}

	public function get_head()
	{
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
	 *
	 * @param string $path   полный путь до файла
	 * @param array  $params массив дополнительных параметров подключения файла
	 *
	 * @return joosDocument
	 */
	public function add_js_file($path, $params = array('first' => false))
	{
		if (isset($params['first']) && $params['first'] == true) {
			array_unshift(self::$data['js_files'], $path);
		} else {
			self::$data['js_files'][] = $path;
		}

		/**
		@var $this self */

		return $this;
	}

	public function add_js_code($code)
	{
		self::$data['js_code'][] = $code;

		return $this;
	}

	public function add_js_vars($code)
	{
		self::$data['js_vars'][] = $code;

		return $this;
	}

	public function add_css($path, $params = array('media' => 'all'))
	{
		self::$data['css'][] = array($path, $params);

		return $this;
	}

	public function seo_tag($name, $value)
	{
		self::$seotag[$name] = $value;

		return $this;
	}

	public static function javascript()
	{
		return self::js_files() . self::js_code();
	}

	/**
	 * Подготовка JS файлов к выводу
	 * Если включено кэширование, файлы будут минимизированы и склеены
	 */
	public static function js_files()
	{
		//Если включено кэширование JS-файлов, оптимизируем и склеиваем
		if (joosConfig::get2('cache', 'js_cache')) {
			$js_file = joosJSOptimizer::optimize_and_save(self::$data['js_files']);

			return joosHtml::js_file($js_file['live'] . (JDEBUG ? '?' . time() : JFILE_ANTICACHE));
		}

		//иначе, отдаём файлы как есть
		else {
			$result = array();
			foreach (self::$data['js_files'] as $js_file) {
				$result[] = joosHtml::js_file($js_file . (JDEBUG ? '?' . time() : false));
			}

			return implode("\n\t", $result) . "\n";
		}
	}

	public static function js_code()
	{
		$c = array();
		foreach (self::$data['js_code'] as $js_code) {
			$c[] = $js_code . ";\n";
		}
		$result = joosHtml::js_code(implode("", $c));

		return $result;
	}

	public static function stylesheet()
	{
		$result = array();

		foreach (self::$data['css'] as $css_file) {
			// если включена отладка - то будет добавлять онтикеш к имени файла
			$result[] = joosHtml::css_file($css_file[0] . (JDEBUG ? '?' . time() : JFILE_ANTICACHE), $css_file[1]['media']);
		}

		return implode("\n\t", $result) . "\n";
	}

	public static function head()
	{
		$jdocument = self::instance();

		$meta = joosDocument::get_data('meta');
		$n = count($meta);

		$description = $keywords = false;

		for ($i = 0; $i < $n; $i++) {
			if ($meta[$i][0] == 'keywords') {
				$keywords = $meta[$i][1];
			} else {
				if ($meta[$i][0] == 'description') {
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

	public static function body()
	{
		echo self::$data['page_body'];
	}

	public static function footer_data()
	{
		return implode("\n", self::$data['footer']);
	}

	public static function head_data()
	{
		return implode("\n", self::$data['header']);
	}

	public static function header()
	{
		if (!headers_sent()) {
			if (self::$cache_header_time) {
				header_remove('Pragma');
				joosRequest::send_headers('Cache-Control: max-age=' . self::$cache_header_time);
				joosRequest::send_headers('Expires: ' . gmdate('r', time() + self::$cache_header_time));
			} else {
				joosRequest::send_headers('Pragma: no-cache');
				joosRequest::send_headers('Cache-Control: no-cache, must-revalidate');
			}
			joosRequest::send_headers('X-Powered-By: Joostina CMS');
			joosRequest::send_headers('Content-type: text/html; charset=UTF-8');
		}
	}

}
