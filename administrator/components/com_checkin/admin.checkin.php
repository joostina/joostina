<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

// ensure user has access to this function
if(!$acl->acl_check('administration','config','users',$my->usertype)) {
	mosRedirect('index2.php',_NOT_AUTH);
}

require_once ($mainframe->getPath('admin_html'));

// get parameters
$pkey		= mosGetParam($_REQUEST,'pkey','');
$checkid	= mosGetParam($_REQUEST,'checkid','');
$component	= mosGetParam($_REQUEST,'component','');
$editor		= mosGetParam($_REQUEST,'editor','');

switch($task) {
	case 'cancel':
		cancelMyCheckin();
		break;

	case 'checkin':
		checkin($pkey,$checkid,$component,$editor);
		showMyCheckin($option);
		break;

	case 'mycheckin':
		showMyCheckin($option);
		break;

	default:
		checkall($option);
		break;
}

function checkall() {
        $database = database::getInstance();
	$nullDate = $database->getNullDate();

	$cur_file_icons_path = JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE.'/images/ico';
	?>
<table class="adminheading">
	<tr>
		<th class="checkin"><?php echo _GLOBAL_CHECKIN?></th>
	</tr>
</table>
<table class="adminform">
	<tr>
		<th class="title"><?php echo _TABLE_IN_DB?></th>
		<th class="title"><?php echo _OBJECT_COUNT?></th>
		<th class="title"><?php echo _UNBLOCKED?></th>
		<th class="title">&nbsp;</th>
	</tr>
		<?php
		$tables = $database->getUtils()->getTableList();
		$k = 0;
		foreach($tables as $tn) {
			$fields = $database->getUtils()->getTableFields(array($tn));

			$foundCO = false;
			$foundCOT = false;
			$foundE = false;

			$foundCO = isset($fields[$tn]['checked_out']);
			$foundCOT = isset($fields[$tn]['checked_out_time']);
			$foundE = isset($fields[$tn]['editor']);

			if($foundCO && $foundCOT) {
				if($foundE) {
					$query = "SELECT checked_out, editor FROM $tn WHERE checked_out > 0";
				} else {
					$query = "SELECT checked_out FROM $tn WHERE checked_out > 0";
				}
				$database->setQuery($query);
				$res = $database->query();
				$num = $database->getNumRows($res);

				if($foundE) {
					$query = "UPDATE $tn SET checked_out = 0, checked_out_time = ".$database->Quote($nullDate).", editor = NULL WHERE checked_out > 0";
				} else {
					$query = "UPDATE $tn SET checked_out = 0, checked_out_time = ".$database->Quote($nullDate)." WHERE checked_out > 0";
				}
				$database->setQuery($query);
				$res = $database->query();

				if($res == 1) {
					if($num > 0) {
						echo "<tr class=\"row$k\">";
						echo "\n<td width=\"350\">"._CHECHKED_TABLE." - $tn</td>";
						echo "\n<td width=\"150\">"._UNBLOCKED." - <b>$num</b></td>";
						echo "\n<td width=\"100\" align=\"center\"><img src=\"".$cur_file_icons_path."/tick.png\" border=\"0\" alt=\"tick\" /></td>";
						echo "\n<td>&nbsp;</td>";
						echo "\n</tr>";
					} else {
						echo "<tr class=\"row$k\">";
						echo "\n<td width=\"350\">"._CHECHKED_TABLE." - $tn</td>";
						echo "\n<td width=\"150\">"._UNBLOCKED." - <b>$num</b></td>";
						echo "\n<td width=\"100\">&nbsp;</td>";
						echo "\n<td>&nbsp;</td>";
						echo "\n</tr>";
					}
					$k = 1 - $k;
				}
			}
		}
		?>
	<tr>
		<td colspan="4">
			<strong><?php echo _ALL_BLOCKED_IS_UNBLOCKED?></strong>
		</td>
	</tr>
</table>
	<?php
}

/**
 * List the records
 * @param string The current GET/POST option
 */
