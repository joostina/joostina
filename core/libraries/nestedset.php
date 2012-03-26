<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
  * Библиотека рвботы с вложенными категориями и деревьями в базе данных
 * Системная библиотека
 *
 * @version    1.0
 * @package    Libraries
 * @subpackage Libraries
 * @subpackage joosModel
 * @subpackage joosDatabase
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosNestedSet extends joosModel {

	/**
	 * Уникальный идентификатор узла
	 *
	 * @var int
	 */
	public $id;
	/**
	 * Левый ключ узла
	 *
	 * @var int
	 */
	public $lft;
	/**
	 * Правый ключ узла
	 *
	 * @var int
	 */
	public $rgt;
	/**
	 * Уровень узла
	 *
	 * @var int
	 */
	public $level;
	/**
	 * ID родительского узла
	 *
	 * @var int
	 */
	public $parent_id;
	public $moved;
	/**
	 * Имя узла
	 *
	 * @var str
	 */
	public $name;
	/**
	 * Компонент
	 *
	 * @var str
	 */
	public $group;
	/**
	 * Ссылка
	 *
	 * @var str
	 */
	public $slug;
	/**
	 * Database Table Vars
	 *
	 * @var     array
	 */
	private $params = array ();
	/**
	 * Error Messages
	 *
	 * @var array
	 */
	private $errors = array ();

	/**
	 * Constructor set up Vars
	 *
	 * @param     object         $db            Object of mysqli-Connection
	 * @param     array          $params        Array with Database-Table Vars
	 *
	 * @return     void
	 */
	public function __construct( $params ) {
		parent::__construct( $params['table'] , 'id' );

		$this->params = array ( 'nid'  => 'id' ,
		                        'l'    => 'lft' ,
		                        'r'    => 'rgt' ,
		                        'mov'  => 'moved' ,
		                        'name' => 'name' );
	}

	/**
	 * Создание корневого узла, если он не существует
	 *
	 * @param     string         $nodeName        Имя узла
	 *
	 * @return     boolean                        True or False
	 */
	public function insert_root_node( $nodeName ) {

		//проверяем, не существует ли уже корневой узел
		//если существует - прекрашаем выполнение
		if ( $this->check_root_node() === true ) {
			$error = __( 'Корневой узел уже существует. NestedSet::insert_root_node ("' . $nodeName . '")' );
			$this->_set_error( $error );
			return false;
		}

		$this->name  = 'root';
		$this->lft   = 1;
		$this->rgt   = 2;
		$this->level = 0;

		if ( $this->store() ) {
			$error = __( 'Ошибка запроса к БД. NestedSet::insert_root_node ("' . $nodeName . '")' );
			$this->_set_error( $error );
			return false;
		}

		return true;
	}

	/**
	 * Создание/обновление узла
	 *
	 * @param array  $data   массив свойств название поля=>значение поля для заполнения свойств модели
	 * @param string $ignore название аттрибута для игнорирования
	 *
	 * @return boolean     True or False
	 */
	public function save( array $source , $ignore = '' ) {

		//Получаем информацию о родительском узле
		$parent = new self( array ( 'table' => $this->_tbl ) );

		if ( !$parent->load( $source['parent_id'] ) ) {
			$error = __( 'Родительский узел не найден ("' . $source['parent_id'] . '")' );
			$this->_set_error( $error );
			return false;
		}

		//TODO: здесь можно добавить проверку на то, сменился ли родитель и делать пересчет узла только в случаем смены
		$sql = 'UPDATE ' . $this->_tbl . ' SET `rgt` = `rgt` + 2  WHERE `rgt` >= ' . $parent->rgt;
		$this->_db->set_query( $sql )->query();


		$sql = 'UPDATE ' . $this->_tbl . ' SET `lft` = `lft` + 2  WHERE `lft` > ' . $parent->rgt;
		$this->_db->set_query( $sql )->query();

		$this->lft       = $parent->rgt;
		$this->rgt       = $parent->rgt + 1;
		$this->level     = $parent->level + 1;
		$this->parent_id = $parent->id;


		parent::save( $source , $ignore );

		return true;
	}

	/**
	 * Удаление узла и всех его потомков (удаление ветви)
	 *
	 * @param integer $id Id узла
	 *
	 * @return boolean    True or False
	 */
	public function delete_branch( $id ) {

		$branch = clone $this;

		if ( !$branch->load( $id ) ) {
			$error = __( 'Требуемый узел не найден' );
			$this->_set_error( $error );
			return false;
		}

		//Удаление ветки
		$branch->delete_list( array ( 'where' => 'lft BETWEEN ' . $branch->lft . ' AND ' . $branch->rgt ) );

		//Обновление последующих узлов
		$sql = "UPDATE $this->_tbl SET lft = lft - ROUND(($branch->rgt - $branch->lft + 1)) WHERE lft > $branch->rgt";
		$this->_db->set_query( $sql )->query();


		//Обновление родительской ветки
		$sql = "UPDATE $this->_tbl SET rgt = rgt - ROUND(($branch->rgt - $branch->lft + 1)) WHERE rgt > $branch->rgt";
		$this->_db->set_query( $sql )->query();

		return true;
	}

	/**
	 * Удаление одиночного узла
	 *
	 * @param     integer     $id        Id узла
	 *
	 * @return     boolean                    True or False
	 */
	public function delete_node( $id ) {

		$node = clone $this;

		if ( !$node->load( $id ) ) {
			$error = __( 'Требуемый узел не найден' );
			$this->_set_error( $error );
			return false;
		}

		$sql = "DELETE FROM $this->_tbl WHERE lft = $node->lft";
		$this->_db->set_query( $sql )->query();

		$sql = "UPDATE $this->_tbl SET lft = lft - 1, rgt = rgt - 1 WHERE lft BETWEEN $node->lft AND $node->rgt";
		$this->_db->set_query( $sql )->query();

		$sql = "UPDATE $this->_tbl SET lft = lft - 2 WHERE lft > $node->rgt";
		$this->_db->set_query( $sql )->query();

		$sql = "UPDATE $this->_tbl SET rgt = rgt - 2 WHERE rgt > $node->rgt";
		$this->_db->set_query( $sql )->query();

		return true;
	}

	/**
	 * Узел/ветка влево (перемещение "вверх" в пределах уровня)
	 *
	 * @param   integer $id Id узла
	 *
	 * @return     boolean    True or False
	 */
	public function move_lft( $id ) {

		$node = clone $this;

		if ( !$node->load( $id ) ) {
			$error = __( 'Требуемый узел не найден' );
			$this->_set_error( $error );
			return false;
		}

		$a_lft = $node->lft;
		$a_rgt = $node->rgt;

		if ( !$b_id = $this->_get_id( $a_lft - 1 , 'r' ) ) {
			$error = __( 'Родственный узел слева не найден. NestedSet::move_lft (' . $id . ')' );
			$this->_set_error( $error );
			return false;
		}

		if ( !$b = $this->_get_node( $b_id ) ) {
			$error = __( 'Родственный узел слева не найден. NestedSet::move_lft (' . $id . ')' );
			$this->_set_error( $error );
			return false;
		}

		$b_lft   = $b['lft'];
		$b_rgt   = $b['rgt'];

		$diffRgt = $a_rgt - $b_rgt;
		$diffLft = $a_lft - $b_lft;

		$sql     = sprintf( 'UPDATE `%1$s` SET `%2$s` = %11$d WHERE `%2$s` <> %11$d' , $this->_tbl , $this->params['mov'] , 'rgt' , 'lft' , (int) $diffRgt , (int) $diffLft , (int) $a_lft , (int) $a_rgt , (int) $b_lft , (int) $b_rgt , 0 , 1 );
		$this->_db->set_query( $sql )->query();

		$sql = sprintf( 'UPDATE `%1$s` SET `%3$s` = `%3$s` + %5$d,`%4$s` = `%4$s` + %5$d,`%2$s` = %12$d WHERE `%4$s` BETWEEN %9$d AND %10$d' , $this->_tbl , $this->params['mov'] , 'rgt' , 'lft' , (int) $diffRgt , (int) $diffLft , (int) $a_lft , (int) $a_rgt , (int) $b_lft , (int) $b_rgt , 0 , 1 );
		$this->_db->set_query( $sql )->query();

		$sql = sprintf( 'UPDATE `%1$s` SET `%3$s` = `%3$s` - %6$d,`%4$s` = `%4$s` - %6$d WHERE `%4$s` BETWEEN %7$d AND %8$d AND `%2$s` = %11$d' , $this->_tbl , $this->params['mov'] , 'rgt' , 'lft' , (int) $diffRgt , (int) $diffLft , (int) $a_lft , (int) $a_rgt , (int) $b_lft , (int) $b_rgt , 0 , 1 );
		$this->_db->set_query( $sql )->query();

		$sql = sprintf( 'UPDATE `%1$s` SET`%2$s` = %11$d WHERE `%2$s` <> %11$d' , $this->_tbl , $this->params['mov'] , 'rgt' , 'lft' , (int) $diffRgt , (int) $diffLft , (int) $a_lft , (int) $a_rgt , (int) $b_lft , (int) $b_rgt , 0 , 1 );
		$this->_db->set_query( $sql )->query();

		return true;
	}

	/**
	 * Узел/ветка вправо (перемещение "вниз" в пределах уровня)
	 *
	 * @param     integer     $nodeId     Id узла
	 *
	 * @return     boolean                    True or False
	 */
	public function move_rgt( $nodeId ) {
		$nodeLevel = $this->_get_node_level( $nodeId );

		if ( $nodeLevel == 0 ) {
			$error = __( 'Это корневой узел, его нельзя перемещать. NestedSet::move_rgt (' . $nodeId . ' ' . $nodeLevel . ')' );
			$this->_set_error( $error );
			return false;
		}

		$a     = $this->_get_node( $nodeId );
		$a_lft = $a['lft'];
		$a_rgt = $a['rgt'];

		if ( !$b_id = $this->_get_id( $a_rgt + 1 , 'l' ) ) {
			$error = __( 'Родственный узел справа не найден. NestedSet::move_rgt (' . $nodeId . ')' );
			$this->_set_error( $error );
			return false;
		}

		if ( !$b = $this->_get_node( $b_id ) ) {
			$error = __( 'Родственный узел справа не найден. NestedSet::move_rgt (' . $nodeId . ')' );
			$this->_set_error( $error );
			return false;
		}

		$b_lft   = $b['lft'];
		$b_rgt   = $b['rgt'];

		$diffRgt = $b_rgt - $a_rgt;
		$diffLft = $b_lft - $a_lft;

		$sql     = sprintf( 'UPDATE `%1$s` SET `%2$s` = %11$d WHERE `%2$s` <> %11$d' , $this->_tbl , $this->params['mov'] , 'lft' , 'rgt' , (int) $diffLft , (int) $diffRgt , (int) $b_lft , (int) $b_rgt , (int) $a_lft , (int) $a_rgt , 0 , 1 );
		$this->_db->set_query( $sql )->query();


		$sql = sprintf( 'UPDATE `%1$s` SET `%4$s` = `%4$s` - %5$d, `%3$s` = `%3$s` - %5$d, `%2$s` = %12$d WHERE `%3$s` BETWEEN %7$d AND %8$d' , $this->_tbl , $this->params['mov'] , 'lft' , 'rgt' , (int) $diffLft , (int) $diffRgt , (int) $b_lft , (int) $b_rgt , (int) $a_lft , (int) $a_rgt , 0 , 1 );
		$this->_db->set_query( $sql )->query();


		$sql = sprintf( 'UPDATE `%1$s` SET `%4$s` = `%4$s` + %6$d, `%3$s` = `%3$s` + %6$d WHERE `%3$s` BETWEEN %9$d AND %10$d AND `%2$s` = %11$d' , $this->_tbl , $this->params['mov'] , 'lft' , 'rgt' , (int) $diffLft , (int) $diffRgt , (int) $b_lft , (int) $b_rgt , (int) $a_lft , (int) $a_rgt , 0 , 1 );
		$this->_db->set_query( $sql )->query();

		$sql = sprintf( 'UPDATE `%1$s` SET `%2$s` = %11$d WHERE `%2$s` <> %11$d' , $this->_tbl , $this->params['mov'] , 'lft' , 'rgt' , (int) $diffLft , (int) $diffRgt , (int) $b_lft , (int) $b_rgt , (int) $a_lft , (int) $a_rgt , 0 , 1 );
		$this->_db->set_query( $sql )->query();

		return true;
	}

	/**
	 * Узел/ветка вверх (перемещение на уровень вверх)
	 *
	 * @param     integer     $nodeId     Id узла
	 *
	 * @return    boolean                    True or False
	 */
	public function move_up( $nodeId ) {

		$nodeLevel = $this->_get_node_level( $nodeId );

		//echo 'Уровень узла: '.$nodeLevel;

		if ( $nodeLevel == 0 ) {
			$error = __( 'Это корневой узел, его нельзя перемещать. NestedSet::move_up (' . $nodeId . ')' );
			$this->_set_error( $error );
			return false;
		}

		if ( $nodeLevel == 1 ) {
			$error = __( 'Родственный узел справа не найден. NestedSet::move_up (' . $nodeId . ')' );
			$this->_set_error( $error );
			return false;
		}

		do {
			if ( !$moved = $this->move_rgt( $nodeId ) ) {
				break;
			}
		} while ( $moved === true );


		$a     = $this->_get_node( $nodeId );
		$a_lft = $a['lft'];
		$a_rgt = $a['rgt'];

		if ( !$b_id = $this->_get_id( $a_rgt + 1 , 'r' ) ) {
			$error = __( 'На корневной уровень нельзя переместиться. NestedSet::move_up (' . $nodeId . ')' );
			$this->_set_error( $error );
			return false;
		}

		if ( !$b = $this->_get_node( $b_id ) ) {
			$error = __( 'На корневной уровень нельзя переместиться. NestedSet::move_up (' . $nodeId . ')' );
			$this->_set_error( $error );
			return false;
		}

		//$b_lft = $b['lft'];
		//$b_rgt = $b['rgt'];

		$nodeWidth = $a_rgt - $a_lft + 1;

		//узел и дочерние узлы
		$sql = sprintf( 'UPDATE `%1$s` SET `%2$s` = `%2$s` + %9$d,`%3$s` = `%3$s` + %9$d, level = level - 1  WHERE `%3$s`BETWEEN %5$d AND %6$d' , $this->_tbl , 'rgt' , 'lft' , $this->params['nid'] , (int) $a_lft , (int) $a_rgt , (int) $nodeWidth , (int) $b_id , 1 );
		$this->_db->set_query( $sql )->query();

		//родительский узел
		$sql = sprintf( 'UPDATE `%1$s` SET `%2$s` = `%2$s` - %7$d WHERE `%4$s` = %8$d' , $this->_tbl , 'rgt' , 'lft' , $this->params['nid'] , (int) $a_lft , (int) $a_rgt , (int) $nodeWidth , (int) $b_id , 1 );
		$this->_db->set_query( $sql )->query();

		//меняем parent_id у перемещаемого узла
		$a    = $this->_get_node( $nodeId );
		$p_id = $this->get_parent( $a )->id;
		$sql  = "UPDATE $this->_tbl SET parent_id = $p_id  WHERE id = " . $nodeId;
		$this->_db->set_query( $sql )->query();

		return true;
	}

	public function get_parent( $child ) {
		$sql    = "SELECT * FROM  $this->_tbl WHERE lft <= {$child['lft']} AND rgt >= {$child['rgt']} AND level = {$child['level']} - 1 ORDER BY lft";

		$result = null;
		$this->_db->set_query( $sql )->load_object( $result );

		return $result;
	}

	/**
	 * Узел/ветка вниз (перемещение на уровень вниз)
	 *
	 * @param     integer     $nodeId     Id узла
	 *
	 * @return     boolean                    True or False
	 */
	public function move_down( $nodeId ) {

		$nodeLevel = $this->_get_node_level( $nodeId );

		if ( $nodeLevel == 1 ) {
			$error = __( 'Это корневой узел, его нельзя перемещать. NestedSet::move_down (' . $nodeId . ')' );
			$this->_set_error( $error );
			return false;
		}

		$a     = $this->_get_node( $nodeId );
		$a_lft = $a['lft'];
		$a_rgt = $a['rgt'];

		if ( !$b_id = $this->_get_id( $a_lft - 1 , 'r' ) ) {
			$error = __( 'Родственный узел слева не найден. NestedSet::move_down (' . $nodeId . ')' );
			$this->_set_error( $error );
			return false;
		}
		if ( !$b = $this->_get_node( $b_id ) ) {
			$error = __( 'Родственный узел слева не найден. NestedSet::move_down (' . $nodeId . ')' );
			$this->_set_error( $error );
			return false;
		}

		//$b_lft = $b['lft'];
		//$b_rgt = $b['rgt'];

		$nodeWidth = $a_rgt - $a_lft + 1;

		$sql       = sprintf( 'UPDATE `%1$s` SET `%2$s` = `%2$s` - %9$d, `%3$s` = `%3$s` - %9$d, level = level + 1  WHERE `%3$s` BETWEEN %5$d AND %6$d' , $this->_tbl , 'rgt' , 'lft' , $this->params['nid'] , (int) $a_lft , (int) $a_rgt , (int) $nodeWidth , (int) $b_id , 1 );
		$this->_db->set_query( $sql )->query();

		$sql = sprintf( 'UPDATE `%1$s` SET parent_id = %8$d  WHERE id = ' . $nodeId , $this->_tbl , 'rgt' , 'lft' , $this->params['nid'] , (int) $a_lft , (int) $a_rgt , (int) $nodeWidth , (int) $b_id , 1 );
		$this->_db->set_query( $sql )->query();


		$sql = sprintf( 'UPDATE `%1$s` SET `%2$s` = `%2$s` + %7$d WHERE `%4$s` = %8$d' , $this->_tbl , 'rgt' , 'lft' , $this->params['nid'] , (int) $a_lft , (int) $a_rgt , (int) $nodeWidth , (int) $b_id , 1 );
		$this->_db->set_query( $sql )->query();

		//меняем parent_id у перемещаемого узла
		$a    = $this->_get_node( $nodeId );
		$p_id = $this->get_parent( $a )->id;
		$sql  = "UPDATE $this->_tbl SET parent_id = $p_id  WHERE id = " . $nodeId;
		$this->_db->set_query( $sql )->query();

		return true;
	}

	/**
	 * Проверка на существование корневого узла
	 *
	 * @return     boolean  True or False
	 */
	public function check_root_node() {

		$sql = sprintf( 'SELECT `%1$s` FROM `%2$s` WHERE `%3$s` = %4$d' , $this->params['nid'] , $this->_tbl , 'lft' , 1 );
		$this->_db->set_query( $sql );

		if ( !$result = $this->_db->query() ) {
			$error = __( 'Ошибка запроса к БД. NestedSet::check_root_node ()' );
			$this->_set_error( $error );
			return false;
		}

		if ( !$result->num_rows ) {
			$error = __( 'Корневой узел не найден. NestedSet::check_root_node ()' );
			$this->_set_error( $error );
			return false;
		}

		return true;
	}

	/**
	 * Проверка на существвоание ошибок
	 *
	 * @return     boolean        True or False
	 */
	public function is_error() {
		return ( empty( $this->errors ) ) ? false : true;
	}

	/**
	 * Возвращает текст ошибки
	 *
	 * @return mixed        array         array Error Messages or null
	 */
	public function get_error() {
		return ( true === $this->is_error() ) ? $this->errors : null;
	}

	/**
	 * Получение полного дерево в виде массива
	 *
	 * @return     mixed    Возвращает массив или False
	 */
	public function get_full_tree_extended() {

		$where_group = $this->group ? ' AND n.group = "' . $this->group . '" ' : '';

		$sql         = sprintf( 'SELECT
							`%1$s`.*, round((`%1$s`.`%4$s` - `%1$s`.`%3$s` - %8$d) / %9$d, %7$d) AS childs,
							n.level AS level,
							((min(`%2$s`.`%4$s`) - `%1$s`.`%4$s` - (`%1$s`.`%3$s` > %8$d)) / %9$d) > %7$d AS lower,
							(((`%1$s`.`%3$s` - max(`%2$s`.`%3$s`) > %8$d))) AS upper
						FROM `%5$s` `%1$s`, `%5$s` `%2$s`
						WHERE
							`%1$s`.`%3$s` BETWEEN `%2$s`.`%3$s`
							AND `%2$s`.`%4$s` AND ( `%2$s`.`%6$s` != `%1$s`.`%6$s` OR `%1$s`.`%3$s` = %8$d )
							' . $where_group . '
						GROUP BY `%1$s`.`%6$s`
						ORDER BY `%1$s`.`%3$s`' , 'n' , 'p' , 'lft' , 'rgt' , $this->_tbl , $this->params['nid'] , 0 , 1 , 2 );

		$this->_db->set_query( $sql );

		return $this->_db->load_assoc_list();
	}

	/**
	 * Получение полного дерево в виде массива
	 *
	 * @return     mixed    Возвращает массив или False
	 */
	public function get_full_tree_simple() {

		$where_group = $this->group ? ' WHERE `group` = "' . $this->group . '" ' : '';

		$this->_db->set_query( "SELECT * FROM $this->_tbl $where_group ORDER BY lft ASC" );

		$result = $this->_db->load_assoc_list( 'id' );
		return $result ? $result : array ();
	}

	/**
	 * Выбор ветки
	 *
	 * @param     integer     $lft     Левая граница корня ветки
	 * @param     integer     $rgt     Правая граница корня ветки
	 *
	 * @return     mixed    Возвращает ассоциативный массив
	 */
	public function get_branch( $lft , $rgt , $object_list = false ) {

		$this->_db->set_query( "SELECT *, round((rgt - lft - 1) / 2, 0) AS childs_count FROM $this->_tbl WHERE lft >= $lft AND rgt <= $rgt ORDER BY lft" );

		return $object_list ? $this->_db->load_object_list( 'id' ) : $this->_db->load_assoc_list( 'id' );

		//return $result ? $result : array();
	}

	/**
	 * Выбор подчиненных категорий
	 *
	 * @param     integer     $id     ID родителя
	 *
	 * @return     mixed    Возвращает ассоциативный массив
	 */
	public function get_children( $id , $object_list = false ) {

		$this->_db->set_query( "SELECT * FROM $this->_tbl WHERE parent_id = $id  ORDER BY lft" );

		return $object_list ? $this->_db->load_object_list( 'id' ) : $this->_db->load_assoc_list( 'id' );

		//return $result ? $result : array();
	}

	/**
	 * Получение пути от корня до требуемого узла
	 *
	 * @param     integer     $nodeId     Id узла
	 *
	 * @return    mixed                    массив или False
	 */
	public function get_path_from_root( $nodeId , $object_list = false ) {

		$sql = "SELECT p.*
			FROM $this->_tbl AS n, $this->_tbl AS p
			WHERE p.lft <= n.lft AND p.rgt >= n.rgt AND n." . $this->params['nid'] . " = " . (int) $nodeId . "
			ORDER BY p.lft";

		return $object_list ? $this->_db->set_query( $sql )->load_object_list() : $this->_db->set_query( $sql )->load_assoc_list( 'id' );
	}

	/**
	 * Получение ID узла по значению его левой или правой границы
	 *
	 * @param     integer        $directionValue        Значение левой или правой границы
	 * @param     string         $direction             Граница: левая или правая
	 *
	 * @return     mixed                            integer Id узла или  False
	 */
	private function _get_id( $directionValue , $direction ) {

		$sql = sprintf( 'SELECT `%1$s` FROM `%2$s` WHERE `%3$s` = %4$d' , $this->params['nid'] , $this->_tbl , $this->params[$direction] , (int) $directionValue );
		$this->_db->set_query( $sql );

		if ( !$result = $this->_db->query() ) {
			$error = __( 'Ошибка запроса к БД. NestedSet::_get_id()' );
			$this->_set_error( $error );
			return false;
		}

		if ( !$result->num_rows ) {
			$error = __( 'Невозможно получить информацию по предоставленным данным. NestedSet::_get_id()' );
			$this->_set_error( $error );
			return false;
		}

		$row = $result->fetch_assoc();
		return $row[$this->params['nid']];
	}

	/**
	 * Получение массива со следующими данными: ID узла, значение левой и правой границы узла
	 *
	 * @param     integer     $nodeId        Id узла
	 *
	 * @return     mixed                     Массив с данными или False
	 */
	private function _get_node( $nodeId ) {

		$sql = sprintf( 'SELECT `%1$s`,`%2$s`,`%3$s`, level FROM `%4$s` WHERE `%1$s` = %5$d' , $this->params['nid'] , 'lft' , 'rgt' , $this->_tbl , (int) $nodeId );
		$this->_db->set_query( $sql );

		if ( !$result = $this->_db->query() ) {
			$error = __( 'Ошибка запроса к БД. NestedSet::_get_node()' );
			$this->_set_error( $error );
			return false;
		}

		if ( !$result->num_rows ) {
			$error = __( 'Требуемый узел не найден. NestedSet::_get_node()' );
			$this->_set_error( $error );
			return false;
		}

		$row = $result->fetch_assoc();
		return $row;
	}

	/**
	 * Определение уровня узла
	 *
	 * @param     integer     $nodeId        Id узла
	 *
	 * @return     mixed        integer     Уровень узла (0 = Root) или False
	 */
	private function _get_node_level( $nodeId ) {

		/*
		$sql = sprintf('
							SELECT COUNT(*) AS `level`
							FROM `%3$s` `%2$s`,`%3$s` `%1$s`
							WHERE `%1$s`.`%5$s` BETWEEN `%2$s`.`%5$s` AND `%2$s`.`%6$s`
							GROUP BY `%1$s`.`%5$s`
							ORDER BY ABS(`%1$s`.`%4$s` - %7$d)', 'n', 'p', $this->_tbl, $this->params['nid'], 'lft', 'rgt', (int) $nodeId);
		$this->_db->set_query($sql);


		if (!$result = $this->_db->query()) {
			$error = __('Ошибка запроса к БД. NestedSet::_get_node_level()');
			$this->_set_error($error);
			return false;
		}

		if (!$result->num_rows) {
			$error = __('Требуемый узел не найден. NestedSet::_get_node_level()');
			$this->_set_error($error);
			return false;
		}

		$row = $result->fetch_assoc();
		return $row['level'];
		*/

		$query = 'SELECT level FROM `' . $this->_tbl . '` WHERE `' . $this->_tbl . '`.`' . $this->params['nid'] . '` = ' . (int) $nodeId;
		if ( !$result = $this->_db->set_query( $query )->load_result() ) {
			return false;
		}
		return $result;

	}

	/**
	 * Получение общего количества узлов
	 *
	 * @return mixed    integer Количество или boolean False
	 */
	private function _count_nodes() {

		$sql = sprintf( 'SELECT COUNT(`%1$s`) FROM `%2$s` AS `count`' , $this->params['nid'] , $this->_tbl );
		$this->_db->set_query( $sql );

		if ( !$result = $this->_db->query() ) {
			$error = __( 'Ошибка запроса к БД. NestedSet::_count_nodes()' );
			$this->_set_error( $error );
			return false;
		}

		if ( !$result->num_rows ) {
			$error = __( 'Узлы не найдены. NestedSet::_count_nodes()' );
			$this->_set_error( $error );
			return false;
		}

		$row = $result->fetch_assoc();

		return $row['count'];
	}

	/**
	 * SСохранение ошибки в массив с ошибками
	 *
	 * @param     string         $error        Текст ошибки
	 *
	 * @return     void
	 */
	private function _set_error( $error ) {
		$this->errors[] = $error;
	}

}

class TreeBuilder {

	public $items = array ();
	public $children = array ();

	function __construct( $items ) {

		$this->items    = $items;
		$this->children = array ();

		foreach ( $items as $v ) {
			$list = isset( $this->children[$v->parent_id] ) ? $this->children[$v->parent_id] : array ();
			array_push( $list , $v );
			$this->children[$v->parent_id] = $list;
		}

		//_xdump($this->children);
	}

	function build_tree( $id , $list = array () , $maxlevel = 9999 , $level = 0 ) {

		if ( isset( $this->children[$id] ) && $level <= $maxlevel ) {
			$i = 1;

			foreach ( $this->children[$id] as $v ) {
				$id = $v->id;


				if ( isset( $this->children[$id] ) ) {
					$list[$id]           = $v;
					$list[$id]->children = $this->children[$id];
					$list                = $this->build_tree( $id , $list , $maxlevel , $level + 1 );
				} else {
					//$list[$id]->children = 0;
				}

				//$list[$id]->children = isset($this->children[$id]) ? $this->children[$id] : 0;
				//$list = $this->build_tree($id, $list, $maxlevel, $level + 1);
				$i++;
			}

			//unset($this->children[$id]);
		}


		//_xdump($list);

		return $list;
	}

}
