<?php
/**
 * @JoostFREE
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

if(!class_exists('mosMenuBar')) {
	class mosMenuBar {

		public static function startTable() {
			?><div id="toolbar"><ul><?php
				}

				public static function ext($alt = _BUTTON,$href = '',$class = '',$extra = '') {
					?><li><a class="tb-ext<?php echo $class; ?>" href="<?php echo $href; ?>" <?php echo $extra; ?>><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function custom($task = '',$icon = '',$iconOver = '',$alt = '',$listSelect = true) {
					if($listSelect) {
						$href = "javascript:if (document.adminForm.boxchecked.value == 0){ alert('"._PLEASE_CHOOSE_ELEMENT."');}else{submitbutton('$task')}";
					} else {
						$href = "javascript:submitbutton('$task')";
					}
					?><li><a class="tb-custom<?php echo $icon; ?>" href="<?php echo $href; ?>"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function customX($task = '',$class = '',$iconOver = '',$alt = '',$listSelect = true) {
					if($listSelect) {
						$href = "javascript:if (document.adminForm.boxchecked.value == 0){ alert('"._PLEASE_CHOOSE_ELEMENT."');}else{submitbutton('$task')}";
					} else {
						$href = "javascript:submitbutton('$task')";
					}
					?><li><a class="tb-custom-x<?php echo $class; ?>" href="<?php echo $href; ?>"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function addNew($task = 'new',$alt = _NEW) {
					?><li><a class="tb-add-new" href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function addNewX($task = 'new',$alt = _NEW) {
					?><li><a class="tb-add-new-x" href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function publish($task = 'publish',$alt = _SHOW) {
					?><li><a class="tb-publish" href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function publishList($task = 'publish',$alt = _SHOW) {
					?><li><a class="tb-publish-list" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_FOR_PUBLICATION?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function makeDefault($task = 'default',$alt = _DEFAULT) {
					?><li><a class="tb-makedefault" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_MAKE_DEFAULT?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function assign($task = 'assign',$alt = _ASSIGN) {
					?><li><a class="tb-assign" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_ASSIGN?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function unpublish($task = 'unpublish',$alt = _HIDE) {
					?><li><a class="tb-unpublish" href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function unpublishList($task = 'unpublish',$alt = _HIDE) {
					?><li><a class="tb-unpublish-list" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_UNPUBLISH?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function archiveList($task = 'archive',$alt = _TO_ARCHIVE) {
					?><li><a class="tb-archive-list" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_ARCHIVE?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function unarchiveList($task = 'unarchive',$alt = _FROM_ARCHIVE) {
					?><li><a class="tb-unarchive-list" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_UNARCHIVE?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function editList($task = 'edit',$alt = _EDIT) {
					?><li><a class="tb-edit-list" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_EDIT?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function editListX($task = 'edit',$alt = _EDIT) {
					?><li><a class="tb-edit-list-x" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_EDIT?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function editHtml($task = 'edit_source',$alt = _EDIT_HTML) {
					?><li><a class="tb-edit-html" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_EDIT?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function editHtmlX($task = 'edit_source',$alt = _EDIT_HTML) {
					?><li><a class="tb-edit-html-x" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_EDIT?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function editCss($task = 'edit_css',$alt = _EDIT_CSS) {
					?><li><a class="tb-edit-css" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_EDIT?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function editCssX($task = 'edit_css',$alt = _EDIT_CSS) {
					?><li><a class="tb-edit-css-x" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_EDIT?>'); } else {submitbutton('<?php echo $task; ?>', '');}"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function deleteList($msg = '',$task = 'remove',$alt = _DELETE) {
					?><li><a class="tb-delete-list" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_DELETE?>'); } else if (confirm('<?php echo _REALLY_WANT_TO_DELETE_OBJECTS?> <?php echo $msg; ?>')){ submitbutton('<?php echo $task; ?>');}"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function deleteListX($msg = '',$task = 'remove',$alt = _DELETE) {
					?><li><a class="tb-delete-list-x" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('<?php echo _PLEASE_CHOOSE_ELEMENT_TO_DELETE?>'); } else if (confirm('<?php echo _REALLY_WANT_TO_DELETE_OBJECTS?> <?php echo $msg; ?>')){ submitbutton('<?php echo $task; ?>');}"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function trash($task = 'remove',$alt = _REMOVE_TO_TRASH,$check = true) {
					if($check) {
						$js = "javascript:if (document.adminForm.boxchecked.value == 0){ alert('"._PLEASE_CHOOSE_ELEMENT_TO_TRASH."'); } else { submitbutton('$task');}";
					} else {
						$js = "javascript:submitbutton('$task');";
					}
					?><li><a class="tb-trash" href="<?php echo $js; ?>"><span><?php echo $alt; ?></span></a></li><?php
				}


				public static function help($ref,$com = false) {
					return '';
				}

				public static function apply($task = 'apply',$alt = _APPLY) {
						?><li><a class="tb-apply" href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo $alt; ?></span></a></li><?php
					}

					public static function save($task = 'save',$alt = _SAVE) {
					?><li><a class="tb-save" href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function savenew() {
					?><li><a class="tb-save-new" href="javascript:submitbutton('savenew');"><span><?php echo _SAVE?></span></a></li><?php
				}

				public static function saveedit() {
					?><li><a class="tb-save-edit" href="javascript:submitbutton('saveedit');"><span><?php echo _SAVE?></span></a></li><?php
				}

				public static function cancel($task = 'cancel',$alt = _CANCEL) {
					?><li><a class="tb-cancel" href="javascript:submitbutton('<?php echo $task; ?>');"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function back($alt = _MENU_BACK,$href = false) {
					$link = $href ? $href : 'javascript:window.history.back();';
					?><li><a class="tb-back" href="<?php echo $link; ?>"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function divider() {
					?><li>&nbsp;|&nbsp;</li><?php
				}

				public static function media_manager($directory = '',$alt = _TASK_UPLOAD) {
					?><li><a class="tb-media-manager" href="#" onclick="popupWindow('popups/uploadimage.php?directory=<?php echo $directory; ?>&amp;t=<?php echo JTEMPLATE; ?>','win1',250,100,'no');"><span><?php echo $alt; ?></span></a></li><?php
				}

				public static function spacer($width = '0') {
					return '';
				}

				public static function endTable() {
					?></ul></div><?php
		}
	}


}