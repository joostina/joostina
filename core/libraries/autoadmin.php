<?php
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosAutoAdmin - Библиотека автоматической генерации интерфейсов панели управлениями 
 * Системная библиотека
 *
 * @version 1.0
 * @package Joostina.Libraries
 * @subpackage Libraries
 * @category Libraries
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
define('DISPATCHED', true);

class joosAutoAdmin {

	private static $js_onformsubmit = array();
	public static $model;
	private static $data;
	public static $submenu;
	public static $component_title;
	private static $data_overload = false;
	private static $class;
	private static $option;
	private static $task;

	/**
	 * Автоматическое определение и запуск метода действия
	 */
	public static function dispatch() {

		$id = joosRequest::int('id', 0);
		$page = joosRequest::int('page', false, $_GET);

		$page = $page ? $page : 0;
		$id = $id ? $id : $page;

		$task = joosRequest::param('task', 'index');
		$option = joosRequest::param('option');
		$class = 'actionsAdmin' . ucfirst($option);

		self::$class = $class;
		self::$option = $option;
		self::$task = $task;


		!JDEBUG ? : joosDebug::add('joosAutoAdmin::dispatch() - ' . $class . '::' . $task);

		// в контроллере можно прописать общие действия необходимые при любых действиях контроллера - они будут вызваны первыми, например подклбчение можделей, скриптов и т.д.
		method_exists($class, 'on_start') ? call_user_func_array($class . '::on_start', array()) : null;

		//Установка тулбаров
		//Если тулбары определены в компоненте - выводим их
		//self::toolbar();


		if (method_exists($class, $task)) {
			echo call_user_func_array($class . '::' . $task, array($option, $id, $page, $task));
		} elseif (method_exists($class, 'index')) {
			echo call_user_func_array($class . '::index', array($option, $id, $page, $task));
		} else {
			throw new joosException('Ошибкаааа!');
		}

		// если контроллер содержит метод вызываемый после окончания работы основного контроллера, то он тоже вызовется
		method_exists($class, 'on_stop') ? call_user_func_array($class . '::on_stop', array()) : null;
	}

	// автодиспатчер для Ajax - обработчиков
	public static function dispatch_ajax() {

		$id = joosRequest::int('id', 0);
		$page = joosRequest::int('page', false, $_GET);

		$page = $page ? $page : 0;
		$id = $id ? $id : $page;

		$task = joosRequest::param('task', 'index');
		$option = joosRequest::param('option');
		$class = 'actionsAjax' . ucfirst($option);

		JDEBUG ? joosDebug::add($class . '::' . $task) : null;


		// в контроллере можно прописать общие действия необходимые при любых действиях контроллера - они будут вызваны первыми, например подклбчение можделей, скриптов и т.д.
		method_exists($class, 'on_start') ? call_user_func_array($class . '::on_start', array()) : null;

		if (method_exists($class, $task)) {
			echo call_user_func_array($class . '::' . $task, array($option, $id, $page, $task));
		} else {
			echo call_user_func_array($class . '::index', array($option, $id, $page, $task));
		}

		// контроллер может содержать метод вызываемый после окончания работы основного контроллера, но тоже вызовется
		method_exists($class, 'on_stop') ? call_user_func_array($class . '::on_stop', array()) : null;
	}

