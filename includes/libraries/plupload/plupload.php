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

/**
 * upload.php
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */

class Plupload {

    private static function send_headers() {
// HTTP headers for no cache etc
        header('Content-type: text/plain; charset=UTF-8');
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }

    private static function get_filefolder( $rootdir = false, $filename = false, $fileid = false ) {
        mosMainFrame::addLib('files');
        mosMainFrame::addLib('attached');

        $id = $fileid ? $fileid : attached::add($filename)->id;

        $rootdir = $rootdir ? $rootdir : File::mime_content_type($filename);

        return array( 'file'=>JPATH_BASE.DS.'attachments'.DS.$rootdir.DS. File::makefilename( $id ), 'file_id'=>$id) ;
    }

    public static function upload( $newnameforfile = false, $rootdir = false, $fileid = false, $show_result = true ) {

        self::send_headers();

// Settings
        $targetDir = JPATH_BASE.DS.'tmp' ;
        $maxFileAge = 60 * 60; // Temp file age in seconds

// Get parameters
        $chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
        $chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
        $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

// Clean the fileName for security reasons
        $fileName = preg_replace('/[^\w\._]+/', '', $fileName);
        //$fileName = sprintf("%u",crc32( $fileName ));

// Create target dir
        if (!file_exists($targetDir)) {
            mkdir($targetDir,0755,true);
        }

// Remove old temp files
        if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
            while (($file = readdir($dir)) !== false) {
                $filePath = $targetDir . DIRECTORY_SEPARATOR . $file;

// Remove temp files if they are older than the max age
                if (preg_match('/\\.tmp$/', $file) && (filemtime($filePath) < time() - $maxFileAge)) {
                    @unlink($filePath);
                }
            }

            closedir($dir);
        } else
            die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');

// Look for the content type header
        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
            $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

        if (isset($_SERVER["CONTENT_TYPE"]))
            $contentType = $_SERVER["CONTENT_TYPE"];

        if (strpos($contentType, "multipart") !== false) {
            if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
// Open temp file
                $out = fopen($targetDir . DS . $fileName, $chunk == 0 ? "wb" : "ab");
                if ($out) {
// Read binary input stream and append it to temp file
                    $in = fopen($_FILES['file']['tmp_name'], "rb");

                    if ($in) {
                        while ($buff = fread($in, 4096))
                            fwrite($out, $buff);
                    } else
                        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

                    fclose($out);
                    unlink($_FILES['file']['tmp_name']);
                } else
                    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            } else
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
        } else {
// Open temp file
            $targetfile = $targetDir . DS . $fileName;
            $out = fopen($targetfile, $chunk == 0 ? "wb" : "ab");
            if ($out) {
// Read binary input stream and append it to temp file
                $in = fopen("php://input", "rb");

                if ($in) {
                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                    fclose($out);
                    if( is_file($targetfile) ) {
                        mosMainFrame::addLib('files');

                        if( $newnameforfile ) {
                            $filedata = File::ext($targetfile);
                            $fileName = $newnameforfile.'.'.$filedata['extension'];
                        }

                        $file = new File( 0755 );
                        $file_upload = self::get_filefolder( $rootdir, $targetfile, $fileid );
                        $file_pach = $file_upload['file'].DS.$fileName;
                        $file->move($targetfile, $file_pach );
                    }
                } else
                    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            } else
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        $file_live = str_replace( JPATH_BASE , '', $file_pach);
        $file_live = str_replace('\\', '/', $file_live);
        $file_location = str_replace( $fileName , '', $file_live );
        echo $show_result ? '{"jsonrpc" : "2.0", "result" : "'.$file_live.'", "id" : "id"}' : '';
        return array( 'basename'=>$file_pach, 'livename'=>$file_live, 'location'=>$file_location, 'file_id'=>$file_upload['file_id'], 'file_name'=>$fileName );
    }

}

