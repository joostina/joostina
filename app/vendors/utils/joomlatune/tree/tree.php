<?php

/**
 * Simple tree class
 *
 * @version   1.0
 * @package   JoomlaTune.Framework
 * @author    Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2010 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license   GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 * @access    public
 * */
// Check for double include
if ( !defined( 'JOOMLATUNE_TREE' ) ) {

	define( 'JOOMLATUNE_TREE' , 1 );

	class JoomlaTuneTree {
		/* @var array children list */

		var $children = array ();
		var $params = array ();

		/**
		 * Class constructor
		 *
		 * @access    protected
		 *
		 * @param    array    $items    array of all objects (objects must contain id and parent fields)
		 *
		 * @return    object
		 */
		function __construct( $items , $params = array () ) {

			$this->children          = array ();

			$this->$params['parent'] = isset( $params['parent'] ) ? $params['parent'] : 'parent';

			foreach ( $items as $v ) {
				$parent = $this->$params['parent'];
				$pt     = $v->$parent;
				$list   = isset( $this->children[$pt] ) ? $this->children[$pt] : array ();
				array_push( $list , $v );
				$this->children[$pt] = $list;
			}

			//_xdump($this->children);
		}

		/**
		 * Recursive building tree
		 *
		 * @access    protected
		 * @return    array
		 */
		function _buildTree( $id , $list = array () , $maxlevel = 9999 , $level = 0 , $number = '' ) {
			if ( isset( $this->children[$id] ) && $level <= $maxlevel ) {
				if ( $number != '' ) {
					$number .= '.';
				}

				$i = 1;

				foreach ( $this->children[$id] as $v ) {
					$id                  = $v->id;
					$list[$id]           = $v;
					$list[$id]->level    = $level;
					$list[$id]->number   = $number . $i;
					$list[$id]->children = isset( $this->children[$id] ) ? count( $this->children[$id] ) : 0;
					$list                = $this->_buildTree( $id , $list , $maxlevel , $level + 1 , $list[$id]->number );
					$i++;
				}
			}
			return $list;
		}

		/**
		 * Recursive building descendants list
		 *
		 * @access    protected
		 * @return    array
		 */
		function _getDescendants( $id , $list = array () , $maxlevel = 9999 , $level = 0 ) {
			if ( isset( $this->children[$id] ) && $level <= $maxlevel ) {
				foreach ( $this->children[$id] as $v ) {
					$id     = $v->id;
					$list[] = $v->id;
					$list   = $this->_getDescendants( $id , $list , $maxlevel , $level + 1 );
				}
			}
			return $list;
		}

		/**
		 * Return objects tree
		 *
		 * @access public
		 *
		 * @param int    $id node id (by default node id is 0 - root node)
		 *
		 * @return array
		 */
		function get( $id = 0 ) {
			return $this->_buildTree( $id );
		}

		/**
		 * Return children items for given node or empty array for empty children list
		 *
		 * @access public
		 *
		 * @param int    $id node id (by default node id is 0 - root node)
		 *
		 * @return array
		 */
		function children( $id = 0 ) {
			return isset( $this->children[$id] ) ? $this->children[$id] : array ();
		}

		/**
		 * Return array with descendants id for given node
		 *
		 * @access public
		 *
		 * @param int $id node id (by default node id is 0 - root node)
		 *
		 * @return array
		 */
		function descendants( $id = 0 ) {
			return $this->_getDescendants( $id );
		}

	}

} // end of double include check