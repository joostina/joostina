<?php defined('_JOOS_CORE') or exit;

/**
 * Модель компонента управляемой генерации расширений системы
 * Модель панели управления
 *
 * @version    1.0
 * @package    Components\Coder
 * @subpackage Models\Admin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class modelAdminCoder
{
    public function get_tableinfo()
    {
        return array(
            'header_main'=>'Кодер'
        );
    }

    public static function get_model($table, $implode_models = false)
    {
        $table_fields = joosDatabase::instance()->get_utils()->get_table_fields($table);
        $tableName = str_replace(array('#__', '#_', joosDatabase::instance()->get_prefix()), '', $table);

        $className = str_replace(' ', '', ucwords(strtolower(str_replace('_', ' ', $tableName))));

        $buffer_site = array();
        $buffer_site[] = "\n/**";
        $buffer_site[] = "\n * Модель сайта компонента $className";
        $buffer_site[] = "\n * ";
        $buffer_site[] = "\n * @package Components\\$className";
        $buffer_site[] = "\n * @subpackage Models\Site";
        $buffer_site[] = "\n * @author JoostinaTeam <info@joostina.ru>";
        $buffer_site[] = "\n * @copyright (C) 2007-2012 Joostina Team";
        $buffer_site[] = "\n * @license MIT License http://www.opensource.org/licenses/mit-license.php";
        $buffer_site[] = "\n * @created " . JCURRENT_SERVER_TIME;
        $buffer_site[] = "\n * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights";
        $buffer_site[] = "\n * ";
        $buffer_site[] = "\n */";

        $buffer_site[] = "\nclass model$className extends joosModel {";
        foreach ($table_fields as $k => $v) {
            $buffer_site[] = "\n	/**";
            $buffer_site[] = "\n	 * @field $v";
            $field_type = preg_replace('#[^A-Z]#i', '', $v);
            $field_type = str_replace('unsigned', '', $field_type);
            $field_type_name = self::get_type($field_type);
            $buffer_site[] = "\n	 * @type $field_type_name";
            $buffer_site[] = "\n	 */";
            $buffer_site[] = "\n	public \$$k;";
        }
        $buffer_site[] = "\n\n	/*";
        $buffer_site[] = "\n	 * Constructor";
        $buffer_site[] = "\n	 *";
        $buffer_site[] = "\n	 */";
        $buffer_site[] = "\n	function __construct(){";
        $buffer_site[] = "\n		parent::__construct( '#__$tableName', 'id' );";
        $buffer_site[] = "\n	}";

        $buffer_site[] = "\n\n	public function check() {";
        $buffer_site[] = "\n		\$this->filter();";
        $buffer_site[] = "\n		return true;";
        $buffer_site[] = "\n	}\n";

        $buffer_site[] = "\n\n	public function before_insert() {\n";
        $buffer_site[] = "		return true;";
        $buffer_site[] = "\n	}\n";

        $buffer_site[] = "\n\n	public function after_insert() {\n";
        $buffer_site[] = "		return true;";
        $buffer_site[] = "\n	}\n";

        $buffer_site[] = "\n\n	public function before_update() {\n";
        $buffer_site[] = "		return true;";
        $buffer_site[] = "\n	}\n";

        $buffer_site[] = "\n\n	public function after_update() {\n";
        $buffer_site[] = "		return true;";
        $buffer_site[] = "\n	}\n";

        $buffer_site[] = "\n\n	public function before_store() {\n";
        $buffer_site[] = "		return true;";
        $buffer_site[] = "\n	}\n";

        $buffer_site[] = "\n\n	public function after_store() {\n";
        $buffer_site[] = "		return true;";
        $buffer_site[] = "\n	}\n";

        $buffer_site[] = "\n\n	public function before_delete() {\n";
        $buffer_site[] = "		return true;";
        $buffer_site[] = "\n	}\n";

        $buffer_site[] = "\n}\n";

        $buffer_admin[] = "\n/**";
        $buffer_admin[] = "\n * Модель панели управления компонента $className";
        $buffer_admin[] = "\n * ";
        $buffer_admin[] = "\n * @package Components\\$className";
        $buffer_admin[] = "\n * @subpackage Models\Admin";
        $buffer_admin[] = "\n * @author JoostinaTeam <info@joostina.ru>";
        $buffer_admin[] = "\n * @copyright (C) 2007-2012 Joostina Team";
        $buffer_admin[] = "\n * @license MIT License http://www.opensource.org/licenses/mit-license.php";
        $buffer_admin[] = "\n * @created " . JCURRENT_SERVER_TIME;
        $buffer_admin[] = "\n * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights";
        $buffer_admin[] = "\n * ";
        $buffer_admin[] = "\n */";

        $buffer_admin[] = "\nclass modelAdmin$className extends model$className {";

        $buffer_admin[] = "\n\n	public function get_fieldinfo() {\n";
        $buffer_admin[] = "		return array(";
        foreach ($table_fields as $k => $v) {
            $buffer_admin[] = "\n			'$k' => array(";
            $buffer_admin[] = "\n				'name' => '$k',";
            $buffer_admin[] = "\n				'editable' => true,";
            $buffer_admin[] = "\n				'in_admintable' => true,";
            $buffer_admin[] = "\n				'html_table_element' => 'value',";
            $buffer_admin[] = "\n				'html_table_element_param' => array(),";
            $buffer_admin[] = "\n				'html_edit_element' => 'edit',";
            $buffer_admin[] = "\n				'html_edit_element_param' => array(),";
            $buffer_admin[] = "\n			),";
        }
        $buffer_admin[] = "\n		);";
        $buffer_admin[] = "\n	}\n";

        $buffer_admin[] = "\n\n	public function get_tableinfo() {\n";
        $buffer_admin[] = "			return array(";
        $buffer_admin[] = "\n				'header_main' => '$className',";
        $buffer_admin[] = "\n				'header_list' => '$className',";
        $buffer_admin[] = "\n				'header_new' => 'Создание $className',";
        $buffer_admin[] = "\n				'header_edit' => 'Редактирование $className'";
        $buffer_admin[] = "\n			);";
        $buffer_admin[] = "\n	}\n";

        $buffer_admin[] = "\n\n	public function get_extrainfo() {\n";
        $buffer_admin[] = "			return array(";
        $buffer_admin[] = "\n				'search' => array(),";
        $buffer_admin[] = "\n				'filter' => array(),";
        $buffer_admin[] = "\n				'extrafilter' => array()";
        $buffer_admin[] = "\n			);";
        $buffer_admin[] = "\n	}\n";

        $buffer_admin[] = "\n}\n";

        $return = array();

        $return['site'] = $implode_models ? implode('', $buffer_site) : joosHTML::textarea(
            array('name' => $tableName,
                'value' => implode('', $buffer_site),
                'rows' => '5',
                'class' => 'coder_model_area'
            )
        );

        $return['admin'] = $implode_models ? implode('', $buffer_admin) : joosHTML::textarea(
            array('name' => $tableName,
                'value' => implode('', $buffer_site),
                'rows' => '5',
                'class' => 'coder_model_area'
            )
        );

        return $return;
    }

    /**
     * Формирование общего представления переменной по типу поля
     *
     * @param  string $ident_string
     * @return string
     */
    private static function get_type($ident_string)
    {
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

class modelAdminCoder_Faker
{
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
