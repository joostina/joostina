<?php
/**
 * @package Joostina
 * @subpackage Page cache handler
 * @copyright Авторские права (C) 2009 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

defined('_JOOS_CORE') or die();

class JCachePage extends JCache {

	var $_id = '';
	var $_group	= '';
	var $_uri = null;
	var $_data = '';
	var $_option = '';
	var $_cache_filename = null;
	var $_cache_modified = 0;
	var $_content_modified = 0;
	var $_content = null;
	var $_hash = null;

	function __construct($options) {
		parent::__construct($options);

		if(isset($options['URI'])) {
			$URI = $options['URI'];
		}
		if(isset($options['option'])) {
			$option = $options['option'];
		}
		if(isset($options['data'])) {
			$data = $options['data'];
		}

		if($option=='com_frontpage')
			$option='com_content';

		$this->_uri = $URI;
		//$this->_data = $data;
		$this->_option = $option;
		global $mosConfig_secret;
		$file = md5('' . '-' . $this->_makeId().'-'.$mosConfig_secret . '-' . $this->_options['language']).'.php';
		$this->_cache_filename = $GLOBALS['mosConfig_cachepath'].'/page/' . $file;
	}
	function getCacheModified() {
		return $this->_cache_modified;
	}
	function getContentModified() {
		return $this->_content_modified;
	}
	function getContent() {
		return $this->_content;
	}
	function getHash() {
		return $this->_hash;
	}
	function generateHash($content) {
		return md5($content);
	}
	function setHash($hash) {
		$this->_hash = $hash;
	}
	function clear($option=null) {
		if($option==null)
			$option = isset($this) ? $this->_option : '';
		if($option=='com_frontpage')
			$option='com_content';
		if($dh=@opendir($GLOBALS['mosConfig_cachepath'] . '/page/')) {
			while(($file=readdir($dh))!==false) {
				if($this->_cache_filename==($GLOBALS['mosConfig_cachepath'] . '/page/' . $file)) {
					touch($this->_cache_filename,0);
				}
			}
			closedir($dh);
		}
	}
	function clearall() {
		if($dh=@opendir($GLOBALS['mosConfig_cachepath'].'/page/')) {
			while(($file=readdir($dh))!==false)
				touch($GLOBALS['mosConfig_cachepath'].'/page/'.$file,0);
			closedir($dh);
		}
	}
	function get( $id=false, $group='page' ) {
		// Initialize variables
		$data = false;

		// If an id is not given generate it from the request
		if ($id == false) {
			$id = $this->_makeId();
		}

		// If the etag matches the page id ... sent a no change header and exit : utilize browser cache
		if ( !headers_sent() && isset($_SERVER['HTTP_IF_NONE_MATCH']) ) {
			$etag = stripslashes($_SERVER['HTTP_IF_NONE_MATCH']);
			if( $etag == $id) {
				$browserCache = isset($this->_options['browsercache']) ? $this->_options['browsercache'] : false;
				if ($browserCache) {
					$this->_noChange();
				}
			}
		}

		// We got a cache hit... set the etag header and echo the page data
		$data = parent::get($id, $group);

		if ($data !== false) {
			header('ETag: '.$id);
			return $data;
		}

		// Set id and group placeholders
		$this->_id		= $id;
		$this->_group	= $this->_options['defaultgroup'];
		return false;
	}
	/**
	 * There is no change in page data so send a not modified header and die gracefully
	 *
	 * @access	private
	 * @return	void
	 * @since	1.3
	 */
	function _noChange() {
		global $mainframe;

		// Send not modified header and exit gracefully
		header( 'HTTP/1.x 304 Not Modified', true );
		$mainframe->close();
	}

	function update() {
		touch($this->_cache_filename);
	}

	function checkNotModified($send304=true) {
		$client_etag = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) : false;
		$client_last_modified = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE']) : false;
		if(($client_etag==false)&&($client_last_modified==false))
			return false;
		$notmodified = false;
		$etag = '"'.$this->_hash.'"';
		if(($client_last_modified!==false)&&(($pos=strpos($client_last_modified,';'))!==false))
			$client_last_modified=substr($client_last_modified,0,$pos);
		if($client_etag===$etag)
			$notmodified = true;
		elseif(($client_last_modified!==false)&&(strtotime($client_last_modified) == $this->_content_modified))
			$notmodified = true;
		if($notmodified && $send304) {
			@ob_clean();
			$http1x = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.x';
			if(strcmp(PHP_VERSION,'PHP 4.3.0')>=0)
				header($http1x.' 304 Not Modified',true,304);
			else
				header($http1x.' 304 Not Modified');
			header('Status: 304 Not Modified');
			header('ETag: '.$etag);
			exit(0);
		}
		return $notmodified;
	}
	function setModified() {
		$this->_content_modified = time();
	}

	function setHeader($ttl=null) {
		header('Last-Modified: '.gmdate("D, d M Y H:i:s", $this->_content_modified).' GMT');
		if($this->_hash)
			header('ETag: "'.$this->_hash.'"');
		if($ttl!==null)
			header('Expires: '.gmdate("D, d M Y H:i:s", time()+$ttl).' GMT');
		header('Cache-Control: public');
		header('Pragma: no-cache');
	}
	function setLogged() {
		setcookie('cache_login', '1', time()+365*24*60*60);
	}
	function resetLogged() {
		setcookie('cache_login', '', time()-365*24*60*60);
	}
	function checkLogged() {
		return isset($_COOKIE['cache_login']) && ($_COOKIE['cache_login']=='1');
	}
	function updateLogged($status) {
		if($status) {
			if(!$this->checkLogged())
				$this->setLogged();
		}
		elseif($this->checkLogged())
			$this->resetLogged();
	}
	function startTimer() {
		global $start_timestamp_int,$start_timestamp_frac;
		list($start_timestamp_frac,$start_timestamp_int)=explode(' ',microtime());
	}
	function readTimer() {
		global $start_timestamp_int,$start_timestamp_frac;
		list($end_timestamp_frac,$end_timestamp_int)=explode(' ',microtime());
		return ((int)$end_timestamp_int-(int)$start_timestamp_int) +
				((float)$end_timestamp_frac-(float)$start_timestamp_frac);
	}

	function store($content,$hash=null) {
		if($hash===null)
			$hash=$this->generateHash($content);
		$this->_hash = $hash;
		$this->_content = $content;
		$data=$content;

		// Get id and group and reset them placeholders
		$id		= $this->_makeId();
		$group	= $this->_options['defaultgroup'];
		$this->_id		= null;
		$this->_group	= null;

		if ($data) {
			return parent::store($data, $id, $group);
		}
		return false;
	}

	function _makeId() {
		return md5($this->_uri);
	}
}