<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

global $mosConfig_list_limit, $my, $option;
$mainframe = joosMainframe::instance();
$cur_file_icons_path = joosConfig::get('admin_icons_path');

require_once (JPATH_BASE_ADMIN . '/includes/pageNavigation.php');

$limit = joosSession::get_user_state_from_request("viewlistlimit", 'limit', $mosConfig_list_limit);
$limitstart = joosSession::get_user_state_from_request("view{$option}", 'limitstart', 0);

// hides Administrator or Super Administrator from list depending on usertype
$and = '';
// администраторы
if ($my->gid == 24) {
	$and = "\n AND userid != '25'";
}
// менеджеры
if ($my->gid == 23) {
	$and = "\n AND userid != '25' AND userid != '24'";
}

// полное число авторизованных пользователей
$query = "SELECT COUNT(*) FROM #__session WHERE userid != 0" . $and;
$database->set_query($query);
$total = $database->load_result();

// page navigation
$pageNav = new joosPagenator($total, $limitstart, $limit);

$query = "SELECT* FROM #__session WHERE userid != 0"
		. $and
		. "\n ORDER BY usertype, username";
$database->set_query($query, $pageNav->limitstart, $pageNav->limit);
$rows = $database->load_object_list();
?>
<table class="adminlist">
	<tr>
		<th colspan="4" class="title"><?php echo _NOW_ON_SITE_REGISTERED ?></th>
	</tr>
	<?php
	$i = 0;
	$k = 0;
	foreach ($rows as $row) {
		if ($acl->acl_check('administration', 'manage', 'users', $my->usertype, 'components', 'com_users')) {
			$link = 'index2.php?option=com_users&task=editA&hidemainmenu=1&id=' . $row->userid;
			$name = '<a href="' . $link . '" title="' . _CHANGE_USER_DATA . '">' . $row->username . '</a>';
		} else {
			$name = $row->username;
		}
		?>
		<tr class="row<?php echo $k; ?>">
			<td width="5%"><?php echo $pageNav->row_number($i); ?></td>
			<td width="60%"><?php echo $name; ?></td>
			<td><?php echo $row->usertype; ?></td>
			<?php
			if ($acl->acl_check('administration', 'manage', 'users', $my->usertype, 'components', 'com_users')) {
				?>
				<td>
					<a href="index2.php?option=com_users&task=flogout&id=<?php echo $row->userid; ?>&<?php echo joosSpoof::get_code(); ?>=1">
					<img src="<?php echo $cur_file_icons_path; ?>/publish_x.png" width="12" height="12" border="0" alt="<?php echo _DISABLE ?>" title="<?php echo _DISABLE ?>" />
					</a>
				</td>
				<?php
			}
			?>
		</tr>
		<?php
		$i++;
	}
	?>
</table>
<?php echo $pageNav->get_list_footer(); ?>
<input type="hidden" name="option" value="" />