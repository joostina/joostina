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

class mosToolBar {

	public static function startTable() {
		?><table border="0" id="toolbar">	<tr valign="middle" align="center"><?php
			}

			public static function custom($task = '',$icon = null,$iconOver = '',$alt = '',$listSelect = true) {
				if($listSelect) {
					$href = "javascript:if (document.adminForm.boxchecked.value == 0){ alert('Выберите объект из списка для $alt');}else{submitbutton('$task')}";
				} else {
					$href = "javascript:submitbutton('$task')";
				}
				?><td><a class="toolbar" href="<?php echo $href; ?>" ><img name="<?php echo $task; ?>" src="images/system/<?php echo $iconOver; ?>" alt="<?php echo $alt; ?>" title="<?php echo $alt; ?>" border="0" /></a></td><?php
			}
			
			public static function addNew($task = 'new',$alt = _NEW) {
				$image = mosAdminMenus::ImageCheck('new_f2.png','/images/system/',null,null,$alt,$task,1,'middle',$alt);
						?><td><a class="toolbar" href="javascript:submitbutton('<?php echo $task; ?>');" ><?php echo $image; ?></a></td><?php
					}

					public static function publish($task = 'publish',$alt = _PUBLISHED) {
						$image = mosAdminMenus::ImageCheck('publish_f2.png','/images/system/',null,null,$alt,$task,1,'middle',$alt);
				?><td><a class="toolbar" href="javascript:submitbutton('<?php echo $task; ?>');" ><?php echo $image; ?></a></td><?php
			}

			public static function publishList($task = 'publish',$alt = _PUBLISHED) {
				$image = mosAdminMenus::ImageCheck('publish_f2.png','/images/system/',null,null,$alt,$task,1,'middle',$alt);
				?><td><a class="toolbar" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _SELECT_OBJ_FOR_PUB?>'); } else {submitbutton('<?php echo $task; ?>', '');}" ><?php echo $image; ?></a></td><?php
			}

			public static function unpublish($task = 'unpublish',$alt = _UNPUBLISHED) {
				$image = mosAdminMenus::ImageCheck('unpublish_f2.png','/images/system/',null,null,$alt,$task,1,'middle',$alt);
				?><td><a class="toolbar" href="javascript:submitbutton('<?php echo $task; ?>');" ><?php echo $image; ?></a></td><?php
			}

			public static function unpublishList($task = 'unpublish',$alt = _UNPUBLISHED) {
				$image = mosAdminMenus::ImageCheck('unpublish_f2.png','/images/system/',null,null,$alt,$task,1,'middle',$alt);
				?><td><a class="toolbar" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Выберите объект из списка для отмены его публикации'); } else {submitbutton('<?php echo $task; ?>', '');}" ><?php echo $image; ?></a></td><?php
			}

			public static function archiveList($task = 'archive',$alt = _CMN_ARCHIVE) {
				$image = mosAdminMenus::ImageCheck('archive_f2.png','/images/system/',null,null,$alt,$task,1,'middle',$alt);
				?><td><a class="toolbar" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Выберите объект из списка для перемещения в архив'); } else {submitbutton('<?php echo $task; ?>', '');}" ><?php echo $image; ?></a></td><?php
			}

			public static function unarchiveList($task = 'unarchive',$alt = _CMN_UNARCHIVE) {
				$image = mosAdminMenus::ImageCheck('unarchive_f2.png','/images/system/',null,null,$alt,$task,1,'middle',$alt);
				?><td><a class="toolbar" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Выберите материал для восстановления его из архива'); } else {submitbutton('<?php echo $task; ?>', '');}" ><?php echo $image; ?></a></td><?php
			}

			public static function editList($task = 'edit',$alt = _EDIT) {
				$image = mosAdminMenus::ImageCheck('edit_f2.png','/images/system/',null,null,$alt,$task,1,'middle',$alt);
				?><td><a class="toolbar" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Выберите объект из списка для его редактирования'); } else {submitbutton('<?php echo $task; ?>', '');}" ><?php echo $image; ?></a></td><?php
			}

			public static function editHtml($task = 'edit_source',$alt = _CMN_EDIT_HTML) {
				$image = mosAdminMenus::ImageCheck('edit_f2.png','/images/system/',null,null,$alt,$task,1,'middle',$alt);
				?><td><a class="toolbar" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Выберите объект из списка для его редактирования'); } else {submitbutton('<?php echo $task; ?>', '');}" ><?php echo $image; ?></a></td><?php
			}

