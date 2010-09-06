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


class mosAdminMenus {

	public static function Ordering(&$row,$id) {
		if($id) {
			$query = "SELECT ordering AS value, name AS text"
					." FROM #__menu"
					."\n WHERE menutype = ".database::getInstance()->Quote($row->menutype)
					."\n AND parent = ".(int)$row->parent."\n AND published != -2"
					."\n ORDER BY ordering";
			$order = mosCommonHTML::mosGetOrderingList($query);
			$ordering = mosHTML::selectList($order,'ordering','class="inputbox" size="1"','value','text',intval($row->ordering));
		} else {
			$ordering = '<input type="hidden" name="ordering" value="'.$row->ordering.'" />'._NEW_ITEM_LAST;
		}
		return $ordering;
	}

	public static function Access(&$row,$guest=false) {
		$query = "SELECT id AS value, name AS text FROM #__groups ORDER BY id";
		$groups = database::getInstance()->setQuery($query)->loadObjectList();

		$guest ? $groups[] = mosHTML::makeOption(3,_COM_MODULES_GUEST) : null;
		return mosHTML::selectList($groups,'access','class="inputbox" size="4"','value','text',intval($row->access));
	}

	public static function Parent(&$row) {
		$database = database::getInstance();

		$id = '';
		if($row->id) $id = "\n AND id != ".(int)$row->id;

		$query = "SELECT m.* FROM #__menu m WHERE menutype = ".$database->Quote($row->menutype)." AND published != -2".$id." ORDER BY parent, ordering";
		$database->setQuery($query);
		$mitems = $database->loadObjectList();
		// establish the hierarchy of the menu
		$children = array();
		if($mitems) {
			// first pass - collect children
			foreach($mitems as $v) {
				$pt = $v->parent;
				$list = @$children[$pt]?$children[$pt]:array();
				array_push($list,$v);
				$children[$pt] = $list;
			}
		}
		// second pass - get an indent list of the items
		$list = mosTreeRecurse(0,'',array(),$children,20,0,0);
		// assemble menu items to the array
		$mitems = array();
		$mitems[] = mosHTML::makeOption('0','Top');
		foreach($list as $item) {
			$mitems[] = mosHTML::makeOption($item->id,'&nbsp;&nbsp;&nbsp;'.$item->treename);
		}
		return mosHTML::selectList($mitems,'parent','class="inputbox" size="10"','value','text',$row->parent);
	}

	public static function Published($row,$field_name = 'published' ) {
		return mosHTML::yesnoRadioList('published','class="inputbox"',$row->$field_name);
	}

	public static function Link(&$row,$id,$link = null) {
		global $mainframe;

		if($id) {
			switch($row->type) {
				case 'content_item_link':
				case 'content_typed':
				// load menu params
					$params = new mosParameters($row->params,$mainframe->getPath('menu_xml',$row->type),'menu');

					if($params->get('unique_itemid')) {
						$row->link .= '&Itemid='.$row->id;
					} else {
						$temp = split('&task=view&id=',$row->link);
					}

					$link = $row->link;
					break;

				default:
					if($link) {
						$link = $row->link;
					} else {
						$link = $row->link.'&amp;Itemid='.$row->id;
					}
					break;
			}
		} else {
			$link = null;
		}

		return $link;
	}

	public static function Target(&$row) {
		$click[] = mosHTML::makeOption('0',_ADM_MENUS_TARGET_CUR_WINDOW);
		$click[] = mosHTML::makeOption('1',_ADM_MENUS_TARGET_NEW_WINDOW_WITH_PANEL);
		$click[] = mosHTML::makeOption('2',_ADM_MENUS_TARGET_NEW_WINDOW_WITHOUT_PANEL);
		return mosHTML::selectList($click,'browserNav','class="inputbox" size="4"','value','text',intval($row->browserNav));
	}

