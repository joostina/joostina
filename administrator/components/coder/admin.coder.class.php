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

class Coder {

	public static function get_model($table) {

		$table_fields = database::getInstance()->getUtils()->getTableFields($table);
		$tableName = str_replace(array('#__', '#_'), '', $table);

		$className = str_replace(' ', '', ucwords(strtolower(str_replace('_', ' ', $tableName))));

		$return = array();
		$return[] = '<h2>Модель: ' . $className . '</h2>';

		$buffer = array();
		$buffer[] = "\n/**";
		$buffer[] = "\n * Class $className";
		$buffer[] = "\n * @package	$className";
		$buffer[] = "\n * @subpackage	Joostina CMS";
		$buffer[] = "\n * @created	" . _CURRENT_SERVER_TIME;
		$buffer[] = "\n */";
		$buffer[] = "\nclass $className extends JDBmodel {";
		foreach ($table_fields as $k => $v) {
			$buffer[] = "\n	/**";
			$buffer[] = "\n	 * @var $v";
			//$field_type = preg_replace( '#[^A-Z]#i','', $v);
			//$field_type = str_replace( 'unsigned' , '', $field_type);
			//$buffer[] = "\n	 * @type $field_type";
			$buffer[] = "\n	 */";
			$buffer[] = "\n	public \$$k;";
		}
		$buffer[] = "\n\n	/*";
		$buffer[] = "\n	 * Constructor";
		$buffer[] = "\n	 * @param object Database object";
		$buffer[] = "\n	 */";
		$buffer[] = "\n	function __construct(){";
		$buffer[] = "\n		\$this->JDBmodel( '#__$tableName', 'id' );";
		$buffer[] = "\n	}";

		$buffer[] = "\n\n	public function check() {";
		$buffer[] = "\n		\$this->filter();";
		$buffer[] = "\n		return true;";
		$buffer[] = "\n	}\n";

		$buffer[] = "\n\n	public function after_update() {\n";
		$buffer[] = "		return true;";
		$buffer[] = "\n	}\n";

		$buffer[] = "\n\n	public function after_store() {\n";
		$buffer[] = "		return true;";
		$buffer[] = "\n	}\n";

		$buffer[] = "\n\n	public function before_store() {\n";
		$buffer[] = "		return true;";
		$buffer[] = "\n	}\n";

		$buffer[] = "\n\n	public function before_delete() {\n";
		$buffer[] = "		return true;";
		$buffer[] = "\n	}\n";

		$buffer[] = "\n\n	public function get_fieldinfo() {\n";
		$buffer[] = "		return array(";
		foreach ($table_fields as $k => $v) {
			$buffer[] = "\n			'$k' => array(";
			$buffer[] = "\n				'name' => '$k',";
			$buffer[] = "\n				'editable' => true,";
			//$buffer[]= "\n				'sortable' => false,";
			$buffer[] = "\n				'in_admintable' => true,";
			$buffer[] = "\n				'html_table_element' => 'value',";
			$buffer[] = "\n				'html_table_element_param' => array(),";
			//$buffer[]= "\n					'width' => '20px',";
			//$buffer[]= "\n					'align' => 'center'";
			//$buffer[]= "\n				),";
			$buffer[] = "\n				'html_edit_element' => 'edit',";
			$buffer[] = "\n				'html_edit_element_param' => array(),";
			$buffer[] = "\n			),";
		}
		$buffer[] = "\n		);";
		$buffer[] = "\n	}\n";

		$buffer[] = "\n\n	public function get_tableinfo() {\n";
		$buffer[] = "			return array(";
		$buffer[] = "\n				'header_list' => '$className',";
		$buffer[] = "\n				'header_new' => 'Создание $className',";
		$buffer[] = "\n				'header_edit' => 'Редактирование $className'";
		$buffer[] = "\n			);";
		$buffer[] = "\n	}\n";



		$buffer[] = "\n}\n";

		$return[] = form::textarea(array('name' => $tableName, 'value' => implode('', $buffer), 'rows' => '5'));

		return implode("\n", $return);
	}

}