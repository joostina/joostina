<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

/**
 * Класс для получения параметров из базы данных
 */
class DBconfig {
	var $_group = '';
	var $_subgroup = '';
	var $_db = null;
	var $_error = '';
	var $_loaded = null;

	function DBconfig($database, $group = '', $subgroup='') {
		global $option;

		$database = database::getInstance();
		$this->_db = $database;

		// проверяем - задана ли группа
		if (trim($group) == '') {
			$this->_group = $option;// нет не задана - принимаем значение $option
		} else {
			$this->_group = $group;// задано
		}

		// проверяем - задана ли подгруппа
		if (trim($subgroup) == '') {
			$this->_subgroup = '';
		} else {
			$this->_subgroup = $subgroup;
		}

		// получаем все значение из базы данных
		if($this->bindConfig($this->_formatArray($values = $this->getBatchValues()))) {
			return $values;
		};
		return false;
	}

	function bindConfig($array, $prefix = '') {
		if (!is_array($array)) {
			$this->_error = 'No array param';
			return false;
		} else {
			$prefix = trim($prefix);
			$rows = get_object_vars($this);
			foreach ($rows as $key => $value) {
				if (isset($array[$prefix . $key]) and substr($key, 0, 1) !== '_') {
					$this->$key = $array[$key];
				}
			}
			return true;
		}
	}

	function prepare_for_xml_render() {

		$rows = get_object_vars($this);
		$array = array();
		foreach ($rows as $key => $value) {
			if(substr($key, 0, 1) !== '_') {
				$array[] = "$key=$value";
			}
		}
		$txt = implode("\n", $array);
		return $txt;
	}

	function storeConfig() {
		$rows = get_object_vars($this);

		$title = '';
		$info = '';

		foreach ( $rows as $key => $value ) {

			if (substr($key, 0, 1) !== '_') {
				$return = $this->setValue($key, $value);
				if (!$return) {
					break;
				}
			}
		}
		return $return;
	}

	function def($key,$value = '') {
		return $this->set($key,$this->get($key,$value));
	}

	function get($key,$default = '') {
		if(isset($this->$key)) {
			return $this->$key === ''?$default:$this->$key;
		} else {
			return $default;
		}
	}

	function set($key,$value = '') {
		$this->$key = $value;
		return $value;
	}

	function getBatchValues() {

		$where = '';
		if($this->_subgroup) {
			$where = " AND c.subgroup = '".$this->_subgroup."'";
		}

		$this->_db->setQuery("SELECT c.name, c.value, c.subgroup FROM #__config AS c WHERE c.group='$this->_group'".$where);

		$return = $this->_db->loadObjectList();
		if (count($return) && is_array($return)) {
			$this->_loaded = 1;
			return $return;
		} else {
			return null;
		}
	}


	/**
	 * Получение одиночного параметра из базы данных
	 *
	 * @var string $name Имя параметра
	 * @var string $group Группа параметра
	 * @var string $type Тип записи: s - текстовая строка; i - целое число; f - дробное число; a - массив; b - логическое И/Л
	 * @var variant $default Значение по умолчанию, если параметр не будет найден в базе данных, то значение по умолчанию передается
	 *
	 * @return variant Значение параметра конфигурации
	 */
	function getValue($name, $default = null) {
		// выбираем строку со значением из базы данных
		$this->_db->setQuery("SELECT c.value " .
				"FROM #__config AS c " .
				"WHERE c.name='$name' " .
				"AND c.group='$this->_group' " .
				"LIMIT 1");
		$value = $this->_db->loadResult();

		// получаем значение параметра
		$return = $this->_parseValue($value);

		if (!$return) {
			$return = $default;
		}

		return $return;
	}

