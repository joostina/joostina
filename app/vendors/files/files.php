<?php

/**
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Базируется на DooFile class file.
 *
 * @author     Leng Sheng Hong <darkredz@gmail.com>
 * @link       http://www.doophp.com/
 * @copyright  Copyright &copy; 2009-2010 Leng Sheng Hong
 * @license    http://www.doophp.com/license
 * @since      1.3
 *
 * @deprecated адаптировать функционал, убрать библиотеку
 */

/**
 * Provides functions for managing file system
 */
class __Files {
	const LIST_FILE = 'file';
	const LIST_FOLDER = 'folder';

	public $chmod;

	public function __construct( $chmod = null ) {
		$this->chmod = $chmod;
	}

	/**
	 * Delete contents in a folder recursively
	 *
	 * @param string $dir Path of the folder to be deleted
	 *
	 * @return int Total of deleted files/folders
	 */
	public function purgeContent( $dir ) {
		$totalDel = 0;
		$handle   = opendir( $dir );

		while ( false !== ( $file = readdir( $handle ) ) ) {
			if ( $file != '.' && $file != '..' ) {
				if ( is_dir( $dir . $file ) ) {
					$totalDel += $this->purgeContent( $dir . $file . '/' );
					if ( rmdir( $dir . $file ) ) {
						$totalDel++;
					}
				} else {
					if ( unlink( $dir . $file ) ) {
						$totalDel++;
					}
				}
			}
		}
		closedir( $handle );
		return $totalDel;
	}

	/**
	 * Delete a folder (and all files and folders below it)
	 *
	 * @param string $path       Path to folder to be deleted
	 * @param bool   $deleteSelf true if the folder should be deleted. false if just its contents.
	 *
	 * @return int|bool Returns the total of deleted files/folder. Returns false if delete failed
	 */
	public function delete( $path , $deleteSelf = true ) {

		//delete all sub folder/files under, then delete the folder itself
		if ( is_dir( $path ) ) {
			if ( $path[strlen( $path ) - 1] != '/' && $path[strlen( $path ) - 1] != '\\' ) {
				$path .= DIRECTORY_SEPARATOR;
				$path = str_replace( '\\' , '/' , $path );
			}
			if ( $total = $this->purgeContent( $path ) ) {
				if ( $deleteSelf ) {
					if ( $t = rmdir( $path ) ) {
						return $total + $t;
					}
				}
				return $total;
			} else if ( $deleteSelf ) {
				return rmdir( $path );
			}
			return false;
		} else {
			return unlink( $path );
		}
	}

	/**
	 * If the folder does not exist creates it (recursively)
	 *
	 * @param string $path          Path to folder/file to be created
	 * @param mixed  $content       modelContent to be written to the file
	 * @param string $writeFileMode Mode to write the file
	 *
	 * @return bool Returns true if file/folder created
	 */
	public function create( $path , $content = null , $writeFileMode = 'w+' ) {
		//create file if content not empty
		if ( !empty( $content ) ) {
			if ( strpos( $path , '/' ) !== false || strpos( $path , '\\' ) !== false ) {
				$path     = str_replace( '\\' , '/' , $path );
				$filename = $path;
				$path     = explode( '/' , $path );
				array_splice( $path , sizeof( $path ) - 1 );

				$path = implode( '/' , $path );
				if ( $path[strlen( $path ) - 1] != '/' ) {
					$path .= '/';
				}
			} else {
				$filename = $path;
			}

			if ( $filename != $path && !file_exists( $path ) ) {
				mkdir( $path , $this->chmod , true );
			}
			$fp = fopen( $filename , $writeFileMode );
			$rs = fwrite( $fp , $content );
			fclose( $fp );
			return ( $rs > 0 );
		} else {
			if ( !file_exists( $path ) ) {
				return mkdir( $path , $this->chmod , true );
			} else {
				return true;
			}
		}
	}

	/**
	 * Move/rename a file/folder
	 *
	 * @param string $from Original path of the folder/file
	 * @param string $to   Destination path of the folder/file
	 *
	 * @return bool Returns true if file/folder created
	 */
	public function move( $from , $to ) {
		if ( strpos( $to , '/' ) !== false || strpos( $to , '\\' ) !== false ) {
			$path = str_replace( '\\' , '/' , $to );
			$path = explode( '/' , $path );
			array_splice( $path , sizeof( $path ) - 1 );

			$path = implode( '/' , $path );
			if ( $path[strlen( $path ) - 1] != '/' ) {
				$path .= '/';
			}
			if ( !file_exists( $path ) ) {
				mkdir( $path , $this->chmod , true );
			}
		}

		return rename( $from , $to );
	}

