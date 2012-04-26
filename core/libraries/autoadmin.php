<?php defined('_JOOS_CORE') or die();

/**
 * Библиотека автоматической генерации интерфейсов панели управлениями
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Autoadmin
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosAutoadmin {

    private static $js_onformsubmit = array();

    private static $data;
    private static $data_overload = false;

    private static $option;
    private static $task;

    /**
     * @var joosModel
     */
    private static $active_model_name;
    private static $active_actions_class;
    private static $active_menu_name;

    public static function get($param){
        return self::$$param;
    }

    public static function dispatch( ) {

        $id = joosRequest::int('id', 0);
        $page = joosRequest::int('page', false, $_GET);

        $page = $page ? $page : 0;
        $id = $id ? $id : $page;

        $task = joosRequest::param('task', 'index');
        $option = joosRequest::param('option','site');
        $class = 'actionsAdmin' . joosInflector::camelize($option);

        self::$active_actions_class = $class;
        self::$option = $option;
        self::$task = $task;

        // подключаем js код библиотеки
        joosDocument::instance()
            ->add_js_file(JPATH_SITE . '/core/libraries/autoadmin/media/js/autoadmin.js');


        !JDEBUG ? : joosDebug::add('joosAutoadmin::dispatch() - ' . $class . '::' . $task);

        // в контроллере можно прописать общие действия необходимые при любых действиях контроллера - они будут вызваны первыми, например подклбчение можделей, скриптов и т.д.
        method_exists($class, 'action_before') ? call_user_func_array($class . '::action_before', array(self::$task)) : null;

        $events_name = sprintf('controller.admin.*');
        joosEvents::has_events($events_name) ? joosEvents::fire_events($events_name, $class, $task) : null;

        $events_name = sprintf('controller.admin.%s.*', $class);
        joosEvents::has_events($events_name) ? joosEvents::fire_events($events_name, $task) : null;


        $events_name = sprintf('controller.admin.%s.%s', $class, $task);
        joosEvents::has_events($events_name) ? joosEvents::fire_events($events_name) : null;

        if (method_exists($class, $task)) {
            $results = call_user_func_array($class . '::' . $task, array($option, $id, $page, $task));
            method_exists($class, 'action_after') ? call_user_func_array($class . '::action_after', array(self::$task)) : null;
        } elseif (method_exists($class, 'index')) {
            $results = call_user_func_array($class . '::index', array($option, $id, $page, $task));
            method_exists($class, 'action_after') ? call_user_func_array($class . '::action_after', array(self::$task)) : null;
        } else {
            throw new joosException('Контроллер :controller, либо требуемая задача :task не найдены.', array(':controller' => $class,':task' => $task));
        }

        if (is_array($results)) {
            self::views($results, self::$option, self::$task);
        } elseif (is_string($results)) {
            echo $results;
        }

        // если контроллер содержит метод вызываемый после окончания работы основного контроллера, то он тоже вызовется
        method_exists($class, 'action_after') ? call_user_func_array($class . '::action_after', array()) : null;
    }

    private static function views(array $params, $controller, $method) {

        $template = isset($params['template']) ? $params['template'] : 'default';
        $views = isset($params['method']) ? $params['method'] : $method;

        extract($params, EXTR_OVERWRITE);
        $viewfile = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $controller . DS . 'admin_views' . DS . $views . DS . $template . '.php';

        joosFile::exists($viewfile) ? require ( $viewfile ) : null;
    }

    // автодиспатчер для Ajax - обработчиков
    public static function dispatch_ajax() {

        $id = joosRequest::int('id', 0);
        $page = joosRequest::int('page', false, $_GET);

        $page = $page ? $page : 0;
        $id = $id ? $id : $page;

        $task = joosRequest::param('task', 'index');
        $option = joosRequest::param('option');
        $class = 'actionsAjaxAdmin' . ucfirst($option);

        JDEBUG ? joosDebug::add($class . '::' . $task) : null;

        // в контроллере можно прописать общие действия необходимые при любых действиях контроллера - они будут вызваны первыми, например подклбчение можделей, скриптов и т.д.
        method_exists($class, 'action_before') ? call_user_func_array($class . '::action_before', array()) : null;

        $events_name = sprintf('ajax.controller.admin.*');
        joosEvents::has_events($events_name) ? joosEvents::fire_events($events_name, $class, $task) : null;


        $events_name = sprintf('ajax.controller.admin.%s.*', $class);
        joosEvents::has_events($events_name) ? joosEvents::fire_events($events_name, $task) : null;


        $events_name = sprintf('ajax.controller.admin.%s.%s', $class, $task);
        joosEvents::has_events($events_name) ? joosEvents::fire_events($events_name) : null;

        if (method_exists($class, $task)) {
            $result = call_user_func_array($class . '::' . $task, array($option, $id, $page, $task));
        } else {
            $result = call_user_func_array($class . '::index', array($option, $id, $page, $task));
        }

        // контроллер может содержать метод вызываемый после окончания работы основного контроллера, но тоже вызовется
        method_exists($class, 'action_after') ? call_user_func_array($class . '::action_after', array($task, $result)) : null;

        if (is_array($result)) {
            echo json_encode($result);
        } elseif (is_string($result)) {
            echo $result;
        }
    }

    /**
     * joosAutoadmin::listing()
     *
     * Генерация таблицы с записями
     *
     * @param object joosModel $obj
     * @param array  $obj_list
     * @param object joosAdminPagenator $pagenav
     * @param array  $fields_list
     * @param string $group_by Используется для указания границ сортировки (для сортировки в пределах определенного значения. Например, в модулях, сортировка происходит в границах позиции модуля (за пределы группы нельзя перетащить строку в процессе сортировки))
     */
    public static function listing(joosModel $obj, array $obj_list, joosAdminPagenator $pagenav, array $fields_list, $group_by = '') {

        // получаем название текущего компонента
        $option = joosRequest::param('option');
        $task = joosRequest::param('task');

        $fields_info = $obj->get_fieldinfo();

        $header = $obj->get_tableinfo();

        $header_extra = self::get_extrainfo($obj);
        $header_extra = self::prepare_extra($obj, $header_extra);

        joosAdminView::set_param( 'component_title' , isset($header['header_main']) ? $header['header_main'] : '');
        joosAdminView::set_param( 'component_header' , $header['header_list']);

        $class = self::$active_actions_class;
        joosAdminView::set_param('submenu', $class::get_submenu() );
        joosAdminView::set_param('current_model', self::get_active_menu_name() );


        //для подсчёта количества столбцов таблицы
        $fields_to_table = array();

        $table_headers = ''; //сюда будем складывать заголовки
        //перебор полей для вывода в виде заголовков столбцов
        foreach ($fields_list as $field) {
            //если этот столбец нужно выводить
            if (isset($fields_info[$field]['in_admintable']) && $fields_info[$field]['in_admintable'] == TRUE) {

                //ширина столбца
                $width = isset($fields_info[$field]['html_table_element_param']['width']) ? ' width="' . $fields_info[$field]['html_table_element_param']['width'] . '"' : '';

                //дополнительный класс
                $class = isset($fields_info[$field]['html_table_element_param']['class']) ? ' class="' . $fields_info[$field]['html_table_element_param']['class'] . '"' : '';

                $table_headers .= '<th ' . $width . $class . '>' . $fields_info[$field]['name'] . '</th>';
                $fields_to_table[] = $field;
            }
        }
        joosAdminView::set_listing_param('table_headers', $table_headers);



        //сюда соберём содержимое таблички
        $table_body = '';
        $n = count($fields_to_table);
        $k = 1;
        $i = 0;
        foreach ($obj_list as $values) {
            $dop_class = $group_by ? $group_by . '-' . $values->$group_by : '';

            $table_body .= '<tr class="row-' . $k . '" ' . ( $group_by ? ' data-obj-ordering="' . $values->ordering . '"' : '' ) . ' data-obj-id="' . $values->{$obj->get_key_field()} . '" id="adminlist-row-' . $values->{$obj->get_key_field()} . '" rel="' . $dop_class . '">';
            $table_body .= '<td align="center">' . joosHtml::idBox($i, $values->{$obj->get_key_field()}) . '</td>';
            for ($index = 0; $index < $n; $index++) {
                $current_value = isset($values->$fields_to_table[$index]) ? $values->$fields_to_table[$index] : null;
                $data = joosAutoadmin::get_listing_html_element($obj, $fields_info[$fields_to_table[$index]], $fields_to_table[$index], $current_value, $values, $option);
                $class = isset($fields_info[$fields_to_table[$index]]['html_table_element_param']['class']) ? ' class="' . $fields_info[$fields_to_table[$index]]['html_table_element_param']['class'] . '"' : '';
                $align = isset($fields_info[$fields_to_table[$index]]['html_table_element_param']['align']) ? ' align="' . $fields_info[$fields_to_table[$index]]['html_table_element_param']['align'] . '" ' : '';

                $table_body .= '<td ' . $align . $class . '>' . $data . '</td>';
            }
            $table_body .= '</tr>';
            $k = 1 - $k;
            ++$i;
        }
        joosAdminView::set_listing_param('table_body', $table_body);

        //Подключаем шаблон листинга
        //@todo Хочу красивше чтобы было *Ирина
        require_once JTEMPLATE_ADMIN_BASE.DS.'html'.DS.'listing.php';

    }

    public static function get_listing_html_element(joosModel $obj, array $element_param, $key, $value, stdClass $values, $option) {

        $class_file = JPATH_BASE . '/app/plugins/autoadmin/table.' . $element_param['html_table_element'] . '.php';
        $class_name = 'pluginAutoadminTable' . self::get_plugin_name($element_param['html_table_element']);

        if (! joosFile::exists($class_file)) {
            throw new joosAutoadminFilePluginNotFoundException(
                sprintf(__('Файл плагина joosAutoadmin %s  для вывода элемента %s не найден'), $class_file, $class_name)
            );
        }

        require_once $class_file;

        if (!class_exists($class_name)) {
            throw new joosAutoadminClassPlugionNotFoundException(
                sprintf(__('Класс для обработки %s средствами joosAutoadmin в файле %s не найден'), $class_file, $class_name)
            );
        }

        // ограничение на длину текста
        $text_limit = isset($element_param['html_table_element_param']['text_limit']) ? $element_param['html_table_element_param']['text_limit'] : false;
        if ($text_limit) {
            $value = joosText::character_limiter($value, $text_limit);
        }

        return call_user_func_array($class_name . '::render', array($obj, $element_param, $key, $value, $values, $option));
    }

    /**
     * joosAutoadmin::edit()
     *
     * Генерация формы добавления/редактирования записи
     *
     * @param joosModel $obj
     * @param object $obj_data
     * @param array  $params
     */
    public static function edit(joosModel $obj, $obj_data, $params = null) {

        self::$active_model_name = get_class($obj);

        $option = joosRequest::param('option');
        $task = joosRequest::param('task');

        //Настраиваем параметры HTML-разметки формы
        if (!$params) {
            $params = array(
                //'wrap_begin' => '<table class="admin_form joiadmin">',
                //'wrap_end' => '</table>',

                'label_begin' => '<div class="control-group">',
                'label_end' => '',
                'el_begin' => '<div class="controls">',
                'el_end' => '</div></div>');

            //'tab_wrap_begin' => '<tr><td>',
            //'tab_wrap_end' => '</td></tr>',);
        }

        //Вывод заголовка страницы с формой
        $header = $obj->get_tableinfo(); //Получаем данные

        joosAdminView::set_param( 'component_title' , isset($header['header_main']) ? $header['header_main'] : '');
        joosAdminView::set_param( 'component_header' ,  $obj_data->{$obj->get_key_field()} > 0 ? $header['header_edit'] : $header['header_new'] );

        $class = self::$active_actions_class;
        joosAdminView::set_param('submenu', $class::get_submenu());
        joosAdminView::set_param('current_model', self::get_active_model_name());

        //echo self::header(array(), 'edit');

        // начинаем отлавливать поступаемый JS код
        //@ зачем?
        //self::$js_onformsubmit[] = '<script type="text/javascript" charset="utf-8">function submitbutton(pressbutton) {';

        //Работа с табами
        $tabs = new htmlTabs();

        //Массив сформированных элементов для вывода
        $_elements = array();
        //Получаем данные о элементах формы
        $fields_info = $obj->get_fieldinfo();
        foreach ($fields_info as $key => $field) {
            if ($field['editable'] == true && !( isset( $field['hide_on']) &&  $field['hide_on'] === $task ) ):
                $v = isset($obj_data->$key) ? $obj_data->$key : '';
                $_elements[$key] = self::get_edit_html_element($field, $key, $v, $obj_data, $params, $tabs);
            endif;
        }

        //Если заданы табы
        //@todo Реализуем позже
        /*
		$_tabs_areas = '';
		$_tabs_array = array();
		$_tabs_new = is_callable(array($obj, 'get_tabsinfo')) ? $obj->get_tabsinfo() : null;
		if ($_tabs_new) {
			$_tabs_areas .= '<div id="tabs_wrap"><ul id="tabs_list">';
			foreach ($_tabs_new as $_tab_key => $_tab_fields) {
				$_tabs_areas .= '<li><span rel="tab_' . $_tab_key . '">' . $_tab_fields['title'] . '</span></li>';
				foreach ($_tab_fields['fields'] as $f) {
					if (isset($_elements[$f])) {
						$_tabs_array[$_tab_key]['title'] = __($_tab_fields['title']);
						$_tabs_array[$_tab_key]['elements'][] = $_elements[$f];
					}
				}
			}
			$_tabs_areas .= '</ul></div>';

			$i = 1;
			foreach ($_tabs_array as $tab_area_key => $tab_fields) {
				$_tabs_areas .= '<div ' . ( $i == 1 ? '' : 'style="display: none" ' ) . ' class="tab_area tab_area_' . $i . '" id="tab_' . $tab_area_key . '">';

				//Начало общего контейнера
				$_tabs_areas .= $params['wrap_begin'];

				//Вывод элементов
				$_tabs_areas .= implode('', $tab_fields['elements']);

				//Конец общего контейнера
				$_tabs_areas .= $params['wrap_end'];

				$_tabs_areas .= '</div>';

				$i++;
			}

			echo $_tabs_areas;
		} else {
        */

        /*
			//Начало общего контейнера
			echo $params['wrap_begin'];

			//Вывод элементов
			echo implode('', $_elements);

			//Конец общего контейнера
			echo $params['wrap_end'];
		}
        */




        // закрываем JS вкрапления
        //self::$js_onformsubmit[] = 'submitform( pressbutton );';
        //self::$js_onformsubmit[] = '};</script>';

        //echo "\n" . implode("\n", self::$js_onformsubmit) . "\n";

        require_once JTEMPLATE_ADMIN_BASE.DS.'html'.DS.'edit.php';
    }

    // получение типа элемента для формы редактирования
    public static function get_edit_html_element($element_param, $key, $value, $obj_data, $params, $tabs) {

        $class_file = JPATH_BASE . '/app/plugins/autoadmin/edit.' . $element_param['html_edit_element'] . '.php';
        $class_name = 'pluginAutoadminEdit' . self::get_plugin_name($element_param['html_edit_element']);

        if (! joosFile::exists($class_file)) {
            throw new joosAutoadminFilePluginNotFoundException(sprintf(__('Файл плагина joosAutoadmin %s  для редактирования элемента %s не найден'), $class_file, $class_name));
        }

        require_once $class_file;

        if (!class_exists($class_name)) {
            throw new joosAutoadminClassPlugionNotFoundException(sprintf(__('Класс для обработки %s средствами joosAutoadmin в файле %s не найден'), $class_file, $class_name));
        }

        return call_user_func_array($class_name . '::render', array($element_param, $key, $value, $obj_data, $params, $tabs));
    }

    public static function add_js_onformsubmit($js_raw_code) {
        self::$js_onformsubmit[] = $js_raw_code;
    }

    // упрощенная система получения пагинатора
    public static function pagenav($total) {

        $com_name = self::$option;

        $limit = (int) joosSession::get_user_state_from_request("{$com_name}_viewlistlimit", 'limit', joosConfig::get2('admin', 'list_limit', 25));
        $limitstart = (int) joosSession::get_user_state_from_request("{$com_name}_limitstart" . self::get_active_model_name(), 'limitstart', 0);

        return new joosAdminPagenator($total, $limitstart, $limit);
    }

    /**
     * joosAutoadmin::header()
     * Вывод заголовка с управляющими элементами
     *
     * @param array  $extra           Всяческие куртые штуки типа поля поиска, фильтров и т.п. (подтягивается из `get_extrainfo()` текущей модели)
     * @param string $task            Параметр передается в случае, если необходимо вывести стандартный тулбар
     *                         (т.е. когда метод вызывается из joosAutoadmin::listing или joosAutoadmin::edit)
     *
     * @return string HTML-представление заголовка: название текущей страницы, субменю, системное сообщение, фильтры, тулбар (кнопки управления)
     */
    public static function header( array $extra = array(), $task = '') {

        //Заголовок страницы + тулбар
        require_once JTEMPLATE_ADMIN_BASE.DS.'html'.DS.'table_header.php';

        //Поиск, фильтры и т.п.
        /**
         * @todo запилить позже, потому как слишком уж гибко там внтурях
         */
        //$return .= joosAutoadminHTML::controller_header(false, 'config', $extra);
        //return $return;
    }

    // тело таблицы
    public static function table_body(  ) {
        require_once JTEMPLATE_ADMIN_BASE.DS.'html'.DS.'table_body.php';
    }


    //Определение заголовка компонента по его названию
    //Требуется в компонентах, которые выступают в качестве интерфейса
    //например: компонент категорий, компонент настроек и т .п
    public static function get_component_title($name) {

        /**
         * @type joosModel
         */
        $admin_model = 'admin' . ucfirst($name);

        $admin_model = new $admin_model;
        $titles = $admin_model->get_tableinfo();
        $component_title = isset($titles['header_main']) ? $titles['header_main'] : '';
        return $component_title;
    }

    /**
     * Получение меню компонента по его имени
     *
     * @param string $component Название компонента
     *
     * @return array меню компонента или false
     */
    public static function get_component_submenu($component) {

        $controller = 'actionsAdmin' . ucfirst($component);
        joosLoader::admin_controller($component);

        if (isset($controller::$submenu)) {
            return $controller::$submenu;
        }

        return false;
    }


