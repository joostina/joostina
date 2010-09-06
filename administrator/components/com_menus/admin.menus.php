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

Jacl::isDeny('menumanager') ? mosRedirect('index2.php?', _NOT_AUTH) : null;

require_once ($mainframe->getPath('admin_html'));

$path = JPATH_BASE_ADMIN.DS.'components'.DS.'com_menus'.DS;

$menutype = stripslashes(strval(mosGetParam($_REQUEST,'menutype','mainmenu')));
$type = stripslashes(strval(mosGetParam($_REQUEST,'type',false)));
$menu = stripslashes(strval(mosGetParam($_POST,'menu','')));

$cid = josGetArrayInts('cid');

switch($task) {
    case 'new':
        addMenuItem($cid,$menutype,$option,$task);
        break;

    case 'edit':
        $cid[0] = ($id?$id:intval($cid[0]));
        $menu = new mosMenu();
        if($cid[0]) {
            $menu->load($cid[0]);
        } else {
            $menu->type = $type;
        }

        if($menu->type) {
            $type = $menu->type;
            require_once ($path.$menu->type.DS.$menu->type.'.menu.php');
        }
        break;

    case 'save':
    case 'apply':
    case 'save_and_new':

    // очитска кэша контента
        mosCache::cleanCache('com_content');

        require_once ($path.$type.DS.$type.'.menu.php');
        break;

    case 'publish':
    case 'unpublish':
        publishMenuSection($cid,($task == 'publish'),$menutype);
        break;

    case 'remove':
        TrashMenusection($cid,$menutype);
        break;

    case 'cancel':
        cancelMenu($option);
        break;

    case 'orderup':
        orderMenu(intval($cid[0]),-1,$option);
        break;

    case 'orderdown':
        orderMenu(intval($cid[0]),1,$option);
        break;

    case 'accesspublic':
        accessMenu(intval($cid[0]),0,$option,$menutype);
        break;

    case 'accessregistered':
        accessMenu(intval($cid[0]),1,$option,$menutype);
        break;

    case 'accessspecial':
        accessMenu(intval($cid[0]),2,$option,$menutype);
        break;

    case 'movemenu':
        moveMenu($option,$cid,$menutype);
        break;

    case 'movemenusave':
        moveMenuSave($option,$cid,$menu,$menutype);
        break;

    case 'copymenu':
        copyMenu($option,$cid,$menutype);
        break;

    case 'copymenusave':
        copyMenuSave($option,$cid,$menu,$menutype);
        break;

    case 'cancelcopymenu':
    case 'cancelmovemenu':
        viewMenuItems($menutype,$option);
        break;

    case 'saveorder':
        saveOrder($cid,$menutype);
        break;

    default:
        $type = stripslashes(strval(mosGetParam($_REQUEST,'type')));
        if($type) {
            // adding a new item - type selection form
            require_once ($path.$type.DS.$type.'.menu.php');
        } else {
            viewMenuItems($menutype,$option);
        }
        break;
}

/**
 * Shows a list of items for a menu
 */