	/**
	 * joosAutoAdmin::listing()
	 *
	 * Генерация таблицы с записями
	 *
	 * @param object joosModel $obj
	 * @param array $obj_list
	 * @param object joosAdminPagenator $pagenav
	 * @param array $fields_list
	 */
	public static function listing(joosModel $obj, array $obj_list, joosAdminPagenator $pagenav, array $fields_list, $group_by = '') {

		// получаем название текущего компонента
		$option = joosRequest::param('option');

		// путь к текущим графическим элементам
		echo joosHTML::js_code('image_path ="' . joosConfig::get('admin_icons_path') . '"; _option="' . $option . '";');

		// подключаем js код библиотеки
		joosDocument::instance()
				->add_js_file(JPATH_SITE . '/core/libraries/autoadmin/media/js/autoadmin.js');

		$fields_info = $obj->get_fieldinfo();

		$header = $obj->get_tableinfo();

		$header_extra = self::get_extrainfo($obj);
		$header_extra = self::prepare_extra($obj, $header_extra);

		//Вывод заголовка
		echo self::header((isset($header['header_main']) ? $header['header_main'] : ''), $header['header_list'], $header_extra['for_header'], 'listing');

		//Вывод основного содержимого - таблицы с записями
		echo '<form action="index2.php" method="post" name="adminForm" id="adminForm">';
		echo '<table class="adminlist' . ($group_by ? ' drag' : '') . '" id="adminlist"><thead><tr>';
		echo '<th width="20px"><input type="checkbox" onclick="checkAll();" value="" name="toggle"></th>';

		$fields_to_table = array();
		foreach ($fields_list as $field) {
			if (isset($fields_info[$field]['in_admintable']) && $fields_info[$field]['in_admintable'] == TRUE) {
				$sortable = (isset($fields_info[$field]['sortable']) && $fields_info[$field]['sortable'] == true) ? ' class="column_sortable"' : '';
				$width = isset($fields_info[$field]['html_table_element_param']['width']) ? ' width="' . $fields_info[$field]['html_table_element_param']['width'] . '"' : '';
				$class = isset($fields_info[$field]['html_table_element_param']['class']) ? ' class="' . $fields_info[$field]['html_table_element_param']['class'] . '"' : '';

				echo '<th ' . $sortable . $width . $class . '>' . $fields_info[$field]['name'] . '</th>';
				$fields_to_table[] = $field;
			}
		}

		echo '</thead></tr>';

		$n = count($fields_to_table);
		$k = 1;
		$i = 0;
		foreach ($obj_list as $values) {
			$dop_class = $group_by ? $group_by . '-' . $values->$group_by : '';

			echo "\n\t" . '<tr class="row-' . $k . '" ' . ($group_by ? 'obj_ordering="' . $values->ordering . '"' : '') . ' obj_id="' . $values->{$obj->get_key_field()} . '" id="adminlist-row-' . $values->{$obj->get_key_field()} . '" rel="' . $dop_class . '">' . "\n\t";
			echo "\t" . '<td align="center">' . html::idBox($i, $values->{$obj->get_key_field()}) . '</td>' . "\n";
			for ($index = 0; $index < $n; $index++) {
				$current_value = isset($values->$fields_to_table[$index]) ? $values->$fields_to_table[$index] : null;
				$data = joosAutoAdmin::get_listing_html_element($obj, $fields_info[$fields_to_table[$index]], $fields_to_table[$index], $current_value, $values, $option);
				$class = isset($fields_info[$fields_to_table[$index]]['html_table_element_param']['class']) ? ' class="' . $fields_info[$fields_to_table[$index]]['html_table_element_param']['class'] . '"' : '';
				$align = isset($fields_info[$fields_to_table[$index]]['html_table_element_param']['align']) ? ' align="' . $fields_info[$fields_to_table[$index]]['html_table_element_param']['align'] . '" ' : '';

				echo "\t\t" . '<td ' . $align . $class . '>' . $data . '</td>' . "\n";
			}
			echo "\t" . '</tr>' . "\n";
			$k = 1 - $k;
			++$i;
		}

		echo '</tr></table>' . "\n";
		echo $pagenav->get_list_footer();
		echo "\n";
		echo self::footer();

		echo $header_extra['hidden_ellements'];
		echo forms::hidden('option', $option);
		echo forms::hidden('model', self::$model);
		echo forms::hidden('task', '');
		echo forms::hidden('boxchecked', '');
		echo forms::hidden('obj_name', get_class($obj));
		echo forms::hidden(joosCSRF::get_code(), 1);
		echo forms::close();
	}

