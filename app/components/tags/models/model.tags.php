<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Tags - Модель тэгов
 * Модель для работы сайта
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Tags
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class Tags extends joosModel {

	/**
	 * @var $id int ID тэга
	 */
	public $id = null;
	/**
	 * @var $obj_id int ID объекта, которому сопоставлен тэг
	 */
	public $obj_id = null;
	/**
	 * @var $obj_option string Тип объекта, которому сопоставлен тэг [com_component]
	 */
	public $obj_option = null;
	/**
	 * @var $tag string Тэг
	 */
	public $tag = null;

	/**
	 * @var $_tags array Массив тэгов
	 */
	//public $_tags = array();

	/**
	 * joiTags::__construct()
	 *
	 */
	function __construct() {
		parent::__construct('#__tags', 'id');
	}

	public function check() {
		$this->filter();
	}

	/**
	 * joiTags::display_object_tags()
	 *
	 * @param object $obj
	 * @return
	 */
	function display_object_tags($obj) {

		$plugin = new tagsPlugins($obj);

		if ($plugin->check()) {
			require_once($plugin->plugin);
			$func = 'tags_' . $plugin->option . '_object_tags';
			return call_user_func($func, $obj, $this);
		}

		return false;
	}

	/**
	 * joiTags::display_object_tags_edit()
	 *
	 * @param mixed $obj
	 * @return
	 */
	function display_object_tags_edit($obj) {

		$this->obj_option = strtolower($obj->classname());
		$plugin = new tagsPlugins($obj);

		if ($plugin->check()) {
			require_once($plugin->plugin);
			$func = 'tags_' . $plugin->option . '_object_tags_edit';
			return call_user_func($func, $obj, $this);
		}

		return false;
	}

	/**
	 * joiTags::display_group_tags()
	 *
	 * @param mixed $objs
	 * @return
	 */
	function display_group_tags($objs) {

		$plugin = new tagsPlugins($this->obj_option);

		if ($plugin->check()) {
			require_once($plugin->plugin);
			$func = 'tags_' . $plugin->option . '_group_tags';
			call_user_func($func, $objs, $this);
		}

		return false;
	}

	/**
	 * Вывод тэгов указанного объекта
	 * @param joosModel $obj требуемый объект
	 * @return string html код ссылок-тэгов
	 */
	function show_tags(joosModel $obj) {

		$id = $obj->{$obj->_tbl_key};

		$tags = $this->get_list(array('select' => 'id,tag', 'where' => "obj_option = '{$obj->classname()}' AND obj_id = $id"));

		$t = array();
		foreach ($tags as $tag) {
			$t[] = $tag->tag;
		}

		return $this->render_tags_href($t);
	}

	/**
	 * joiTags::load_by_obj()
	 *
	 * @param mixed $id
	 * @return
	 */
	function load_by_obj($obj) {

		$id = $obj->{$obj->_tbl_key};

		$this->_tags = array();

		if ($id < 1) {
			return $this->_tags;
		}

		$tags = $this->get_list(array('where' => "obj_option = '{$obj->classname()}' AND obj_id = $id"));


		foreach ($tags as $tag) {
			$this->_tags[$id][] = $tag;
		}

		return $this->_tags;
	}

	/**
	 * joiTags::load_by_obj_simple()
	 *
	 * @param mixed $id
	 * @return
	 */
	function load_by_obj_simple($id) {
		$arr = array();
		$tags = $this->get_list(array('where' => "obj_option = '$this->obj_option' AND obj_id = $id"));

		if (!$tags) {
			return null;
		}

		foreach ($tags as $tag) {
			$arr[] = "'" . $tag->tag . "'";
		}

		return $arr;
	}

	/**
	 * joiTags::load_by_group()
	 *
	 * @param mixed $rows
	 * @param string $id
	 * @return
	 */
	function load_by_group($rows = null, $id = 'id') {

		$ids = array();

		if (!$rows) {
			return;
		}

		foreach ($rows as $row) {
			$ids[] = $row->$id;
		}
		$_ids = implode(',', $ids);

		$sql = 'SELECT * FROM ' . $this->_tbl . ' WHERE obj_option = \'' . $this->obj_option . '\' AND obj_id  IN (' . $_ids . ' )';
		$tags = $this->_mainframe->_db->set_query($sql)->load_object_list();

		if (!$tags) {
			return;
		}

		foreach ($tags as $tag) {
			$this->_tags[$tag->obj_id][] = $tag;
		}

		return $this->_tags;
	}

	function search($tag, $table, $offset = 0, $limit = 0) {
		$sql = "SELECT datas.* FROM #__tags AS tag
        INNER JOIN $table AS datas ON datas.id = tag.obj_id
        WHERE tag = '$tag' AND datas.state=1 ORDER BY id DESC";
		return $this->_db->set_query($sql, $offset, $limit)->load_object_list();
	}

	function search_count($tag) {
		$sql = 'SELECT COUNT(tag.id) FROM #__tags AS tag WHERE tag.tag = \'' . $tag . '\'';
		return $this->_db->set_query($sql)->load_result();
	}

	/**
	 * joiTags::load_by_type()
	 *
	 * @return
	 */
	function load_by_type() {
		$sql = "SELECT tag, obj_id, obj_option FROM $this->_tbl WHERE obj_option = '$this->obj_option'";
		return $this->_db->set_query($sql)->load_result_array();
	}

	function load_all() {
		$sql = "SELECT tag, obj_id, obj_option FROM $this->_tbl";
		return $this->_db->set_query($sql)->load_result_array();
	}

	/**
	 * joiTags::print_tags()
	 *
	 * @param mixed $obj_id
	 * @param string $ds
	 * @return
	 */
	function print_tags($obj_id, $ds = ', ') {

		if ((!$this->_tags
				|| !$obj_id
				|| !isset($this->_tags[$obj_id->{$obj_id->_tbl_key}]))) {
			return;
		}

		$tags = $this->_tags[$obj_id->{$obj_id->_tbl_key}];

		$return = array();
		foreach ($tags as $tag) {
			$return[] = '<a class="tag" href="' . self::get_tag_url($tag->tag) . '">' . $tag->tag . '</a>';
		}

		return implode($ds, $return);
	}

	function render_tags_href($tags_array, $ds = ', ') {
		$return = array();
		foreach ($tags_array as $tag) {
			$return[] = '<a class="tag" href="' . self::get_tag_url($tag) . '">' . $tag . '</a>';
		}

		return implode($ds, $return);
	}

	/**
	 * joiTags::print_tags_edit()
	 *
	 * @param mixed $obj_id
	 * @param string $ds
	 * @return
	 */
	function print_tags_edit($obj_id, $ds = ', ') {

		if (!$obj_id) {
			return 'error';
		}

		$values = '';

		if ($this->_tags && isset($this->_tags[$obj_id->{$obj_id->_tbl_key}])) {
			$tags = $this->_tags[$obj_id->{$obj_id->_tbl_key}];
			$values = array();
			foreach ($tags as $tag) {
				$values[] = $tag->tag;
			}
			$values = implode($ds, $values);
		}

		return '<input type="text" name="tags" class="inputbox"  value="' . $values . '" size="100" />';
	}

	/**
	 * joiTags::save_tags()
	 *
	 * @param mixed $obj_id
	 * @return
	 */
	function save_tags($obj) {

		$obj_option = strtolower($obj->classname());
		$obj_id = $obj->{$obj->_tbl_key};

		$sql = "DELETE FROM {$this->_tbl} WHERE  obj_option = '{$obj_option}' " . ($obj->{$obj->_tbl_key} > 0 ? ' AND obj_id = ' . $obj->{$obj->_tbl_key} : '');
		$this->_db->set_query($sql)->Query();

		$tag_string = joosRequest::post('tags');
		$tags = $this->clear_tags(explode(',', $tag_string));

		$max = count($tags);

		if ($max < 1) {
			return;
		}

		$sql_ = '';
		$n = 1;
		foreach ($tags as $tag) {
			$sql_ .= '(' . $obj_id . ', \'' . $obj_option . '\',   \'' . $tag . '\')';
			if ($n < $max) {
				$sql_ .= ',';
			}
			$n++;
		}

		$sql = "INSERT {$this->_tbl} (obj_id, obj_option, tag) VALUES " . $sql_;
		return $this->_db->set_query($sql)->query();
	}

	/**
	 * joiTags::del_tags()
	 *
	 * @param mixed $cid
	 * @return
	 */
	function del_tags($cid) {
		return $this->delete_array($cid, 'obj_id');
	}

	/**
	 * joiTags::get_tag_url()
	 *
	 * @param mixed $tag
	 * @param string $group
	 * @return
	 */
	public static function get_tag_url($tag, $group = '') {
		return joosRoute::href('tags_view', array('tag' => $tag));
	}

	/**
	 * joiTags::clear_tags()
	 *
	 * @param mixed $tags
	 * @return
	 */
	function clear_tags($tags) {
		$return = array();
		foreach ($tags as $tag) {
			$tag = self::good_tag($tag);
			if ($tag) {
				$return[$tag] = $tag;
			}
		}

		return $return;
	}

	/**
	 * joiTags::good_tag()
	 *
	 * @param mixed $tag
	 * @return
	 */
	public static function good_tag($tag) {
		$bad_tag = array('я', ' ');

		if (in_array($tag, $bad_tag)) {
			return false;
		}

		if ($tag == '') {
			return false;
		}

		$tag = joosText::text_clean($tag);
		return trim($tag);
	}

}