	/**
	 * Copy a file/folder to a destination
	 *
	 * @param string $from    Original path of the folder/file
	 * @param string $to      Destination path of the folder/file
	 * @param array  $exclude An array of file and folder names to be excluded from a copy
	 *
	 * @return bool|int Returns true if file copied. If $from is a folder, returns the number of files/folders copied
	 */
	public function copy( $from , $to , $exclude = array () ) {
		if ( is_dir( $from ) ) {
			if ( $to[strlen( $to ) - 1] != '/' && $to[strlen( $to ) - 1] != '\\' ) {
				$to .= DIRECTORY_SEPARATOR;
				$to = str_replace( '\\' , '/' , $to );
			}
			if ( $from[strlen( $from ) - 1] != '/' && $from[strlen( $from ) - 1] != '\\' ) {
				$from .= DIRECTORY_SEPARATOR;
				$from = str_replace( '\\' , '/' , $from );
			}
			if ( !file_exists( $to ) ) {
				mkdir( $to , $this->chmod , true );
			}

			return $this->copyContent( $from , $to , $exclude );
		} else {
			if ( strpos( $to , '/' ) !== false || strpos( $to , '\\' ) !== false ) {
				$path = str_replace( '\\' , '/' , $to );
				$path = explode( '/' , $path );
				array_splice( $path , sizeof( $path ) - 1 );

				$path = implode( '/' , $path );
				if ( $path[strlen( $path ) - 1] != '/' ) {
					$path .= '/';
				}

				if ( !file_exists( $path ) ) {
					mkdir( $path , $this->chmod , true );
				}
			}
			return copy( $from , $to );
		}
	}

	/**
	 * Copy contents in a folder recursively
	 *
	 * @param string $dir     Path of the folder to be copied
	 * @param string $to      Destination path
	 * @param array  $exclude An array of file and folder names to be excluded from a copy
	 *
	 * @return int Total of files/folders copied
	 */
	public function copyContent( $dir , $to , $exclude = array () ) {
		$totalCopy = 0;
		$handle    = opendir( $dir );

		while ( false !== ( $file = readdir( $handle ) ) ) {
			if ( $file != '.' && $file != '..' && !in_array( $file , $exclude ) ) {

				if ( is_dir( $dir . $file ) ) {
					if ( !file_exists( $to . $file ) ) {
						mkdir( $to . $file , $this->chmod , true );
					}

					$totalCopy += $this->copyContent( $dir . $file . '/' , $to . $file . '/' , $exclude );
				} else {
					if ( copy( $dir . $file , $to . $file ) ) {
						$totalCopy++;
					}
				}
			}
		}
		closedir( $handle );
		return $totalCopy;
	}

	/**
	 * Get the space used up by a folder recursively.
	 *
	 * @param string $dir  Directory path.
	 * @param string $unit Case insensitive units: B, KB, MB, GB or TB
	 * @param int    $precision
	 *
	 * @return float total space used up by the folder (KB)
	 */
	public function getSize( $dir , $unit = 'KB' , $precision = 2 ) {
		if ( !is_dir( $dir ) ) {
			return filesize( $dir );
		}
		$dir = str_replace( '\\' , '/' , $dir );
		if ( $dir[strlen( $dir ) - 1] != '/' ) {
			$dir .= '/';
		}

		$totalSize = 0;
		$handle    = opendir( $dir );

		while ( false !== ( $file = readdir( $handle ) ) ) {
			if ( $file != '.' && $file != '..' ) {
				if ( is_dir( $dir . $file ) ) {
					$totalSize += $this->getSize( $dir . $file , false );
				} else {
					$totalSize += filesize( $dir . $file );
				}
			}
		}
		closedir( $handle );
		return self::formatBytes( $totalSize , $unit , $precision );
	}

	/**
	 * Convert bytes into KB, MB, GB or TB.
	 *
	 * @param int    $bytes
	 * @param string $unit Case insensitive units: B, KB, MB, GB or TB OR false if not to format the size
	 * @param int    $precision
	 *
	 * @return float
	 */
	public static function formatBytes( $bytes , $unit = 'KB' , $precision = 2 ) {
		if ( $unit === false ) {
			return $bytes;
		}
		$unit    = strtoupper( $unit );
		$unitPow = array ( 'B'  => 0 ,
		                   'KB' => 1 ,
		                   'MB' => 2 ,
		                   'GB' => 3 ,
		                   'TB' => 4 );
		$bytes /= pow( 1024 , $unitPow[$unit] );
		return round( $bytes , $precision );
	}

