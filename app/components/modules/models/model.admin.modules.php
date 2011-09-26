<?php
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * adminModules - Модель компонента управления модулями
 * Модель панели управления
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Modules
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class adminModules extends Modules {

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
					'align' => 'center',
					'class' => 'ordering'
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
				'html_edit_element' => 'textarea',
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
			'header_new' => 'Создание модуля',
			'header_edit' => 'Редактирование модуля'
		);
	}

	public function get_extrainfo() {
		return array(
			'search' => array(
				'title'
			),
			'filter' => array(
				'client_id' => array(
					'name' => 'Область действия',
					'call_from' => 'Blog::get_blog_cats'
				),
			),
		);
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

	public static function get_module_filename($item) {
		return $item->module ? $item->module . '.php' : 'содержимое пользователя';
	}

	public static function get_modules_positions($item) {
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
		return ob_get_clean();
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