function viewMenuItems($menutype,$option) {

    $database = database::getInstance();
    $mainframe = mosMainFrame::getInstance();

    $limit = intval($mainframe->getUserStateFromRequest("viewlistlimit",'limit',$mainframe->getCfg('list_limit')));
    $limitstart = intval($mainframe->getUserStateFromRequest("view{$option}limitstart$menutype",'limitstart',0));
    $levellimit = intval($mainframe->getUserStateFromRequest("view{$option}limit$menutype",'levellimit',10));
    $search = $mainframe->getUserStateFromRequest("search{$option}$menutype",'search','');

    if(get_magic_quotes_gpc()) {
        $search = stripslashes($search);
    }

    // select the records
    // note, since this is a tree we have to do the limits code-side
    if($search) {
        $query = "SELECT m.id FROM #__menu AS m WHERE menutype = ".$database->Quote($menutype)."\n AND LOWER( m.name ) LIKE '%".$database->getEscaped(Jstring::trim(Jstring::strtolower($search)))."%'";
        $database->setQuery($query);
        $search_rows = $database->loadResultArray();
    }

    $query = "SELECT m.*, u.username AS editor, g.name AS groupname, com.name AS com_name".
            "\n FROM #__menu AS m".
            "\n LEFT JOIN #__users AS u ON u.id = m.checked_out".
            "\n LEFT JOIN #__groups AS g ON g.id = m.access".
            "\n LEFT JOIN #__components AS com ON com.id = m.componentid AND m.type = 'components'".
            "\n WHERE m.menutype = ".$database->Quote($menutype)."\n AND m.published != -2".
            "\n ORDER BY parent, ordering";
    $rows = $database->setQuery($query)->loadObjectList();

    // создание иерархии меню
    $children = array();
    // first pass - collect children
    foreach($rows as $v) {
        $pt = $v->parent;
        $list = @$children[$pt]?$children[$pt]:array();
        array_push($list,$v);
        $children[$pt] = $list;
    }
    unset($rows);
    // second pass - get an indent list of the items
    $list = mosTreeRecurse(0,'',array(),$children,max(0,$levellimit - 1));
    // eventually only pick out the searched items.
    if($search) {
        $list1 = array();

        foreach($search_rows as $sid) {
            foreach($list as $item) {
                if($item->id == $sid) {
                    $list1[] = $item;
                }
            }
        }
        // replace full list with found items
        $list = $list1;
    }

    $total = count($list);

    require_once (JPATH_BASE.DS.JADMIN_BASE.DS.'includes/pageNavigation.php');
    $pageNav = new mosPageNav($total,$limitstart,$limit);

    $levellist = mosHTML::integerSelectList(1,20,1,'levellimit','class="inputbox" size="1" onchange="document.adminForm.submit();"',$levellimit);

    // slice out elements based on limits
    $list = array_slice($list,$pageNav->limitstart,$pageNav->limit);

    $i = 0;
    foreach($list as $mitem) {
        $edit = '';
        switch($mitem->type) {
            case 'separator':
            case 'component_item_link':
                break;

            case 'url':
                if(eregi('index.php\?',$mitem->link)) {
                    if(!eregi('Itemid=',$mitem->link)) {
                        $mitem->link .= '&Itemid='.$mitem->id;
                    }
                }
                break;

            case 'newsfeed_link':
                $edit = 'index2.php?option=com_newsfeeds&task=edit&hidemainmenu=1A&id='.$mitem->componentid;
                $list[$i]->descrip = _CHANGE_THIS_NEWSFEED;
                $mitem->link .= '&Itemid='.$mitem->id;
                break;

            case 'contact_item_link':
                $edit = 'index2.php?option=com_contact&task=editA&hidemainmenu=1&id='.$mitem->componentid;
                $list[$i]->descrip = _CHANGE_THIS_CONTACT;
                $mitem->link .= '&Itemid='.$mitem->id;
                break;

            case 'content_item_link':
                $edit = 'index2.php?option=com_content&task=edit&hidemainmenu=1&id='.$mitem->componentid;
                $list[$i]->descrip = _CHANGE_THIS_CONTENT;
                break;

            case 'content_typed':
                $edit = 'index2.php?option=com_typedcontent&task=edit&hidemainmenu=1&id='.$mitem->componentid;
                $list[$i]->descrip = _CHANGE_THIS_STATIC_CONTENT;
                break;

            default:
                $mitem->link .= '&Itemid='.$mitem->id;
                break;
        }
        $list[$i]->link = $mitem->link;
        $list[$i]->edit = $edit;

        $row = ReadMenuXML($mitem->type,$mitem->com_name);
        $list[$i]->type = $row[0];
        if(!isset($list[$i]->descrip)) {
            $list[$i]->descrip = $row[1];
        }
        unset($row,$mitem);
        $i++;
    }

    HTML_menusections::showMenusections($list,$pageNav,$search,$levellist,$menutype,$option);
}

/**
 * Displays a selection list for menu item types
 */
