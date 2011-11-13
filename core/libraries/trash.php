<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * joosTrash - Библиотека работы с общесистемной корзиной
 * Системная библиотека
 *
 * @version    1.0
 * @package    Joostina.Libraries
 * @subpackage Libraries
 * @subpackage joosModel
 * @subpackage joosDatabase
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2011 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosTrash extends joosModel {

	public $id;
	public $obj_id;
	public $obj_table;
	public $title;
	public $data;
	public $user_id;
	public $deleted_at;

	public function __construct() {
		parent::__construct( '#__trash' , 'id' );
	}

	/**
	 * Добавление копии удалённого объекта в корзину
	 *
	 * @global User    $my  - объект текущего пользователя
	 * @param stdClass $obj - удаляемый объект
	 *
	 * @return boolean результат сохранения копии удаляемого объекта в корзину
	 */
	public static function add( $obj_original ) {

		$obj = clone $obj_original;

		// ключевое индексное поле объекта
		$_tbl_key = $obj->_tbl_key;

		// если у удаляемого объекта отсутствует ключ - то объет не определён
		if ( !$obj_original->$_tbl_key ) {
			return false;
		}

		// удаляем объект базы данных
		unset( $obj->_db , $obj->_error );

		// собираем данные для сохранения резервной копии
		$trash             = new self;
		$trash->obj_id     = $obj->$_tbl_key;
		$trash->obj_table  = $obj->_tbl;
		$trash->title      = isset( $obj->title ) ? $obj->title : $obj->$_tbl_key;
		$trash->data       = json_encode( $obj );
		$trash->user_id    = modelUsers::instance()->id;
		$trash->deleted_at = _CURRENT_SERVER_TIME;

		return (bool) $trash->store();
	}

}