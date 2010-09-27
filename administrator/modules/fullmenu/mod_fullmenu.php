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

class mosFullAdminMenu {

	public static function show($groupname = '') {
		global $my;

		$database = database::getInstance();
		$config = Jconfig::getInstance();

		echo '<div id="myMenuID"></div>'; // в этот слой выводится содержимое меню
		if ($config->config_adm_menu_cache) { // проверяем, активировано ли кэширование в панели управления
			$groupname = $my->groupname;
			$groupname_menu = str_replace(' ', '_', $groupname);
			// название файла меню получим из md5 хеша типа пользователя и секретного слова конкретной установки
			$menuname = md5($groupname_menu . $config->config_secret);
			echo '<script type="text/javascript" src="' . JPATH_SITE . '/cache/adm_menu_' . $menuname . '.js?r=' . $config->config_cache_key . '"></script>';
			if (js_menu_cache('', $groupname_menu, 1) == 'true') { // файл есть, выводим ссылку на него и прекращаем работу
				return; // дальнейшую обработку меню не ведём
			} // файла не было - генерируем его, создаём и всё равно возвращаем ссылку
		}

		$canConfig = $manageTemplates = $manageTrash = $manageMenuMan = $manageLanguages = $installModules = $editAllModules = $installComponents = $editAllComponents = $canMassMail = $canManageUsers = true;

		$menuTypes = mosAdminMenus::menutypes();


		// получеполучаем каталог с графикой верхнего меню
		$cur_file_icons_path = JPATH_SITE . '/' . JADMIN_BASE . '/images/menu/old_ico/';

		ob_start(); // складываем всё выдаваемое меню в буфер
?>
		var myMenu =[
		[null,'<?php echo _SITE ?>',null,null,'<?php echo _MOD_FULLMENU_CMS_FEATURES ?>',
<?php
		if ($canConfig) {
?>['<img src="<?php echo $cur_file_icons_path ?>config.png" />','<?php echo _GLOBAL_CONFIG ?>','index2.php?option=config&hidemainmenu=1',null,'<?php echo _GLOBAL_CONFIG_TIP ?>'],
<?php
		}
		if ($manageLanguages) {
?>['<img src="<?php echo $cur_file_icons_path ?>language.png" />','<?php echo _LANGUAGE_PACKS ?>','index2.php?option=languages',null,'<?php echo _LANGUAGE_PACKS_TIP ?>',

			],
<?php
		}
?>['<img src="<?php echo $cur_file_icons_path ?>preview.png" />', '<?php echo _MOD_FULLMENU_SITE_PREVIEW ?>', null, null, '<?php echo _MOD_FULLMENU_SITE_PREVIEW ?>',
		['<img src="<?php echo $cur_file_icons_path ?>preview.png" />','<?php echo _BUTTON_LINK_IN_NEW_WINDOW ?>','<?php echo JPATH_SITE; ?>/index.php','_blank','<?php echo JPATH_SITE; ?>'],
		['<img src="<?php echo $cur_file_icons_path ?>preview.png" />','<?php echo _MOD_FULLMENU_SITE_PREVIEW_IN_THIS_WINDOW ?>','index2.php?option=admin&task=preview',null,'<?php echo JPATH_SITE; ?>'],
		['<img src="<?php echo $cur_file_icons_path ?>preview.png" />','<?php echo _MOD_FULLMENU_SITE_PREVIEW_WITH_MODULE_POSITIONS ?>','index2.php?option=admin&task=preview2',null,'<?php echo JPATH_SITE; ?>'],
		],
		['<img src="<?php echo $cur_file_icons_path ?>globe1.png" />', '<?php echo _MOD_FULLMENU_SITE_STATS ?>', null, null, '<?php echo _MOD_FULLMENU_SITE_STATS_TIP ?>',
		['<img src="<?php echo $cur_file_icons_path ?>search_text.png" />', '<?php echo _MOD_FULLMENU_SEARCHES ?>', 'index2.php?option=statistics&task=searches', null, '<?php echo _MOD_FULLMENU_SEARCHES_TIP ?>'],
		],
<?php
		if ($manageTemplates) {
?>['<img src="<?php echo $cur_file_icons_path ?>template.png" />','<?php echo _TEMPLATES ?>',null,null,'<?php echo _MOD_FULLMENU_NEW_SITE_TEMPLATE ?>',
				['<img src="<?php echo $cur_file_icons_path ?>template.png" />','<?php echo _COM_INSTALLER_SITE_TEMPLATES ?>','index2.php?option=templates',null,'<?php echo _COM_INSTALLER_SITE_TEMPLATES ?>'],
				['<img src="<?php echo $cur_file_icons_path ?>template.png" />','<?php echo _COM_INSTALLER_ADMIN_TEMPLATES ?>','index2.php?option=templates&client=admin',null,'<?php echo _COM_INSTALLER_ADMIN_TEMPLATES ?>'],
				_cmSplit,
				['<img src="<?php echo $cur_file_icons_path ?>template.png" />','<?php echo _MOD_FULLMENU_MODULES_POSITION ?>','index2.php?option=templates&task=positions',null,'<?php echo _MOD_FULLMENU_MODULES_POSITION ?>'],
				['<img src="<?php echo $cur_file_icons_path ?>install.png" />','<?php echo _MOD_FULLMENU_NEW_SITE_TEMPLATE ?>','index2.php?option=installer&element=template&client=admin',null,'<?php echo _MOD_FULLMENU_NEW_SITE_TEMPLATE ?>']
				],
<?php
		}
		// Menu Sub-Menu
?>],
<?php if ($canManageUsers || $canMassMail) {
?>[null,'<?php echo _USERS ?>',null,null,'<?php echo _USERS ?>',
				['<img src="<?php echo $cur_file_icons_path ?>user.png" />','<?php echo _MOD_FULLMENU_ALL_USERS ?>','index2.php?option=users&task=view',null,'<?php echo _MOD_FULLMENU_ALL_USERS ?>'],
				['<img src="<?php echo $cur_file_icons_path ?>template.png" />','<?php echo _MOD_FULLMENU_ADD_USER ?>','index2.php?option=users&task=edit',null,'<?php echo _MOD_FULLMENU_ADD_USER ?>'],
				_cmSplit,
				['<img src="<?php echo $cur_file_icons_path ?>config.png" />','<?php echo _MOD_FULLMENU_REGISTER_SETUP ?>','index2.php?option=users&task=config&act=registration',null,'<?php echo _MOD_FULLMENU_REGISTER_SETUP ?>'],
				['<img src="<?php echo $cur_file_icons_path ?>config.png" />','<?php echo _MOD_FULLMENU_PROFILE_SETUP ?>','index2.php?option=users&task=config&act=profile',null,'<?php echo _MOD_FULLMENU_PROFILE_SETUP ?>'],
					['<img src="<?php echo $cur_file_icons_path ?>config.png" />','<?php echo _MOD_FULLMENU_LOSTPASS_SETUP ?>','index2.php?option=users&task=config&act=lostpass',null,'<?php echo _MOD_FULLMENU_LOSTPASS_SETUP ?>']
			],
<?php } ?>

		[null,'<?php echo _MENU ?>',null,null,'<?php echo _MENU ?>',
<?php
		if ($manageMenuMan) {
?>['<img src="<?php echo $cur_file_icons_path ?>menus.png" />','<?php echo _MENU ?>','index2.php?option=menumanager',null,'<?php echo _MENU ?>'],
			_cmSplit,
<?php
		}
		foreach ($menuTypes as $menuType) {
?>['<img src="<?php echo $cur_file_icons_path ?>menus.png" />','<?php echo $menuType; ?>','index2.php?option=menus&menutype=<?php echo $menuType; ?>',null,''],
<?php
		}
		if ($manageTrash) {
?>
			_cmSplit,['<img src="<?php echo $cur_file_icons_path ?>trash.png" />','<?php echo _TRASH ?>','index2.php?option=trash&catid=menu',null,'<?php echo _TRASH ?>'],
<?php } ?>
		],
<?php
		// Components Sub-Menu
		if ($installComponents | $editAllComponents) {
?>
			[null,'<?php echo _COMPONENTS ?>',null,null,'<?php echo _COMPONENTS ?>',
<?php
			$query = "SELECT* FROM #__components ORDER BY title";
			$comps = $database->setQuery($query)->loadObjectList();

			$subs = array(); // sub menus
			// first pass to collect sub-menu items
			foreach ($comps as $row) {
				if ($row->parent) {
					if (!array_key_exists($row->parent, $subs)) {
						$subs[$row->parent] = array();
					}
					$subs[$row->parent][] = $row;
				}
			}
			$topLevelLimit = 19; //You can get 19 top levels on a 800x600 Resolution
			$topLevelCount = 0;
			foreach ($comps as $row) {
				$row->admin_menu_link = 'index2.php?option='.$row->title;
				//if($editAllComponents | $acl->acl_check('administration','edit','users',$groupname,'components',$row->option)) {
				if (true) {
					if ($row->parent == 0 && (trim($row->admin_menu_link) || array_key_exists($row->id, $subs))) {
						$topLevelCount++;
						if ($topLevelCount > $topLevelLimit) {
							continue;
						}
						$name = addslashes($row->title);
						$alt = addslashes( sprintf('Версия %s, выпущено: %s' , $row->version, $row->author ) );
						$link = $row->admin_menu_link ? "'index2.php?$row->admin_menu_link'" : "null";
						echo "\t['<img src=\"" . JPATH_SITE . "/$row->admin_menu_img\" />','$name',$link,null,'$alt'";
						if (array_key_exists($row->id, $subs)) {
							foreach ($subs[$row->id] as $sub) {
								echo ",\n";
								$name = addslashes($sub->name);
								$alt = addslashes($sub->admin_menu_alt);
								$link = $sub->admin_menu_link ? "'index2.php?$sub->admin_menu_link'" : "null";
								echo "['<img src=\"" . JPATH_SITE . "/$sub->admin_menu_img\" />','$name',$link,null,'$alt']";
							}
						}
						echo "],\n";
					}
				}
			}
			if ($topLevelLimit < $topLevelCount) {
				echo "['<img src=\"<?php echo $cur_file_icons_path ?>sections.png\" />','" . _MOD_FULLMENU_ALL_COMPONENTS . "','index2.php?option=admin&task=listcomponents',null,'" . _MOD_FULLMENU_ALL_COMPONENTS . "'],\n";
			}
			if ($installModules) {
?> _cmSplit,
									['<img src="<?php echo $cur_file_icons_path ?>install.png" />', '<?php echo _MOD_FULLMENU_EDIT_COMPONENTS_MENU ?>','index2.php?option=linkeditor ',null,'<?php echo _MOD_FULLMENU_EDIT_COMPONENTS_MENU ?>'],
									['<img src="<?php echo $cur_file_icons_path ?>install.png" />', '<?php echo _MOD_FULLMENU_COMPONENTS_INSTALL_UNINSTALL ?>','index2.php?option=installer&element=component',null,'<?php echo _MOD_FULLMENU_COMPONENTS_INSTALL_UNINSTALL ?>'],
									],
<?php
			}
			// Modules Sub-Menu
			if ($installModules | $editAllModules) {
?>
				[null,'<?php echo _MODULES ?>',null,null,'<?php echo _MOD_FULLMENU_MODULES_SETUP ?>',
<?php
				if ($editAllModules) {
?>
						['<img src="<?php echo $cur_file_icons_path ?>module.png" />', '<?php echo _SITE_MODULES ?>', "index2.php?option=modules", null, '<?php echo _SITE_MODULES ?>'],
						['<img src="<?php echo $cur_file_icons_path ?>module.png" />', '<?php echo _ADMIN_MODULES ?>', "index2.php?option=modules&client=admin", null, '<?php echo _ADMIN_MODULES ?>'],
						_cmSplit,
						['<img src="<?php echo $cur_file_icons_path ?>install.png" />', '<?php echo _MOD_FULLMENU_MODULES_SETUP ?>', 'index2.php?option=installer&element=module', null, '<?php echo _MOD_FULLMENU_MODULES_SETUP ?>'],
<?php
				}
?>],
<?php
			}
		} if
		($installModules) {
 ?>
			[null,'<?php echo _EXTENSIONS ?>',null,null,'<?php echo _EXTENSION_MANAGEMENT ?>',
			['<img src="<?php echo $cur_file_icons_path ?>install.png" />', '<?php echo _INSTALLATION . " / " . _DELETING ?>','index2.php?option=installer&element=installer',null,'<?php echo _INSTALLATION . " / " . _DELETING ?>'],
<?php if ($manageLanguages) { ?>
				_cmSplit,['<img src="<?php echo $cur_file_icons_path ?>install.png" />','<?php echo _COM_INSTALLER_SITE_LANGUAGES ?>','index2.php?option=installer&element=language',null,'<?php echo _COM_INSTALLER_SITE_LANGUAGES ?>'],
<?php } if
			($manageTemplates) {
 ?>
				_cmSplit,
				['<img src="<?php echo $cur_file_icons_path ?>install.png" />','<?php echo _COM_INSTALLER_SITE_TEMPLATES ?>','index2.php?option=installer&element=template&client=',null,'<?php echo _COM_INSTALLER_SITE_TEMPLATES ?>'],
				['<img src="<?php echo $cur_file_icons_path ?>install.png" />','<?php echo _COM_INSTALLER_ADMIN_TEMPLATES ?>','index2.php?option=installer&element=template&client=admin',null,'<?php echo _COM_INSTALLER_ADMIN_TEMPLATES ?>'],
<?php } ?>
			],
<?php } ?>
		[null,'<?php echo _MOD_FULLMENU_TOOLS ?>',null,null,'<?php echo _MOD_FULLMENU_TOOLS ?>',
<?php if ($canConfig) { ?>
			['<img src="<?php echo $cur_file_icons_path ?>finder.png" />','<?php echo _COM_FILES ?>','index2.php?option=finder',null,'<?php echo _COM_FILES ?>'],
<?php } ?>
<?php if ($config->config_caching == 1) {
 ?>
<?php if ($config->config_cache_handler == 'file') {
 ?>
						['<img src="<?php echo $cur_file_icons_path ?>config.png" />','<?php echo _CACHE_MANAGEMENT ?>','index2.php?option=cache',null,'<?php echo _CACHE_MANAGEMENT ?>'],
<?php } ?>
				['<img src="<?php echo $cur_file_icons_path ?>config.png" />','<?php echo _MOD_FULLMENU_CLEAR_CONTENT_CACHE ?>','index2.php?option=admin&task=clean_cache',null,'<?php echo _MOD_FULLMENU_CLEAR_CONTENT_CACHE ?>'],
				['<img src="<?php echo $cur_file_icons_path ?>config.png" />','<?php echo _MOD_FULLMENU_CLEAR_ALL_CACHE ?>','index2.php?option=admin&task=clean_all_cache',null,'<?php echo _MOD_FULLMENU_CLEAR_ALL_CACHE ?>'],
<?php } ?>
<?php
		if ($canConfig) {
?>['<img src="<?php echo $cur_file_icons_path ?>sysinfo.png" />', '<?php echo _MOD_FULLMENU_SYSTEM_INFO ?>', 'index2.php?option=admin&task=sysinfo', null,'<?php echo _MOD_FULLMENU_SYSTEM_INFO ?>'],<?php
		}
?>['<img src="<?php echo $cur_file_icons_path ?>favicon.ico" />', '<?php echo _MOD_FULLMENU_JOOSTINARU ?>', 'http://www.joostina.ru/?from_adminpanel', '_blank','<?php echo _MOD_FULLMENU_JOOSTINARU ?>'],
		],
		_cmSplit];
		cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
<?php
		// boston, складываем меню в кэш, и записываем в файл
		$cur_menu = ob_get_contents();
		ob_end_clean();
		if ($config->config_adm_menu_cache) {
			js_menu_cache($cur_menu, $groupname_menu);
		} else {
		 Jdocument::$data['footer'][] = '<script language="JavaScript" type="text/javascript">' . $cur_menu . '</script>';
		}
	}