function showMyCheckin($option) {
	global $mainframe,$mosConfig_db,$database;

	$lt = mysql_list_tables($mosConfig_db);
	$k = 0;
	$dbprefix = $mainframe->getCfg('mosConfig_dbprefix');

	$mosusers = new mosUser($database);
	$list = "";
	$listcnt = 0;

	while(list($tn) = mysql_fetch_array($lt)) {
		// make sure we get the right tables based on prefix
		if(!preg_match("/^".$dbprefix."/i",$tn)) {
			continue;
		}
		$lf = mysql_list_fields($mosConfig_db,"$tn");
		$nf = mysql_num_fields($lf);

		$foundCO = false; // checked_out
		$foundCOT = false; // checked_out_time
		$foundTit = false; // title
		$foundE = false; // title
		$foundName = false; // name
		$keyname = "";

		$selstr = "checked_out, checked_out_time";

		// Search the table definition for the words 'checked_out', 'checked_out_time' and 'editor'
		for($i = 0; $i < $nf; $i++) {
			$fname = mysql_field_name($lf,$i);
			switch($fname) {
				case 'checked_out':
					$foundCO = true;
					break;

				case 'checked_out_time':
					$foundCOT = true;
					break;

				case 'editor':
					$foundE = true;
					break;

				case 'title':
					$foundTit = true;
					$selstr .= ", title";
					break;

				case 'name':
					$foundName = true;
					$selstr .= ", name";
					break;

				default:
					break;
			}
			if(preg_match("/primary_key/i",mysql_field_flags($lf,$i))) {
				$keyname = $fname;
				$selstr .= ", $fname";
			}
		}

		if($foundCO && $foundCOT) {
			$database->setQuery("SELECT $selstr FROM $tn WHERE checked_out > 0");

			$res = $database->query();
			$num = $database->getNumRows($res);

			if($num > 0) {
				$rows = $database->loadObjectList();
				for($i = 0; $i < $num; $i++) {
					if($foundTit) {
						$str = $rows[$i]->title;
					} elseif($foundName) {
						$str = $rows[$i]->name;
					} else {
						$str = "unknown";
					}
					$mosusers->load($rows[$i]->checked_out);
					$checkouttime = mktime(substr($rows[$i]->checked_out_time,11,2),substr($rows[$i]->checked_out_time,
							14,2),substr($rows[$i]->checked_out_time,17,2),substr($rows[$i]->checked_out_time,
							5,2),substr($rows[$i]->checked_out_time,8,2),substr($rows[$i]->checked_out_time,
							0,4));

					$duration = round((time() - $checkouttime) / 60);
					if($duration <= 120) {
						$duration .= " "._MINUTES;
					} else
					if($duration <= (48* 60)) {
						$duration = round($duration / 60);
						$duration .= " "._HOURS;
					} else {
						$duration = round($duration / (60* 24));
						$duration .= " "._DAYS;
					}

					$list[$listcnt] = array("component" => $tn,"title" => $str,"name" => $mosusers->name,
							"cotime" => $rows[$i]->checked_out_time." ($duration)","PKEY" => $keyname,"id" =>
							$rows[$i]->$keyname,"editor" => ($foundE)?'Y':'N');
					$listcnt++;
				}
			}
		}
	}

	HTML_checkin::showlist($option,$list,$listcnt);
}

function checkin($pkey,$checkid,$component,$editor) {
	global $database;

	$mainframe = mosMainFrame::getInstance();
	$cur_file_icons_path = JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE.'/images/ico';

	if($editor == "Y") {
		$database->setQuery("UPDATE $component SET checked_out=0, checked_out_time='00:00:00', editor=NULL WHERE $pkey = $checkid AND checked_out > 0");
	} else {
		$database->setQuery("UPDATE $component SET checked_out=0, checked_out_time='00:00:00' WHERE $pkey = $checkid AND checked_out > 0");
	}
	$res = $database->query();

	echo "<tr class=\"row1\">";
	echo "<td align=\"center\" width=\"70%\"><b>$component</b> "._UNBLOCKED2;
	if($res == 1) {
		echo "<img src=\"".$cur_file_icons_path."/tick.png\" border=\"0\" alt=\"успешно\" />";
	} else {
		echo _ERROR_WHEN_UNBLOCKING;
	}
	echo "</td></tr>";
}

/**
 * Cancels editing and checks in the record
 * @int the contact id
 */
function cancelMyCheckin($cid) {
	mosRedirect('index2.php');
}