	public static function get_listing_html_element(joosModel $obj, array $element_param, $key, $value, stdClass $values, $option) {

		$class_file = JPATH_BASE . '/app/plugins/autoadmin/list.' . $element_param['html_table_element'] . '.php';
		$class_name = 'autoadminList' . self::get_plugin_name($element_param['html_table_element']);

		if (!is_file($class_file)) {
			throw new joosAutoAdminFilePluginNotFoundException(sprintf(__('Файл плагина joosAutoAdmin %s  для вывода элемента %s не найден'), $class_file, $class_name));
		}

		require_once $class_file;

		if (!class_exists($class_name)) {
			throw new joosAutoAdminClassPlugionNotFoundException(sprintf(__('Класс для обработки %s средствами joosAutoAdmin в файле %s не найден'), $class_file, $class_name));
		}

		// ограничение на длину текста
		$text_limit = isset($element_param['html_table_element_param']['text_limit']) ? $element_param['html_table_element_param']['text_limit'] : false;
		if ($text_limit) {
			$value = joosText::character_limiter($value, $text_limit);
		}

		return call_user_func_array($class_name . '::render', array($obj, $element_param, $key, $value, $values, $option));
	}

	/**
	 * joosAutoAdmin::edit()
	 *
	 * Генерация формы добавления/редактирования записи
	 *
	 * @param object $obj
	 * @param object $obj_data
	 * @param array $params
	 */
	public static function edit(joosModel $obj, $obj_data, $params = null) {

		self::$model = get_class($obj);

		//Библиотека работы с табами
		$tabs = new htmlTabs();

		$option = joosRequest::param('option');

		//Настраиваем параметры HTML-разметки формы
		if (!$params) {
			$params = array(
				'wrap_begin' => '<table class="adminform joiadmin">',
				'wrap_end' => '</table>',
				'label_begin' => '<tr><td width="150" align="right" valign="top">',
				'label_end' => '</td>',
				'el_begin' => '<td>',
				'el_end' => '</td></tr>',
				'tab_wrap_begin' => '<tr><td>',
				'tab_wrap_end' => '</td></tr>',
			);
		}

		//Вывод заголовка страницы с формой
		$header = $obj->get_tableinfo(); //Получаем данные
		$component_title = isset($header['header_main']) ? $header['header_main'] : '';
		$header_text = $obj_data->{$obj->get_key_field()} > 0 ? $header['header_edit'] : $header['header_new'];

		//Выводим заголовок

		echo self::header($component_title, $header_text, array(), 'edit');

		// начинаем отлавливать поступаемый JS код
		self::$js_onformsubmit[] = '<script type="text/javascript" charset="utf-8">function submitbutton(pressbutton) {';

		//открываем форму
		echo forms::open('index2.php', array('name' => 'adminForm', 'id' => 'adminForm'));

		//Начало общего контейнера
		echo $params['wrap_begin'];

		//Получаем данные о элементах формы
		$fields_info = $obj->get_fieldinfo();
		foreach ($fields_info as $key => $field) {
			if (isset($field['editable']) && $field['editable'] == true):
				$v = isset($obj_data->$key) ? $obj_data->$key : '';
				//Вывод элемента
				echo self::get_edit_html_element($field, $key, $v, $obj_data, $params, $tabs);
			endif;
		}

		//Выводим скрытые поля формы
		echo forms::hidden($obj->get_key_field(), $obj_data->{$obj->get_key_field()}) . "\t"; // id объекта
		echo forms::hidden('option', $option) . "\t";
		echo forms::hidden('model', self::$model) . "\t";
		echo forms::hidden('task', 'save') . "\t";
		echo forms::hidden(joosCSRF::get_code(), 1); // элемент защиты от XSS
		//Конец общего контейнера
		echo $params['wrap_end'];

		//Закрываем форму
		echo forms::close();


		// закрываем JS вкрапления
		self::$js_onformsubmit[] = 'submitform( pressbutton );';
		self::$js_onformsubmit[] = '};</script>';

		echo "\n" . implode("\n", self::$js_onformsubmit) . "\n";
		echo self::footer();
	}

// получение типа элемента для формы редактирования
	public static function get_edit_html_element($element_param, $key, $value, $obj_data, $params, $tabs) {

		$class_file = JPATH_BASE . '/app/plugins/autoadmin/edit.' . $element_param['html_edit_element'] . '.php';
		$class_name = 'autoadminEdit' . self::get_plugin_name($element_param['html_edit_element']);

		if (!is_file($class_file)) {
			throw new joosAutoAdminFilePluginNotFoundException(sprintf(__('Файл плагина joosAutoAdmin %s  для редактирования элемента %s не найден'), $class_file, $class_name));
		}

		require_once $class_file;

		if (!class_exists($class_name)) {
			throw new joosAutoAdminClassPlugionNotFoundException(sprintf(__('Класс для обработки %s средствами joosAutoAdmin в файле %s не найден'), $class_file, $class_name));
		}

		return call_user_func_array($class_name . '::render', array($element_param, $key, $value, $obj_data, $params, $tabs));
	}