			public static function editCss($task = 'edit_css',$alt = _CMN_EDIT_CSS) {
				$image = mosAdminMenus::ImageCheck('css_f2.png','/images/system/',null,null,$alt,$task,1,'middle',$alt);
				?><td><a class="toolbar" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Выберите объект из списка для его редактирования'); } else {submitbutton('<?php echo $task; ?>', '');}" ><?php echo $image; ?></a></td><?php
			}

			public static function deleteList($msg = '',$task = 'remove',$alt = _DELETE) {
				$image = mosAdminMenus::ImageCheck('delete_f2.png','/images/system/',null,null,$alt,$task,1,'middle',$alt);
				?><td><a class="toolbar" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Выберите объект из списка для его удаления'); } else if (confirm('Вы действительно хотите удалить выбранные объекты? <?php echo $msg; ?>')){ submitbutton('<?php echo $task; ?>');}"><?php echo $image; ?></a></td><?php
			}

			public static function preview($popup = '') {
				$image = mosAdminMenus::ImageCheck('preview_f2.png','images/system/',null,null,'Просмотр','preview',1);
				?><td><a class="toolbar" href="#" onclick="window.open('popups/<?php echo $popup; ?>.php?t=<?php echo JTEMPLATE; ?>', 'win1', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');" ><?php echo $image; ?></a></td><?php
			}

			public static function save($task = 'save',$alt = _SAVE) {
				$image = mosAdminMenus::ImageCheck('save_f2.png','/images/system/',null,null,$alt,$task,1,'middle',$alt);
				?><td><a class="toolbar" href="javascript:submitbutton('<?php echo $task; ?>');" ><?php echo $image; ?></a></td><?php
			}

			public static function apply($task = 'apply',$alt = _APPLY) {
				$image = mosAdminMenus::ImageCheck('apply_f2.png','/images/system/',null,null,$alt,$task,1,'middle',$alt);
				?><td><a class="toolbar" href="javascript:submitbutton('<?php echo $task; ?>');" ><?php echo $image; ?></a></td><?php
			}

			public static function savenew() {
				$image = mosAdminMenus::ImageCheck('save_f2.png','/images/system/',null,null,'save','save',1);
				?><td><a class="toolbar" href="javascript:submitbutton('savenew');" ><?php echo $image; ?></a></td><?php
			}

			public static function saveedit() {
				$image = mosAdminMenus::ImageCheck('save_f2.png','/images/system/',null,null,'save','save',1);
				?><td><a class="toolbar" href="javascript:submitbutton('saveedit');" ><?php echo $image; ?></a></td><?php
			}

			public static function cancel($task = 'cancel',$alt = _CANCEL) {
				$image = mosAdminMenus::ImageCheck('cancel_f2.png','/images/system/',null,null,$alt,$task,	1,'middle',$alt);
				?><td><a class="toolbar" href="javascript:submitbutton('<?php echo $task; ?>');" ><?php echo $image; ?></a></td><?php
			}

			public static function back() {
				$image = mosAdminMenus::ImageCheck('back_f2.png','/images/system/',null,null,'back','cancel',1);
				?><td><a class="toolbar" href="javascript:window.history.back();" ><?php echo $image; ?></a></td><?php
			}

			public static function divider() {
				$image = mosAdminMenus::ImageCheck('menu_divider.png','/images/system/');
				?><td><?php echo $image; ?></td><?php
			}

			public static function media_manager($directory = '') {
				$image = mosAdminMenus::ImageCheck('upload_f2.png','/images/system/',null,null,_UPLOAD_FILE,'uploadPic',1);
				?><td><a class="toolbar" href="#" onclick="popupWindow('popups/uploadimage.php?directory=<?php echo $directory; ?>','win1',250,100,'no');"><?php echo $image; ?></a></td><?php
			}

			public static function spacer($width = '') {
				if($width != '') {
					?><td width="<?php echo $width; ?>">&nbsp;</td><?php
				} else {
					?><td>&nbsp;</td><?php
				}
			}

			public static function endTable() {
				?></tr></table><?php
	}
}