	public static function filesize_format( $bytes , $format = '' , $force = '' ) {
		$force         = strtoupper( $force );
		$defaultFormat = '%01d %s';
		if ( strlen( $format ) == 0 ) {
			$format = $defaultFormat;
		}
		$bytes = max( 0 , (int) $bytes );
		$units = array ( 'b' , 'Kb' , 'Mb' , 'GB' , 'TB' , 'PB' );
		$power = array_search( $force , $units );
		if ( $power === false ) {
			$power = $bytes > 0 ? floor( log( $bytes , 1024 ) ) : 0;
		}
		return sprintf( $format , $bytes / pow( 1024 , $power ) , $units[$power] );
	}

	/**
	 * Get a list of folders or files or both in a given path.
	 *
	 * @param string $path      Path to get the list of files/folders
	 * @param string $listOnly  List only files or folders. Use value DooFiles::LIST_FILE or DooFiles::LIST_FOLDER
	 * @param string $unit      Unit for the size of files. Case insensitive units: B, KB, MB, GB or TB
	 * @param int    $precision Number of decimal digits to round the file size to
	 *
	 * @return array Returns an assoc array with keys: name(file name), path(full path to file/folder), folder(boolean), extension, type, size(KB)
	 */
	public function getList( $path , $listOnly = null , $unit = 'KB' , $precision = 2 ) {
		$path = str_replace( '\\' , '/' , $path );
		if ( $path[strlen( $path ) - 1] != '/' ) {
			$path .= '/';
		}

		$filetype     = array ( '.' , '..' );
		$name         = array ( '.svn' );

		$dir          = @opendir( $path );
		if ( $dir === false ) {
			return false;
		}

		while ( $file = readdir( $dir ) ) {
			if ( !in_array( substr( $file , -1 , strlen( $file ) ) , $filetype ) && !in_array( substr( $file , -2 , strlen( $file ) ) , $filetype ) ) {
				$name[] = $path . $file;
			}
		}
		closedir( $dir );

		if ( count( $name ) == 0 ) {
			return false;
		}

		$fileInfo = array ();
		foreach ( $name as $val ) {
			if ( $listOnly == self::LIST_FILE ) {
				if ( is_dir( $val ) ) {
					continue;
				}
			}
			if ( $listOnly == self::LIST_FOLDER ) {
				if ( !is_dir( $val ) ) {
					continue;
				}
			}
			$filename = explode( '/' , $val );
			$filename = $filename[count( $filename ) - 1];
			$ext      = explode( '.' , $val );

			if ( !is_dir( $val ) ) {
				$fileInfo[] = array ( 'name'      => $filename ,
				                      'path'      => $val ,
				                      'folder'    => is_dir( $val ) ,
				                      'extension' => strtolower( $ext[sizeof( $ext ) - 1] ) ,
				                      'type'      => self::mime_content_type( $val ) ,
				                      'size'      => filesize( $val ) );
			} else {
				$fileInfo[] = array ( 'name'   => $filename ,
				                      'path'   => $val ,
				                      'folder' => is_dir( $val ) );
			}
		}
		return $fileInfo;
	}

	/**
	 * Save the uploaded file(s) in HTTP File Upload variables
	 *
	 * @param string $uploadPath Path to save the uploaded file(s)
	 * @param string $filename   The file input field name in $_FILES HTTP File Upload variables
	 * @param string $rename     Rename the uploaded file (without extension)
	 *
	 * @return string|array The file name of the uploaded file.
	 */
	public function upload( $uploadPath , $filename , $rename = '' ) {
		$file = !empty( $_FILES[$filename] ) ? $_FILES[$filename] : null;
		if ( $file == Null ) {
			return;
		}

		if ( !file_exists( $uploadPath ) ) {
			$this->create( $uploadPath );
		}

		if ( is_array( $file['name'] ) === False ) {
			$pic = strrpos( $file['name'] , '.' );
			$ext = substr( $file['name'] , $pic + 1 );

			if ( $rename == '' ) {
				$newName = time() . '-' . mt_rand( 1000 , 9999 ) . '.' . $ext;
			} else {
				$newName = $rename . '.' . $ext;
			}

			$filePath = $uploadPath . $newName;

			if ( move_uploaded_file( $file['tmp_name'] , $filePath ) ) {
				return $newName;
			}
		} else {
			if ( !file_exists( $uploadPath ) ) {
				$this->create( $uploadPath );
			}
			$uploadedPath = array ();
			foreach ( $file['error'] as $k => $error ) {
				if ( empty( $file['name'][$k] ) ) {
					continue;
				}
				if ( $error == UPLOAD_ERR_OK ) {
					$pic = strrpos( $file['name'][$k] , '.' );
					$ext = substr( $file['name'][$k] , $pic + 1 );

					if ( $rename == '' ) {
						$newName = time() . '-' . mt_rand( 1000 , 9999 ) . '_' . $k . '.' . $ext;
					} else {
						$newName = $rename . '_' . $k . '.' . $ext;
					}

					$filePath = $uploadPath . $newName;

					if ( move_uploaded_file( $file['tmp_name'][$k] , $filePath ) ) {
						$uploadedPath[] = $newName;
					}
				} else {
					return false;
				}
			}
			return $uploadedPath;
		}
	}