	public static function MenuLinks(&$lookup,$all = null,$none = null,$unassigned = 1) {
		$database = database::getInstance();

		// get a list of the menu items
		$query = "SELECT m.* FROM #__menu AS m WHERE m.published = 1 ORDER BY m.menutype, m.parent, m.ordering";
		$database->setQuery($query);
		$mitems = $database->loadObjectList();
		$mitems_temp = $mitems;

		// establish the hierarchy of the menu
		$children = array();
		// first pass - collect children
		foreach($mitems as $v) {
			$pt = $v->parent;
			$list = @$children[$pt]?$children[$pt]:array();
			array_push($list,$v);
			$children[$pt] = $list;
		}
		// second pass - get an indent list of the items
		$list = mosTreeRecurse(intval($mitems[0]->parent),'',array(),$children,20,0,0);

		// Code that adds menu name to Display of Page(s)
		$text_count = 0;
		$mitems_spacer = $mitems_temp[0]->menutype;
		foreach($list as $list_a) {
			foreach($mitems_temp as $mitems_a) {
				if($mitems_a->id == $list_a->id) {
					// Code that inserts the blank line that seperates different menus
					if($mitems_a->menutype != $mitems_spacer) {
						$list_temp[] = mosHTML::makeOption(-999,'----');
						$mitems_spacer = $mitems_a->menutype;
					}

					// do not display `url` menu item types that contain `index.php` and `Itemid`
					if(!($mitems_a->type == 'url' && strpos($mitems_a->link,'index.php') !== false &&
							strpos($mitems_a->link,'Itemid=') !== false)) {
						$text = $mitems_a->menutype.' : '.$list_a->treename;
						$list_temp[] = mosHTML::makeOption($list_a->id,$text);

						if(strlen($text) > $text_count) {
							$text_count = strlen($text);
						}
					}
				}
			}
		}
		$list = $list_temp;

		$mitems = array();
		if($all) {
			// prepare an array with 'all' as the first item
			$mitems[] = mosHTML::makeOption(0,_ALL);
			// adds space, in select box which is not saved
			$mitems[] = mosHTML::makeOption(-999,'----');
		}
		if($none) {
			// prepare an array with 'all' as the first item
			$mitems[] = mosHTML::makeOption(-999,_NOT_EXISTS);
			// adds space, in select box which is not saved
			$mitems[] = mosHTML::makeOption(-999,'----');
		}
		if($unassigned) {
			// prepare an array with 'all' as the first item
			$mitems[] = mosHTML::makeOption(99999999,_WITH_UNASSIGNED);
			// adds space, in select box which is not saved
			$mitems[] = mosHTML::makeOption(-999,'----');
		}

		// append the rest of the menu items to the array
		foreach($list as $item) {
			$mitems[] = mosHTML::makeOption($item->value,$item->text);
		}

		return mosHTML::selectList($mitems,'selections[]','class="inputbox" size="26" multiple="multiple"','value','text',$lookup);
	}

	public static function Category(&$menu,$id,$javascript = '') {
		$database = database::getInstance();

		$query = "SELECT c.id AS `value`, c.section AS `id`, CONCAT_WS( ' / ', s.title, c.title) AS `text` FROM #__sections AS s INNER JOIN #__categories AS c ON c.section = s.id WHERE s.scope = 'content' ORDER BY s.name, c.name";
		$database->setQuery($query);
		$rows = $database->loadObjectList();
		$category = '';
		if($id) {
			foreach($rows as $row) {
				if($row->value == $menu->componentid) {
					$category = $row->text;
				}
			}
			$category .= '<input type="hidden" name="componentid" value="'.$menu->componentid.'" />';
			$category .= '<input type="hidden" name="link" value="'.$menu->link.'" />';
		} else {
			$category = mosHTML::selectList($rows,'componentid','class="inputbox" size="10"'.$javascript,'value','text');
			$category .= '<input type="hidden" name="link" value="" />';
		}
		return $category;
	}

	public static function Section(&$menu,$id,$all = 0) {
		$database = database::getInstance();

		$query = "SELECT s.id AS `value`, s.id AS `id`, s.title AS `text` FROM #__sections AS s WHERE s.scope = 'content' ORDER BY s.name";
		$database->setQuery($query);
		if($all) {
			$rows[] = mosHTML::makeOption(0,'- Все разделы -');
			$rows = array_merge($rows,$database->loadObjectList());
		} else {
			$rows = $database->loadObjectList();
		}

		if($id) {
			foreach($rows as $row) {
				if($row->value == $menu->componentid) {
					$section = $row->text;
				}
			}
			$section .= '<input type="hidden" name="componentid" value="'.$menu->componentid.'" />';
			$section .= '<input type="hidden" name="link" value="'.$menu->link.'" />';
		} else {
			$section = mosHTML::selectList($rows,'componentid','class="inputbox" size="10"','value','text');
			$section .= '<input type="hidden" name="link" value="" />';
		}
		return $section;
	}

