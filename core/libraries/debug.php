<?php defined('_JOOS_CORE') or die();

/**
 * Библиотека отладки и логирования системных действий
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Debug
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosDebug {

	private static $instance;
	/* стек сообщений лога */
	private static $_log = array();
	/* счетчики */
	private static $_inc = array();

	public static function instance() {
		if (self::$instance === null) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function __clone() {}

	public static function log($str, array $params = array()) {
		$value = strtr($str, $params);
		self::$_log[] = joosFilter::htmlspecialchars($value);
	}

	public static function add($text, $top = 0) {
		$top ? array_unshift(self::$_log, $text) : self::$_log[] = $text;
	}

	public static function add_top($text) {
		self::add($text, true);
	}

	public static function inc($key) {
		if (!isset(self::$_inc[$key])) {
			self::$_inc[$key] = 0;
		}
		self::$_inc[$key]++;
	}

	public static function get() {

		$f = '';

		$f .= '<div id="ptb_data_cont_custom" class="ptb_data_cont" style="display: none;">
				  <ul class="ptb_tabs">
					<li id="ptb_tab_custom_default">messages <span>(' . count(self::$_log) . ')</span></li>
				  </ul>
				  <div id="ptb_tab_cont_custom_default" class="ptb_tab_cont">
					<table class="ptb_tab_cont_table">
					  <tbody>
						<tr>
						  <th style="width:20px;">№</th>
						  <th>message</th>
						</tr>';
		$c = 1;
		foreach (self::$_log as $value) {
			$f .= '<tr><td>' . $c . '</td><td> ' . $value . '</td></tr>';
			$c++;
		}
		unset($c);
		$f .= '<tr class="total">
						<th></th>
						<th>total ' . count(self::$_log) . ' messages</th>
					  </tr>
					  </tbody>
					</table>
				  </div>
				</div>';

		/* подключенные файлы */
		$files = get_included_files();
		$f .= '<div id="ptb_data_cont_files" class="ptb_data_cont" style="display: none;">
				  <ul class="ptb_tabs">
					<li id="ptb_tab_files">files <span>(' . count($files) . ')</span></li>
				  </ul>
				  <div id="ptb_tab_cont_files" class="ptb_tab_cont">
					<table class="ptb_tab_cont_table">
					  <tbody>
						<tr>
						  <th style="width:20px;">№</th>
						  <th>file</th>
						</tr>';
		$c = 1;
		foreach ($files as $value) {
			$f .= '<tr><td>' . $c . '</td><td> ' . $value . '</td></tr>';
			$c++;
		}
		unset($c);
		$f .= '<tr class="total">
						<th></th>
						<th>total ' . count($files) . ' files</th>
					  </tr>
					  </tbody>
					</table>
				  </div>
				</div>';


		$profs = joosDatabase::instance()->set_query('show profiles;')->load_assoc_list();

		// Начало вывода панели
		echo '<span style="display:none"><![CDATA[<noindex>]]></span>
		<!-- ============================= PROFILER TOOLBAR ============================= -->
		<script type="text/javascript" src="' . JPATH_SITE . '/media/js/profilertoolbar.js"></script>
		<link rel="stylesheet" href="' . JPATH_SITE . '/media/css/profilertoolbar.css">
		<div id="ptb">
			<ul id="ptb_toolbar" class="ptb_bg">
				<li class="time" title="application execution time"><span class="icon"></span> ' . self::$_log[1] . '</li>
				<li class="ram" title="memory peak usage"><span class="icon"></span> ' . self::$_log[0] . ' </li>
				<li class="custom"><span class="icon"></span> logs <span class="total">(' . count($files) . ')</span></li>
				<li class="sql"><span class="icon"></span> sql <span class="total">(' . count($profs) . ')</span></li>
				<li class="files"><span class="icon"></span> files <span class="total">(' . count($files) . ')</span></li>
				<li class="hide" title="Hide Profiler Toolbar"><span class="icon"></span></li>
				<li class="show" title="Show Profiler Toolbar"><span class="icon"></span></li>
			</ul>
			<div id="ptb_data" class="ptb_bg" style="display: none;">
				' . self::db_debug() . '
				' . $f . '
			</div>
		</div>
		<!-- ============================= /PROFILER TOOLBAR ============================= -->
		<span style="display:none"><![CDATA[</noindex>]]></span>';
	}

	private static function db_debug() {
		$profs = joosDatabase::instance()->set_query('show profiles;')->load_assoc_list();

		$total_time = 0;
		$r = '
			<div id="ptb_data_cont_sql" class="ptb_data_cont">
				<ul class="ptb_tabs">
				  <li id="ptb_tab_sqldefault">default <span>(' . count($profs) . ')</span></li>
				</ul>
				<div id="ptb_tab_cont_sqldefault" class="ptb_tab_cont">
				<table class="ptb_tab_cont_table">
					<tbody>
					<tr>
					  <th style="width:20px;">№</th>
					  <th>query</th>
					  <th style="width:100px;">time</th>
					</tr>';
		if (isset($profs[0])) {
			foreach ($profs as $prof) {
				$r .= '<tr valign="top"><td>' . $prof['Query_ID'] . ' </td><td> ' . $prof['Query'] . ' </td><td class="tRight"> ' . $prof['Duration'] . ' s</td></tr>';
				$total_time += $prof['Duration'];
			}
		}
		$r .= '<tr class="total">
					<td></td>
					<td>total ' . count($profs) . ' queries</td>
					<td class="tRight">' . $total_time . ' s</td>
				  </tr>
				  </tbody>
				</table>
				</div>
			</div>';
		return $r;
	}

	/**
	 * Вывод информации о переменной
	 *
	 * @tutorial joosDebug::dump( array(1, 'aad', time() ), $var_name );
	 * @tutorial joosDebug::dump( $var_name_1,  $var_name_2,  $var_name_3,  $var_name_4 );
	 *
	 * @param mixed функция принимает неограниченное число параметров - переменных для анализа и вывода
	 *
	 * @todo расширить для использования в ajax-запросах
	 */
	public static function dump() {

		joosRequest::send_headers_by_code(503);

		// обозначение места вызова функции отладки
		$trace = debug_backtrace();
		if (isset($trace[1]['file'])) {
			$file_content = self::get_file_context($trace[1]['file'], $trace[1]['line']);
		} else {
			$file_content = self::get_file_context($trace[0]['file'], $trace[0]['line']);
		}

		if (ob_get_level()) {
			ob_end_clean();
		}
		ob_start();

		$func_args = func_get_args();

		$args_count = count($func_args);
		var_dump($args_count == 1 ? $func_args[0] : $func_args );
		$output = ob_get_clean();
		$output = preg_replace('/]\=>\n(\s+)/m', '] => ', $output);

		/**
		 * @todo тут надо провреить, переменная судя по всему не используется в полном объёме
		 */
		$result = joosFilter::htmlspecialchars($output);
		$file_content = joosFilter::htmlspecialchars($file_content);

		$result = <<<HTML
  <style>
    body { background-color: #fff; color: #333; }
    body, p, ol, ul, td { font-family: verdana, arial, helvetica, sans-serif; font-size: 13px; line-height: 25px; }
    pre { background-color: #eee; padding: 10px; font-size: 11px; line-height: 18px; }
    a { color: #000; }
    a:visited { color: #666; }
    a:hover { color: #fff; background-color:#000; }
  </style>
<div style="width:99%; position:relative">
<h2 id='Title'>Результат отладки</h2>
<div id="Context" style="display: block;">Место вызова:<pre>{$file_content}</pre></div>
<div id="Context" style="display: block;">Полученные параметры:<pre>{$result}</pre></div>
HTML;
		$result .= "</div>";

		echo $result;
		die();
	}

	/**
	 *
	 * @deprecated собрать с классом joosException в один класс joosFile
	 */
	private static function get_file_context($file, $line_number) {

		$context = array();
		$i = 0;
		foreach (file($file) as $line) {
			$i++;
			if ($i >= $line_number - 3 && $i <= $line_number + 3) {
				if ($i == $line_number) {
					$context[] = ' >>   ' . $i . "\t" . $line;
				} else {
					$context[] = "\t" . $i . "\t" . $line;
				}
			}
			if ($i > $line_number + 3) {
				break;
			}
		}

		return "\n" . implode("", $context);
	}

}
