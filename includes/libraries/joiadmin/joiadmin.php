<?php

/**
 * JoiAdmin - класс для автоматической генерации административного интерфейса компонента
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
defined('_VALID_MOS') or die();

/**
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
class JoiAdmin {

	private static $js_onformsubmit = array();
	public static $model;

	/**
	 * Автоматическое определение и запуск метода действия
	 */
	public static function dispatch() {

		$id = (int) mosGetParam($_REQUEST, 'id', 0);
		$page = (int) mosGetParam($_GET, 'page', false);

		$page = $page ? $page : 0;
		$id = $id ? $id : $page;

		$task = (string) mosGetParam($_REQUEST, 'task', 'index');
		$option = (string) mosGetParam($_REQUEST, 'option');
		$action = (string) mosGetParam($_REQUEST, 'action', $option);
		$action = str_replace('com_', '', $action);

		$class = 'actions' . ucfirst($action);

		JDEBUG ? jd_log($class . '::' . $task) : null;

		if (method_exists($class, $task)) {
			echo call_user_func_array($class . '::' . $task, array($option, $id, $page, $task));
		} else {
			echo call_user_func_array($class . '::index', array($option, $id, $page, $task));
		}
	}

	/**
	 * JoiAdmin::listing()
	 *
	 * Генерация таблицы с записями
	 *
	 * @param object mosDBTable $obj
	 * @param array $obj_list
	 * @param object mosPageNav $pagenav
	 * @param array $fields_list
	 */
	public static function listing(mosDBTable $obj, array $obj_list, mosPageNav $pagenav, array $fields_list) {

		// получаем название текущего компонента
		$option = mosGetParam($_REQUEST, 'option', '');

		// путь к текущим графическим элементам
		$image_path = JPATH_SITE . '/' . JADMIN_BASE . '/templates/' . JTEMPLATE . '/images/ico';
		echo JHTML::js_code('image_path ="' . $image_path . '/"; _option="' . $option . '";');

		// класс работы с формами
		mosMainFrame::addLib('form');

		// устанавливаем туллбар для таблицы
		mosMainFrame::getInstance(true)->setPath('toolbar', JPATH_BASE . '/includes/libraries/joiadmin/html/list_toolbar.php');

		mosCommonHTML::loadJquery();
		// подключаем js код библиотеки
		Jdocument::getInstance()->addJS(JPATH_SITE . '/includes/libraries/joiadmin/js/joiadmin.js');

		$header = $obj->get_tableinfo();

		echo self::header($header['header_list']);

		echo '<form action="index2.php" method="post" name="adminForm" id="adminForm">';
		echo '<table class="adminlist"><tr>';
		echo '<th width="20px"><input type="checkbox" onclick="checkAll();" value="" name="toggle"></th>';

		$fields_info = $obj->get_fieldinfo();
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

		$n = count($fields_to_table);
		$k = 1;
		$i = 0;
		foreach ($obj_list as $values) {
			echo "\n\t" . '<tr class="row' . $k . '">' . "\n\t";
			echo "\t" . '<td>' . mosHTML::idBox($i, $values->{$obj->getKeyField()} ) . '</td>' . "\n";
			for ($index = 0; $index < $n; $index++) {
				$current_value = isset($values->$fields_to_table[$index]) ? $values->$fields_to_table[$index] : null;
				$data = JoiAdmin::get_listing_html_element($obj, $fields_info[$fields_to_table[$index]], $fields_to_table[$index], $current_value, $values, $option);
				$class = isset($fields_info[$fields_to_table[$index]]['html_table_element_param']['class']) ? ' class="' . $fields_info[$fields_to_table[$index]]['html_table_element_param']['class'] . '"' : '';
				$align = isset($fields_info[$fields_to_table[$index]]['html_table_element_param']['align']) ? ' align="' . $fields_info[$fields_to_table[$index]]['html_table_element_param']['align'] . '" ' : '';

				echo "\t\t" . '<td ' . $align . $class . '>' . $data . '</td>' . "\n";
			}
			echo "\t" . '</tr>'. "\n";
			$k = 1 - $k;
			++$i;
		}

		echo '</tr></table>'. "\n";
		echo $pagenav->getListFooter();
		echo "\n";

		echo form::hidden('option', $option);
		echo form::hidden('model', self::$model);
		echo form::hidden('task', '');
		echo form::hidden('boxchecked', '');
		echo form::hidden('obj_name', get_class($obj));
		echo form::hidden(josSpoofValue(), 1);
		echo form::close();
	}

	public static function get_listing_html_element(mosDBTable $obj, array $element_param, $key, $value, stdClass $values, $option) {

		static $element_datas = array();

		$element = '';

		// ограничение на длину текста
		$text_limit = isset($element_param['html_table_element_param']['text_limit']) ? $element_param['html_table_element_param']['text_limit'] : false;
		if ($text_limit) {
			mosMainFrame::addLib('text');
			$value = Text::character_limiter($value, $text_limit);
		};

		switch ($element_param['html_table_element']) {

			// тип - просто текст
			case 'value':
				$element .= $value;
				//$element .= "\n\t";
				break;

			// тип - ссылка редактирования
			case 'editlink':
				$element .= '<a href="index2.php?option=' . $option . ( self::$model ? '&model=' . self::$model : '') . '&task=edit&'.$obj->getKeyField().'=' . $values->{$obj->getKeyField()} . '">' . $value . '</a>';
				break;

			// тип одно значение из массива
			case 'one_from_array':
				$datas_for_select = array();
				// избавления из от множества запросов
				if (!isset($element_datas[$key])) {
					// сохраняем полученные значения в статичном мессиве
					$element_datas[$key] = ( isset($element_param['html_table_element_param']['call_from']) && is_callable($element_param['html_table_element_param']['call_from']) ) ? call_user_func($element_param['html_table_element_param']['call_from']) : $datas_for_select;
				};
				$datas_for_select = $element_datas[$key];

				$datas_for_select = isset($element_param['html_table_element_param']['options']) ? $element_param['html_table_element_param']['options'] : $datas_for_select;
				$element .= isset($datas_for_select[$value]) ? $datas_for_select[$value] : $value;
				break;

			// тип - аякс выбор состояния
			case 'statuschanger':
				// расположение текущих значков
				$image_path = JPATH_SITE . '/' . JADMIN_BASE . '/templates/' . JTEMPLATE . '/images/ico';

				$images = isset($element_param['html_table_element_param']['images'][$value]) ? $element_param['html_table_element_param']['images'][$value] : 'error.png';
				$text = isset($element_param['html_table_element_param']['statuses'][$value]) ? $element_param['html_table_element_param']['statuses'][$value] : 'ERROR';

				$element .= '<img class="img-mini-state" src="' . $image_path . '/' . $images . '" id="img-pub-' . $values->id . '" obj_id="' . $values->id . '" obj_key="' . $key . '" alt="' . $text . '" />';
				break;

			case 'extra':
				$element .= ( isset($element_param['html_table_element_param']['call_from']) && is_callable($element_param['html_table_element_param']['call_from']) ) ? call_user_func($element_param['html_table_element_param']['call_from'], $values) : $datas_for_select;
				break;

			// по умолчанию элемент выведем скрытым
			default:
				$element .= "\n\t";
				$element = '<!-- no-viewed :: ' . $key . ' -->';
				break;
		}

		return $element;
	}

	/**
	 * JoiAdmin::edit()
	 *
	 * Генерация формы добавления/редактирования записи
	 *
	 * @param object $obj
	 * @param object $obj_data
	 * @param array $params
	 */
	public static function edit(mosDBTable $obj, $obj_data, $params = null) {

		//Подключаем библиотеку работы с формами
		mosMainFrame::addLib('form');

		//Библиотека работы с табами
		mosMainFrame::addClass('mosTabs');
		$tabs = new mosTabs();

		$option = mosGetParam($_REQUEST, 'option', '');

		//Настраиваем параметры HTML-разметки формы
		if (!$params) {
			$params = array(
				'wrap_begin' => '<table class="adminform">',
				'wrap_end' => '</table>',
				'label_begin' => '<tr><td width="100" align="right">',
				'label_end' => '</td>',
				'el_begin' => '<td>',
				'el_end' => '</td></tr>'
			);
		}

		// устанавливаем туллбар для страницы создания/редактирования
		mosMainFrame::getInstance(true)->setPath('toolbar', JPATH_BASE . '/includes/libraries/joiadmin/html/edit_toolbar.php');

		//Вывод заголовка страницы с формой
		$header = $obj->get_tableinfo(); //Получаем данные
		$header_text = $obj_data->{$obj->getKeyField()} > 0 ? $header['header_edit'] : $header['header_new'];

		//Выводим заголовок
		echo self::header($header_text);

		// начинаем отлавливать поступаемый JS код
		self::$js_onformsubmit[] = '<script type="text/javascript" charset="utf-8">function submitbutton(pressbutton) {';

		//Начало общего контейнера
		echo $params['wrap_begin'];

		//открываем форму
		echo form::open('index2.php', array('name' => 'adminForm', 'id' => 'adminForm'));

		//Получаем данные о элементах формы
		$fields_info = $obj->get_fieldinfo();
		foreach ($fields_info as $key => $field) {
			if ( isset($field['editable']) && $field['editable'] == true):
				$v = isset($obj_data->$key) ? $obj_data->$key : '';
				//Вывод элемента
				echo self::get_edit_html_element($field, $key, $v, $obj_data, $params, $tabs);
			endif;
		}

		//Выводим скрытые поля формы
		echo form::hidden( $obj->getKeyField() , $obj_data->{$obj->getKeyField()} ) . "\t";  // id объекта
		echo form::hidden('option', $option) . "\t";
		echo form::hidden('model', self::$model)."\t";
		echo form::hidden('task', '') . "\t";
		echo form::hidden(josSpoofValue(), 1); // элемент защиты от XSS
		//Закрываем форму
		echo form::close();

		// закрываем JS вкрапления
		self::$js_onformsubmit[] = 'submitform( pressbutton );';
		self::$js_onformsubmit[] = '};</script>';

		echo "\n" . implode("\n", self::$js_onformsubmit) . "\n";

		//Конец общего контейнера
		echo $params['wrap_end'];
	}

