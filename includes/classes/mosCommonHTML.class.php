<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class mosCommonHTML {

	public static function ContentLegend() {
		$cur_file_icons_path = JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE.'/images/ico';
		?>
<table cellspacing="0" cellpadding="4" border="0" align="center">
	<tr align="center">
		<td><img src="<?php echo $cur_file_icons_path;?>/publish_g.png" alt="<?php echo _PUBLISHED_AND_ACTIVE?>" border="0" /></td>
		<td><?php echo _PUBLISHED_AND_ACTIVE?> |</td>
		<td><img src="<?php echo $cur_file_icons_path;?>/publish_x.png" alt="<?php echo _UNPUBLISHED?>" border="0" /></td>
		<td><?php echo _UNPUBLISHED?></td>
		<td><img src="<?php echo $cur_file_icons_path;?>/publish_r.png" alt="<?php echo _PUBLISHED_BUT_DATE_EXPIRED?>" border="0" /></td>
		<td><?php echo _PUBLISHED_BUT_DATE_EXPIRED?> |</td>
		<td><img src="<?php echo $cur_file_icons_path;?>/publish_y.png" alt="<?php echo _PUBLISHED_VUT_NOT_ACTIVE?>" border="0" /></td>
		<td><?php echo _PUBLISHED_VUT_NOT_ACTIVE?> |</td>
	</tr>
</table>
		<?php
	}

	public static function menuLinksContent(&$menus) {
		?>
<script language="javascript" type="text/javascript">
	function go2( pressbutton, menu, id ) {
		var form = document.adminForm;
		// assemble the images back into one field
		var temp = new Array;
		for (var i=0, n=form.imagelist.options.length; i < n; i++) {
			temp[i] = form.imagelist.options[i].value;
		}
		form.images.value = temp.join( '\n' );

		if (pressbutton == 'go2menu') {
			form.menu.value = menu;
			submitform( pressbutton );
			return;
		}

		if (pressbutton == 'go2menuitem') {
			form.menu.value		 = menu;
			form.menuid.value		 = id;
			submitform( pressbutton );
			return;
		}
	}
</script>
		<?php
		foreach($menus as $menu) {
			?>
<tr>
	<td colspan="2">
		<hr />
	</td>
</tr>
<tr>
	<td width="90px" valign="top"><?php echo _MENU?></td>
	<td><a href="javascript:go2( 'go2menu', '<?php echo $menu->menutype; ?>' );"><?php echo $menu->menutype; ?></a></td>
</tr>
<tr>
	<td width="90px" valign="top"><?php echo _LINK_NAME?></td>
	<td>
		<strong><a href="javascript:go2( 'go2menuitem', '<?php echo $menu->menutype; ?>', '<?php echo $menu->id; ?>' );" ><?php echo $menu->name; ?></a></strong>
	</td>
</tr>
<tr>
	<td width="90px" valign="top"><?php echo _E_STATE?></td>
	<td>
					<?php
					switch($menu->published) {
						case - 2:
							echo '<font color="red">'._MENU_EXPIRED.'</font>';
							break;
						case 0:
							echo _UNPUBLISHED;
							break;
						case 1:
						default:
							echo '<font color="green">'._PUBLISHED.'</font>';
							break;
					}
					?>
	</td>
</tr>
			<?php
		}
		?>
<input type="hidden" name="menu" value="" />
<input type="hidden" name="menuid" value="" />
		<?php
	}

	public static function menuLinksSecCat(&$menus) {
		?>
<script language="javascript" type="text/javascript">
	function go2( pressbutton, menu, id ) {
		var form = document.adminForm;

		if (pressbutton == 'go2menu') {
			form.menu.value = menu;
			submitform( pressbutton );
			return;
		}

		if (pressbutton == 'go2menuitem') {
			form.menu.value		 = menu;
			form.menuid.value	 = id;
			submitform( pressbutton );
			return;
		}
	}
</script>
		<?php foreach($menus as $menu) { ?>
<tr>
	<td colspan="2"><hr /></td>
</tr>
<tr>
	<td width="90px" valign="top"><?php echo _MENU?></td>
	<td><a href="javascript:go2( 'go2menu', '<?php echo $menu->menutype; ?>' );" ><?php echo $menu->menutype; ?></a></td>
</tr>
<tr>
	<td width="90px" valign="top"><?php echo _TYPE?></td>
	<td><?php echo $menu->type; ?></td>
</tr>
<tr>
	<td width="90px" valign="top"><?php echo _MENU_ITEM_NAME?></td>
	<td>
		<strong>
			<a href="javascript:go2( 'go2menuitem', '<?php echo $menu->menutype; ?>', '<?php echo $menu->id; ?>' );"><?php echo $menu->name; ?></a>
		</strong>
	</td>
</tr>
<tr>
	<td width="90px" valign="top"><?php echo _E_STATE?></td>
	<td>
					<?php
					switch($menu->published) {
						case - 2:
							echo '<font color="red">'._MENU_EXPIRED.'</font>';
							break;
						case 0:
							echo _UNPUBLISHED;
							break;
						case 1:
						default:
							echo '<font color="green">'._PUBLISHED.'</font>';
							break;
					}
					?>
	</td>
</tr>
			<?php } ?>
<input type="hidden" name="menu" value="" />
<input type="hidden" name="menuid" value="" />
		<?php
	}

	public static function checkedOut(&$row,$overlib = 1) {
		$hover = '';
		if($overlib) {
			$date = mosFormatDate($row->checked_out_time,'%A, %d %B %Y');
			$time = mosFormatDate($row->checked_out_time,'%H:%M');
			$editor = addslashes(htmlspecialchars(html_entity_decode($row->editor,ENT_QUOTES)));
			$checked_out_text = '<table>';
			$checked_out_text = '<tr><td>'.$editor.'</td></tr>';
			$checked_out_text .= '<tr><td>'.$date.'</td></tr>';
			$checked_out_text .= '<tr><td>'.$time.'</td></tr>';
			$checked_out_text .= '</table>';
			$hover = 'onMouseOver="return overlib(\''.$checked_out_text.'\', CAPTION, \''._CHECKED_OUT.'\', BELOW, RIGHT);" onMouseOut="return nd();"';
		}
		$cur_file_icons_path = JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE.'/images/ico';
		return '<img src="'.$cur_file_icons_path.'/checked_out.png" '.$hover.'/>';
	}

	public static function loadOverlib($ret = false) {
		if(!defined('_LOADOVERLIB')) {
			// установка флага о загруженной библиотеке всплывающих подсказок
			define('_LOADOVERLIB',1);
			MosMainFrame::getInstance()->addJS(JPATH_SITE.'/includes/js/overlib_full.js');
			return true;
		}

		if( $ret ) {
			echo JHTML::js_file( JPATH_SITE.'/includes/js/overlib_full.js' );
		}
	}

	public static function loadCalendar() {
		if(!defined('_CALLENDAR_LOADED')) {
			define('_CALLENDAR_LOADED',1);
			$mainframe = MosMainFrame::getInstance();
			$mainframe->addCSS(JPATH_SITE.'/includes/js/calendar/calendar.css');
			$mainframe->addJS(JPATH_SITE.'/includes/js/calendar/calendar.js');
			$_lang_file = JPATH_BASE.'/includes/js/calendar/lang/calendar-'._LANGUAGE.'.js';
			$_lang_file = (is_file($_lang_file)) ? JPATH_SITE.'/includes/js/calendar/lang/calendar-'._LANGUAGE.'.js' : JPATH_SITE.'/includes/js/calendar/lang/calendar-ru.js';
			$mainframe->addJS($_lang_file);
		}
	}

	public static function loadJquery($ret = false) {
		if(!defined('_JQUERY_LOADED')) {
			define('_JQUERY_LOADED',1);
			if($ret) {
				echo JHTML::js_file( JPATH_SITE.'/media/js/jquery.js' );
			}else {
			 Jdocument::getInstance()->addJS(JPATH_SITE.'/media/js/jquery.js', array('first'=>true) );
			}
		}
	}

	public static function loadJqueryUI($ret = false) {
		if(!defined('_JQUERY_UI_LOADED')) {
			define('_JQUERY_UI_LOADED',1);
			if($ret) {
				echo JHTML::js_file( JPATH_SITE.'/media/js/jquery.ui/jquery-ui.js' );
			}else {
				Jdocument::getInstance()->addJS(JPATH_SITE.'/media/js/jquery.ui/jquery-ui.js');
			}
		}
	}

	public static function loadJqueryUICSS($ret = false, $theme='ui-lightness') {
		if(!defined('_JQUERY_UICSS_LOADED')) {
			define('_JQUERY_UICSS_LOADED',1);
			if($ret) {
				echo JHTML::css_file( JPATH_SITE.'/media/js/jquery.ui/themes/'.$theme.'/jquery-ui.css' );
			}else {
				Jdocument::getInstance()->addCSS(JPATH_SITE.'/media/js/jquery.ui/themes/'.$theme.'/jquery-ui.css');
			}
		}
	}

	public static function AccessProcessing(&$row,$i,$ajax=null) {
		if(!$row->access) {
			$color_access = 'style="color: green;"';
			$task_access = 'accessregistered';
		} elseif($row->access == 1) {
			$color_access = 'style="color: red;"';
			$task_access = 'accessspecial';
		} else {
			$color_access = 'style="color: black;"';
			$task_access = 'accesspublic';
		}
		if(!$ajax) {
			$href = '<a href="javascript: void(0);" onclick="return listItemTask(\'cb'.$i.'\',\''.$task_access.'\')" '.$color_access.'>'.$row->groupname.'</a>';
		}else {
			$option = strval(mosGetParam($_REQUEST,'option',''));
			$href = '<a href="#" onclick="ch_access('.$row->id.',\''.$task_access.'\',\''.$option.'\');" '.$color_access.'>'.$row->groupname.'</a>';
		}
		return $href;
	}

	public static function CheckedOutProcessing(&$row,$i) {
		if($row->checked_out) {
			$checked = mosCommonHTML::checkedOut($row);
		} else {
			global $my;
			$checked = mosHTML::idBox($i,$row->id,($row->checked_out && $row->checked_out !=$my->id));
		}
		return $checked;
	}

	public static function PublishedProcessing(&$row,$i) {
		$cur_file_icons_path = JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE.'/images/ico';
		$img = $row->published ? 'publish_g.png':'publish_x.png';
		$task = $row->published ? 'unpublish':'publish';
		$alt = $row->published ? _PUBLISHED:_UNPUBLISHED;
		$action = $row->published ? _HIDE:_PUBLISH_ON_FRONTPAGE;
		return '<a href="javascript: void(0);" onclick="return listItemTask(\'cb'.$i.'\',\''.$task.'\')" title="'.$action.'"><img src="'.$cur_file_icons_path.'/'.$img.'" border="0" alt="'.$alt.'" /></a>';
	}

	public static function get_element($file) {

		$file_templ = 'templates/'.JTEMPLATE.'/images/elements/'.$file;
		$file_system = 'M_images/'.$file;

		$return = $file_templ;
		if(!is_file(JPATH_BASE.DS.$file_templ)) {
			$return = $file_system;
		}

		return $return;
	}


	/**
	 * @param string SQL with ordering As value and 'name field' AS text
	 * @param integer The length of the truncated headline
	 */
	public static function mosGetOrderingList($sql,$chop = '30') {
		$database = database::getInstance();

		$order = array();
		$database->setQuery($sql);
		if(!($orders = $database->loadObjectList())) {
			if($database->getErrorNum()) {
				echo $database->stderr();
				return false;
			} else {
				$order[] = mosHTML::makeOption(1,_FIRST);
				return $order;
			}
		}
		$order[] = mosHTML::makeOption(0,'0 '._FIRST);
		for($i = 0,$n = count($orders); $i < $n; $i++) {
			if(strlen($orders[$i]->text) > $chop) {
				$text = Jstring::substr($orders[$i]->text,0,$chop)."...";
			} else {
				$text = $orders[$i]->text;
			}
			$order[] = mosHTML::makeOption($orders[$i]->value,$orders[$i]->value.' ('.$text.')');
		}
		$order[] = mosHTML::makeOption($orders[$i - 1]->value + 1,($orders[$i - 1]->value +1).' '._LAST);
		return $order;
	}
}