/**
 * joiTagsCloud
 */
class tagsCloud {

	/**
	 * @var array $tags Простой одномерный массив тэгов
	 * */
	var $tags = array();
	/**
	 * @var int $font_size_min Минимальный размер шрифта в облаке
	 * */
	var $font_size_min = null;
	/**
	 * @var int $font_size_max Максимальный размер шрифта в облаке
	 * */
	var $font_size_max = null;
	/**
	 * @var int $step Шаг шрифта
	 * */
	var $step = null;

	/**
	 * joiTagsCloud::__construct()
	 *
	 * @param array $tags Массив тэгов
	 * @param integer $font_size_min Минимальный размер шрифта в облаке
	 * @param integer $font_size_max Максимальный размер шрифта в облаке
	 */
	function __construct($tags, $font_size_min = 12, $font_size_max = 35) {
		$this->tags = $tags;
		$this->font_size_min = $font_size_min;
		$this->font_size_max = $font_size_max;
	}

	/**
	 * joiTagsCloud::get_cloud()
	 * Формируем массив вида: array('тэг'=>[размер_шрифта])
	 *
	 * @return array $cloud
	 */
	function get_cloud($group = '', $limit = 0) {

		$cloud = array();

		//Формируем  массив вида: array('тэг'=>[вес_тэга])
		$tags_weights = self::tags_weights($this->tags);
		//Сортируем по убыванию
		arsort($tags_weights);

		if (count($tags_weights) < 1)
			return $cloud;

		if ($limit) {
			$tags_weights = array_slice($tags_weights, 0, $limit);
		}

		//Узнаём самый мелкий тэг
		$min_count = min(array_values($tags_weights));
		//Самый крупный тэг
		$max_count = max(array_values($tags_weights));
		// плечо
		$spread = $max_count - $min_count;
		if ($spread == 0) {
			$spread = 1;
		}
		//Шаг для размера шрифта
		$this->step = ($this->font_size_max - $this->font_size_min) / ($spread);

		//Формируем облако
		foreach ($tags_weights as $tag => $count) {

			$font_size = round($this->font_size_min + (($count - $min_count) * $this->step));
			$cloud[] = '<a title="' . $tag . '" style="font-size: ' . $font_size . 'px;"  href="' . Tags::get_tag_url($tag, $group) . '">' . $tag . '</a>';
		}
		return $cloud;
	}

	/**
	 * joiTagsCloud::get_tag_count()
	 *
	 * @param mixed $tag_name
	 * @param mixed $tags
	 * @return
	 */
	public static function tags_weights($tags) {

		$tags_weights = array();
		foreach ($tags as $tag) {
			if (key_exists($tag, $tags_weights)) {
				$tags_weights[$tag] = $tags_weights[$tag] + 1;
			} else {
				$tags_weights[$tag] = 1;
			}
		}

		return $tags_weights;
	}

}

/**
 * joiTagsPlugins
 */
class tagsPlugins {

	var $option = null;
	var $plugin = null;

	/**
	 * joiTagsPlugins::joiTagsPlugins()
	 *
	 * @param string $option
	 * @return
	 */
	function __construct($obj) {
		$this->option = strtolower($obj->classname());
	}

	/**
	 * joiTagsPlugins::check()
	 *
	 * @return
	 */
	function check() {
		$plugin = JPATH_BASE . DS . 'plugins' . DS . 'tags' . DS . $this->option . '.php';

		if (is_file($plugin)) {
			$this->plugin = $plugin;
			return true;
		} else {
			return false;
		}
	}

}