	/**
	 * Get the uploaded files' type
	 *
	 * @param string $filename The file field name in $_FILES HTTP File Upload variables
	 *
	 * @return string|array The image format type of the uploaded image.
	 */
	public function getUploadFormat( $filename ) {
		if ( !empty( $_FILES[$filename] ) ) {
			$type = $_FILES[$filename]['type'];
			if ( is_array( $type ) === False ) {
				if ( !empty( $type ) ) {
					return $type;
				}
			} else {
				$typelist = array ();
				foreach ( $type as $t ) {
					$typelist[] = $t;
				}
				return $typelist;
			}
		}
	}

	/**
	 * Checks if file mime type of the uploaded file(s) is in the allowed list
	 *
	 * @param string $filename  The file input field name in $_FILES HTTP File Upload variables
	 * @param array  $allowType Allowed file type.
	 *
	 * @return bool Returns true if file mime type is in the allowed list.
	 */
	public function checkFileType( $filename , $allowType ) {
		$type = $this->getUploadFormat( $filename );
		if ( is_array( $type ) === False ) {
			return in_array( $type , $allowType );
		} else {
			foreach ( $type as $t ) {
				if ( $t === Null || $t === '' ) {
					continue;
				}
				if ( !in_array( $t , $allowType ) ) {
					return false;
				}
			}
			return true;
		}
	}

	/**
	 * Checks if file extension of the uploaded file(s) is in the allowed list.
	 *
	 * @param string $filename The file input field name in $_FILES HTTP File Upload variables
	 * @param array  $allowExt Allowed file extensions.
	 *
	 * @return bool Returns true if file extension is in the allowed list.
	 */
	public function checkFileExtension( $filename , $allowExt ) {
		if ( !empty( $_FILES[$filename] ) ) {
			$name = $_FILES[$filename]['name'];
			if ( is_array( $name ) === False ) {
				$n   = strrpos( $name , '.' );
				$ext = strtolower( substr( $name , $n + 1 ) );
				return in_array( $ext , $allowExt );
			} else {
				foreach ( $name as $nm ) {
					$n   = strrpos( $nm , '.' );
					$ext = strtolower( substr( $nm , $n + 1 ) );
					if ( !in_array( $ext , $allowExt ) ) {
						return false;
					}
				}
				return true;
			}
		}
	}

	/**
	 * Checks if file size does not exceed the max file size allowed.
	 *
	 * @param string $filename The file input field name in $_FILES HTTP File Upload variables
	 * @param int    $maxSize  Allowed max file size in kilo bytes.
	 *
	 * @return bool Returns true if file size does not exceed the max file size allowed.
	 */
	public function checkFileSize( $filename , $maxSize ) {
		if ( !empty( $_FILES[$filename] ) ) {
			$size = $_FILES[$filename]['size'];
			if ( is_array( $size ) === False ) {
				if ( ( $size / 1024 ) > $maxSize ) {
					return false;
				}
			} else {
				foreach ( $size as $s ) {
					if ( ( $s / 1024 ) > $maxSize ) {
						return false;
					}
				}
			}
			return true;
		}
	}

	/**
	 * Reads the contents of a given file
	 *
	 * @param string $fullFilePath Full path to file whose contents should be read
	 *
	 * @return string|bool Returns file contents or false if file not found
	 */
	function readFileContents( $fullFilePath , $flags = 0 , resource $context = null , $offset = -1 , $maxlen = null ) {
		if ( file_exists( $fullFilePath ) ) {
			if ( $maxlen !== null ) {
				return file_get_contents( $fullFilePath , $flags , $context , $offset , $maxlen );
			} else {
				return file_get_contents( $fullFilePath , $flags , $context , $offset );
			}
		} else {
			return false;
		}
	}