	public static function showDisabled($groupname = '') {
		$canConfig = $installModules = $editAllModules = $installComponents = $editAllComponents = true;

		$text = _MOD_FULLMENU_NO_ACTIVE_MENU_ON_THIS_PAGE;
?><div id="myMenuID" class="inactive"></div>
		<script language="JavaScript" type="text/javascript">
		    var myMenu =[
		        [null,'<?php echo _SITE; ?>',null,null,'<?php echo $text; ?>'],
		        [null,'<?php echo _USERS ?>',null,null,'<?php echo _USERS ?>'],
		        [null,'<?php echo _MENU; ?>',null,null,'<?php echo $text; ?>'],
<?php
		if ($installComponents | $editAllComponents) {
?>[null,'<?php echo _COMPONENTS; ?>',null,null,'<?php echo $text; ?>'],<?php
		}

		if ($installModules | $editAllModules) {
?>[null,'<?php echo _MODULES; ?>',null,null,'<?php echo $text; ?>'],<?php
		}

		if ($installModules) {
?>[null,'<?php echo _EXTENSIONS; ?>',null,null,'<?php echo $text; ?>'],<?php
		}

		if ($canConfig) {
?>,[null,'<?php echo _MOD_FULLMENU_TOOLS; ?>',null,null,'<?php echo $text; ?>'],<?php
		}
?>];cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
		</script>
<?php
	}

}

$hide = intval(mosGetParam($_REQUEST, 'hidemainmenu', 0));

global $my;

if ($hide) {
	mosFullAdminMenu::showDisabled($my->groupname);
} else {
	mosFullAdminMenu::show($my->groupname);
}