// автоматическя обработка яксовых операций
    public static function autoajax() {

        //$option = joosRequest::param('option');
        // выполняемая задача
        $task = joosRequest::param('task');
        // идентификатор запрашиваемого элемента
        $obj_id = joosRequest::int('obj_id', 0, $_POST);
        // ключ-название запрашиваемого элемента
        $obj_key = joosRequest::post('obj_key');
        // название объекта запрашиваемого элемента
        $model = joosRequest::param('model');
        if (!$model) {
            return false;
        }
        // пустой объект для складирования результата
        $return_onj = new stdClass();

        if (class_exists($model)) {
            // создаём объект класса
            $obj = new $model;

            switch ($task) {
                case 'status_change':
                    $obj->load($obj_id);
                    // меняем состояние объекта на противоположное
                    $obj->change_state($obj_key);

                    // получаем настройки полей
                    $fields_info = $obj->get_fieldinfo();

                    $fields_info[$obj_key] = array_merge_recursive(
                        $fields_info[$obj_key], array(
                        'html_table_element_param' => array(
                            'statuses' => array(
                                0 => __('Скрыто'),
                                1 => __('Опубликовано')
                            ),
                            'images' => array(
                                0 => 'publish_x.png',
                                1 => 'publish_g.png',)
                        ))
                    );

                    // формируем ответ из противоположных элементов текущему состоянию
                    $return_onj->image = isset($fields_info[$obj_key]['html_table_element_param']['images'][!$obj->$obj_key]) ? $fields_info[$obj_key]['html_table_element_param']['images'][!$obj->$obj_key] : 'error.png';
                    $return_onj->mess = isset($fields_info[$obj_key]['html_table_element_param']['statuses'][!$obj->$obj_key]) ? $fields_info[$obj_key]['html_table_element_param']['statuses'][!$obj->$obj_key] : 'ERROR';
                    break;

                default:
                    return false;
                    break;
            }

            echo json_encode($return_onj);
            return true;
        }

        $return_onj->image = 'error.png';
        $return_onj->mess = 'error-class';

        echo json_encode($return_onj);
        return false;
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

                        $search_value = joosSession::get_user_state_from_request("search-" . $obj->get_class_name(), 'search', false);

                        $results[] = forms::input(array('name' => 'search_elements','id' => 'search_elements'), $search_value);
                        $hidden_elements[] = forms::hidden('search', $search_value);

                        if ($search_value !== false && joosString::trim($search_value) != '') {
                            foreach ($value as $selected_value) {
                                $wheres_search[] = sprintf('%s LIKE ( %s )', joosDatabase::instance()->get_name_quote($selected_value), joosDatabase::instance()->get_quoted("%" . $search_value . "%"));
                            }
                        }
                        break;

                    case 'filter':

                        foreach ($value as $params_key => $params_value) {

                            $field_name = $params_key;
                            $field_title = $value[$field_name]['name'];

                            $results[] = forms::label(array(
                                'for' => 'filter_' . $field_name
                            ), $field_title);

                            $datas_for_select = array(
                                -1 => __('Всё сразу')
                            );
                            $datas_for_select += ( isset($value[$field_name]['call_from']) && is_callable($value[$field_name]['call_from']) ) ? call_user_func($value[$field_name]['call_from'], $obj, $params_key) : array();

                            $selected_value = joosSession::get_user_state_from_request('filter-' . '-' . $field_name . '-' . $obj->get_class_name(), $field_name, -1);
                            $selected_value = $selected_value === '0' ? '0' : $selected_value;

                            $results[] = forms::dropdown(
                                array(
                                    'name' => 'filter_' . $field_name,
                                    'data-obj-name' => $field_name,
                                    'class' => 'filter_elements',
                                    'options' => $datas_for_select,
                                    'selected' => $selected_value
                                )
                            );

                            $hidden_elements[] = forms::hidden($field_name, $selected_value);
                            if (( $selected_value && $selected_value != -1 ) OR $selected_value === '0') {
                                $wheres_filter[] = sprintf('%s=%s', joosDatabase::instance()->get_name_quote($field_name), joosDatabase::instance()->get_quoted($selected_value));
                            }
                        }
                        break;

                    case 'extrafilter':
                        $datas_for_select = array(-1 => 'Всё сразу');
                        foreach ($value as $params_key => $params_value) {

                            $field_name = $params_key;

                            $datas_for_select += array($params_key => $value[$field_name]['name']);
                        }

                        $selected_value = joosSession::get_user_state_from_request("extrafilter-" . $obj->get_class_name(), 'filter_extrafilter', -1);

                        $results[] = forms::label(array('for' => 'filter_extrafilter'), 'Фильтр');
                        $results[] = forms::dropdown(array('name' => 'filter_extrafilter_selector',
                            'class' => 'extrafilter_elements',
                            'options' => $datas_for_select,
                            'selected' => $selected_value));
                        $hidden_elements[] = forms::hidden('filter_extrafilter', $selected_value);

                        //self::$data_overload = ( $selected_value && isset($value[$selected_value]['call_from']) && is_callable($value[$selected_value]['call_from']) ) ? call_user_func($value[$selected_value]['call_from'], $obj) : array();
                        self::$data_overload = ( $selected_value && isset($value[$selected_value]['call_from']) && is_callable($value[$selected_value]['call_from']) ) ? $value[$selected_value]['call_from'] : array();
                        break;

                    default:
                        break;
                }
            }

            $wheres = array(implode(' AND ', $wheres_filter),);

            if (count($wheres_search) > 0) {
                $wheres[] = ' (' . implode(' OR ', $wheres_search) . ' )';
            }

            self::$data = array('for_header' => $results,
                'hidden_ellements' => implode("\n", $hidden_elements),
                'wheres' => implode(' AND ', $wheres),
                'data_overload' => self::$data_overload,);
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
                    'call_from' => 'joosAutoadmin::get_state_selector',
                )
            );
        }
        return $header_extra;
    }

    public static function get_count(joosModel $obj) {

        $header_extra = self::get_extrainfo($obj);
        $header_extra = self::prepare_extra($obj, $header_extra);

        $params = array('where' => $header_extra['wheres'],
            'only_count' => true);

        return self::$data_overload ? call_user_func(self::$data_overload, $params) : $obj->count('WHERE ' . $header_extra['wheres']);
    }

    public static function get_list(joosModel $obj, $params) {

        $header_extra = self::get_extrainfo($obj);
        $header_extra = self::prepare_extra($obj, $header_extra);

        if (isset($params['where'])) {
            $params['where'] = $header_extra['wheres'] . ' AND ' . $params['where'];
        } else {
            $params['where'] = $header_extra['wheres'];
        }

        return self::$data_overload ? call_user_func(self::$data_overload, $params) : $obj->get_list($params);
    }

    public static function get_state_selector() {
        return array(
            0 => 'Не активно',
            1 => 'Активно'
        );
    }

    private static function get_plugin_name($string) {

        return joosInflector::camelize($string);
    }

    public static function set_active_model_name( $model_name ){
        self::$active_model_name = $model_name;
    }

    public static function get_active_model_name(){
        return self::$active_model_name;
    }

    /**
     *
     *
     * @return joosModel
     */
    public static function get_active_model_obj(){
        return new self::$active_model_name;
    }

    public static function set_active_menu_name( $menu_name ){
        return self::$active_menu_name = $menu_name;
    }

    public static function get_active_menu_name(){
        return self::$active_menu_name;
    }

    public static function get_option(){
        return self::$option;
    }

}

