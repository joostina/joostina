<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Access - Модель управления правами доступа
 * Модель панели управления
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Access
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class Access extends joosModel {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var varchar(25)
	 */
	public $section;
	/**
	 * @var varchar(25)
	 */
	public $subsection;
	/**
	 * @var varchar(25)
	 */
	public $action;
	/**
	 * @var varchar(255)
	 */
	public $action_label;
	/**
	 * @var tinytext
	 */
	public $accsess;
	private $_subsection_access = array();


	/*
	 * Constructor
	 */
	function __construct() {
		$this->joosDBModel('#__access', 'id');
	}

	public function check() {
		$this->filter();
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
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'section' => array(
				'name' => 'section',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'subsection' => array(
				'name' => 'subsection',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'action' => array(
				'name' => 'action',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'accsess' => array(
				'name' => 'accsess',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Access',
			'header_new' => 'Создание Access',
			'header_edit' => 'Редактирование Access'
		);
	}

	public function fill_rights($section, $subsection) {

		$this->section = $section;
		$this->subsection = $subsection;

		$this->_subsection_access = $this->get_list(array('where' => 'section="' . $this->section . '" AND subsection = "' . $this->subsection . '"'));
	}

	public function draw_config_table() {

		$groups = new UsersGroups;
		$groups = $groups->get_list();

		ob_start();
	   ?>
<table class="table-inset" width="100%">
	<tr>
		<th>
			<span id="check-all">
				<a class="checker active" id="check_it" href="#">Отметить все</a>
			</span>
		</th>

				<?php foreach ($groups as $v) { ?>
					<th><?php echo $v->title ?></th>
				<?php } ?>
			</tr>

			<?php foreach ($this->_subsection_access as $item): $allow_groups = json_decode($item->accsess, true) ?>
				<tr>
					<th><?php echo $item->action ?></th>
					<?php foreach ($groups as $v) {
						$checked = (in_array($v->id, $allow_groups) ? 'checked="checked"' : ''); ?>
						<td><?php echo '<input type="checkbox" class="urights_box" name="access[' . $item->action . '][]" value="' . $v->id . '" ' . $checked . ' />' ?></td>
					<?php } ?>
				</tr>
			<?php endforeach; ?>

		</table>


		<?php
		$return = ob_get_contents();
		ob_end_clean();

		return $return;
	}

}

class joiRights {

	private $component;
	private $rights;
	private $object;

	function __construct($component) {
		$this->component = $component;
		$this->fill_rights();
	}

	private function fill_rights() {
		$rights = array(
			'topic' => array(
				'edit' => array('admin', 'owner', 'manager'),
				'del' => array('admin'),
				'add' => array('admin'),
				'publish' => array('admin', 'owner'),
				'del_offer' => array('manager')
			),
			'games' => array(
				'edit' => array('admin', 'owner'),
				'del' => array('admin'),
				'add' => array('registred', 'admin')
			),
			'workflow' => array(
				'view' => array('admin'),
				'del' => array('admin')
			)
		);

		$this->rights = $rights;
	}

	public function allow_me($action, $object = null) {

		$this->object = $object;

		if (!isset($this->rights[$this->component])) {
			return false;
		}

		$local_rights = $this->rights[$this->component];

		if (isset($local_rights[$action])) {
			return $this->check_rights($local_rights[$action]);
		}
	}

	private function check_rights($usergroups = array()) {
		foreach ($usergroups as $group) {

			if (method_exists(get_class($this), $group)) {
				if ($this->$group() && $this->extended_rights($group)) {

					return true;
				}
			} else {
				if ($this->extended_rights($group)) {
					return true;
				}
			}
		}
		return false;
	}

	private function extended_rights($group) {
		if (is_file($ext = JPATH_BASE . '/components/com_rights/extensions/' . $this->component . '.rights.php')) {
			require_once $ext;
			$model = $this->component . 'Rights';
			return (class_exists($model) AND method_exists($model, $group)) ? call_user_func(array($model, $group), $this->object) : true;
		}
		return true;
	}

	private function admin() {
		if (Users::current()->gid == 8) {
			return true;
		}
		return false;
	}

	private function registred() {
		if (Users::current()->id) {
			return true;
		}
		return false;
	}

	private function owner() {
		if (Users::current()->id == $this->object->user_id) {
			return true;
		}
		return false;
	}

}