	/**
	 * Установка значения параметра
	 *
	 * @var string $name Имя параметра
	 * @var variant $value значение параметра
	 * @var string $group группа параметра, если значение не задано, то берется название компонента из переменной $option
	 * @var string $type тип параметра, если тип не задан, то осуществляется попытка определить тип параметра, по умолчанию - строка
	 * @return int передается значение о результатах работы из $database->query()
	 */
	function setValue($name, $value, $type = '', $title = '', $info = '') {
		// Попытка определить тип
		if ($type == '') {						// если тип не задан по умолчанию
			if (is_array($value)) {				// если массив
				$type = 'a';					// то устанавливаем тип "массив"
			} else if (is_bool($value)) {		// если логический
				$type = 'b';					// то устанавливаем тип "логический"
			} else if (is_int($value)) {		// если целое число
				$type = 'i';					// то устанавливаем тип "целое число"
			} else if (is_float($value)) {		// если дробное число
				$type = 'f';					// то устанавливаем тип "дробное число"
			} else {							// иначе - текстовая строка
				$type = 's';					// то устанавливаем тип "текстовая строка"
			}
		}

		$str = $this->_formatValue($type,$value);

		$where = '';
		if($this->_subgroup) {
			$where = " AND c.subgroup = '".$this->_subgroup."'";
		}

		// проверим, есть ли в базе параметр с таким именем в группе
		$this->_db->setQuery("SELECT c.id " .
				"FROM #__config AS c " .
				"WHERE c.name='$name' " .
				"AND c.group='$this->_group' $where " .
				"LIMIT 1;");

		$id = $this->_db->loadResult();
		if ($id) {							// если есть,
			$sql = "UPDATE #__config " .	// то формируем запрос
					"SET value='$str' " .	// для обновления записи
					"WHERE id='$id' " .
					"LIMIT 1;";
		} else {							// если нет,
			$sql = "INSERT INTO #__config " .// то формируем запрос
					"VALUES (0,'$this->_group', '$this->_subgroup', '$name', '$str')";	// для добавления записи
		}
		$this->_db->setQuery($sql);
		// передаем результат
		return $this->_db->query();
	}

	/**
	 * Получение "разрезанной" строки на массив, где
	 * type - тип параметра
	 * length - длина строки со значением
	 * value - значение параметра
	 * Строка в базе имеет вид: <type>:<length>{<value>},
	 *
	 * @var string $value строка для разбора
	 */
	function _parseValue($value) {
		$value_array = array();

		if (substr($value, 1, 1) == ":") {
			$type = substr($value, 0, 1);
		} else {
			$type = "u";
		}
		$pos = strpos($value, "{");
		$length = (int)substr($value, 2, $pos-2);
		$value = substr($value, $pos+1, strlen($value)-2-$pos);
		$value = substr($value, 0, $length);

		// задаем тип параметра
		switch ($type) {
			// текстовая строка
			case 's':
				$return = (string)substr($value, 0, $length);
				break;

			// целое число
			case 'i':
				$return = (int)substr($value, 0, $length);
				break;

			// дробное число
			case 'f':
				$return = (float)substr($value, 0, $length);
				break;

			// массив значений
			case 'a':
				$items = explode(";",$value);
				$return = array();
				for ($i=0; $i<count($items); $i++) {
					$return[] = $this->_parseValue($items[$i]);
				}
				break;

			// логическое значение
			case 'b':
				$return = ($value_array['value'] == '1')?true:false;
				break;

			case 'u':
			default:
				$return = $value;
				break;
		}

		return $return;
	}

	/**
	 * Формирование строки для записи в базу данных
	 *
	 * @var string $type Тип параметра
	 * @var variant $value Значение параметра
	 */
	function _formatValue($type, $value) {
		switch ($type) {
			case 's':
			case 'i':
			case 'f':
				$v = (string)$value;
				break;

			case 'b':
				$v = ($value == true or $value == '1' or $value == 1)?'1':'0';
				break;

			case 'a':
			// проверка является ли значение массивом
				if (is_array($value)) {
					$a = array();
					for ($i=0; $i<count($value); $i++) {
						$a[] = $this->_formatValue($value[$i]['type'], $value[$i]['value']);
					}
					$v = implode(';', $a);
				}
				break;
		}
		return $type . ":" . strlen($v) . "{" . $v . "}";
	}

	function _formatArray($array) {
		$return = array();
		if (is_array($array) and count($array) > 0) {
			for ( $i = 0; $i < count($array); $i++ ) {
				$item = &$array[$i];
				$return[$item->name] = $this->_parseValue($item->value);
			}
		}
		return $return;
	}
}