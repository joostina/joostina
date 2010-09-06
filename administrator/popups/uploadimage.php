<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

define("_VALID_MOS",1);

/** проверка безопасности*/
require ('../includes/auth.php');

$mainframe = mosMainFrame::getInstance(true);
$mainframe->set('lang', $mosConfig_lang);
include_once($mainframe->getLangFile());

/*
* Stops file upload below /images/stories directory
* Added 1.0.11
*/
function limitDirectory(&$directory) {
	if(strpos($directory,'../') !== false) {
		$directory = str_replace('../','',$directory);
	}

	if(strpos($directory,'..\\') !== false) {
		$directory = str_replace('..\\','',$directory);
	}

	if(strpos($directory,':') !== false) {
		$directory = str_replace(':','',$directory);
	}

	return $directory;
}

// limit access to functionality
$option = strval(mosGetParam($_SESSION,'option',''));
$task = strval(mosGetParam($_SESSION,'task',''));

switch($option) {
	case 'com_categories':
	case 'com_content':
	case 'com_sections':
	case 'com_typedcontent':
		if($task != 'edit' && $task != 'editA' && $task != 'new') {
			echo _NOT_AUTH;
			return;
		}
		break;

	default:
		echo _NOT_AUTH;
		return;
		break;
}
// mainframe is an API workhorse, lots of 'core' interaction routines
$mainframe = new mosMainFrame( $database, $option, JPATH_BASE, true );
$directory = mosGetParam($_REQUEST,'directory','');
$css = mosGetParam($_REQUEST,'t','');

$media_path = JPATH_BASE.'/media/';

$userfile2 = (isset($_FILES['userfile']['tmp_name'])?$_FILES['userfile']['tmp_name']:"");
$userfile_name = (isset($_FILES['userfile']['name'])?$_FILES['userfile']['name']:"");

limitDirectory($directory);

// check to see if directory exists
if($directory != 'banners' && $directory != '' && !is_dir(JPATH_BASE.'/images/stories/'.$directory)) {
	$directory = '';
}

$action = "window.location.href = 'uploadimage.php?directory=$directory&amp;t=$css'";

if(isset($_FILES['userfile'])) {
	if($directory == 'banners') {
		$base_Dir = "../../images/banners/";
	} elseif($directory != '') {
		$base_Dir = '../../images/stories/'.$directory;

		if(!is_dir(JPATH_BASE.'/images/stories/'.$directory)) {
			$base_Dir = '../../images/stories/';
			$directory = '';
		}
	} else {
		$base_Dir = '../../images/stories/';
	}

	if(empty($userfile_name)) {
		mosErrorAlert(_CHOOSE_IMAGE_FOR_UPLOAD,$action);
	}

	$filename = split("\.",$userfile_name);

	if(eregi("[^0-9a-zA-Z_]",$filename[0])) {
		mosErrorAlert(_BAD_UPLOAD_FILE_NAME,$action);
	}

	if(file_exists($base_Dir.$userfile_name)) {
		mosErrorAlert($userfile_name.' '._IMAGE_ALREADY_EXISST,$action);
	}

	if((strcasecmp(substr($userfile_name,-4),'.gif')) && (strcasecmp(substr($userfile_name,-4),'.jpg')) && (strcasecmp(substr($userfile_name,-4),'.png')) && (strcasecmp(substr($userfile_name,-4),'.bmp')) && (strcasecmp(substr($userfile_name,-4),'.doc')) &&(strcasecmp(substr($userfile_name,-4),'.xls')) && (strcasecmp(substr($userfile_name,-4),'.ppt')) && (strcasecmp(substr($userfile_name,-4),'.swf')) && (strcasecmp(substr($userfile_name,-4),'.pdf'))) {
		mosErrorAlert(_FILE_MUST_HAVE_THIS_EXTENSION.' gif, png, jpg, bmp, swf, doc, xls, ppt',$action);
	}

	mosMainFrame::addLib('files');

	if(eregi('.pdf',$userfile_name) || eregi('.doc',$userfile_name) || eregi('.xls',$userfile_name) || eregi('.ppt',$userfile_name)) {
		if(!move_uploaded_file($_FILES['userfile']['tmp_name'],$media_path.$_FILES['userfile']['name']) || !mosChmod($media_path.$_FILES['userfile']['name'])) {
			mosErrorAlert(_FILE_UPLOAD_UNSUCCESS.' '.$userfile_name,$action);
		} else {
			mosErrorAlert(_FILE_UPLOAD_SUCCESS.' '.$userfile_name.' - '.$base_Dir,"window.close()");//
		}
	} elseif(!move_uploaded_file($_FILES['userfile']['tmp_name'],$base_Dir.$_FILES['userfile']['name']) || !mosChmod($base_Dir.$_FILES['userfile']['name'])) {
		mosErrorAlert(_FILE_UPLOAD_UNSUCCESS.' '.$userfile_name,$action);
	} else {
		mosErrorAlert(_FILE_UPLOAD_SUCCESS.' '.$userfile_name.' - '.$base_Dir,"window.close()");
	}
	echo $base_Dir.$_FILES['userfile']['name'];
}

// css file handling
// check to see if template exists
if($css != '' && !is_dir(JPATH_BASE_ADMIN.'/templates/'.$css.'/css/template_css.css')) {
	$css = 'joostfree';
} else
if($css == '') {
	$css = 'joostfree';
}

$iso = split('=',_ISO);
// xml prolog
echo '<?xml version="1.0" encoding="'.$iso[1].'"?'.'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo _FILE_UPLOAD?></title>
		<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
	</head>
	<body>

		<link rel="stylesheet" href="../templates/<?php echo $css; ?>/css/template_css.css" type="text/css" />
		<form method="post" action="uploadimage.php" enctype="multipart/form-data" name="filename" id="filename">
			<table class="adminform">
				<tr>
					<th class="title">
						<?php echo _FILE_UPLOAD?>: <?php echo $directory; ?>
					</th>
				</tr>
				<tr>
					<td align="center">
						<input class="inputbox" name="userfile" type="file" /><input class="button" type="submit" value="<?php echo _TASK_UPLOAD?>" name="fileupload" />
					</td>
				</tr>
				<tr>
					<td><?php echo _MAX_SIZE?> = <?php echo ini_get('post_max_size'); ?></td>
				</tr>
			</table>
			<input type="hidden" name="directory" value="<?php echo $directory; ?>" />
			<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
	</body>
</html>
