<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * modelAdminCoder - Модель компонента управляемой генерации расширений системы
 * Модель панели управления
 *
 * @version    1.0
 * @package    Joostina.Models
 * @subpackage Coder
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class modelAdminCoder {

	//Для вывода меню компонента
	public static $_submenu = array(
		'model' => array(
			'title' => 'Генератор моделей',
			'href' => 'index2.php?option=coder&task=model'
		),
		'module' => array(
			'title' => 'Генератор модулей',
			'href' => 'index2.php?option=coder&task=module'
		)
	);

	public static function get_model($table, $implode_models = false) {

		$table_fields = joosDatabase::instance()->get_utils()->get_table_fields($table);
		$tableName = str_replace(array('#__', '#_', joosDatabase::instance()->get_prefix()), '', $table);

		$className = str_replace(' ', '', ucwords(strtolower(str_replace('_', ' ', $tableName))));

		$return = array();
		$return[] = $implode_models ? null : '<h2>Модель: ' . $className . '</h2>';

		$buffer = array();
		$buffer[] = "\n/**";
		$buffer[] = "\n * Class $className";
		$buffer[] = "\n * @package Joostina.Components";
		$buffer[] = "\n * @subpackage $className";
		$buffer[] = "\n * @author JoostinaTeam";
		$buffer[] = "\n * @copyright (C) 2007-2012 Joostina Team";
		$buffer[] = "\n * @license MIT License http://www.opensource.org/licenses/mit-license.php";
		$buffer[] = "\n * @version 1";
		$buffer[] = "\n * @created " . JCURRENT_SERVER_TIME;
		$buffer[] = "\n * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights";

		$buffer[] = "\n */";
		$buffer[] = "\nclass model$className extends joosModel {";
		foreach ($table_fields as $k => $v) {
			$buffer[] = "\n	/**";
			$buffer[] = "\n	 * @field $v";
			$field_type = preg_replace('#[^A-Z]#i', '', $v);
			$field_type = str_replace('unsigned', '', $field_type);
			$field_type_name = self::get_type($field_type);
			$buffer[] = "\n	 * @type $field_type_name";
			$buffer[] = "\n	 */";
			$buffer[] = "\n	public \$$k;";
		}
		$buffer[] = "\n\n	/*";
		$buffer[] = "\n	 * Constructor";
		//$buffer[] = "\n	 * @param object Database object";
		$buffer[] = "\n	 */";
		$buffer[] = "\n	function __construct(){";
		$buffer[] = "\n		parent::__construct( '#__$tableName', 'id' );";
		$buffer[] = "\n	}";

		$buffer[] = "\n\n	public function check() {";
		$buffer[] = "\n		\$this->filter();";
		$buffer[] = "\n		return true;";
		$buffer[] = "\n	}\n";

		$buffer[] = "\n\n	public function before_insert() {\n";
		$buffer[] = "		return true;";
		$buffer[] = "\n	}\n";

		$buffer[] = "\n\n	public function after_insert() {\n";
		$buffer[] = "		return true;";
		$buffer[] = "\n	}\n";

		$buffer[] = "\n\n	public function before_update() {\n";
		$buffer[] = "		return true;";
		$buffer[] = "\n	}\n";

		$buffer[] = "\n\n	public function after_update() {\n";
		$buffer[] = "		return true;";
		$buffer[] = "\n	}\n";

		$buffer[] = "\n\n	public function before_store() {\n";
		$buffer[] = "		return true;";
		$buffer[] = "\n	}\n";

		$buffer[] = "\n\n	public function after_store() {\n";
		$buffer[] = "		return true;";
		$buffer[] = "\n	}\n";

		$buffer[] = "\n\n	public function before_delete() {\n";
		$buffer[] = "		return true;";
		$buffer[] = "\n	}\n";

		$buffer[] = "\n}\n";

		$buffer[] = "\nclass modelAdmin$className extends model$className {";


		$buffer[] = "\n\n	public function get_fieldinfo() {\n";
		$buffer[] = "		return array(";
		foreach ($table_fields as $k => $v) {
			$buffer[] = "\n			'$k' => array(";
			$buffer[] = "\n				'name' => '$k',";
			$buffer[] = "\n				'editable' => true,";
			$buffer[] = "\n				'in_admintable' => true,";
			$buffer[] = "\n				'html_table_element' => 'value',";
			$buffer[] = "\n				'html_table_element_param' => array(),";
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

		$buffer[] = "\n\n	public function get_extrainfo() {\n";
		$buffer[] = "			return array(";
		$buffer[] = "\n				'search' => array(),";
		$buffer[] = "\n				'filter' => array(),";
		$buffer[] = "\n				'extrafilter' => array()";
		$buffer[] = "\n			);";
		$buffer[] = "\n	}\n";


		$buffer[] = "\n}\n";

		$return[] = $implode_models ? implode('', $buffer) : forms::textarea(array('name' => $tableName,
					'value' => implode('', $buffer),
					'rows' => '5',
					'class' => 'coder_model_area'));

		return implode("\n", $return);
	}

	/**
	 * Формирование общего представления переменной по типу поля
	 * @param string $ident_string 
	 */
	private static function get_type($ident_string) {

		$ident_string = strtolower($ident_string);

		switch ($ident_string) {
			case 'tinyint':
			case 'smallint':
			case 'mediumint':
			case 'int':
			case 'bigint':
			case 'decimal':
			case 'double':
			case 'real':
			case 'bit':
			case 'serial':

				$type = 'int';
				break;

			case 'char':
			case 'varchar':
			case 'tinytext';
			case 'text';
			case 'mediumtext';
			case 'longtext';
			case 'binary':
			case 'varbinary':
			case 'tinyblob':
			case 'mediumblob':
			case 'blob':
			case 'longblob':
			case 'enum':
			case 'set':

				$type = 'string';
				break;

			case 'date':
			case 'datetime':
			case 'timestamp':
			case 'time':
			case 'year':

				$type = 'datetime';
				break;

			case 'boolean':

				$type = 'boolean';
				break;

			case 'float':

				$type = 'float';
				break;

			default:
				$type = 'unknown';
				break;
		}

		return $type;
	}

}

class modelAdminCoder_Faker {

	public static $data_types = array('text' => array('name' => 'Текст',
			'types' => array('text', 'tinytext', 'mediumtext', 'longtext', 'blob', 'tinyblob', 'mediumblob', 'longblob'),),
		'text_small' => array('name' => 'Заголовок',
			'types' => array('varchar'),),
		'text_name' => array('name' => 'Имя',
			'types' => array('varchar'),),
		'href' => array('name' => 'Ссылка',
			'types' => array('varchar'),),
		'integer' => array('name' => 'Число',
			'types' => array('tinyint', 'smallint', 'int'),),
		'integer_range' => array('name' => 'Числа из диапазона',
			'types' => array('tinyint', 'smallint', 'int'),),
		'date' => array('name' => 'Дата',
			'types' => array('date'),),
		'time' => array('name' => 'Время',
			'types' => array('time'),),
		'date_time' => array('name' => 'Дата и время',
			'types' => array('datetime'),));
	public static $types_mapping = array(
	);

}

