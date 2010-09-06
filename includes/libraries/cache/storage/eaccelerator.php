<?php
/**
 * @package Joostina
 * @subpackage Cache handler
 * @copyright Авторские права (C) 2009 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// Check to ensure this file is within the rest of the framework
defined('_VALID_MOS') or die();

/**
 * eAccelerator cache storage handler
 *
 * @author
 * @package		Joostina
 * @subpackage	Cache
 * @since		1.3
 */
class JCacheStorageEaccelerator extends JCacheStorage {
	/**
	 * Constructor
	 *
	 * @access protected
	 * @param array $options optional parameters
	 */
	function __construct( $options = array() ) {
		global $mosConfig_secret;
		parent::__construct($options);

		$this->_hash	= $mosConfig_secret;
	}

	/**
	 * Get cached data by id and group
	 *
	 * @access	public
	 * @param	string	$id			The cache data id
	 * @param	string	$group		The cache data group
	 * @param	boolean	$checkTime	True to verify cache time expiration threshold
	 * @return	mixed	Boolean false on failure or a cached data string
	 * @since	1.3
	 */
	function get($id, $group, $checkTime) {
		$cache_id = $this->_getCacheId($id, $group);
		$this->_setExpire($cache_id);
		$cache_content = eaccelerator_get($cache_id);
		if($cache_content === null) {
			return false;
		}
		return $cache_content;
	}

	/**
	 * Store the data to by id and group
	 *
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @param	string	$data	The data to store in cache
	 * @return	boolean	True on success, false otherwise
	 * @since	1.3
	 */
	function store($id, $group, $data) {
		$cache_id = $this->_getCacheId($id, $group);
		eaccelerator_put($cache_id.'_expire', time());
		return eaccelerator_put($cache_id, $data, $this->_lifetime);
	}

	/**
	 * Remove a cached data entry by id and group
	 *
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @return	boolean	True on success, false otherwise
	 * @since	1.3
	 */
	function remove($id, $group) {
		$cache_id = $this->_getCacheId($id, $group);
		eaccelerator_rm($cache_id.'_expire');
		return eaccelerator_rm($cache_id);
	}

	/**
	 * Clean cache for a group given a mode.
	 *
	 * group mode		: cleans all cache in the group
	 * notgroup mode	: cleans all cache not in the group
	 *
	 * @access	public
	 * @param	string	$group	The cache data group
	 * @param	string	$mode	The mode for cleaning cache [group|notgroup]
	 * @return	boolean	True on success, false otherwise
	 * @since	1.3
	 */
	function clean($group, $mode) {
		return parent::clean();
	}

	/**
	 * Garbage collect expired cache data
	 *
	 * @access public
	 * @return boolean  True on success, false otherwise.
	 */
	function gc() {
		return eaccelerator_gc();
	}

	/**
	 * Test to see if the cache storage is available.
	 *
	 * @static
	 * @access public
	 * @return boolean  True on success, false otherwise.
	 */
	function test() {
		return (extension_loaded('eaccelerator') && function_exists('eaccelerator_get'));
	}

	/**
	 * Set expire time on each call since memcache sets it on cache creation.
	 *
	 * @access private
	 *
	 * @param string  $key   Cache key to expire.
	 * @param integer $lifetime  Lifetime of the data in seconds.
	 */
	function _setExpire($key) {
		$lifetime	= $this->_lifetime;
		$expire		= eaccelerator_get($key.'_expire');

		// set prune period
		if ($expire + $lifetime < time()) {
			eaccelerator_rm($key);
			eaccelerator_rm($key.'_expire');
		} else {
			eaccelerator_put($key.'_expire',  time());
		}
	}

	/**
	 * Get a cache_id string from an id/group pair
	 *
	 * @access	private
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @return	string	The cache_id string
	 * @since	1.3
	 */
	function _getCacheId($id, $group) {
		global $mosConfig_cache_key;
		$name	= md5($mosConfig_cache_key . "-" . $this->_application.'-'.$id.'-'.$this->_hash.'-'.$this->_language);
		return 'cache_'.$group.'-'.$name;
	}
}