function addMenuItem(&$cid,$menutype,$option,$task) {
    $types = array();

    // list of directories
    $dirs = mosReadDirectory(JPATH_BASE_ADMIN.DS.'components/com_menus');

    // load files for menu types
	$i = 0;
	foreach($dirs as $dir) {
        // needed within menu type .php files
        $type = $dir;
        $dir = JPATH_BASE_ADMIN.DS.'components/com_menus/'.$dir;
        if(is_dir($dir)) {
            $files = mosReadDirectory($dir,".\.menu\.php$");
            foreach($files as $file) {
				$types[$i] = new stdClass;
                $types[$i]->type = $type;
				$i++;
            }
        }
    }

    $i = 0;
    foreach($types as $type) {
        // pulls name and description from menu type xml
        $row = ReadMenuXML($type->type);
        $types[$i]->name = $row[0];
        $types[$i]->descrip = $row[1];
        $types[$i]->group = $row[2];
        $i++;
        unset($row);
    }

    mosMainFrame::addLib('utils');
    // sort array of objects alphabetically by name of menu type
    SortArrayObjects($types,'name',1);

    // split into Content
    $i = 0;
    foreach($types as $type) {
        if(strstr($type->group,'Content')) {
            $types_content[] = $types[$i];
        }
        $i++;
    }

    // split into Links
    $i = 0;
    foreach($types as $type) {
        if(strstr($type->group,'Link')) {
            $types_link[] = $types[$i];
        }
        $i++;
    }

	$types_component = array();
    // split into Component
    $i = 0;
    foreach($types as $type) {
        if(strstr($type->group,'Component')) {
            $types_component[] = $types[$i];
        }
        $i++;
    }

    // split into Other
    $i = 0;
    foreach($types as $type) {
        if(strstr($type->group,'Other') || !$type->group) {
            $types_other[] = $types[$i];
        }
        $i++;
    }

    // split into Submit
    $i = 0;
    foreach($types as $type) {
        if(strstr($type->group,'Submit') || !$type->group) {
            $types_submit[] = $types[$i];
        }
        $i++;
    }

    HTML_menusections::addMenuItem($cid,$menutype,$option,$types_component,$types_link,$types_other);
}


/**
 * Generic function to save the menu
 */
function saveMenu($option,$task = 'save') {
    josSpoofCheck();

    $database = database::getInstance();

    $params = mosGetParam($_POST,'params','');
    // TODO тут бедас русским языком...
    mosMainFrame::addLib('json');
    $_POST['params'] = php2js($params);

    $row = new mosMenu($database);

    if(!$row->bind($_POST)) {
        echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
        exit();
    }

    $row->name = ampReplace($row->name);

    if(!$row->check()) {
        echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
        exit();
    }
    if(!$row->store()) {
        echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
        exit();
    }
    $row->updateOrder('menutype = '.$database->Quote($row->menutype).' AND parent = '.(int)$row->parent);

    $msg = _MENU_ITEM_SAVED;
    switch($task) {
        case 'apply':
            mosRedirect('index2.php?option='.$option.'&menutype='.$row->menutype.'&task=edit&id='.$row->id.'&hidemainmenu=1',$msg);
            break;

        case 'save':
        default:
            mosRedirect('index2.php?option='.$option.'&menutype='.$row->menutype,$msg);
            break;

        case 'save_and_new':
        default:
            mosRedirect('index2.php?option='.$option.'&task=new&menutype='.$row->menutype.'&'.josSpoofValue().'=1');
            break;

    }
}

/**
 * Publishes or Unpublishes one or more menu sections
 * @param database A database connector object
 * @param string The name of the category section
 * @param array An array of id numbers
 * @param integer 0 if unpublishing, 1 if publishing
 */
