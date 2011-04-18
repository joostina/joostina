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

joosAutoAdmin::dispatch_ajax();

class actionsAjaxCoder {

	private static $implode_model = true;

	public static function on_start() {
		joosLoader::lib('forms');
	}

	public static function index() {
		$tables = joosRequest::array_param('codertable');

		$ret = array();
		foreach ($tables as $table) {
			$ret[] = adminCoder::get_model($table, self::$implode_model);
		}

		return self::$implode_model ? forms::textarea(array('name' => 'all_models', 'value' => implode('', $ret), 'rows' => '25', 'class' => 'coder_model_area')) : implode("\n", $ret);
	}

	public static function table_select() {
		$table = joosRequest::post('table');

		$types = dbFaker::$data_types;

		array_walk($types, function(&$v) {
					$v = $v['name'];
				});

		$table_fields = joosDatabase::instance()->get_utils()->get_table_fields($table);

		$ret = array();
		$ret[] = '<table><tr><th>Поле<th><th>Заполнить<th></tr>';
		foreach ($table_fields as $key => $value) {
			$type = preg_replace('#[^A-Z]#i', '', $value);
			$type = str_replace('unsigned', '', $type);
			$faker_selector = forms::dropdown('type', $types, null);
			$ret[] = sprintf('<tr><td>%s <small>(%s)</small></td><td>%s</td></tr>', $key, $type, $faker_selector);
		}
		$ret[] = '</table>';

		return implode('<br />', $ret);
	}

	public static function generate_code() {
		self::generate_component();
	}

	public static function generate_files() {
		self::generate_component(true);
	}

	public static function generate_component($create_files = false) {

		$tpls_vars = array(
			'name' => '',
			'desc' => '',
			'author' => '',
			'authoremail' => '',
			'title' => ''
		);

		foreach ($tpls_vars as $var => $val) {
			$tpls_vars[$var] = joosRequest::post('component_' . $var, '');
		}

		$tpls_vars['name_lower'] = strtolower($tpls_vars['name']);
		$tpls_vars['name_upper'] = ucfirst($tpls_vars['name']);

		$tpl_path = JPATH_BASE . DS . 'app' . DS . 'components' . DS . 'coder' . DS . 'tpls' . DS . 'componenter';

		$ret = array();

		$c_path = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $tpls_vars['name_lower'];
		if ($create_files == true) {
			joosLoader::lib('files');
			$_blank = '<html><body></body></html>';
			$file = new Files;
			$file->create($c_path);
			$file->create($c_path . DS . 'index.html', $_blank);
			$file->create($c_path . DS . 'views');
			$file->create($c_path . DS . 'views' . DS . 'index.html', $_blank);
			$file->create($c_path . DS . 'admin_views');
			$file->create($c_path . DS . 'admin_views' . DS . 'index.html', $_blank);
			$file->create($c_path . DS . 'media');
			$file->create($c_path . DS . 'media' . DS . 'index.html', $_blank);
		}

		$files = array(
			'c' => $tpls_vars['name_lower'],
			'c_ajax' => $tpls_vars['name_lower'] . '.ajax',
			'c_class' => $tpls_vars['name_lower'] . '.class',
			'c_admin' => 'admin.' . $tpls_vars['name_lower'],
			'c_admin_ajax' => 'admin.' . $tpls_vars['name_lower'] . '.ajax',
			'c_admin_class' => 'admin.' . $tpls_vars['name_lower'] . '.class',
			'c_params' => $tpls_vars['name_lower'] . '.params'
		);

		foreach ($files as $type => $name) {
			$content = file_get_contents($tpl_path . DS . $type . '.txt');
			$content = sprintf($content, $tpls_vars['name_upper'], $tpls_vars['name_lower'], $tpls_vars['desc'], $tpls_vars['author'], $tpls_vars['authoremail'], $tpls_vars['title']);
			$ret[] = '<strong>' . $name . '.php</strong><br/>' . forms::textarea(array('name' => 'c_admin', 'value' => $content, 'rows' => '5', 'class' => 'c_content')) . '<br/><br/>';
			if ($create_files == true) {
				$file->create($c_path . DS . $name . '.php', $content);
			}
		}

		$output = implode('', $ret);
		$output .= is_dir($c_path) ? 'Такой компонент уже существует, введите другое имя, чтобы создать файлы' : '<button id="create_component_files">Создать файлы</button>';

		echo $output;
	}

}