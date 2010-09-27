<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 *
 * Конфигурация для библиотеки SmartOptimizer (с) Author: Ali Farhadi (http://farhadi.ir/)
 * Документация: http://farhadi.ir/works/smartoptimizer
 */

/*
 * SmartOptimizer Configuration File
 */

//base dir (a relative path to the base directory)
$settings['baseDir'] = dirname(dirname(dirname(dirname(__FILE__)))).'/';

//Encoding of your js and css files. (utf-8 or iso-8859-1)
$settings['charSet'] = 'utf-8'; 

//Show error messages if any error occurs (true or false)
$settings['debug'] = true;

//use this to set gzip compression On or Off
$settings['gzip'] = true;

//use this to set gzip compression level (an integer between 1 and 9)
$settings['compressionLevel'] = 9;

//these types of files will not be gzipped nor minified
$settings['gzipExceptions'] = array('gif','jpeg','jpg','png','swf'); 

//use this to set Minifier On or Off
$settings['minify'] = true;

//use this to set file concatenation On or Off
$settings['concatenate'] = true;

//separator for files to be concatenated
$settings['separator'] = ',';

//specifies whether to emebed files included in css files using the data URI scheme or not 
$settings['embed'] = true;

//The maximum size of an embedded file. (use 0 for unlimited size)
$settings['embedMaxSize'] = 5120; //5KB

//these types of files will not be embedded
$settings['embedExceptions'] = array('htc'); 

//to set server-side cache On or Off
$settings['serverCache'] = true;

//if you change it to false, the files will not be checked for modifications and always cached files will be used (for better performance)
$settings['serverCacheCheck'] = true;

//cache dir
$settings['cacheDir'] = $settings['baseDir'].'cache/smartoptimizer/';

//prefix for cache files
$settings['cachePrefix'] = 'so_';

//to set client-side cache On or Off
$settings['clientCache'] = true;

//Setting this to false will force the browser to use cached files without checking for changes.
$settings['clientCacheCheck'] = false;

/**
 * Вариант использования множества файлов за один проход:
 * <link rel="stylesheet" href="path/to/file/cssfile1.css,cssfile2.css,cssfile3.css" />
 * <script src="path/to/file/jsfile1.js,jsfile2.js,jsfile3.js"></script>
 *
 * Или:
 * <script src="includes/libraries/smartoptimizer/?path/to/file/jsfile.js"></script>
 * <link rel="stylesheet" href="includes/libraries/smartoptimizer/?path/to/file/cssfile.css" />
 */