function publishMenuSection($cid = null,$publish = 1,$menutype) {

    $database = database::getInstance();

    if(!is_array($cid) || count($cid) < 1) {
        return _CHOOSE_OBJECT_FOR.' '.($publish?'publish':'unpublish');
    }

    $menu = new mosMenu($database);
    foreach($cid as $id) {
        $menu->load($id);
        $menu->published = $publish;

        if(!$menu->check()) {
            return $menu->getError();
        }
        if(!$menu->store()) {
            return $menu->getError();
        }

        if($menu->type) {
            $database = &$database;
            $task = $publish?'publish':'unpublish';
            // $type value is used in*.menu.php
            $type = $menu->type;
            require_once (JPATH_BASE_ADMIN.DS.'components/com_menus'.DS.$type.DS.$type.'.menu.php');
        }
    }

    // clean any existing cache files
    mosCache::cleanCache('com_content');

    mosRedirect('index2.php?option=com_menus&menutype='.$menutype);
}

/**
 * Trashes a menu record
 */
function TrashMenuSection($cid = null,$menutype = 'mainmenu') {
    $database = database::getInstance();

    $nullDate = $database->getNullDate();
    $state = -2;

    $query = "SELECT* FROM #__menu WHERE menutype = ".$database->Quote($menutype)."\n AND published != ".(int)$state."\n ORDER BY menutype, parent, ordering";
    $mitems = $database->setQuery($query)->loadObjectList();

    // determine if selected item has an child items
    $children = array();
    foreach($cid as $id) {
        foreach($mitems as $item) {
            if($item->parent == $id) {
                $children[] = $item->id;
            }
        }
    }
    $list = josMenuChildrenRecurse($mitems,$children,$children);
    $list = array_merge($cid,$list);

    mosArrayToInts($list);
    $ids = 'id='.implode(' OR id=',$list);

    $query = "UPDATE #__menu SET published = ".(int)$state.", ordering = 0, checked_out = 0, checked_out_time = ".$database->Quote($nullDate)." WHERE ( $ids )";
    $database->setQuery($query);
    if(!$database->query()) {
        echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
        exit();
    }

    $total = count($cid);

    // clean any existing cache files
    mosCache::cleanCache('com_content');

    $msg = _MOVED_TO_TRASH.': '.$total;
    mosRedirect('index2.php?option=com_menus&menutype='.$menutype,$msg);
}

/**
 * Cancels an edit operation
 */
function cancelMenu($option) {
    josSpoofCheck();

    $database = database::getInstance();

    $menu = new mosMenu($database);
    $menu->bind($_POST);
    $menuid = intval(mosGetParam($_POST,'menuid',0));
    if($menuid) {
        $menu->id = $menuid;
    }

    mosRedirect('index2.php?option='.$option.'&menutype='.$menu->menutype);
}

/**
 * Moves the order of a record
 * @param integer The increment to reorder by
 */
function orderMenu($uid,$inc,$option) {
    $database = database::getInstance();

    $row = new mosMenu($database);
    $row->load($uid);
    $row->move($inc,"menutype = ".$database->Quote($row->menutype)." AND parent = ".(int)$row->parent);

    // clean any existing cache files
    mosCache::cleanCache('com_content');

    mosRedirect('index2.php?option='.$option.'&menutype='.$row->menutype);
}


/**
 * changes the access level of a record
 * @param integer The increment to reorder by
 */
function accessMenu($uid,$access,$option,$menutype) {
    $database = database::getInstance();

    $menu = new mosMenu($database);
    $menu->load($uid);
    $menu->access = $access;

    if(!$menu->check()) {
        return $menu->getError();
    }
    if(!$menu->store()) {
        return $menu->getError();
    }

    // clean any existing cache files
    mosCache::cleanCache('com_content');

    mosRedirect('index2.php?option='.$option.'&menutype='.$menutype);
}

/**
 * Form for moving item(s) to a specific menu
 */
function moveMenu($option,$cid,$menutype) {
    $database = database::getInstance();

    if(!is_array($cid) || count($cid) < 1) {
        echo "<script> alert('"._CHOOSE_OBJECT_TO_MOVE."'); window.history.go(-1);</script>\n";
        exit;
    }

    ## query to list selected menu items
    mosArrayToInts($cid);
    $cids = 'a.id='.implode(' OR a.id=',$cid);
    $query = "SELECT a.name FROM #__menu AS a WHERE ( $cids )";
    $database->setQuery($query);
    $items = $database->loadObjectList();

    $menuTypes = mosAdminMenus::menutypes();

    foreach($menuTypes as $menuType) {
        $menu[] = mosHTML::makeOption($menuType,$menuType);
    }

    // build the html select list
    $MenuList = mosHTML::selectList($menu,'menu','class="inputbox" size="10"','value','text',null);

    HTML_menusections::moveMenu($option,$cid,$MenuList,$items,$menutype);
}