	public static function add_js_onformsubmit($js_raw_code) {
		self::$js_onformsubmit[] = $js_raw_code;
	}

// упрощенная система получения пагинатора
	public static function pagenav($total, $com_name = '') {

		$limit = (int) joosSession::get_user_state_from_request("{$com_name}_viewlistlimit", 'limit', joosConfig::get2('admin', 'list_limit', 25));
		$limitstart = (int) joosSession::get_user_state_from_request("{$com_name}_limitstart" . self::$model, 'limitstart', 0);

		return new joosAdminPagenator($total, $limitstart, $limit);
	}

	/**
	 * joosAutoAdmin::header()
	 * Вывод заголовка с управляющими элементами
	 *
	 * @param string $component_title Заголовок текущего компонента ('header_main')
	 * @param string $header Заголовок текущей станицы (берётся из метода `get_tableinfo()` текущей модели)
	 * @param array  $extra  Всяческие куртые штуки типа поля поиска, фильтров и т.п. (подтягивается из `get_extrainfo()` текущей модели)
	 * @param string $task   Параметр передается в случае, если необходимо вывести стандартный тулбар
	 *                         (т.е. когда метод вызывается из joosAutoAdmin::listing или joosAutoAdmin::edit)
	 * @return HTML-представление заголовка: название текущей страницы, субменю, системное сообщение, фильтры, тулбар (кнопки управления)
	 */
	public static function header($component_title = '', $header = '', array $extra = array(), $task = '') {

		$class = self::$class;

		$component_title = (isset(self::$component_title) ? self::$component_title . ':' : ($component_title ? $component_title . ':' : ''));

		$return = '';

		//Заголовок страницы + тулбар
		if (isset($class::$submenu)) {
			$return = '<div class="page_title"><h1 class="title"><span>' . $component_title . '</span></h1>';
			$return .= joosAutoAdmin::submenu() . '</div>';
		}

		$return .= '<div class="page_subtitle"><h2>' . $header . '</h2>' . self::toolbar($task) . '</div>';

		//Вывод системного соощения
		ob_start();
		joosModuleAdmin::load_by_name('flashmessage');
		$return .= ob_get_contents();
		ob_end_clean();

		$return .= '<div id="component_form">';

		//Поиск, фильтры и т.п.
		$return .= adminHTML::controller_header(false, 'config', $extra);

		return $return;
	}

	//Определение заголовка компонента по его названию
	//Требуется в компонентах, которые выступают в качестве интерфейса
	//например: компонент категорий, компонент настроек и т .п
	public static function get_component_title($name) {
		$admin_model = 'admin' . ucfirst($name);

		//joosLoader::admin_model($name);
		$admin_model = new $admin_model;
		$titles = $admin_model->get_tableinfo();
		$component_title = isset($titles['header_main']) ? $titles['header_main'] : '';
		return $component_title;
	}

