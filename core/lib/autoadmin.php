<?php
/**
 * joosAutoAdmin - класс для автоматической генерации административного интерфейса компонента
 *
 * @version 1.0
 * @package Joostina CMS
 * @subpackage Libraries
 * @filename joiadmin.php
 * @author JoostinaTeam
 * @copyright (C) 2007-2010 Joostina Team
 * @license see license.txt
 *
 * */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/*
 * =================================
 * Вывод заголовка-разделителя:
 * =================================
 * ПРИМЕР:
 * ----------------------------------
 * sep1' => array(
 *                 'name' => 'Заголовок области',
 *                 'editable' => true,
 *                 'html_edit_element' => 'h3'
 *             )
 * ----------------------------------
 * ОПИСАНИЕ:
 * ----------------------------------
 * [sep1] = произвольное имя, в результатах выполнения не фигурирует
 * [name] = текст заголовка
 * [editable] = true (указывать обязательно)
 * [html_edit_element] => 'h3' (указывать обязательно)
 *
 *
 * =================================
 * Вывод области с табами:
 * =================================
 * ПРИМЕР:
 * ----------------------------------
  //Начало области с табами
  'startPane1' => array(
  'name' => '-',
  'editable' => true,
  //произвольное, уникальное имя, используется для задания id HTML-контейнера (div-а)
  'html_edit_element' => 'start_pane'
  ),
  //Начинается первый таб
  //[tab_1] - произвольное имя, в выводе не участвует
  'tab_1' => array(
  'name' => 'Первая вкладка', //Заголовок таба
  'editable' => true,
  'html_edit_element' => 'start_tab' //ID таба
  ),
  //Поля внутри первого таба
  'поле_формы' => array(
  .....................
  ),
  'поле_формы' => array(
  .....................
  ),
  //Первый таб закончился, закрываем его
  //[tab_1_end] - произвольное имя, в выводе не участвует
  'tab_1_end' => array(
  'name' => '-',
  'editable' => true,
  'html_edit_element' => 'end_tab'
  ),

  //Начинается второй таб
  'tab_2' => array(
  'name' => 'Вторая вкладка',
  'editable' => true,
  'html_edit_element' => 'start_tab'
  ),
  'поле_формы' => array(
  .....................
  ),
  'поле_формы' => array(
  .....................
  ),
  //Второй таб закончился
  'tab_2_end' => array(
  'name' => '-',
  'editable' => true,
  'html_edit_element' => 'end_tab'
  ),
  //Закрываем область с табами
  //endPane1 - произвольное имя
  'endPane1' => array(
  'name' => '-',
  'editable' => true,
  'html_edit_element' => 'end_pane'
  )
 */
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


		JDEBUG ? joosDebug::add('joosAutoAdmin::dispatch() - ' . $class . '::' . $task) : null;

		//joosLoader::admin_template_view('joiadmin');
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
	 * @param object joosDBModel $obj
	 * @param array $obj_list
	 * @param object joosAdminPagenator $pagenav
	 * @param array $fields_list
	 */
	public static function listing(joosDBModel $obj, array $obj_list, joosAdminPagenator $pagenav, array $fields_list, $group_by = '') {

		// получаем название текущего компонента
		$option = joosRequest::param('option');

		// путь к текущим графическим элементам
		echo joosHTML::js_code('image_path ="' . joosConfig::get('admin_icons_path') . '"; _option="' . $option . '";');

		// класс работы с формами
		joosLoader::lib('forms');

		// подключаем js код библиотеки
		joosDocument::instance()->add_js_file(JPATH_SITE . '/core/libraries/system/joiadmin/media/js/joiadmin.js');


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
		echo forms::hidden(joosSpoof::get_code(), 1);
		echo forms::close();
	}

	public static function get_listing_html_element(joosDBModel $obj, array $element_param, $key, $value, stdClass $values, $option) {

		static $element_datas = array();

		$element = '';

		// ограничение на длину текста
		$text_limit = isset($element_param['html_table_element_param']['text_limit']) ? $element_param['html_table_element_param']['text_limit'] : false;
		if ($text_limit) {
			joosLoader::lib('text', 'joostina');
			$value = joosText::character_limiter($value, $text_limit);
		}
		;

		switch ($element_param['html_table_element']) {

			// тип - просто текст
			case 'value':
				$element .= $value;
				//$element .= "\n\t";
				break;

			// тип - ссылка редактирования
			case 'editlink':
				$element .= '<a href="index2.php?option=' . $option . (self::$model ? '&model=' . self::$model : '') . '&task=edit&' . $obj->get_key_field() . '=' . $values->{$obj->get_key_field()} . '">' . $value . '</a>';
				break;

			// тип одно значение из массива
			case 'one_from_array':
				$datas_for_select = array();
				// избавления из от множества запросов
				if (!isset($element_datas[$key])) {
					// сохраняем полученные значения в статичном мессиве
					$element_datas[$key] = (isset($element_param['html_table_element_param']['call_from']) && is_callable($element_param['html_table_element_param']['call_from'])) ? call_user_func($element_param['html_table_element_param']['call_from']) : $datas_for_select;
				}
				;
				$datas_for_select = $element_datas[$key];

				$datas_for_select = isset($element_param['html_table_element_param']['options']) ? $element_param['html_table_element_param']['options'] : $datas_for_select;
				$element .= isset($datas_for_select[$value]) ? $datas_for_select[$value] : $value;
				break;

			// тип - аякс выбор состояния
			case 'statuschanger':

				$images = isset($element_param['html_table_element_param']['images'][$value]) ? $element_param['html_table_element_param']['images'][$value] : 'error.png';
				$text = isset($element_param['html_table_element_param']['statuses'][$value]) ? $element_param['html_table_element_param']['statuses'][$value] : 'ERROR';

				$element .= '<img class="img-mini-state" src="' . joosConfig::get('admin_icons_path') . $images . '" id="img-pub-' . $values->id . '" obj_id="' . $values->id . '" obj_key="' . $key . '" alt="' . $text . '" />';
				break;

			// тип - контролы для сортировки
			case 'ordering':
				$element .= self::order($values);
				break;


			case 'extra':
				$element .= ( isset($element_param['html_table_element_param']['call_from']) && is_callable($element_param['html_table_element_param']['call_from'])) ? call_user_func($element_param['html_table_element_param']['call_from'], $values) : $datas_for_select;
				break;

			// по умолчанию элемент выведем скрытым
			default:
				$element .= "\n\t";
				$element = '<!-- no-viewed :: ' . $key . ' -->';
				break;
		}

		return $element;
	}

	public static function order($item) {
		return '<img src="' . joosConfig::get('admin_icons_path') . '/cursor_drag_arrow.png" alt="Переместить" />';
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
	public static function edit(joosDBModel $obj, $obj_data, $params = null) {

		self::$model = get_class($obj);

		//Подключаем библиотеку работы с формами
		joosLoader::lib('forms');

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

		// устанавливаем туллбар для страницы создания/редактирования (только если он не установлен компонентом)
		//if (!joosMainframe::instance()->getPath('toolbar')){
		//joosMainframe::instance(true)->setPath('toolbar', JPATH_BASE . '/includes/libraries/joostina/joiadmin/html/edit_toolbar.php');
		//}
		//echo joiadmin::submenu(self::$submenu);
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
		echo forms::hidden(joosSpoof::get_code(), 1); // элемент защиты от XSS
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

		$element = '';

		switch ($element_param['html_edit_element']) {

			// тип - простое одностроное поле редактирования
			case 'edit':
				$element .= $params['label_begin'];
				$element .= forms::label(
								array(
							'for' => $key
								), $element_param['name']);
				$element .= $params['label_end'];
				$element .= $params['el_begin'];
				$element .= forms::input(
								array(
							'name' => $key,
							'class' => 'text_area',
							'size' => 100,
							'style' => (isset($element_param['html_edit_element_param']['style']) ? $element_param['html_edit_element_param']['style'] : 'width:100%'),
								), $value);
				$element .= $params['el_end'];
				break;

			// тип - большое текстовое поле
			case 'text':
				$element .= $params['label_begin'];
				$element .= forms::label(
								array(
							'for' => $key
								), $element_param['name']);
				$element .= $params['label_end'];
				$element .= $params['el_begin'];
				$element .= forms::textarea(
								array(
							'name' => $key,
							'class' => 'text_area',
							'rows' => (isset($element_param['html_edit_element_param']['rows']) ? $element_param['html_edit_element_param']['rows'] : 10),
							'cols' => (isset($element_param['html_edit_element_param']['cols']) ? $element_param['html_edit_element_param']['cols'] : 40),
							'style' => (isset($element_param['html_edit_element_param']['style']) ? $element_param['html_edit_element_param']['style'] : 'width:100%'),
								), $value);
				$element .= $params['el_end'];
				break;

			// тип - большое текстовое поле
			case 'text_area':
				$element .= $params['label_begin'];
				$element .= forms::label(
								array(
							'for' => $key
								), $element_param['name']);
				$element .= $params['label_end'];
				$element .= $params['el_begin'];
				$element .= forms::textarea(
								array(
							'name' => $key,
							'class' => 'text_area',
							'rows' => (isset($element_param['html_edit_element_param']['rows']) ? $element_param['html_edit_element_param']['rows'] : 10),
							'cols' => (isset($element_param['html_edit_element_param']['cols']) ? $element_param['html_edit_element_param']['cols'] : 40),
							'style' => (isset($element_param['html_edit_element_param']['style']) ? $element_param['html_edit_element_param']['style'] : 'width:100%'),
								), $value);
				$element .= $params['el_end'];
				break;

			// тип - большое текстовое поле с редактором
			case 'text_area_wysiwyg':
				$element .= $params['label_begin'];
				$element .= forms::label(
								array(
							'for' => $key
								), $element_param['name']);
				$element .= $params['label_end'];
				$element .= $params['el_begin'];

				$editor_params = array(
					'editor' => isset($element_param['html_edit_element_param']['editor']) ? $element_param['html_edit_element_param']['editor'] : 'elrte',
					'rows' => isset($element_param['html_edit_element_param']['rows']) ? $element_param['html_edit_element_param']['rows'] : null,
					'cols' => isset($element_param['html_edit_element_param']['cols']) ? $element_param['html_edit_element_param']['cols'] : null,
					'width' => isset($element_param['html_edit_element_param']['width']) ? $element_param['html_edit_element_param']['width'] : '"100%"',
					'height' => isset($element_param['html_edit_element_param']['height']) ? $element_param['html_edit_element_param']['height'] : '200px',
				);

				$element .= joosEditor::display($key, $value, $editor_params);
				self::$js_onformsubmit[] = joosEditor::get_content($key);
				$element .= $params['el_end'];
				break;

			// тип - чекбокс
			case 'checkbox':
				$element .= $params['label_begin'];
				$element .= forms::label(
								array(
							'for' => $key
								), (isset($element_param['html_edit_element_param']['text']) ? $element_param['html_edit_element_param']['text'] : $element_param['name']));
				$element .= $params['label_end'];
				$element .= forms::hidden($key, 0);
				$element .= $params['el_begin'];
				$element .= forms::checkbox(
								array(
							'name' => $key,
							'class' => 'text_area',
								), 1, $value);
				$element .= $params['el_end'];
				break;

			// тип - выпадающий список
			case 'option':
				$element .= $params['label_begin'];
				$element .= forms::label(
								array(
							'for' => $key
								), (isset($element_param['html_edit_element_param']['text']) ? $element_param['html_edit_element_param']['text'] : $element_param['name']));

				$element .= $params['label_end'];
				$element .= $params['el_begin'];
				$datas_for_select = array();
				$datas_for_select = (isset($element_param['html_edit_element_param']['call_from']) && is_callable($element_param['html_edit_element_param']['call_from'])) ? call_user_func($element_param['html_edit_element_param']['call_from'], $value) : $datas_for_select;
				$datas_for_select = isset($element_param['html_edit_element_param']['options']) ? $element_param['html_edit_element_param']['options'] : $datas_for_select;

				$element .= forms::dropdown(array('name' => $key, 'options' => $datas_for_select, 'selected' => $value));

				$element .= $params['el_end'];
				break;

			// тип - произвольнеое расширенное поле
			case 'extra':
				// скрываем левую колонку с названием поля
				if (!isset($element_param['html_edit_element_param']['hidden_label'])) {
					$element .= $params['label_begin'];
					$element .= forms::label(
									array(
								'for' => $key
									), (isset($element_param['html_edit_element_param']['text']) ? $element_param['html_edit_element_param']['text'] : $element_param['name']));

					$element .= $params['label_end'];
				}
				$element .= $params['el_begin'];
				$element .= ( isset($element_param['html_edit_element_param']['call_from']) && is_callable($element_param['html_edit_element_param']['call_from'])) ? call_user_func($element_param['html_edit_element_param']['call_from'], $obj_data) : $datas_for_select;
				$element .= forms::hidden('extrafields[]', $key);
				$element .= $params['el_end'];
				break;

			// тип - json
			case 'json':
				$_add_data = isset($element_param['html_edit_element_param']['call_params']) ? $element_param['html_edit_element_param']['call_params'] : null;
				$data = (isset($element_param['html_edit_element_param']['call_from']) && is_callable($element_param['html_edit_element_param']['call_from'])) ? call_user_func($element_param['html_edit_element_param']['call_from'], $obj_data, $_add_data) : null;

				if (!$data) {
					break;
				}

				$main_key = $key;
				$values = $obj_data->$main_key;

				foreach ($data as $key => $field) {
					if (isset($field['editable']) && $field['editable'] == true) {
						$v = isset($values[$key]) ? $values[$key] : '';
						$element .= self::get_edit_html_element($field, $main_key . '[' . $key . ']', $v, $obj_data, $params, $tabs);
					}
				}
				break;


			case 'access':
				$data = (isset($element_param['html_edit_element_param']['call_from']) && is_callable($element_param['html_edit_element_param']['call_from'])) ? call_user_func($element_param['html_edit_element_param']['call_from'], $obj_data) : null;

				if (!$data) {
					break;
				}

				joosLoader::admin_model('access');
				$access = new Access;
				$access->fill_rights($data['section'], $data['subsection']);


				$element .= $params['label_begin'];
				$element .= forms::label(
								array(
							'for' => $key
								), (isset($element_param['html_edit_element_param']['text']) ? $element_param['html_edit_element_param']['text'] : $element_param['name']));

				$element .= $params['label_end'];
				$element .= $params['el_begin'];
				$element .= $access->draw_config_table();
				$element .= $params['el_end'];

				break;

			case 'params':
				$data = (isset($element_param['html_edit_element_param']['call_from']) && is_callable($element_param['html_edit_element_param']['call_from'])) ? call_user_func($element_param['html_edit_element_param']['call_from'], $obj_data) : null;

				if (!$data) {
					break;
				}

				$element .= $params['label_begin'];
				$element .= forms::label(
								array(
							'for' => $key
								), (isset($element_param['html_edit_element_param']['text']) ? $element_param['html_edit_element_param']['text'] : $element_param['name']));

				$element .= $params['label_end'];
				$element .= $params['el_begin'];

				$main_key = $key;
				$values = $obj_data->$main_key;

				$element .= '<table class="admin_params">';
				foreach ($data as $key => $field) {
					if (isset($field['editable']) && $field['editable'] == true) {
						$v = isset($values[$key]) ? $values[$key] : '';
						$element .= self::get_edit_html_element($field, $main_key . '[' . $key . ']', $v, $obj_data, $params, $tabs);
					}
				}
				$element .= '</table>';


				$element .= $params['el_end'];

				break;


			case 'extra_fields':
				$data = (isset($element_param['html_edit_element_param']['call_from']) && is_callable($element_param['html_edit_element_param']['call_from'])) ? call_user_func($element_param['html_edit_element_param']['call_from'], $obj_data) : null;

				if (!$data) {
					break;
				}

				$element .= $params['label_begin'];
				$element .= forms::label(
								array(
							'for' => $key
								), (isset($element_param['html_edit_element_param']['text']) ? $element_param['html_edit_element_param']['text'] : $element_param['name']));

				$element .= $params['label_end'];
				$element .= $params['el_begin'];

				$main_key = $key;
				$values = isset($data['values']) ? $data['values'] : array();

				$element .= '<table class="admin_extrafields">';
				foreach ($data['rules'] as $key => $field) {
					if (isset($field['editable']) && $field['editable'] == true) {
						$v = isset($values[$key]) ? $values[$key] : '';
						$element .= self::get_edit_html_element($field, $main_key . '[' . $key . ']', $v, $obj_data, $params, $tabs);
					}
				}
				$element .= '</table>';
				$element .= $params['el_end'];

				break;

			// тип - скрытое поле с идентификатором текущего пользователя
			case 'current_user_id':
				global $my;
				$element .= forms::hidden($key, $my->id);
				break;

			case 'h3':
				$element .= $params['label_begin'];
				$element .= $params['label_end'];
				$element .= $params['el_begin'];
				$element .= '<h3>' . $element_param['name'] . '</h3>';
				$element .= $params['el_end'];
				break;

			case 'start_pane':
				$element .= $params['tab_wrap_begin'];
				$element .= $tabs->startPane($key, 1);
				break;

			case 'end_pane':
				$element .= $tabs->endPane();
				$element .= $params['tab_wrap_end'];
				break;

			case 'start_tab':
				$element .= $tabs->startTab($element_param['name'], $key, 1);
				$element .= $params['wrap_begin'];

				break;

			case 'end_tab':
				$element .= $params['wrap_end'];
				$element .= $tabs->endTab();
				break;

			case 'tags':
				$tags = new Tags;

				$element .= $params['label_begin'];
				$element .= forms::label(
								array(
							'for' => $key
								), $element_param['name']);
				$element .= $params['label_end'];
				$element .= $params['el_begin'];
				$element .= $tags->display_object_tags_edit($obj_data);
				$element .= $params['el_end'];

				break;

			// тип - прямой вывод значения
			case 'value':
				$element .= $params['label_begin'];
				$element .= forms::label(
								array(
							'for' => $key
								), $element_param['name']);
				$element .= $params['label_end'];
				$element .= $params['el_begin'];
				$element .= $value;
				$element .= $params['el_end'];
				break;

			// тип - скрытое поле
			case 'hidden':
				$element .= forms::hidden($key, $value);
				break;

			// по умолчанию поле вывод закомментированным
			default:
				$element .= "\n\t";
				$element .= '<!-- no-viewed :: ' . $key . ' -->';
				break;
		}

		return $element;
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
		joosModule::load_by_name('adminmsg');
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
		joosLoader::admin_model($name);
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
			joosLoader::lib('html');
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

	private static function prepare_extra(joosDBModel $obj, array $extra_data) {

		if (self::$data === NULL) {


			joosLoader::lib('forms');

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

	private static function get_extrainfo(joosDBModel $obj) {

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

	public static function get_count(joosDBModel $obj) {

		$header_extra = self::get_extrainfo($obj);
		$header_extra = self::prepare_extra($obj, $header_extra);

		$params = array(
			'where' => $header_extra['wheres'],
			'only_count' => true
		);

		return self::$data_overload ? call_user_func(self::$data_overload, $params) : $obj->count('WHERE ' . $header_extra['wheres']);
	}

	public static function get_list(joosDBModel $obj, $params) {

		$header_extra = self::get_extrainfo($obj);
		$header_extra = self::prepare_extra($obj, $header_extra);

		$params += array('where' => $header_extra['wheres']);

		return self::$data_overload ? call_user_func(self::$data_overload, $params) : $obj->get_list($params);
	}

	public static function get_state_selector(joosDBModel $obj, $params_key) {
		return array(
			0 => 'Не активно',
			1 => 'Активно',
		);
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
		mosMenuBar::custom('save_and_new', '-save-and-new', '', _SAVE_AND_ADD, false);
		mosMenuBar::apply();
		echo implode('', self::$add_button);
		joosRequest::int('id', false) ? mosMenuBar::cancel('cancel', _CLOSE) : mosMenuBar::cancel();
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
			$href = "javascript:if (document.adminForm.boxchecked.value == 0){ alert('" . _PLEASE_CHOOSE_ELEMENT . "');}else{submitbutton('$task')}";
		} else {
			$href = "javascript:submitbutton('$task')";
		}
		?><li><a class="tb-custom<?php echo $icon; ?>" href="<?php echo $href; ?>"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function customX($task = '', $class = '', $iconOver = '', $alt = '', $listSelect = true) {
			if ($listSelect) {
				$href = "javascript:if (document.adminForm.boxchecked.value == 0){ alert('" . _PLEASE_CHOOSE_ELEMENT . "');}else{submitbutton('$task')}";
			} else {
				$href = "javascript:submitbutton('$task')";
			}
		?><li><a class="tb-custom-x<?php echo $class; ?>"
					   href="<?php echo $href; ?>"><span><?php echo $alt; ?></span></a></li><?php
	}

	public static function addNew($task = 'new', $alt = _NEW) {
		?><li><a class="tb-add-new"
					   href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function addNewX($task = 'new', $alt = _NEW) {
		?><li><a class="tb-add-new-x"
					   href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function copy($task = 'copy', $alt = 'Копировать') {
		?><li><a class="tb-add-new"
					   href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function publish($task = 'publish', $alt = _SHOW) {
		?><li><a class="tb-publish"
					   href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function publishList($task = 'publish', $alt = _SHOW) {
		?><li><a class="tb-publish-list"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_FOR_PUBLICATION ?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function makeDefault($task = 'default', $alt = _DEFAULT) {
		?><li><a class="tb-makedefault"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_MAKE_DEFAULT ?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function assign($task = 'assign', $alt = _ASSIGN) {
		?><li><a class="tb-assign"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_ASSIGN ?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function unpublish($task = 'unpublish', $alt = _HIDE) {
		?><li><a class="tb-unpublish"
					   href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function unpublishList($task = 'unpublish', $alt = _HIDE) {
		?><li><a class="tb-unpublish-list"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_UNPUBLISH ?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function archiveList($task = 'archive', $alt = _TO_ARCHIVE) {
		?><li><a class="tb-archive-list"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_ARCHIVE ?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function unarchiveList($task = 'unarchive', $alt = _FROM_ARCHIVE) {
		?><li><a class="tb-unarchive-list"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_UNARCHIVE ?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function editList($task = 'edit', $alt = _EDIT) {
		?><li><a class="tb-edit-list"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_EDIT ?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function editListX($task = 'edit', $alt = _EDIT) {
		?><li><a class="tb-edit-list-x"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_EDIT ?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function editHtml($task = 'edit_source', $alt = _EDIT_HTML) {
		?><li><a class="tb-edit-html"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_EDIT ?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function editHtmlX($task = 'edit_source', $alt = _EDIT_HTML) {
		?><li><a class="tb-edit-html-x"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_EDIT ?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function editCss($task = 'edit_css', $alt = _EDIT_CSS) {
		?><li><a class="tb-edit-css"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_EDIT ?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function editCssX($task = 'edit_css', $alt = _EDIT_CSS) {
		?><li><a class="tb-edit-css-x"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_EDIT ?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function deleteList($msg = '', $task = 'remove', $alt = _DELETE) {
		?><li><a class="tb-delete-list"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_DELETE ?>'); } else if (confirm('<?php echo _REALLY_WANT_TO_DELETE_OBJECTS ?> <?php echo $msg; ?>')){ submitbutton('<?php echo $task; ?>');}"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function deleteListX($msg = '', $task = 'remove', $alt = _DELETE) {
		?><li><a class="tb-delete-list-x"
					   href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_DELETE ?>'); } else if (confirm('<?php echo _REALLY_WANT_TO_DELETE_OBJECTS ?> <?php echo $msg; ?>')){ submitbutton('<?php echo $task; ?>');}"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function trash($task = 'remove', $alt = _REMOVE_TO_TRASH, $check = true) {
			if ($check) {
				$js = "javascript:if (document.adminForm.boxchecked.value == 0){ alert('" . _PLEASE_CHOOSE_ELEMENT_TO_TRASH . "'); } else { submitbutton('$task');}";
			} else {
				$js = "javascript:submitbutton('$task');";
			}
		?><li><a class="tb-trash" href="<?php echo $js; ?>"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function help($ref, $com = false) {
			return '';
		}

		public static function apply($task = 'apply', $alt = _APPLY) {
		?><li><a class="tb-apply"
					   href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo $alt; ?></span></a></li><?php
	}

	public static function save($task = 'save', $alt = _SAVE) {
		?><li><a class="tb-save"
					   href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function savenew() {
		?><li><a class="tb-save-new"
					   href="javascript:submitbutton('savenew');"><span><?php echo _SAVE ?></span></a></li><?php
		}

		public static function saveedit() {
		?><li><a class="tb-save-edit"
					   href="javascript:submitbutton('saveedit');"><span><?php echo _SAVE ?></span></a></li><?php
		}

		public static function cancel($task = 'cancel', $alt = _CANCEL) {
		?><li><a class="tb-cancel"
					   href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function back($alt = _MENU_BACK, $href = false) {
			$link = $href ? $href : 'javascript:window.history.back();';
		?><li><a class="tb-back" href="<?php echo $link; ?>"><span><?php echo $alt; ?></span></a></li><?php
		}

		public static function divider() {
		?><li>&nbsp;|&nbsp;</li><?php
	}

	public static function media_manager($directory = '', $alt = _TASK_UPLOAD) {
		?><li><a class="tb-media-manager" href="joiadmin.php#"
					   onclick="popupWindow('popups/uploadimage.php?directory=<?php echo $directory; ?>&amp;t=<?php echo JTEMPLATE; ?>','win1',250,100,'no');"><span><?php echo $alt; ?></span></a></li><?php
	}

	public static function spacer($width = '0') {
		return '';
	}

	public static function endTable() {
		?></ul></div><?php
		}

	}