	public static function mime_content_type( $filename ) {
		$mime_types = array ( // all
			'txt'  => 'text/plain' ,
			'htm'  => 'text/html' ,
			'html' => 'text/html' ,
			'php'  => 'text/html' ,
			'css'  => 'text/css' ,
			'js'   => 'application/javascript' ,
			'json' => 'application/json' ,
			'xml'  => 'application/xml' ,
			'swf'  => 'application/x-shockwave-flash' ,
			'flv'  => 'video/x-flv' ,
			'sql'  => 'text/x-sql' ,
			// images
			'png'  => 'image/png' ,
			'jpe'  => 'image/jpeg' ,
			'jpeg' => 'image/jpeg' ,
			'jpg'  => 'image/jpeg' ,
			'gif'  => 'image/gif' ,
			'bmp'  => 'image/bmp' ,
			'ico'  => 'image/vnd.microsoft.icon' ,
			'tiff' => 'image/tiff' ,
			'tif'  => 'image/tiff' ,
			'svg'  => 'image/svg+xml' ,
			'svgz' => 'image/svg+xml' ,
			'tga'  => 'image/x-targa' ,
			'psd'  => 'image/vnd.adobe.photoshop' ,
			// archives
			'zip'  => 'application/zip' ,
			'rar'  => 'application/x-rar-compressed' ,
			'exe'  => 'application/x-msdownload' ,
			'msi'  => 'application/x-msdownload' ,
			'cab'  => 'application/vnd.ms-cab-compressed' ,
			'gz'   => 'application/x-gzip' ,
			'tgz'  => 'application/x-gzip' ,
			'bz'   => 'application/x-bzip2' ,
			'bz2'  => 'application/x-bzip2' ,
			'tbz'  => 'application/x-bzip2' ,
			'zip'  => 'application/zip' ,
			'rar'  => 'application/x-rar' ,
			'tar'  => 'application/x-tar' ,
			'7z'   => 'application/x-7z-compressed' ,
			// audio/video
			'mp3'  => 'audio/mpeg' ,
			'qt'   => 'video/quicktime' ,
			'mov'  => 'video/quicktime' ,
			'avi'  => 'video/x-msvideo' ,
			'dv'   => 'video/x-dv' ,
			'mp4'  => 'video/mp4' ,
			'mpeg' => 'video/mpeg' ,
			'mpg'  => 'video/mpeg' ,
			'wm'   => 'video/x-ms-wmv' ,
			'flv'  => 'video/x-flv' ,
			'mkv'  => 'video/x-matroska' ,
			// adobe
			'pdf'  => 'application/pdf' ,
			'psd'  => 'image/vnd.adobe.photoshop' ,
			'ai'   => 'application/postscript' ,
			'eps'  => 'application/postscript' ,
			'ps'   => 'application/postscript' ,
			// ms office
			'doc'  => 'application/msword' ,
			'rtf'  => 'application/rtf' ,
			'xls'  => 'application/vnd.ms-excel' ,
			'ppt'  => 'application/vnd.ms-powerpoint' ,
			// open office
			'odt'  => 'application/vnd.oasis.opendocument.text' ,
			'ods'  => 'application/vnd.oasis.opendocument.spreadsheet' , );

		$f          = explode( '.' , $filename );
		$ext        = strtolower( array_pop( $f ) );
		if ( array_key_exists( $ext , $mime_types ) ) {
			return $mime_types[$ext];
		} elseif ( function_exists( 'finfo_open' ) ) {
			$finfo    = finfo_open( FILEINFO_MIME );
			$mimetype = finfo_file( $finfo , $filename );
			finfo_close( $finfo );
			return $mimetype;
		} else {
			return 'application/octet-stream';
		}
	}

	/**
	 * Формирование вложенного пути к фацлу с учетом разделения по каталогам
	 *
	 * @param integer $id - номер файла в БД
	 *
	 * @return string - путь к файлу в структуре подкаталогов
	 */
	public static function make_file_location( $id ) {
		$p = sprintf( '%09d' , $id );
		$h = str_split( $p , 3 );
		return implode( '/' , $h );
	}

	/**
	 * Получение расширения файла
	 *
	 * @param string полное или краткое имя файла $filename
	 *
	 * @return string расширение файла
	 */
	public static function ext( $filename ) {
		return pathinfo( $filename );
	}

	public static function filedata( $filename ) {
		$f         = self::ext( $filename );

		$r         = array ();
		$r['mime'] = self::mime_content_type( $filename );
		$r['size'] = filesize( $filename );
		$r['ext']  = $f['extension'];
		$r['name'] = $f['basename'];

		return $r;
	}

}