	public static function Component($menu,$id,$rows=null,$extra = '') {

		if(!$rows) {
			$database = database::getInstance();
			$query = "SELECT c.id AS value, c.name AS text, c.link FROM #__components AS c WHERE c.link != '' ORDER BY c.name";
			$rows = $database->setQuery($query)->loadObjectList();
		}
		if($id) {
			// existing component, just show name
			foreach($rows as $row) {
				if($row->value == $menu->componentid) {
					$component = $row->text;
				}else {
					$component = $menu->name;
				}
			}
			$component .= '<input type="hidden" name="componentid" value="'.$menu->componentid.'" />';
		} else {
			$component = mosHTML::selectList($rows,'componentid','id="componentid" class="inputbox required" size="15" '.$extra,'value','text');
		}

		return $component;
	}

	public static function ComponentName(&$menu,$rows=null) {
		$database = database::getInstance();

		if(!$rows) {
			$query = "SELECT c.id AS value, c.name AS text, c.link FROM #__components AS c WHERE c.link != '' ORDER BY c.name";
			$database->setQuery($query);
			$rows = $database->loadObjectList();
		}

		$component = 'Component';
		foreach($rows as $row) {
			if($row->value == $menu->componentid) {
				$component = $row->text;
			}
		}

		return $component;
	}

	public static function Images($name,&$active,$javascript = null,$directory = null) {

		if(!$directory) {
			$directory = '/images/stories';
		}

		if(!$javascript) {
			$javascript = "onchange=\"javascript:if (document.forms[0].image.options[selectedIndex].value!='') {document.imagelib.src='..$directory/' + document.forms[0].image.options[selectedIndex].value} else {document.imagelib.src='../images/blank.png'}\"";
		}

		$imageFiles = mosReadDirectory(JPATH_BASE.$directory);
		$images = array(mosHTML::makeOption('','- '._CHOOSE_IMAGE.' -'));
		foreach($imageFiles as $file) {
			if(preg_match("/bmp|gif|jpg|png/i",$file)) {
				$images[] = mosHTML::makeOption($file);
			}
		}
		return mosHTML::selectList($images,$name,'class="inputbox" size="1" '.$javascript,'value','text',$active);
	}

	public static function SpecificOrdering(&$row,$id,$query,$neworder = 0,$limit = 30) {
		if($neworder) {
			$text = _NEW_ITEM_FIRST;
		} else {
			$text = _NEW_ITEM_LAST;
		}

		if($id) {
			$order = mosCommonHTML::mosGetOrderingList($query,$limit);
			$ordering = mosHTML::selectList($order,'ordering','class="inputbox" size="1"','value','text',intval($row->ordering));
		} else {
			$ordering = '<input type="hidden" name="ordering" value="'.$row->ordering.'" />'.$text;
		}
		return $ordering;
	}

	public static function UserSelect($name,$active,$nouser = 0,$javascript = null,$order = 'name',$reg = 1) {
		$database = database::getInstance();

		$and = '';
		if($reg) {
			// does not include registered users in the list
			$and = "\n AND gid > 18";
		}

		$query = "SELECT id AS value, name AS text FROM #__users WHERE block = 0 $and ORDER BY $order";
		$database->setQuery($query);
		if($nouser) {
			$users[] = mosHTML::makeOption('0','- '._NO_USER.' -');
			$users = array_merge($users,$database->loadObjectList());
		} else {
			$users = $database->loadObjectList();
		}

		return mosHTML::selectList($users,$name,'class="inputbox" size="1" '.$javascript,'value','text',$active);
	}

	public static function Positions($name,$active = null,$javascript = null,$none = 1,$center = 1,
			$left = 1,$right = 1) {
		if($none) {
			$pos[] = mosHTML::makeOption('',_NONE);
		}
		if($center) {
			$pos[] = mosHTML::makeOption('center',_CENTER);
		}
		if($left) {
			$pos[] = mosHTML::makeOption('left',_LEFT);
		}
		if($right) {
			$pos[] = mosHTML::makeOption('right',_RIGHT);
		}

		$positions = mosHTML::selectList($pos,$name,'class="inputbox" size="1"'.$javascript,'value','text',$active);

		return $positions;
	}

	public static function ComponentCategory($name,$section,$active = null,$javascript = null,$order ='ordering',$size = 1,$sel_cat = 1) {
		$database = database::getInstance();

		$query = "SELECT id AS value, name AS text"
				."\n FROM #__categories"
				."\n WHERE section = ".$database->Quote($section)
				."\n AND published = 1"
				."\n ORDER BY $order";
		$database->setQuery($query);
		if($sel_cat) {
			$categories[] = mosHTML::makeOption('0',_SEL_CATEGORY);
			$categories = array_merge($categories,$database->loadObjectList());
		} else {
			$categories = $database->loadObjectList();
		}

		if(count($categories) < 1) {
			mosRedirect('index2.php?option=com_categories&section='.$section,_CREATE_CATEGORIES_FIRST);
		}

		return mosHTML::selectList($categories,$name,'class="inputbox" size="'.$size.'" '.$javascript,'value','text',$active);
	}