class joosAutoadminHTML {

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

class joosAdminToolbarButtons{

    public static function listing($type = ''){

        switch($type){

            case 'create':
            default:
                return '
                    <button class="btn btn-large js-toolbar js-tooltip js-toolbar_once"  data-toolbar="create" title="Добавить запись">
                        <i class="icon-plus-sign"></i> Добавить
                    </button>';
                break;

            case 'publish':
                return '
                    <button  class="btn btn-large js-toolbar js-tooltip"  data-toolbar="publish" title="Разрешить">
                        <i class="icon-ok"></i>
                    </button>';
                break;

            case 'unpublish':
                return '
                    <button class="btn btn-large js-toolbar js-tooltip" data-toolbar="unpublish"  title="Запретить">
                        <i class="icon-remove"></i>
                    </button>';
                break;

            case 'remove':
                return '
                    <button class="btn btn-large js-toolbar js-tooltip" data-toolbar="remove"  title="Удалить">
                        <i class="icon-trash"></i>
                    </button>';
                break;

        }
    }


    public static function edit($type = ''){

        switch($type){

            case 'save':
            default:
                return '
                    <button class="btn btn-large js-toolbar js-tooltip"  data-toolbar="save" title="Сохранить изменения">
                        <i class="icon-ok-circle"></i> Сохранить
                    </button>';
                break;

            case 'apply':
                return '
                    <button class="btn btn-large js-toolbar js-tooltip"  data-toolbar="apply" title="Применить изменения">
                        <i class="icon-ok-circle"></i> <i class="icon-refresh"></i>
                    </button>';
                break;

            case 'save_and_new':
                return '
                    <button class="btn btn-large js-toolbar js-tooltip"  data-toolbar="save_and_new" title="Сохранить изменения и добавить новую запись">
                        <i class="icon-ok-circle"></i> <i class="icon-plus-sign"></i>
                    </button>';
                break;

            case 'remove':
                return '
                    <button class="btn btn-large js-toolbar js-tooltip" data-toolbar="remove"  title="Удалить">
                        <i class="icon-trash"></i> Удалить
                    </button>';
                break;

            case 'cancel':
                return '
                    <button  class="btn btn-large js-toolbar js-tooltip"  data-toolbar="cancel" title="Отменить">
                        <i class="icon-ban-circle"></i>
                    </button>';
                break;



        }
    }
}


interface joosAutoadminPluginsTable{

    public static function render( joosModel $obj , array $element_param , $key , $value , stdClass $values , $option );

}

interface joosAutoadminPluginsEdit{

    public static function render( $element_param , $key , $value , $obj_data , $params , $tabs );

}


class joosAutoadminFilePluginNotFoundException extends joosException {

}

class joosAutoadminClassPlugionNotFoundException extends joosException {

}
