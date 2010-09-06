<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

include_once 'finder.class.php';

// эти настройки надо формировать в зависимости от текущей группы пользователя и его настроек в даминке
$opts = array(
    'root' => JPATH_BASE,         // path to root directory
    'URL' => JPATH_SITE . '/', // root directory URL
    'rootAlias' => JPATH_SITE,          // display this instead of root directory name
    // 'disabled'     => array(),      // list of not allowed commands
    // 'dotFiles'     => false,        // display dot files
    'dirSize' => false,         // count total directories sizes
    // 'fileMode'     => 0666,         // new files mode
    // 'dirMode'      => 0777,         // new folders mode
    // 'mimeDetect'   => 'auto',       // files mimetypes detection method (finfo, mime_content_type, linux (file -ib), bsd (file -Ib), internal (by extensions))
    //'uploadAllow' => array('image'),      // mimetypes which allowed to upload
    //'uploadDeny' => array('*'),      // mimetypes which not allowed to upload
    //'uploadOrder' => 'allow,deny', // order to proccess uploadAllow and uploadAllow options
    // 'imgLib'       => 'auto',       // image manipulation library (imagick, mogrify, gd)
     'tmbDir'       => 'cache/finder-'.md5(JPATH_BASE),       // directory name for image thumbnails. Set to "" to avoid thumbnails generation
    // 'tmbCleanProb' => 1,            // how frequiently clean thumbnails dir (0 - never, 100 - every init request)
    // 'tmbAtOnce'    => 5,            // number of thumbnails to generate per request
    // 'tmbSize'      => 48,           // images thumbnails size (px)
    // 'fileURL'      => true,         // display file URL in "get info"
    // 'dateFormat'   => 'j M Y H:i',  // file modification date format
    // 'logger'       => null,         // object logger
    //'defaults' => array(        // default permisions
    //    'read' => true,
    //     'write' => false,
    //    'rm' => false
    // ),
    //'perms' => array(
    //    '/\.(jpg|gif|png)$/i' => array(
    //        'read' => true,
    //        'write' => true,
    //        'rm' => false
    //    ),
    //),      // individual folders/files permisions
    'debug' => false,         // send debug to client
        // 'archiveMimes' => array(),      // allowed archive's mimetypes to create. Leave empty for all available types.
        // 'archivers'    => array()       // info about archivers to use. See example below. Leave empty for auto detect
        // 'archivers' => array(
        // 	'create' => array(
        // 		'application/x-gzip' => array(
        // 			'cmd' => 'tar',
        // 			'argc' => '-czf',
        // 			'ext'  => 'tar.gz'
        // 			)
        // 		),
        // 	'extract' => array(
        // 		'application/x-gzip' => array(
        // 			'cmd'  => 'tar',
        // 			'argc' => '-xzf',
        // 			'ext'  => 'tar.gz'
        // 			),
        // 		'application/x-bzip2' => array(
        // 			'cmd'  => 'tar',
        // 			'argc' => '-xjf',
        // 			'ext'  => 'tar.bz'
        // 			)
        // 		)
        // 	)
);

$fm = new elFinder($opts);
$fm->run();