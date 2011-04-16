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

class Coder
{

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

    public static function get_model($table, $implode_models = false)
    {

        $table_fields = joosDatabase::instance()->get_utils()->get_table_fields($table);
        $tableName = str_replace(array('#__', '#_', joosDatabase::instance()->get_prefix()), '', $table);

        $className = str_replace(' ', '', ucwords(strtolower(str_replace('_', ' ', $tableName))));

        $return = array();
        $return[] = $implode_models ? null : '<h2>Модель: ' . $className . '</h2>';

        $buffer = array();
        $buffer[] = "\n/**";
        $buffer[] = "\n * Class $className";
        $buffer[] = "\n * @package	Joostina.Components";
        $buffer[] = "\n * @subpackage	$className";
        $buffer[] = "\n * @author JoostinaTeam";
        $buffer[] = "\n * @copyright (C) 2008-2011 Joostina Team";
        $buffer[] = "\n * @license MIT License http://www.opensource.org/licenses/mit-license.php";
        $buffer[] = "\n * @version ";
        $buffer[] = "\n * @created " . _CURRENT_SERVER_TIME;
        $buffer[] = "\n * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights";

        $buffer[] = "\n */";
        $buffer[] = "\nclass $className extends joosDBModel {";
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
        //$buffer[] = "\n	 * @param object Database object";
        $buffer[] = "\n	 */";
        $buffer[] = "\n	function __construct(){";
        $buffer[] = "\n		\$this->joosDBModel( '#__$tableName', 'id' );";
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

        $buffer[] = "\n\n	public function get_extrainfo() {\n";
        $buffer[] = "			return array(";
        $buffer[] = "\n				'search' => array(),";
        $buffer[] = "\n				'filter' => array(),";
        $buffer[] = "\n				'extrafilter' => array()";
        $buffer[] = "\n			);";
        $buffer[] = "\n	}\n";

        $buffer[] = "\n}\n";

        $return[] = $implode_models ? implode('', $buffer) : forms::textarea(array('name' => $tableName, 'value' => implode('', $buffer), 'rows' => '5', 'class' => 'coder_model_area'));

        return implode("\n", $return);
    }

}


class dbFaker
{

    public static $data_types = array(
        'text' => array(
            'name' => 'Текст',
            'type' => ''
        ),
        'text_small' => array(
            'name' => 'Заголовок',
            'type' => ''
        ),
        'text_name' => array(
            'name' => 'Имя',
            'type' => ''
        ),
        'href' => array(
            'name' => 'Ссылка',
            'type' => ''
        ),
        'integer' => array(
            'name' => 'Число',
            'type' => ''
        ),
        'integer_range' => array(
            'name' => 'Числа из диапазона',
            'type' => ''
        ),
        'date' => array(
            'name' => 'Дата',
            'type' => ''
        ),
        'date_time' => array(
            'name' => 'Дата и время',
            'type' => ''
        )
    );

    public static $types_mapping = array(
        //DATE TIME DATETIME CHAR VARCHAR TEXT TINYTEXT MEDIUMTEXT LONGTEXT BLOB TINYBLOB MEDIUMBLOB LONGBLOB ENUM SET
    );

}