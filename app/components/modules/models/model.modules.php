<?php
/**
 * Modules - управление модулями
 * Модель
 *
 * @version 1.0
 * @package ComponentsAdmin
 * @subpackage Modules
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license see license.txt
 *
 * */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class Modules extends joosDBModel {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var joosText
	 */
	public $title;
	/**
	 * @var joosText
	 */
	public $content;
	/**
	 * @var int(11)
	 */
	public $ordering;
	/**
	 * @var varchar(10)
	 */
	public $position = 'left';
	/**
	 * @var tinyint(1)
	 */
	public $state = 0;
	/**
	 * @var varchar(50)
	 */
	public $module;
	/**
	 * @var varchar(255)
	 */
	public $template;
	/**
	 * @var joosText
	 */
	public $params;
	/**
	 * @var tinyint(4)
	 */
	public $client_id = 0;
	/**
	 * @var int(11)
	 */
	public $cache_time;

	/*
	 * Constructor
	 */
	function __construct() {
		$this->joosDBModel('#__modules', 'id');
	}

	public function check() {
		$this->filter(array('content'));
		return true;
	}

	public function after_update() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value'
			),
			'module' => array(
				'name' => 'Модуль',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '150px',
				),
				'html_edit_element' => 'extra',
				'html_edit_element_param' => array(
					'call_from' => 'Modules::get_module_filename',
				),
			),
			'client_id' => array(
				'name' => 'Тип',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'option',
				'html_edit_element_param' => array(
					'options' => array(
						'0' => 'Модуль сайта',
						'1' => 'Модуль админпанели'
					)
				),
			),
			'state' => array(
				'name' => 'Состояние',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'editlink' => true,
				'html_edit_element' => 'checkbox',
				'html_table_element' => 'state_box',
				'html_edit_element_param' => array(
					'text' => 'Опубликовано',
				),
				'html_table_element' => 'statuschanger',
				'html_table_element_param' => array(
					'statuses' => array(
						0 => 'Скрыто',
						1 => 'Опубликовано'
					),
					'images' => array(
						0 => 'publish_x.png',
						1 => 'publish_g.png',
					),
					'align' => 'center',
					'class' => 'td-state-joiadmin',
					'width' => '20px',
				)
			),
			'title' => array(
				'name' => 'Заголовок',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'editlink',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'template' => array(
				'name' => 'Шаблон',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'ordering' => array(
				'name' => 'Порядок',
				'editable' => false,
				'in_admintable' => true,
				'html_table_element' => 'ordering',
				'html_table_element_param' => array(
					'scope' => array('position'),
					'width' => '100px',
					'align' => 'center'
				),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'position' => array(
				'name' => 'Позиция',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'extra',
				'html_edit_element_param' => array(
					'call_from' => 'Modules::get_modules_positions',
				),
			),
			'cache_time' => array(
				'name' => 'Время кеширования',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'params' => array(
				'name' => 'Параметры',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'json',
				'html_edit_element_param' => array(
					'call_from' => 'Modules::parce_params'
				),
			),
			'content' => array(
				'name' => 'Содержимое (HTML)',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'text',
				'html_edit_element_param' => array(),
			),
			'%modules_pages' => array(
				'name' => 'Где показывать',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'extra',
				'html_edit_element_param' => array(
					'call_from' => 'Modules::get_modules_pages',
				),
			),
			'%access' => array(
				'name' => 'Права доступа',
				'editable' => true,
				'html_edit_element' => 'access',
				'html_edit_element_param' => array(
					'call_from' => 'Modules::get_access_init'
				),
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_main' => 'Модули',
			'header_list' => 'Модули',
			'header_new' => 'Создание Modules',
			'header_edit' => 'Редактирование модуля'
		);
	}

	public static function get_module_filename($item) {
		return $item->module ? $item->module . '.php' : 'содержимое пользователя';
	}

	public static function get_modules_positions($item) {
		joosLoader::admin_model('templates');
		joosLoader::lib('forms');
		$positions = new TemplatePositions;

		$opt = $positions->get_selector(array("key" => "position", "value" => "position"));
		return forms::dropdown('position', $opt, $item->position);
	}

	public static function get_modules_pages($item) {

		$pages = new ModulesPages;

		$pages_list = null;
		if ($item->id) {
			$pages_list = $pages->get_list(array('where' => 'moduleid = ' . $item->id));
		}

		$pages_list = $pages_list ? $pages_list : array($pages);

		$return = '';
		$i = 0;

		ob_start();
		?>
		<div id="modules_pages">
		<?php foreach ($pages_list as $page): ?>

				<div class="fields" title="<?php echo $i ?>">
					<div class="b b-left b-30">
						<label class="b">Контроллер</label>
						<input class="b-90" name="pages[<?php echo $i ?>][controller]" type="text"
							   value="<?php echo $page->controller ?>"/>
					</div>

					<div class="b b-left b-30">
						<label class="b">Метод</label>
						<input class="b-90" name="pages[<?php echo $i ?>][method]" type="text"
							   value="<?php echo $page->method ?>"/>
					</div>

					<div class="b b-left b-20">
						<label class="b">или правило</label>
						<input class="b-90" name="pages[<?php echo $i ?>][rule]" type="text" value="<?php echo $page->rule ?>"/>
					</div>

					<div class="b b-left b-20">
						<label class="b">&nbsp;</label>
						<span class="g-pseudolink module_page_copy">[+]</span>
						<span class="g-pseudolink module_page_del">[x]</span>
					</div>
				</div>
			<?php $i++;
		endforeach; ?>
		</div>

		<input type="hidden" name="fields_count" id="fields_count" value="<?php echo $i ?>"/>

		<?php
		$return = ob_get_contents();
		ob_end_clean();

		return $return;
	}

	public static function parce_params($item) {

		if (!$item->module) {
			return false;
		}

		$file = 'modules' . DS . $item->module . DS . $item->module . '.params.php';
		$file = $item->client_id ? JPATH_BASE . DS . JPATH_BASE_ADMIN . $file : JPATH_BASE . DS . $file;

		if (is_file($file)) {
			require($file);
			return $extension_params;
		} else {
			return false;
		}
	}

	public static function get_access_init($item) {
		return array(
			'section' => 'Module',
			'subsection' => $item->id
		);
	}

}

/**
 * Class ModulesPages
 * @package    ModulesPages
 * @subpackage    Joostina CMS
 * @created    2010-12-12 14:52:47
 */
class ModulesPages extends joosDBModel {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var int(11)
	 */
	public $moduleid;
	/**
	 * @var varchar(25)
	 */
	public $controller;
	/**
	 * @var varchar(50)
	 */
	public $method;
	/**
	 * @var varchar(50)
	 */
	public $rule;

	/*
	 * Constructor
	 */
	function __construct() {
		$this->joosDBModel('#__modules_pages', 'id');
	}

	public function check() {
		//$this->filter();
		return true;
	}

	public function after_update() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

}