	public static function toolbar($task = '') {

		$class = self::$class;
		if (isset($class::$toolbars) && isset($class::$toolbars[self::$task])) {
			return $class::$toolbars[self::$task];
		} else if ($task) {
			return JoiAdminToolbar::$task();
		}
		return false;
	}

	public static function submenu() {

		$class = self::$class;

		if (isset($class::$submenu)) {

			$return = array();
			foreach ($class::$submenu as $href) {
				$return[] = '<li>' . ($href['active'] == false ? HTML::anchor($href['href'], $href['name']) : '<span>' . $href['name'] . '</span>') . '</li>';
			}

			return '<div class="submenu"><ul class="listreset nav-horizontal">' . implode('', $return) . '</ul></div>';
		}
	}

	public static function footer() {
		return '</div>';
	}

// автоматическя обработка яксовых операций
	public static function autoajax() {

		$option = joosRequest::param('option');
		$file = joosCore::path($option, 'class');

		(is_file($file) || is_file($file = joosCore::path($option, 'admin_class'))) ? require_once $file : null;

		// выполняемая задача
		$task = joosRequest::param('task');
		// идентификатор запрашиваемого элемента
		$obj_id = joosRequest::int('obj_id', 0, $_POST);
		// ключ-название запрашиваемого элемента
		$obj_key = joosRequest::post('obj_key');
		// название объекта запрашиваемого элемента
		$obj_name = joosRequest::param('obj_name');
		if (!$obj_name) {
			return;
		}
		// пустой объект для складирования результата
		$return_onj = new stdClass();

		if (class_exists($obj_name)) {
			// создаём объект класса
			$obj = new $obj_name;


			switch ($task) {
				case 'statuschanger':
					$obj->load($obj_id);
					// меняем состояние объекта на противоположное
					$obj->change_state($obj_key);

					// получаем настройки полей
					$fields_info = $obj->get_fieldinfo();

					// формируем ответ из противоположных элементов текущему состоянию
					$return_onj->image = $fields_info[$obj_key]['html_table_element_param']['images'][!$obj->$obj_key];
					$return_onj->mess = $fields_info[$obj_key]['html_table_element_param']['statuses'][!$obj->$obj_key];
					break;

				case 'ordering':
					$obj->load($obj_id);
					$scope = joosRequest::post('scope');
					$new_ordering = joosRequest::post('val');

					//$old_order = $obj->ordering;
					//$where = '1=1';
					//if ($scope) {
					//foreach (explode(',', $scope) as $filed) {
					//$where .=' AND ' . $filed . ' = "' . $obj->$filed . '"';
					//}
					//}
					//$obj->updateOrder($where);
					//$return_onj->mess = 'old - ' . $old_order . ' new - ' . $obj->ordering . '';
					$obj->ordering = $new_ordering;
					$obj->store();
					break;

				case 'reorder':
					$objs = joosRequest::post('objs');
					$return_onj->mess = implode('; ', $objs);

					$old_order = array();
					$new_order = array();
					foreach ($objs as $_obj) {
						$val = explode(':', $_obj);
						$old_order[] = $val[1];
						$new_order[] = $val[0];
					}

					$min = min($old_order);
					$count = count($objs);

					$mess = '';
					$sql = '';
					$i = $min;
					foreach ($new_order as $id) {
						$order =
								$query = 'UPDATE ' . $obj->get('_tbl') . ' SET ordering = ' . $i . ' WHERE id = ' . $id;
						$obj->get('_db')->set_query($query)->query();
						++$i;
						//$mess .= $query . "\n";
					}
					//$obj->ordering = $new_ordering;
					//$obj->store();
					$return_onj->mess = $mess;
					$return_onj->min = $min;
					break;

				default:
					return;
					break;
			}

			echo json_encode($return_onj);
			return;
		}

		$return_onj->image = 'error.png';
		$return_onj->mess = 'error-class';

		echo json_encode($return_onj);
		return;
	}