/**
 * Add all descendants to list of meni id's
 */
function addDescendants($id,&$cid) {
    $database = database::getInstance();

    $query = "SELECT id FROM #__menu WHERE parent = ".(int)$id;
    $rows = $database->setQuery($query)->loadObjectList();
    if($database->getErrorNum()) {
        echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
        exit();
    } // if
    foreach($rows as $row) {
        $found = false;
        foreach($cid as $idx)
            if($idx == $row->id) {
                $found = true;
                break;
            } // if
        if(!$found) $cid[] = $row->id;
        addDescendants($row->id,$cid);
    } // foreach
} // addDescendants

/**
 * Save the item(s) to the menu selected
 */
function moveMenuSave($option,$cid,$menu,$menutype) {
    $database = database::getInstance();

    // add all decendants to the list
    foreach($cid as $id) addDescendants($id,$cid);

    $row = new mosMenu($database);
    $ordering = 1000000;
    $firstroot = 0;
    foreach($cid as $id) {
        $row->load($id);

        // is it moved together with his parent?
        $found = false;
        if($row->parent != 0)
            foreach($cid as $idx)
                if($idx == $row->parent) {
                    $found = true;
                    break;
                } // if
        if(!$found) {
            $row->parent = 0;
            $row->ordering = $ordering++;
            if(!$firstroot) $firstroot = $row->id;
        } // if

        $row->menutype = $menu;
        if(!$row->store()) {
            echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
            exit();
        } // if
    } // foreach

    if($firstroot) {
        $row->load($firstroot);
        $row->updateOrder('menutype = '.$database->Quote($row->menutype).' AND parent = '.(int)$row->parent);
    } // if

    // clean any existing cache files
    mosCache::cleanCache('com_content');

    $msg = count($cid).' ' . _MOVE_MENUS_TO.' '.$menu;
    mosRedirect('index2.php?option='.$option.'&menutype='.$menutype,$msg);
} // moveMenuSave

/**
 * Form for copying item(s) to a specific menu
 */
function copyMenu($option,$cid,$menutype) {
    $database = database::getInstance();

    if(!is_array($cid) || count($cid) < 1) {
        echo "<script> alert('"._CHOOSE_OBJECT_TO_MOVE."'); window.history.go(-1);</script>\n";
        exit;
    }

    ## query to list selected menu items
    mosArrayToInts($cid);
    $cids = 'a.id='.implode(' OR a.id=',$cid);
    $query = "SELECT a.name FROM #__menu AS a WHERE ( $cids )";
    $database->setQuery($query);
    $items = $database->loadObjectList();

    $menuTypes = mosAdminMenus::menutypes();

    foreach($menuTypes as $menuType) {
        $menu[] = mosHTML::makeOption($menuType,$menuType);
    }
    // build the html select list
    $MenuList = mosHTML::selectList($menu,'menu','class="inputbox" size="10"','value','text',null);

    HTML_menusections::copyMenu($option,$cid,$MenuList,$items,$menutype);
}

/**
 * Save the item(s) to the menu selected
 */