// получение типа элемента для формы редактирования
	public static function get_edit_html_element($element_param, $key, $value, $obj_data, $params, $tabs) {

		$element = '';

		switch ($element_param['html_edit_element']) {

			// тип - простое одностроное поле редактирования
			case 'edit':
				$element .= $params['label_begin'];
				$element .= form::label(
								array(
									'for' => $key
								), $element_param['name']);
				$element .= $params['label_end'];
				$element .= $params['el_begin'];
				$element .= form::input(
								array(
									'name' => $key,
									'class' => 'text_area',
									'size' => 100,
									'style' => ( isset($element_param['html_edit_element_param']['style']) ? $element_param['html_edit_element_param']['style'] : 'width:100%' ),
								), $value);
				$element .= $params['el_end'];
				break;

			// тип - большое текстовое поле
			case 'text':
				$element .= $params['label_begin'];
				$element .= form::label(
								array(
									'for' => $key
								), $element_param['name']);
				$element .= $params['label_end'];
				$element .= $params['el_begin'];
				$element .= form::textarea(
								array(
									'name' => $key,
									'class' => 'text_area',
									'rows' => ( isset($element_param['html_edit_element_param']['rows']) ? $element_param['html_edit_element_param']['rows'] : 10 ),
									'cols' => ( isset($element_param['html_edit_element_param']['cols']) ? $element_param['html_edit_element_param']['cols'] : 40 ),
									'style' => ( isset($element_param['html_edit_element_param']['style']) ? $element_param['html_edit_element_param']['style'] : 'width:100%' ),
								), $value);
				$element .= $params['el_end'];
				break;

			// тип - большое текстовое поле
			case 'text_area':
				$element .= $params['label_begin'];
				$element .= form::label(
								array(
									'for' => $key
								), $element_param['name']);
				$element .= $params['label_end'];
				$element .= $params['el_begin'];
				$element .= form::textarea(
								array(
									'name' => $key,
									'class' => 'text_area',
									'rows' => ( isset($element_param['html_edit_element_param']['rows']) ? $element_param['html_edit_element_param']['rows'] : 10 ),
									'cols' => ( isset($element_param['html_edit_element_param']['cols']) ? $element_param['html_edit_element_param']['cols'] : 40 ),
									'style' => ( isset($element_param['html_edit_element_param']['style']) ? $element_param['html_edit_element_param']['style'] : 'width:100%' ),
								), $value);
				$element .= $params['el_end'];
				break;

			// тип - большое текстовое поле с редактором
			case 'text_area_wysiwyg':
				$element .= $params['label_begin'];
				$element .= form::label(
								array(
									'for' => $key
								), $element_param['name']);
				$element .= $params['label_end'];
				$element .= $params['el_begin'];

				$editor_params = array(
					'editor' => isset($element_param['html_edit_element_param']['editor']) ? $element_param['html_edit_element_param']['editor'] : 'elrte',
					'rows' => isset($element_param['html_edit_element_param']['rows']) ? $element_param['html_edit_element_param']['rows'] : null,
					'cols' => isset($element_param['html_edit_element_param']['cols']) ? $element_param['html_edit_element_param']['cols'] : null,
					'width' => isset($element_param['html_edit_element_param']['width']) ? $element_param['html_edit_element_param']['width'] : null,
					'height' => isset($element_param['html_edit_element_param']['height']) ? $element_param['html_edit_element_param']['height'] : null,
				);

				$element .= jooEditor::editor($key, $value, $editor_params);
				self::$js_onformsubmit[] = jooEditor::getContents($key);
				$element .= $params['el_end'];
				break;

			// тип - чекбокс
			case 'checkbox':
				$element .= $params['label_begin'];
				$element .= form::label(
								array(
									'for' => $key
								), ( isset($element_param['html_edit_element_param']['text']) ? $element_param['html_edit_element_param']['text'] : $element_param['name']));
				$element .= $params['label_end'];
				$element .= form::hidden($key, 0);
				$element .= $params['el_begin'];
				$element .= form::checkbox(
								array(
									'name' => $key,
									'class' => 'text_area',
								), 1, $value);
				$element .= $params['el_end'];
				break;

			// тип - выпадающий список
			case 'option':
				$element .= $params['label_begin'];
				$element .= form::label(
								array(
									'for' => $key
								), ( isset($element_param['html_edit_element_param']['text']) ? $element_param['html_edit_element_param']['text'] : $element_param['name']));

				$element .= $params['label_end'];
				$element .= $params['el_begin'];
				$datas_for_select = array();
				$datas_for_select = ( isset($element_param['html_edit_element_param']['call_from']) && is_callable($element_param['html_edit_element_param']['call_from']) ) ? call_user_func($element_param['html_edit_element_param']['call_from']) : $datas_for_select;
				$datas_for_select = isset($element_param['html_edit_element_param']['options']) ? $element_param['html_edit_element_param']['options'] : $datas_for_select;

				$element .= form::dropdown(array('name' => $key, 'options' => $datas_for_select, 'selected' => $value));

				$element .= $params['el_end'];
				break;

			// тип - произвольнеое расширенное поле
			case 'extra':
				$element .= $params['label_begin'];
				$element .= form::label(
								array(
									'for' => $key
								), ( isset($element_param['html_edit_element_param']['text']) ? $element_param['html_edit_element_param']['text'] : $element_param['name']));

				$element .= $params['label_end'];
				$element .= $params['el_begin'];
				$element .= ( isset($element_param['html_edit_element_param']['call_from']) && is_callable($element_param['html_edit_element_param']['call_from']) ) ? call_user_func($element_param['html_edit_element_param']['call_from'], $obj_data) : $datas_for_select;
				$element .= form::hidden('extrafields[]', $key);
				$element .= $params['el_end'];
				break;

			// тип - скрытое поле с идентификатором текущего пользователя
			case 'current_user_id':
				global $my;
				$element .= form::hidden($key, $my->id);
				break;

			case 'h3':
				$element .= $params['label_begin'];
				$element .= $params['label_end'];
				$element .= $params['el_begin'];
				$element .= '<h3>' . $element_param['name'] . '</h3>';
				$element .= $params['el_end'];
				break;

			case 'start_pane':
				$element .= $params['label_begin'];
				$element .= $params['label_end'];
				$element .= $params['el_begin'];
				$element .= $tabs->startPane($key, 1);
				break;

			case 'end_pane':
				$element .= '</div>';
				$element .= $params['el_end'];
				break;

			case 'start_tab':
				$element .= $tabs->startTab($element_param['name'], $key, 1);
				$element .= $params['wrap_begin'];

				break;

			case 'end_tab':
				$element .= $params['wrap_end'];
				$element .= '</div>';
				break;

			case 'tags':
				require_once ( mosMainFrame::getInstance()->getPath('class', 'com_tags'));
				$tags = new Tags;

				$element .= $params['label_begin'];
				$element .= form::label(
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
				$element .= form::label(
								array(
									'for' => $key
								), $element_param['name']);
				$element .= $params['label_end'];
				$element .= $params['el_begin'];
				$element .=  $value;
				$element .= $params['el_end'];
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

		// подключаем класс навигации по страницам
		require_once( JPATH_BASE_ADMIN . DS . '/includes/pageNavigation.php');

		$mainframe = mosMainFrame::getInstance(true);
		$limit = intval($mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mainframe->getCfg('list_limit')));
		$limitstart = intval($mainframe->getUserStateFromRequest("{$com_name}_limitstart" . self::$model, 'limitstart', 0));

		return new mosPageNav($total, $limitstart, $limit);
	}

// вывод заголовка страницы
	public static function header($header) {
		return '<table class="adminheading"><tbody><tr><th class="config">' . $header . '</th></tr></tbody></table>';
	}

// автоматическя обработка яксовых операций
	public static function autoajax() {

		require_once(mosMainFrame::getInstance(true)->getPath('class'));

		// выполняемая задача
		$task = (int) mosGetParam($_REQUEST, 'task', '');
		// идентификатор запрашиваемого элемента
		$obj_id = (int) mosGetParam($_POST, 'obj_id', 0);
		// ключ-название запрашиваемого элемента
		$obj_key = (string) mosGetParam($_POST, 'obj_key', '');
		// название объекта запрашиваемого элемента
		$obj_name = (string) mosGetParam($_POST, 'obj_name', '');
		// пустой объект для складирования результата
		$return_onj = new stdClass();

		// проверяем, существует ли запрашиваемый класс
		if (class_exists($obj_name)) {
			// создаём объект класса
			$obj = new $obj_name;
			$obj->load($obj_id);

			// меняем состояние объекта на противоположное
			$obj->changeState($obj_key);

			// получаем настройки полей
			$fields_info = $obj->get_fieldinfo();

			// формируем ответ из противоположных элементов текущему состоянию
			$return_onj->image = $fields_info[$obj_key]['html_table_element_param']['images'][!$obj->$obj_key];
			$return_onj->mess = $fields_info[$obj_key]['html_table_element_param']['statuses'][!$obj->$obj_key];

			return json_encode($return_onj);
		}

		$return_onj->image = 'error.png';
		$return_onj->mess = 'error-class';

		return json_encode($return_onj);
	}

}