	private static function prepare_extra(joosModel $obj, array $extra_data) {

		if (self::$data === NULL) {

			$results = array();
			$hidden_elements = array();
			$wheres_filter = array('true');
			$wheres_search = array();

			foreach ($extra_data as $key => $value) {
				switch ($key) {


					case 'search':
						$results[] = forms::label(array('for' => 'search_elements'), 'Поиск');

						$search_value = joosSession::get_user_state_from_request("search-" . $obj->classname(), 'search', false);

						$results[] = forms::input(array('name' => 'search_elements', 'id' => 'search_elements'), $search_value);
						$hidden_elements[] = forms::hidden('search', $search_value);

						if ($search_value !== false && joosString::trim($search_value) != '') {
							foreach ($value as $field_name => $selected_value) {
								$wheres_search[] = sprintf('%s LIKE ( %s )', joosDatabase::instance()->name_quote($selected_value), joosDatabase::instance()->quote("%" . $search_value . "%"));
							}
						}
						break;

					case 'filter':

						foreach ($value as $params_key => $params_value) {

							$field_name = $params_key;
							$field_title = $value[$field_name]['name'];

							$results[] = forms::label(array('for' => 'filter_' . $field_name), $field_title);
							$datas_for_select = array(-1 => 'Всё сразу');
							$datas_for_select += ( isset($value[$field_name]['call_from']) && is_callable($value[$field_name]['call_from'])) ? call_user_func($value[$field_name]['call_from'], $obj, $params_key) : array();

							$selected_value = joosSession::get_user_state_from_request("filter-" . '-' . $field_name . '-' . $obj->classname(), $field_name, -1);
							$selected_value = $selected_value === '0' ? "0" : $selected_value;

							$results[] = forms::dropdown(array('name' => 'filter_' . $field_name, 'obj_name' => $field_name, 'class' => 'filter_elements', 'options' => $datas_for_select, 'selected' => $selected_value));

							$hidden_elements[] = forms::hidden($field_name, $selected_value);
							if (($selected_value && $selected_value != -1) OR $selected_value === '0') {
								$wheres_filter[] = sprintf('%s=%s', joosDatabase::instance()->name_quote($field_name), joosDatabase::instance()->quote($selected_value));
							}
						}
						break;

					case 'extrafilter':
						$datas_for_select = array(-1 => 'Всё сразу');
						foreach ($value as $params_key => $params_value) {

							$field_name = $params_key;

							$datas_for_select += array($params_key => $value[$field_name]['name']);
						}

						$selected_value = joosSession::get_user_state_from_request("extrafilter-" . $obj->classname(), 'filter_extrafilter', -1);

						$results[] = forms::label(array('for' => 'filter_extrafilter'), 'Фильтр');
						$results[] = forms::dropdown(array('name' => 'filter_extrafilter_selector', 'class' => 'extrafilter_elements', 'options' => $datas_for_select, 'selected' => $selected_value));
						$hidden_elements[] = forms::hidden('filter_extrafilter', $selected_value);

						//self::$data_overload = ( $selected_value && isset($value[$selected_value]['call_from']) && is_callable($value[$selected_value]['call_from']) ) ? call_user_func($value[$selected_value]['call_from'], $obj) : array();
						self::$data_overload = ($selected_value && isset($value[$selected_value]['call_from']) && is_callable($value[$selected_value]['call_from'])) ? $value[$selected_value]['call_from'] : array();
						break;

					default:
						break;
				}
			}

			$wheres = array(
				implode(' AND ', $wheres_filter),
			);

			if (count($wheres_search) > 0) {
				$wheres[] = ' (' . implode(' OR ', $wheres_search) . ' )';
			}

			self::$data = array(
				'for_header' => $results,
				'hidden_ellements' => implode("\n", $hidden_elements),
				'wheres' => implode(' AND ', $wheres),
				'data_overload' => self::$data_overload,
			);
		}

		return self::$data;
	}