	public static function SelectSection($name,$active = null,$javascript = null,$order ='ordering',$scope='content') {
		$database = database::getInstance();

		$categories[] = mosHTML::makeOption('0',_SEL_SECTION);
		$query = "SELECT id AS value, title AS text"
				."\n FROM #__sections"
				."\n WHERE published = 1 AND scope='$scope'"
				."\n ORDER BY $order";
		$database->setQuery($query);
		$sections = array_merge($categories,$database->loadObjectList());

		$category = mosHTML::selectList($sections,$name,'class="inputbox" size="1" '.$javascript,'value','text',$active);

		return $category;
	}

	public static function Links2Menu($type,$and) {
		$database = database::getInstance();

		$query = "SELECT* FROM #__menu WHERE type = ".$database->Quote($type)." AND published = 1".$and;
		return $database->setQuery($query)->loadObjectList();
	}

	public static function MenuSelect($name = 'menuselect',$javascript = null) {
		$database = database::getInstance();

		$query = "SELECT params FROM #__modules WHERE module LIKE '%menu% ";
		$database->setQuery($query);
		$menus = $database->loadObjectList();
		$i=0;
		$menuselect = array();
		$menus_arr=array();
		foreach($menus as $menu) {
			$params = mosParseParams($menu->params);
			if(!in_array($params->menutype, $menus_arr)) {
				$menuselect[$i]->value = $params->menutype;
				$menuselect[$i]->text = $params->menutype;
				$menus_arr[$i]= $params->menutype;
				$i++;
			}
		}

		mosMainFrame::addLib('utils');
		SortArrayObjects($menuselect,'text',1);

		return mosHTML::selectList($menuselect,$name,'class="inputbox" size="10" '.$javascript,'value','text');
	}

	public static function ReadImages($imagePath,$folderPath,&$folders,&$images) {
		$imgFiles = mosReadDirectory($imagePath);

		foreach($imgFiles as $file) {
			$ff_ = $folderPath.$file.'/';
			$ff = $folderPath.$file;
			$i_f = $imagePath.'/'.$file;

			if(is_dir($i_f) && $file != 'CVS' && $file != '.svn') {
				$folders[] = mosHTML::makeOption($ff_);
				mosAdminMenus::ReadImages($i_f,$ff_,$folders,$images);
			} else
			if(preg_match("/bmp|gif|jpg|png/",$file) && is_file($i_f)) {
				// leading / we don't need
				$imageFile = substr($ff,1);
				$images[$folderPath][] = mosHTML::makeOption($imageFile,$file);
			}
		}
	}

	public static function ReadImagesX(&$folders,&$images) {

		if($folders[0]->value != '*0*') {
			foreach($folders as $folder) {
				$imagePath = JPATH_BASE.'/images/stories'.$folder->value;
				$imgFiles = mosReadDirectory($imagePath);
				$folderPath = $folder->value.'/';

				foreach($imgFiles as $file) {
					$ff = $folderPath.$file;
					$i_f = $imagePath.'/'.$file;

					if(preg_match("/bmp|gif|jpg|png/i",$file) && is_file($i_f)) {
						// leading / we don't need
						$imageFile = substr($ff,1);
						$images[$folderPath][] = mosHTML::makeOption($imageFile,$file);
					}
				}
			}
		} else {
			$folders = array();
			$folders[] = mosHTML::makeOption('None');
		}
	}

	public static function GetImageFolders(&$temps) {
		if($temps[0]->value != 'None') {
			foreach($temps as $temp) {
				if(substr($temp->value,-1,1) != '/') {
					$temp = $temp->value.'/';
					$folders[] = mosHTML::makeOption($temp,$temp);
				} else {
					$temp = $temp->value;
					$temp = ampReplace($temp);
					$folders[] = mosHTML::makeOption($temp,$temp);
				}
			}
		} else {
			$folders[] = mosHTML::makeOption(_NOT_CHOOSED);
		}

		$javascript = "onchange=\"changeDynaList( 'imagefiles', folderimages, document.adminForm.folders.options[document.adminForm.folders.selectedIndex].value, 0, 0);\"";
		return mosHTML::selectList($folders,'folders','class="inputbox" size="1" '.$javascript,'value','text','/');
	}

