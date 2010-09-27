<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2009 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_JOOS_CORE') or die();

Jacl::isDeny('cache') ? mosRedirect('index2.php?', _NOT_AUTH) : null;

// Load the html output class and the model class
require_once ($mainframe->getPath('admin_html'));
require_once ($mainframe->getPath('class'));

$cid = mosGetParam($_REQUEST,'cid',0);

/*
 * This is our main control structure for the component
 *
 * Each view is determined by the $task variable
*/
switch($task) {
	case 'delete':
		CacheController::deleteCache($cid);
		CacheController::showCache();
		break;

	default :
		CacheController::showCache();
		break;
}

/**
 * Static class to hold controller functions for the Cache component
 *
 * @static
 * @package		Joostina
 * @subpackage	Cache
 * @since		1.3
 */
class CacheController {
	/**
	 * Show the cache
	 *
	 * @since	1.3
	 */
	function showCache() {
		global $mainframe, $option;

		$client = intval(mosGetParam($_REQUEST,'client',0));

		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'));
		$limitstart = $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0 );

		$cmData = new CacheData(JPATH_BASE . '/cache');

		require_once (JPATH_BASE . '/'.JADMIN_BASE.'/includes/pageNavigation.php');
		$pageNav = new mosPageNav($cmData->getGroupCount(), $limitstart, $limit);
		CacheView::displayCache( $cmData->getRows( $limitstart, $limit ), $client, $pageNav );
	}

	function deleteCache($cid) {

		$client = intval(mosGetParam($_REQUEST,'client',0));

		$cmData = new CacheData(JPATH_BASE . '/cache');
		$cmData->cleanCacheList( $cid );
	}
}