	private static function get_extrainfo(joosModel $obj) {

		$fields_info = $obj->get_fieldinfo();
		$header_extra = $obj->get_extrainfo();

		if (isset($fields_info['state'])) {
			$header_extra['filter'] = isset($header_extra['filter']) ? $header_extra['filter'] : array();
			$header_extra['filter'] += array(
				'state' => array(
					'name' => 'Состояние',
					'call_from' => 'joosAutoAdmin::get_state_selector'
				),
			);
		}
		return $header_extra;
	}

	public static function get_count(joosModel $obj) {

		$header_extra = self::get_extrainfo($obj);
		$header_extra = self::prepare_extra($obj, $header_extra);

		$params = array(
			'where' => $header_extra['wheres'],
			'only_count' => true
		);

		return self::$data_overload ? call_user_func(self::$data_overload, $params) : $obj->count('WHERE ' . $header_extra['wheres']);
	}

	public static function get_list(joosModel $obj, $params) {

		$header_extra = self::get_extrainfo($obj);
		$header_extra = self::prepare_extra($obj, $header_extra);

		$params += array('where' => $header_extra['wheres']);

		return self::$data_overload ? call_user_func(self::$data_overload, $params) : $obj->get_list($params);
	}

	public static function get_state_selector(joosModel $obj, $params_key) {
		return array(
			0 => 'Не активно',
			1 => 'Активно',
		);
	}

	static private function camelizeCallback($match) {
		return strtoupper($match[1]);
	}

	private static function get_plugin_name($string) {

		if (strpos($string, '_') === FALSE) {
			$string = ucfirst($string);
		} else {
			$string[0] = strtolower($string[0]);
			$string = ucfirst($string);
			$string = preg_replace_callback('#_([a-z0-9])#i', array('self', 'camelizeCallback'), $string);
		}

		return $string;
	}

}

class adminHTML {

	// шапка компонентов
	public static function controller_header($title, $class = 'config', array $extra = array()) {
		$extra = count($extra) > 0 ? self::prepare_header_extra($extra) : '';
		$title = $title != false ? sprintf('<th class="%s">%s</th>', $class, $title) : '';
		return sprintf('<table class="adminheading"><tbody><tr>%s%s</tr></tbody></table>', $title, $extra);
	}

	private static function prepare_header_extra(array $elemets_array) {
		return '<td align="right">' . implode("\n", $elemets_array) . '</td>';
	}

}

class JoiAdminToolbar {

	private static $add_button = array();

	public static function listing() {
		ob_start();

		mosMenuBar::startTable();
		mosMenuBar::addNew('create');
		mosMenuBar::deleteList();
		echo implode('', self::$add_button);
		mosMenuBar::endTable();

		$return = ob_get_contents();
		ob_end_clean();

		return $return;
	}

	public static function edit() {
		ob_start();

		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::custom('save_and_new', '-save-and-new', '', __('Сохранить и добавить'), false);
		mosMenuBar::apply();
		echo implode('', self::$add_button);
		joosRequest::int('id', false) ? mosMenuBar::cancel('cancel', __('Закрыть')) : mosMenuBar::cancel();
		mosMenuBar::endTable();

		$return = ob_get_contents();
		ob_end_clean();

		return $return;
	}

	public static function add_button($button) {
		self::$add_button[] = $button;
	}

}

class mosMenuBar {

	public static function startTable() {
		?><div id="toolbar"><ul class="listreset"><?php
	}

	public static function ext($alt = _BUTTON, $href = '', $class = '', $extra = '') {
		?><li><a class="tb-ext<?php echo $class; ?>"
					   href="<?php echo $href; ?>" <?php echo $extra; ?>><span><?php echo $alt; ?></span></a></li><?php
	}