	public static function GetImages(&$images,$path,$base = '/') {
		if(is_array($base) && count($base) > 0) {
			if($base[0]->value != '/') {
				$base = $base[0]->value.'/';
			} else {
				$base = $base[0]->value;
			}
		} else {
			$base = '/';
		}

		if(!isset($images[$base])) {
			$images[$base][] = mosHTML::makeOption('');
		}

		$javascript = "onchange=\"previewImage( 'imagefiles', 'view_imagefiles', '$path/' )\" onfocus=\"previewImage( 'imagefiles', 'view_imagefiles', '$path/' )\"";
		return mosHTML::selectList($images[$base],'imagefiles','class="inputbox" size="10" multiple="multiple" '.$javascript,'value','text',null);
	}

	public static function GetSavedImages(&$row,$path) {
		$images2 = array();
		foreach($row->images as $file) {
			$temp = explode('|',$file);
			if(strrchr($temp[0],'/')) {
				$filename = substr(strrchr($temp[0],'/'),1);
			} else {
				$filename = $temp[0];
			}
			$images2[] = mosHTML::makeOption($file,$filename);
		}
		$javascript = "onchange=\"previewImage( 'imagelist', 'view_imagelist', '$path/' ); showImageProps( '$path/' ); \"";
		return mosHTML::selectList($images2,'imagelist','class="inputbox" size="10" '.$javascript,'value','text');
	}

	public static function ImageCheck($file,$directory = '/images/M_images/',$param = null,$param_directory ='/images/M_images/',$alt = null,$name = null,$type = 1,$align = 'middle',$title = null,$admin = null) {

		$id		= $name ? ' id="'.$name.'"':'';
		$name	= $name ? ' name="'.$name.'"':'';
		$title	= $title ? ' title="'.$title.'"':'';
		$alt	= $alt ? ' alt="'.$alt.'"':' alt=""';
		$align	= $align ? ' align="'.$align.'"':'';
		// change directory path from frontend or backend
		if($admin) {
			$path = '/'.JADMIN_BASE.'/templates/'.JTEMPLATE.'/images/ico/';
		} else {
			$path = '/templates/'.JTEMPLATE.'/images/ico/';
		}
		if($param) {
			$image = JPATH_SITE.$param_directory.$param;
			if($type) {
				$image = '<img src="'.$image.'" '.$alt.$id.$name.$align.' border="0" />';
			}
		} else
		if($param == -1) {
			$image = '';
		} else {
			if(file_exists(JPATH_BASE.$path.$file)) {
				$image = JPATH_SITE.$path.$file;
			} else {
				$image = JPATH_SITE.$directory.$file;
			}
			if($type) {
				$image = '<img src="'.$image.'" '.$alt.$id.$name.$title.$align.' border="0" />';
			}
		}
		return $image;
	}

	public static function ImageCheckAdmin($file,$directory = '/administrator/images/',$param = null,$param_directory = '/administrator/images/',$alt = null,$name = null,$type = 1,$align = 'middle',$title = null) {
		return mosAdminMenus::ImageCheck($file,$directory,$param,$param_directory,$alt,$name,$type,$align,$title,1);
	}

	public static function menutypes() {
		$database = database::getInstance();

		// ищем все модули содержащие в себе название menu
		$query = "SELECT params FROM #__modules WHERE module LIKE '%menu%' ORDER BY title";
		$modMenus = $database->setQuery($query)->loadObjectList();

		$menuTypes = '';
		foreach($modMenus as $modMenu) {
			$check = 1;
			
			$modParams = mosParseParams($modMenu->params);
			$menuType = isset($modParams->menutype) ? $modParams->menutype : 'mainmenu' ;

			// защита от дублей
			if(!is_array($menuTypes)) {
				$menuTypes[] = $menuType;
			} else {
				$check = 1;
				foreach($menuTypes as $a) {
					if($a == $menuType) {
						$check = 0;
					}
				}
				if($check) {
					$menuTypes[] = $menuType;
				}
			}
		}

		$query = "SELECT menutype FROM #__menu GROUP BY menutype ORDER BY menutype";
		$menuMenus = $database->setQuery($query)->loadObjectList();

		foreach($menuMenus as $menuMenu) {
			$check = 1;
			foreach($menuTypes as $a) {
				if($a == $menuMenu->menutype) {
					$check = 0;
				}
			}
			if($check) {
				$menuTypes[] = $menuMenu->menutype;
			}
		}

		asort($menuTypes);
		return $menuTypes;
	}

	public static function menuItem($item) {

		$path = JPATH_BASE.DS.JADMIN_BASE.'/components/com_menus/'.$item.'/';
		include_once ($path.$item.'.class.php');
		include_once ($path.$item.'.menu.html.php');
	}
}