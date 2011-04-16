<?php

/**
 *
 * */
defined('_JOOS_CORE') or die();

class joosArray {

	public static function array_to_ints(&$array, $default = null) {

		if (is_array($array)) {
			$array = array_map('intval', $array);
		} else {
			if (is_null($default)) {
				$array = array();
				return array();
			} else {
				$array = array((int) $default);
				return array($default);
			}
		}
	}

	/**
	 * ArrayTools::get_position()
	 *
	 * Определение позиции (порядкового номера) элемента массива по ключу
	 *
	 * @param mixed $array
	 * @param mixed $cur
	 * @return integer Порядковый номер элемента в массиве
	 */
	public static function get_position(array $array, $cur) {
		$i = 0;
		foreach ($array as $key => $val) {
			if ($key == $cur)
				return $i;
			$i++;
		}
	}

	/**
	 * ArrayTools::get_next_prev()
	 *
	 * Функция возвращает срез массива по заданным параметрам:
	 *
	 * @param array $array Исходный массив
	 * @param mixed $cur_key Ключ "текущего" элемента (вокруг которого делаем срез)
	 * @param integer $prev Количество элементов, предшествующих текущему
	 * @param integer $next Количество элементов, следуемых за текущим
	 * @param bool $static_count Флаг, повзоляющий всегда сохранять требуемое количество элементов в возвращаемом массиве
	 * @return array
	 *
	 * TODO: добавить возможность не вычислять позицию элемента, если в качестве ключа передается реальный порядковый номер
	 */
	public static function get_next_prev($array, $cur_key, $prev = 3, $next = 5, $static_count = false) {
		$position = self::get_position($array, $cur_key);
		$offset = $position - $prev > 0 ? $position - $prev : 0;
		$length = $prev + $next + 1;

		if ($static_count) {
			$count = count($array);
			$last_index = $count - 1;

			if (($last_index - $offset) < ($prev + $next)) {
				$offset = $offset + ($last_index - $offset) - ($prev + $next);
			}
		}
		return array_slice($array, $offset, $length, true);
	}

	/**
	 * Сортировка массива объектов по свойству объекта
	 * @global type $csort_cmp
	 * @param array $a сортируемый массив
	 * @param string $k свойство-ключ объекта
	 * @param int $sort_direction направление сортировки, 1 - по возрастанию (по умолчанию), -1 - по убывания
	 */
	function SortArrayObjects(array &$a, $k, $sort_direction = 1) {
		global $csort_cmp;
		$csort_cmp = array('key' => $k, 'direction' => $sort_direction);
		usort($a, 'SortArrayObjects_cmp');
		unset($csort_cmp);
	}

	function SortArrayObjects_cmp(&$a, &$b) {
		global $csort_cmp;
		if ($a->$csort_cmp['key'] > $b->$csort_cmp['key']) {
			return $csort_cmp['direction'];
		}
		if ($a->$csort_cmp['key'] < $b->$csort_cmp['key']) {
			return -1 * $csort_cmp['direction'];
		}
		return 0;
	}

}