	public static function custom($task = '', $icon = '', $iconOver = '', $alt = '', $listSelect = true) {
		if ($listSelect) {
			$href = "javascript:if (document.adminForm.boxchecked.value == 0){ alert('" . __('Необходимо выбрать хоть один элемент') . "');}else{submitbutton('$task')}";
		} else {
			$href = "javascript:submitbutton('$task')";
		}
		?><li><a class="tb-custom<?php echo $icon; ?>" href="<?php echo $href; ?>"><span><?php echo __($alt); ?></span></a></li><?php
		}

		public static function addNew($task = 'new', $alt = 'Создать') {
		?><li><a class="tb-add-new"
					   href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo __($alt); ?></span></a></li><?php
	}

	public static function copy($task = 'copy', $alt = 'Копировать') {
		?><li><a class="tb-add-new"
					   href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo __($alt); ?></span></a></li><?php
		}

		public static function publish($task = 'publish', $alt = 'Показать') {
		?><li><a class="tb-publish"
					   href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo __($alt); ?></span></a></li><?php
		}

		public static function publishList($task = 'publish', $alt = 'Показать') {
		?><li><a class="tb-publish-list"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo __('Выберите элементы для публикации') ?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function unpublish($task = 'unpublish', $alt = 'Скрыть') {
		?><li><a class="tb-unpublish"
					   href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function unpublishList($task = 'unpublish', $alt = 'Скрыть') {
		?><li><a class="tb-unpublish-list"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo __('Выберите элементы для скрытия') ?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function editList($task = 'edit', $alt = 'Редактировать') {
		?><li><a class="tb-edit-list"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo __('Выберите элемент для редактирования') ?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo __($alt); ?></span></a></li><?php
		}

		public static function deleteList($msg = '', $task = 'remove', $alt = 'Удалить') {
		?><li><a class="tb-delete-list"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo __('Выберите элементы для удаления') ?>'); } else if (confirm('<?php echo __('Уверены в необходимости удаления объектов?') ?> <?php echo $msg; ?>')){ submitbutton('<?php echo $task; ?>');}"><span><?php echo __($alt); ?></span></a></li><?php
		}

		public static function trash($task = 'remove', $alt = 'Переместить в корзину', $check = true) {
			if ($check) {
				$js = "javascript:if (document.adminForm.boxchecked.value == 0){ alert('" . __('Выберите элементы для перемещения в корзину') . "'); } else { submitbutton('$task');}";
			} else {
				$js = "javascript:submitbutton('$task');";
			}
		?><li><a class="tb-trash" href="<?php echo $js; ?>"><span><?php echo __($alt) ?></span></a></li><?php
		}

		public static function apply($task = 'apply', $alt = 'Применить') {
		?><li><a class="tb-apply"
					   href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo __($alt); ?></span></a></li><?php
	}

	public static function save($task = 'save', $alt = 'Сохранить') {
		?><li><a class="tb-save"
					   href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo __($alt); ?></span></a></li><?php
		}

		public static function savenew() {
		?><li><a class="tb-save-new"
					   href="javascript:submitbutton('savenew');"><span><?php echo __('Сохранить') ?></span></a></li><?php
		}

		public static function saveedit() {
		?><li><a class="tb-save-edit"
					   href="javascript:submitbutton('saveedit');"><span><?php echo __('Сохранить') ?></span></a></li><?php
		}

		public static function cancel($task = 'cancel', $alt = 'Закрыть') {
		?><li><a class="tb-cancel"
					   href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo __($alt); ?></span></a></li><?php
		}

		public static function back($alt = _MENU_BACK, $href = 'javascript:window.history.back();') {
		?><li><a class="tb-back" href="<?php echo $href; ?>"><span><?php echo __($alt); ?></span></a></li><?php
		}

		public static function divider() {
		?><li>&nbsp;|&nbsp;</li><?php
	}

	public static function endTable() {
		?></ul></div><?php
	}

}

class joosAutoAdminFilePluginNotFoundException extends joosException {
	
}

class joosAutoAdminClassPlugionNotFoundException extends joosException {
	
}