function copyMenuSave($option,$cid,$menu,$menutype) {
    $database = database::getInstance();

    $curr = new mosMenu($database);
    $cidref = array();
    foreach($cid as $id) {
        $curr->load($id);
        $curr->id = null;
        if(!$curr->store()) {
            mosErrorAlert( $curr->getError() );
            exit();
        }
        $cidref[] = array($id,$curr->id);
    }
    foreach($cidref as $ref) {
        $curr->load($ref[1]);
        if($curr->parent != 0) {
            $found = false;
            foreach($cidref as $ref2)
                if($curr->parent == $ref2[0]) {
                    $curr->parent = $ref2[1];
                    $found = true;
                    break;
                } // if
            if(!$found && $curr->menutype != $menu) $curr->parent = 0;
        } // if
        $curr->menutype = $menu;
        $curr->ordering = '9999';
        if(!$curr->store()) {
            mosErrorAlert( $curr->getError() );
            exit();
        }
        $curr->updateOrder('menutype = '.$database->Quote($curr->menutype).' AND parent = '.(int)$curr->parent);
    } // foreach

    // clean any existing cache files
    mosCache::cleanCache('com_content');

    $msg = count($cid).' moved to '.$menu;
    mosRedirect('index2.php?option='.$option.'&menutype='.$menutype,$msg);
}

function ReadMenuXML($type,$component = -1) {

    // XML library
    require_once (JPATH_BASE.'/includes/domit/xml_domit_lite_include.php');
    // xml file for module
    $xmlfile = JPATH_BASE_ADMIN.'/components/com_menus/'.$type.DS.$type.'.xml';
    $xmlDoc = new DOMIT_Lite_Document();

    $xmlDoc->resolveErrors(true);

    if($xmlDoc->loadXML($xmlfile,false,true)) {
        $root = $xmlDoc->documentElement;

        if($root->getTagName() == 'mosinstall' && ($root->getAttribute('type') =='component' || $root->getAttribute('type') == 'menu')) {
            // Menu Type Name
            $element = $root->getElementsByPath('name',1);
            $name = $element?trim($element->getText()):'';
            // Menu Type Description
            $element = $root->getElementsByPath('description',1);
            $descrip = $element?trim($element->getText()):'';
            // Menu Type Group
            $element = $root->getElementsByPath('group',1);
            $group = $element?trim($element->getText()):'';
        }
    }

    if(($component != -1) && ($name == 'Component')) {
        $name .= ' - '.$component;
    }

    $row[0] = $name;
    $row[1] = $descrip;
    $row[2] = $group;

    unset($xmlDoc,$root,$name,$descrip,$group);
    return $row;
}

function saveOrder(&$cid,$menutype) {
    josSpoofCheck();

    $database = database::getInstance();

    $total = count($cid);
    $order = josGetArrayInts('order');

    $row = new mosMenu($database);
    $conditions = array();

    // update ordering values
    for($i = 0; $i < $total; $i++) {
        $row->load((int)$cid[$i]);
        if($row->ordering != $order[$i]) {
            $row->ordering = $order[$i];
            if(!$row->store()) {
                echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
                exit();
            }
            // remember to updateOrder this group
            $condition = "menutype = ".$database->Quote($menutype)." AND parent = ".(int)$row->parent." AND published >= 0";
            $found = false;
            foreach($conditions as $cond)
                if($cond[1] == $condition) {
                    $found = true;
                    break;
                }
            if(!$found) $conditions[] = array($row->id,$condition);
        }
    }

    // execute updateOrder for each group
    foreach($conditions as $cond) {
        $row->load($cond[0]);
        $row->updateOrder($cond[1]);
    }

    // clean any existing cache files
    mosCache::cleanCache('com_content');

    $msg = _NEW_ORDER_SAVED;
    mosRedirect('index2.php?option=com_menus&menutype='.$menutype,$msg);
}

/**
 * Returns list of child items for a given set of ids from menu items supplied
 *
 */
function josMenuChildrenRecurse($mitems,$parents,$list,$maxlevel = 20,$level = 0) {
    // check to reduce recursive processing
    if($level <= $maxlevel && count($parents)) {
        $children = array();
        foreach($parents as $id) {
            foreach($mitems as $item) {
                if($item->parent == $id) {
                    $children[] = $item->id;
                }
            }
        }

        // check to reduce recursive processing
        if(count($children)) {
            $list = josMenuChildrenRecurse($mitems,$children,$list,$maxlevel,$level + 1);

            $list = array_merge($list,$children);
        }
    }

    return $list;
}