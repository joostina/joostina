<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
**/

defined('_JOOS_CORE') or die();
require_once JPATH_BASE.'/includes/PEAR/PEAR.php';
define('ARCHIVE_TAR_ATT_SEPARATOR',90001);
define('ARCHIVE_TAR_END_BLOCK',pack("a512",''));
class Archive_Tar extends PEAR {
	var $_tarname = '';
	var $_compress = false;
	var $_compress_type = 'none';
	var $_separator = ' ';
	var $_file = 0;
	var $_temp_tarname = '';
	function Archive_Tar($p_tarname,$p_compress = null) {
		$this->PEAR();
		$this->_compress = false;
		$this->_compress_type = 'none';
		if(($p_compress === null) || ($p_compress == '')) {
			if(@file_exists($p_tarname)) {
				if($fp = @fopen($p_tarname,"rb")) {
					$data = fread($fp,2);
					fclose($fp);
					if($data == "\37\213") {
						$this->_compress = true;
						$this->_compress_type = 'gz';
					} elseif($data == "BZ") {
						$this->_compress = true;
						$this->_compress_type = 'bz2';
					}
				}
			} else {
				if(substr($p_tarname,-2) == 'gz') {
					$this->_compress = true;
					$this->_compress_type = 'gz';
				} elseif((substr($p_tarname,-3) == 'bz2') || (substr($p_tarname,-2) == 'bz')) {
					$this->_compress = true;
					$this->_compress_type = 'bz2';
				}
			}
		} else {
			if(($p_compress === true) || ($p_compress == 'gz')) {
				$this->_compress = true;
				$this->_compress_type = 'gz';
			} else
				if($p_compress == 'bz2') {
					$this->_compress = true;
					$this->_compress_type = 'bz2';
				} else {
					die("Unsupported compression type '$p_compress'\n".
						"Supported types are 'gz' and 'bz2'.\n");
					return false;
				}
		}
		$this->_tarname = $p_tarname;
		if($this->_compress) {
			if($this->_compress_type == 'gz')
				$extname = 'zlib';
			else
				if($this->_compress_type == 'bz2')
					$extname = 'bz2';
			if(!extension_loaded($extname)) {
				PEAR::loadExtension($extname);
			}
			if(!extension_loaded($extname)) {
				die("The extension '$extname' couldn't be found.\n".
					"Please make sure your version of PHP was built "."with '$extname' support.\n");
				return false;
			}
		}
	}
	function _Archive_Tar() {
		$this->_close();
		if($this->_temp_tarname != '')
			@unlink($this->_temp_tarname);
		$this->_PEAR();
	}
	function create($p_filelist) {
		return $this->createModify($p_filelist,'','');
	}
	function add($p_filelist) {
		return $this->addModify($p_filelist,'','');
	}
	function extract($p_path = '') {
		return $this->extractModify($p_path,'');
	}
	function listContent() {
		$v_list_detail = array();
		if($this->_openRead()) {
			if(!$this->_extractList('',$v_list_detail,"list",'','')) {
				unset($v_list_detail);
				$v_list_detail = 0;
			}
			$this->_close();
		}
		return $v_list_detail;
	}
	function createModify($p_filelist,$p_add_dir,$p_remove_dir = '') {
		$v_result = true;
		if(!$this->_openWrite())
			return false;
		if($p_filelist != '') {
			if(is_array($p_filelist))
				$v_list = $p_filelist;
			elseif(is_string($p_filelist))
				$v_list = explode($this->_separator,$p_filelist);
			else {
				$this->_cleanFile();
				$this->_error('Invalid file list');
				return false;
			}
			$v_result = $this->_addList($v_list,$p_add_dir,$p_remove_dir);
		}
		if($v_result) {
			$this->_writeFooter();
			$this->_close();
		} else
			$this->_cleanFile();
		return $v_result;
	}
	function addModify($p_filelist,$p_add_dir,$p_remove_dir = '') {
		$v_result = true;
		if(!$this->_isArchive())
			$v_result = $this->createModify($p_filelist,$p_add_dir,$p_remove_dir);
		else {
			if(is_array($p_filelist))
				$v_list = $p_filelist;
			elseif(is_string($p_filelist))
				$v_list = explode($this->_separator,$p_filelist);
			else {
				$this->_error('Invalid file list');
				return false;
			}
			$v_result = $this->_append($v_list,$p_add_dir,$p_remove_dir);
		}
		return $v_result;
	}
	function addString($p_filename,$p_string) {
		if(!$this->_isArchive()) {
			if(!$this->_openWrite()) {
				return false;
			}
			$this->_close();
		}
		if(!$this->_openAppend())
			return false;
		$v_result = $this->_addString($p_filename,$p_string);
		$this->_writeFooter();
		$this->_close();
		return $v_result;
	}
	function extractModify($p_path,$p_remove_path) {
		$v_list_detail = array();
		if($v_result = $this->_openRead()) {
			$v_result = $this->_extractList($p_path,$v_list_detail,"complete",0,$p_remove_path);
			$this->_close();
		}
		return $v_result;
	}
	function extractInString($p_filename) {
		if($this->_openRead()) {
			$v_result = $this->_extractInString($p_filename);
			$this->_close();
		} else {
			$v_result = null;
		}
		return $v_result;
	}
	function extractList($p_filelist,$p_path = '',$p_remove_path = '') {
		$v_list_detail = array();
		if(is_array($p_filelist))
			$v_list = $p_filelist;
		elseif(is_string($p_filelist))
			$v_list = explode($this->_separator,$p_filelist);
		else {
			$this->_error('Invalid string list');
			return false;
		}
		if($v_result = $this->_openRead()) {
			$v_result = $this->_extractList($p_path,$v_list_detail,"partial",$v_list,$p_remove_path);
			$this->_close();
		}
		return $v_result;
	}
	function setAttribute() {
		$v_result = true;
		if(($v_size = func_num_args()) == 0) {
			return true;
		}
		$v_att_list = &func_get_args();
		$i = 0;
		while($i < $v_size) {
			switch($v_att_list[$i]) {
				case ARCHIVE_TAR_ATT_SEPARATOR:
					if(($i + 1) >= $v_size) {
						$this->_error('Invalid number of parameters for '.
							'attribute ARCHIVE_TAR_ATT_SEPARATOR');
						return false;
					}
					$this->_separator = $v_att_list[$i + 1];
					$i++;
					break;
				default:
					$this->_error('Unknow attribute code '.$v_att_list[$i].'');
					return false;
			}
			$i++;
		}
		return $v_result;
	}
	function _error($p_message) {
		$this->raiseError($p_message);
	}
	function _warning($p_message) {
		$this->raiseError($p_message);
	}
	function _isArchive($p_filename = null) {
		if($p_filename == null) {
			$p_filename = $this->_tarname;
		}
		clearstatcache();
		return @is_file($p_filename);
	}
	function _openWrite() {
		if($this->_compress_type == 'gz')
			$this->_file = @gzopen($this->_tarname,"wb9");
		else
			if($this->_compress_type == 'bz2')
				$this->_file = @bzopen($this->_tarname,"wb");
			else
				if($this->_compress_type == 'none')
					$this->_file = @fopen($this->_tarname,"wb");
				else
					$this->_error('Unknown or missing compression type ('.$this->_compress_type.')');
		if($this->_file == 0) {
			$this->_error('Unable to open in write mode \''.$this->_tarname.'\'');
			return false;
		}
		return true;
	}
	function _openRead() {
		if(strtolower(substr($this->_tarname,0,7)) == 'http://') {
			if($this->_temp_tarname == '') {
				$this->_temp_tarname = uniqid('tar').'.tmp';
				if(!$v_file_from = @fopen($this->_tarname,'rb')) {
					$this->_error('Unable to open in read mode \''.$this->_tarname.'\'');
					$this->_temp_tarname = '';
					return false;
				}
				if(!$v_file_to = @fopen($this->_temp_tarname,'wb')) {
					$this->_error('Unable to open in write mode \''.$this->_temp_tarname.'\'');
					$this->_temp_tarname = '';
					return false;
				}
				while($v_data = @fread($v_file_from,1024))
					@fwrite($v_file_to,$v_data);
				@fclose($v_file_from);
				@fclose($v_file_to);
			}
			$v_filename = $this->_temp_tarname;
		} else
			$v_filename = $this->_tarname;
		if($this->_compress_type == 'gz')
			$this->_file = @gzopen($v_filename,"rb");
		else
			if($this->_compress_type == 'bz2')
				$this->_file = @bzopen($v_filename,"rb");
			else
				if($this->_compress_type == 'none')
					$this->_file = @fopen($v_filename,"rb");
				else
					$this->_error('Unknown or missing compression type ('.$this->_compress_type.')');
		if($this->_file == 0) {
			$this->_error('Unable to open in read mode \''.$v_filename.'\'');
			return false;
		}
		return true;
	}
	function _openReadWrite() {
		if($this->_compress_type == 'gz')
			$this->_file = @gzopen($this->_tarname,"r+b");
		else
			if($this->_compress_type == 'bz2')
				$this->_file = @bzopen($this->_tarname,"r+b");
			else
				if($this->_compress_type == 'none')
					$this->_file = @fopen($this->_tarname,"r+b");
				else
					$this->_error('Unknown or missing compression type ('.$this->_compress_type.')');
		if($this->_file == 0) {
			$this->_error('Unable to open in read/write mode \''.$this->_tarname.'\'');
			return false;
		}
		return true;
	}
	function _close() {
		if(is_resource($this->_file)) {
			if($this->_compress_type == 'gz')
				@gzclose($this->_file);
			else
				if($this->_compress_type == 'bz2')
					@bzclose($this->_file);
				else
					if($this->_compress_type == 'none')
						@fclose($this->_file);
					else
						$this->_error('Unknown or missing compression type ('.$this->_compress_type.')');
			$this->_file = 0;
		}
		if($this->_temp_tarname != '') {
			@unlink($this->_temp_tarname);
			$this->_temp_tarname = '';
		}
		return true;
	}
	function _cleanFile() {
		$this->_close();
		if($this->_temp_tarname != '') {
			@unlink($this->_temp_tarname);
			$this->_temp_tarname = '';
		} else {
			@unlink($this->_tarname);
		}
		$this->_tarname = '';
		return true;
	}
	function _writeBlock($p_binary_data,$p_len = null) {
		if(is_resource($this->_file)) {
			if($p_len === null) {
				if($this->_compress_type == 'gz')
					@gzputs($this->_file,$p_binary_data);
				else
					if($this->_compress_type == 'bz2')
						@bzwrite($this->_file,$p_binary_data);
					else
						if($this->_compress_type == 'none')
							@fputs($this->_file,$p_binary_data);
						else
							$this->_error('Unknown or missing compression type ('.$this->_compress_type.')');
			} else {
				if($this->_compress_type == 'gz')
					@gzputs($this->_file,$p_binary_data,$p_len);
				else
					if($this->_compress_type == 'bz2')
						@bzwrite($this->_file,$p_binary_data,$p_len);
					else
						if($this->_compress_type == 'none')
							@fputs($this->_file,$p_binary_data,$p_len);
						else
							$this->_error('Unknown or missing compression type ('.$this->_compress_type.')');
			}
		}
		return true;
	}
	function _readBlock() {
		$v_block = null;
		if(is_resource($this->_file)) {
			if($this->_compress_type == 'gz')
				$v_block = @gzread($this->_file,512);
			else
				if($this->_compress_type == 'bz2')
					$v_block = @bzread($this->_file,512);
				else
					if($this->_compress_type == 'none')
						$v_block = @fread($this->_file,512);
					else
						$this->_error('Unknown or missing compression type ('.$this->_compress_type.')');
		}
		return $v_block;
	}
	function _jumpBlock($p_len = null) {
		if(is_resource($this->_file)) {
			if($p_len === null)
				$p_len = 1;
			if($this->_compress_type == 'gz') {
				@gzseek($this->_file,gztell($this->_file) + ($p_len* 512));
			} else
				if($this->_compress_type == 'bz2') {
					for($i = 0; $i < $p_len; $i++)
						$this->_readBlock();
				} else
					if($this->_compress_type == 'none')
						@fseek($this->_file,ftell($this->_file) + ($p_len* 512));
					else
						$this->_error('Unknown or missing compression type ('.$this->_compress_type.')');
		}
		return true;
	}
	function _writeFooter() {
		if(is_resource($this->_file)) {
			$v_binary_data = pack('a1024','');
			$this->_writeBlock($v_binary_data);
		}
		return true;
	}
	function _addList($p_list,$p_add_dir,$p_remove_dir) {
		$v_result = true;
		$v_header = array();
		$p_add_dir = $this->_translateWinPath($p_add_dir);
		$p_remove_dir = $this->_translateWinPath($p_remove_dir,false);
		if(!$this->_file) {
			$this->_error('Invalid file descriptor');
			return false;
		}
		if(sizeof($p_list) == 0)
			return true;
		foreach($p_list as $v_filename) {
			if(!$v_result) {
				break;
			}
			if($v_filename == $this->_tarname)
				continue;
			if($v_filename == '')
				continue;
			if(!file_exists($v_filename)) {
				$this->_warning("File '$v_filename' does not exist");
				continue;
			}
			if(!$this->_addFile($v_filename,$v_header,$p_add_dir,$p_remove_dir))
				return false;
			if(@is_dir($v_filename)) {
				if(!($p_hdir = opendir($v_filename))) {
					$this->_warning("Directory '$v_filename' can not be read");
					continue;
				}
				while(false !== ($p_hitem = readdir($p_hdir))) {
					if(($p_hitem != '.') && ($p_hitem != '..')) {
						if($v_filename != ".")
							$p_temp_list[0] = $v_filename.'/'.$p_hitem;
						else
							$p_temp_list[0] = $p_hitem;
						$v_result = $this->_addList($p_temp_list,$p_add_dir,$p_remove_dir);
					}
				}
				unset($p_temp_list);
				unset($p_hdir);
				unset($p_hitem);
			}
		}
		return $v_result;
	}
	function _addFile($p_filename,$p_header,$p_add_dir,$p_remove_dir) {
		if(!$this->_file) {
			$this->_error('Invalid file descriptor');
			return false;
		}
		if($p_filename == '') {
			$this->_error('Invalid file name');
			return false;
		}
		$p_filename = $this->_translateWinPath($p_filename,false);
		;
		$v_stored_filename = $p_filename;
		if(strcmp($p_filename,$p_remove_dir) == 0) {
			return true;
		}
		if($p_remove_dir != '') {
			if(substr($p_remove_dir,-1) != '/')
				$p_remove_dir .= '/';
			if(substr($p_filename,0,strlen($p_remove_dir)) == $p_remove_dir)
				$v_stored_filename = substr($p_filename,strlen($p_remove_dir));
		}
		$v_stored_filename = $this->_translateWinPath($v_stored_filename);
		if($p_add_dir != '') {
			if(substr($p_add_dir,-1) == '/')
				$v_stored_filename = $p_add_dir.$v_stored_filename;
			else
				$v_stored_filename = $p_add_dir.'/'.$v_stored_filename;
		}
		$v_stored_filename = $this->_pathReduction($v_stored_filename);
		if($this->_isArchive($p_filename)) {
			if(($v_file = @fopen($p_filename,"rb")) == 0) {
				$this->_warning("Unable to open file '".$p_filename."' in binary read mode");
				return true;
			}
			if(!$this->_writeHeader($p_filename,$v_stored_filename))
				return false;
			while(($v_buffer = fread($v_file,512)) != '') {
				$v_binary_data = pack("a512","$v_buffer");
				$this->_writeBlock($v_binary_data);
			}
			fclose($v_file);
		} else {
			if(!$this->_writeHeader($p_filename,$v_stored_filename))
				return false;
		}
		return true;
	}
	function _addString($p_filename,$p_string) {
		if(!$this->_file) {
			$this->_error('Invalid file descriptor');
			return false;
		}
		if($p_filename == '') {
			$this->_error('Invalid file name');
			return false;
		}
		$p_filename = $this->_translateWinPath($p_filename,false);
		;
		if(!$this->_writeHeaderBlock($p_filename,strlen($p_string),time(),384,"",0,0))
			return false;
		$i = 0;
		while(($v_buffer = substr($p_string,(($i++)* 512),512)) != '') {
			$v_binary_data = pack("a512",$v_buffer);
			$this->_writeBlock($v_binary_data);
		}
		return true;
	}
	function _writeHeader($p_filename,$p_stored_filename) {
		if($p_stored_filename == '')
			$p_stored_filename = $p_filename;
		$v_reduce_filename = $this->_pathReduction($p_stored_filename);
		if(strlen($v_reduce_filename) > 99) {
			if(!$this->_writeLongHeader($v_reduce_filename))
				return false;
		}
		$v_info = stat($p_filename);
		$v_uid = sprintf("%6s ",DecOct($v_info[4]));
		$v_gid = sprintf("%6s ",DecOct($v_info[5]));
		$v_perms = sprintf("%6s ",DecOct(fileperms($p_filename)));
		$v_mtime = sprintf("%11s",DecOct(filemtime($p_filename)));
		if(@is_dir($p_filename)) {
			$v_typeflag = "5";
			$v_size = sprintf("%11s ",DecOct(0));
		} else {
			$v_typeflag = '';
			clearstatcache();
			$v_size = sprintf("%11s ",DecOct(filesize($p_filename)));
		}
		$v_linkname = '';
		$v_magic = '';
		$v_version = '';
		$v_uname = '';
		$v_gname = '';
		$v_devmajor = '';
		$v_devminor = '';
		$v_prefix = '';
		$v_binary_data_first = pack("a100a8a8a8a12A12",$v_reduce_filename,$v_perms,$v_uid,
			$v_gid,$v_size,$v_mtime);
		$v_binary_data_last = pack("a1a100a6a2a32a32a8a8a155a12",$v_typeflag,$v_linkname,
			$v_magic,$v_version,$v_uname,$v_gname,$v_devmajor,$v_devminor,$v_prefix,'');
		$v_checksum = 0;
		for($i = 0; $i < 148; $i++)
			$v_checksum += ord(substr($v_binary_data_first,$i,1));
		for($i = 148; $i < 156; $i++)
			$v_checksum += ord(' ');
		for($i = 156,$j = 0; $i < 512; $i++,$j++)
			$v_checksum += ord(substr($v_binary_data_last,$j,1));
		$this->_writeBlock($v_binary_data_first,148);
		$v_checksum = sprintf("%6s ",DecOct($v_checksum));
		$v_binary_data = pack("a8",$v_checksum);
		$this->_writeBlock($v_binary_data,8);
		$this->_writeBlock($v_binary_data_last,356);
		return true;
	}
	function _writeHeaderBlock($p_filename,$p_size,$p_mtime = 0,$p_perms = 0,$p_type =
		'',$p_uid = 0,$p_gid = 0) {
		$p_filename = $this->_pathReduction($p_filename);
		if(strlen($p_filename) > 99) {
			if(!$this->_writeLongHeader($p_filename))
				return false;
		}
		if($p_type == "5") {
			$v_size = sprintf("%11s ",DecOct(0));
		} else {
			$v_size = sprintf("%11s ",DecOct($p_size));
		}
		$v_uid = sprintf("%6s ",DecOct($p_uid));
		$v_gid = sprintf("%6s ",DecOct($p_gid));
		$v_perms = sprintf("%6s ",DecOct($p_perms));
		$v_mtime = sprintf("%11s",DecOct($p_mtime));
		$v_linkname = '';
		$v_magic = '';
		$v_version = '';
		$v_uname = '';
		$v_gname = '';
		$v_devmajor = '';
		$v_devminor = '';
		$v_prefix = '';
		$v_binary_data_first = pack("a100a8a8a8a12A12",$p_filename,$v_perms,$v_uid,$v_gid,
			$v_size,$v_mtime);
		$v_binary_data_last = pack("a1a100a6a2a32a32a8a8a155a12",$p_type,$v_linkname,$v_magic,
			$v_version,$v_uname,$v_gname,$v_devmajor,$v_devminor,$v_prefix,'');
		$v_checksum = 0;
		for($i = 0; $i < 148; $i++)
			$v_checksum += ord(substr($v_binary_data_first,$i,1));
		for($i = 148; $i < 156; $i++)
			$v_checksum += ord(' ');
		for($i = 156,$j = 0; $i < 512; $i++,$j++)
			$v_checksum += ord(substr($v_binary_data_last,$j,1));
		$this->_writeBlock($v_binary_data_first,148);
		$v_checksum = sprintf("%6s ",DecOct($v_checksum));
		$v_binary_data = pack("a8",$v_checksum);
		$this->_writeBlock($v_binary_data,8);
		$this->_writeBlock($v_binary_data_last,356);
		return true;
	}
	function _writeLongHeader($p_filename) {
		$v_size = sprintf("%11s ",DecOct(strlen($p_filename)));
		$v_typeflag = 'L';
		$v_linkname = '';
		$v_magic = '';
		$v_version = '';
		$v_uname = '';
		$v_gname = '';
		$v_devmajor = '';
		$v_devminor = '';
		$v_prefix = '';
		$v_binary_data_first = pack("a100a8a8a8a12A12",'././@LongLink',0,0,0,$v_size,0);
		$v_binary_data_last = pack("a1a100a6a2a32a32a8a8a155a12",$v_typeflag,$v_linkname,
			$v_magic,$v_version,$v_uname,$v_gname,$v_devmajor,$v_devminor,$v_prefix,'');
		$v_checksum = 0;
		for($i = 0; $i < 148; $i++)
			$v_checksum += ord(substr($v_binary_data_first,$i,1));
		for($i = 148; $i < 156; $i++)
			$v_checksum += ord(' ');
		for($i = 156,$j = 0; $i < 512; $i++,$j++)
			$v_checksum += ord(substr($v_binary_data_last,$j,1));
		$this->_writeBlock($v_binary_data_first,148);
		$v_checksum = sprintf("%6s ",DecOct($v_checksum));
		$v_binary_data = pack("a8",$v_checksum);
		$this->_writeBlock($v_binary_data,8);
		$this->_writeBlock($v_binary_data_last,356);
		$i = 0;
		while(($v_buffer = substr($p_filename,(($i++)* 512),512)) != '') {
			$v_binary_data = pack("a512","$v_buffer");
			$this->_writeBlock($v_binary_data);
		}
		return true;
	}
	function _readHeader($v_binary_data,&$v_header) {
		if(strlen($v_binary_data) == 0) {
			$v_header['filename'] = '';
			return true;
		}
		if(strlen($v_binary_data) != 512) {
			$v_header['filename'] = '';
			$this->_error('Invalid block size : '.strlen($v_binary_data));
			return false;
		}
		if(!is_array($v_header)) {
			$v_header = array();
		}
		$v_checksum = 0;
		for($i = 0; $i < 148; $i++)
			$v_checksum += ord(substr($v_binary_data,$i,1));
		for($i = 148; $i < 156; $i++)
			$v_checksum += ord(' ');
		for($i = 156; $i < 512; $i++)
			$v_checksum += ord(substr($v_binary_data,$i,1));
		$v_data = unpack("a100filename/a8mode/a8uid/a8gid/a12size/a12mtime/".
			"a8checksum/a1typeflag/a100link/a6magic/a2version/".
			"a32uname/a32gname/a8devmajor/a8devminor",$v_binary_data);
		$v_header['checksum'] = OctDec(trim($v_data['checksum']));
		if($v_header['checksum'] != $v_checksum) {
			$v_header['filename'] = '';
			if(($v_checksum == 256) && ($v_header['checksum'] == 0))
				return true;
			$this->_error('Invalid checksum for file "'.$v_data['filename'].'" : '.$v_checksum.
				' calculated, '.$v_header['checksum'].' expected');
			return false;
		}
		$v_header['filename'] = trim($v_data['filename']);
		if($this->_maliciousFilename($v_header['filename'])) {
			$this->_error('Malicious .tar detected, file "'.$v_header['filename'].
				'" will not install in desired directory tree');
			return false;
		}
		$v_header['mode'] = OctDec(trim($v_data['mode']));
		$v_header['uid'] = OctDec(trim($v_data['uid']));
		$v_header['gid'] = OctDec(trim($v_data['gid']));
		$v_header['size'] = OctDec(trim($v_data['size']));
		$v_header['mtime'] = OctDec(trim($v_data['mtime']));
		if(($v_header['typeflag'] = $v_data['typeflag']) == "5") {
			$v_header['size'] = 0;
		}
		$v_header['link'] = trim($v_data['link']);
		return true;
	}
	function _maliciousFilename($file) {
		if(strpos($file,'/../') !== false) {
			return true;
		}
		if(strpos($file,'../') === 0) {
			return true;
		}
		return false;
	}
	function _readLongHeader(&$v_header) {
		$v_filename = '';
		$n = floor($v_header['size'] / 512);
		for($i = 0; $i < $n; $i++) {
			$v_content = $this->_readBlock();
			$v_filename .= $v_content;
		}
		if(($v_header['size'] % 512) != 0) {
			$v_content = $this->_readBlock();
			$v_filename .= $v_content;
		}
		$v_binary_data = $this->_readBlock();
		if(!$this->_readHeader($v_binary_data,$v_header))
			return false;
		$v_header['filename'] = $v_filename;
		if($this->_maliciousFilename($v_filename)) {
			$this->_error('Malicious .tar detected, file "'.$v_filename.
				'" will not install in desired directory tree');
			return false;
		}
		return true;
	}
	function _extractInString($p_filename) {
		$v_result_str = "";
		while(strlen($v_binary_data = $this->_readBlock()) != 0) {
			if(!$this->_readHeader($v_binary_data,$v_header))
				return null;
			if($v_header['filename'] == '')
				continue;
			if($v_header['typeflag'] == 'L') {
				if(!$this->_readLongHeader($v_header))
					return null;
			}
			if($v_header['filename'] == $p_filename) {
				if($v_header['typeflag'] == "5") {
					$this->_error('Unable to extract in string a directory '.'entry {'.$v_header['filename'].
						'}');
					return null;
				} else {
					$n = floor($v_header['size'] / 512);
					for($i = 0; $i < $n; $i++) {
						$v_result_str .= $this->_readBlock();
					}
					if(($v_header['size'] % 512) != 0) {
						$v_content = $this->_readBlock();
						$v_result_str .= substr($v_content,0,($v_header['size'] % 512));
					}
					return $v_result_str;
				}
			} else {
				$this->_jumpBlock(ceil(($v_header['size'] / 512)));
			}
		}
		return null;
	}
	function _extractList($p_path,&$p_list_detail,$p_mode,$p_file_list,$p_remove_path) {
		$v_result = true;
		$v_nb = 0;
		$v_extract_all = true;
		$v_listing = false;
		$p_path = $this->_translateWinPath($p_path,false);
		if($p_path == '' || (substr($p_path,0,1) != '/' && substr($p_path,0,3) != "../" &&
			!strpos($p_path,':'))) {
			$p_path = "./".$p_path;
		}
		$p_remove_path = $this->_translateWinPath($p_remove_path);
		if(($p_remove_path != '') && (substr($p_remove_path,-1) != '/'))
			$p_remove_path .= '/';
		$p_remove_path_size = strlen($p_remove_path);
		switch($p_mode) {
			case "complete":
				$v_extract_all = true;
				$v_listing = false;
				break;
			case "partial":
				$v_extract_all = false;
				$v_listing = false;
				break;
			case "list":
				$v_extract_all = false;
				$v_listing = true;
				break;
			default:
				$this->_error('Invalid extract mode ('.$p_mode.')');
				return false;
		}
		clearstatcache();
		while(strlen($v_binary_data = $this->_readBlock()) != 0) {
			$v_extract_file = false;
			$v_extraction_stopped = 0;
			if(!$this->_readHeader($v_binary_data,$v_header))
				return false;
			if($v_header['filename'] == '') {
				continue;
			}
			if($v_header['typeflag'] == 'L') {
				if(!$this->_readLongHeader($v_header))
					return false;
			}
			if((!$v_extract_all) && (is_array($p_file_list))) {
				$v_extract_file = false;
				for($i = 0; $i < sizeof($p_file_list); $i++) {
					if(substr($p_file_list[$i],-1) == '/') {
						if((strlen($v_header['filename']) > strlen($p_file_list[$i])) && (substr($v_header['filename'],
							0,strlen($p_file_list[$i])) == $p_file_list[$i])) {
							$v_extract_file = true;
							break;
						}
					} elseif($p_file_list[$i] == $v_header['filename']) {
						$v_extract_file = true;
						break;
					}
				}
			} else {
				$v_extract_file = true;
			}
			if(($v_extract_file) && (!$v_listing)) {
				if(($p_remove_path != '') && (substr($v_header['filename'],0,$p_remove_path_size) ==
					$p_remove_path))
					$v_header['filename'] = substr($v_header['filename'],$p_remove_path_size);
				if(($p_path != './') && ($p_path != '/')) {
					while(substr($p_path,-1) == '/')
						$p_path = substr($p_path,0,strlen($p_path) - 1);
					if(substr($v_header['filename'],0,1) == '/')
						$v_header['filename'] = $p_path.$v_header['filename'];
					else
						$v_header['filename'] = $p_path.'/'.$v_header['filename'];
				}
				if(file_exists($v_header['filename'])) {
					if((@is_dir($v_header['filename'])) && ($v_header['typeflag'] == '')) {
						$this->_error('File '.$v_header['filename'].' already exists as a directory');
						return false;
					}
					if(($this->_isArchive($v_header['filename'])) && ($v_header['typeflag'] == "5")) {
						$this->_error('Directory '.$v_header['filename'].' already exists as a file');
						return false;
					}
					if(!is_writeable($v_header['filename'])) {
						$this->_error('File '.$v_header['filename'].
							' already exists and is write protected');
						return false;
					}
					if(filemtime($v_header['filename']) > $v_header['mtime']) {
					}
				} elseif(($v_result = $this->_dirCheck(($v_header['typeflag'] == "5"?$v_header['filename']:dirname($v_header['filename'])))) != 1) {
					$this->_error('Unable to create path for '.$v_header['filename']);
					return false;
				}
				if($v_extract_file) {
					if($v_header['typeflag'] == "5") {
						if(!@file_exists($v_header['filename'])) {
							if(!@mkdir($v_header['filename'],0777)) {
								$this->_error('Unable to create directory {'.$v_header['filename'].'}');
								return false;
							}
						}
					} elseif($v_header['typeflag'] == "2") {
						if(!@symlink($v_header['link'],$v_header['filename'])) {
							$this->_error('Unable to extract symbolic link {'.$v_header['filename'].'}');
							return false;
						}
					} else {
						if(($v_dest_file = @fopen($v_header['filename'],"wb")) == 0) {
							$this->_error('Error while opening {'.$v_header['filename'].
								'} in write binary mode');
							return false;
						} else {
							$n = floor($v_header['size'] / 512);
							for($i = 0; $i < $n; $i++) {
								$v_content = $this->_readBlock();
								fwrite($v_dest_file,$v_content,512);
							}
							if(($v_header['size'] % 512) != 0) {
								$v_content = $this->_readBlock();
								fwrite($v_dest_file,$v_content,($v_header['size'] % 512));
							}
							@fclose($v_dest_file);
							@touch($v_header['filename'],$v_header['mtime']);
							if($v_header['mode'] & 0111) {
								$mode = fileperms($v_header['filename']) | ( ~ umask() & 0111);
								@chmod($v_header['filename'],$mode);
							}
						}
						clearstatcache();
						if(filesize($v_header['filename']) != $v_header['size']) {
							$this->_error('Extracted file '.$v_header['filename'].
								' does not have the correct file size \''.filesize($v_header['filename']).'\' ('.
								$v_header['size'].' expected). Archive may be corrupted.');
							return false;
						}
					}
				} else {
					$this->_jumpBlock(ceil(($v_header['size'] / 512)));
				}
			} else {
				$this->_jumpBlock(ceil(($v_header['size'] / 512)));
			}
			if($v_listing || $v_extract_file || $v_extraction_stopped) {
				if(($v_file_dir = dirname($v_header['filename'])) == $v_header['filename'])
					$v_file_dir = '';
				if((substr($v_header['filename'],0,1) == '/') && ($v_file_dir == ''))
					$v_file_dir = '/';
				$p_list_detail[$v_nb++] = $v_header;
				if(is_array($p_file_list) && (count($p_list_detail) == count($p_file_list))) {
					return true;
				}
			}
		}
		return true;
	}
	function _openAppend() {
		if(filesize($this->_tarname) == 0)
			return $this->_openWrite();
		if($this->_compress) {
			$this->_close();
			if(!@rename($this->_tarname,$this->_tarname.".tmp")) {
				$this->_error('Error while renaming \''.$this->_tarname.'\' to temporary file \''.
					$this->_tarname.'.tmp\'');
				return false;
			}
			if($this->_compress_type == 'gz')
				$v_temp_tar = @gzopen($this->_tarname.".tmp","rb");
			elseif($this->_compress_type == 'bz2')
				$v_temp_tar = @bzopen($this->_tarname.".tmp","rb");
			if($v_temp_tar == 0) {
				$this->_error('Unable to open file \''.$this->_tarname.'.tmp\' in binary read mode');
				@rename($this->_tarname.".tmp",$this->_tarname);
				return false;
			}
			if(!$this->_openWrite()) {
				@rename($this->_tarname.".tmp",$this->_tarname);
				return false;
			}
			if($this->_compress_type == 'gz') {
				while(!@gzeof($v_temp_tar)) {
					$v_buffer = @gzread($v_temp_tar,512);
					if($v_buffer == ARCHIVE_TAR_END_BLOCK) {
						continue;
					}
					$v_binary_data = pack("a512",$v_buffer);
					$this->_writeBlock($v_binary_data);
				}
				@gzclose($v_temp_tar);
			} elseif($this->_compress_type == 'bz2') {
				while(strlen($v_buffer = @bzread($v_temp_tar,512)) > 0) {
					if($v_buffer == ARCHIVE_TAR_END_BLOCK) {
						continue;
					}
					$v_binary_data = pack("a512",$v_buffer);
					$this->_writeBlock($v_binary_data);
				}
				@bzclose($v_temp_tar);
			}
			if(!@unlink($this->_tarname.".tmp")) {
				$this->_error('Error while deleting temporary file \''.$this->_tarname.'.tmp\'');
			}
		} else {
			if(!$this->_openReadWrite())
				return false;
			clearstatcache();
			$v_size = filesize($this->_tarname);
			fseek($this->_file,$v_size - 1024);
			if(fread($this->_file,512) == ARCHIVE_TAR_END_BLOCK) {
				fseek($this->_file,$v_size - 1024);
			} elseif(fread($this->_file,512) == ARCHIVE_TAR_END_BLOCK) {
				fseek($this->_file,$v_size - 512);
			}
		}
		return true;
	}
	function _append($p_filelist,$p_add_dir = '',$p_remove_dir = '') {
		if(!$this->_openAppend())
			return false;
		if($this->_addList($p_filelist,$p_add_dir,$p_remove_dir))
			$this->_writeFooter();
		$this->_close();
		return true;
	}
	function _dirCheck($p_dir) {
		clearstatcache();
		if((@is_dir($p_dir)) || ($p_dir == ''))
			return true;
		$p_parent_dir = dirname($p_dir);
		if(($p_parent_dir != $p_dir) && ($p_parent_dir != '') && (!$this->_dirCheck($p_parent_dir)))
			return false;
		if(!@mkdir($p_dir,0777)) {
			$this->_error("Unable to create directory '$p_dir'");
			return false;
		}
		return true;
	}
	function _pathReduction($p_dir) {
		$v_result = '';
		if($p_dir != '') {
			$v_list = explode('/',$p_dir);
			for($i = sizeof($v_list) - 1; $i >= 0; $i--) {
				if($v_list[$i] == ".") {
				} else
					if($v_list[$i] == "..") {
						$i--;
					} else
						if(($v_list[$i] == '') && ($i != (sizeof($v_list) - 1)) && ($i != 0)) {
						} else {
							$v_result = $v_list[$i].($i != (sizeof($v_list) - 1)?'/'.$v_result:'');
						}
			}
		}
		$v_result = strtr($v_result,'\\','/');
		return $v_result;
	}
	function _translateWinPath($p_path,$p_remove_disk_letter = true) {
		if(defined('OS_WINDOWS') && OS_WINDOWS) {
			if(($p_remove_disk_letter) && (($v_position = strpos($p_path,':')) != false)) {
				$p_path = substr($p_path,$v_position + 1);
			}
			if((strpos($p_path,'\\') > 0) || (substr($p_path,0,1) == '\\')) {
				$p_path = strtr($p_path,'\\','/');
			}
		}
		return $p_path;
	}
}
?>
