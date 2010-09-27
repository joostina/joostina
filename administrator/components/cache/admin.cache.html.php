<?php
/**
 * @version		$Id: admin.cache.html.php 11403 2009-05-05 06:19:31Z ian $
 * @package		Joostina
 * @subpackage	Cache
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * HTML View class for the Cache component
 *
 * @static
 * @package 	Joostina
 * @subpackage	Cache
 * @since 		1.3
 */
class CacheView {

	/**
	 * Displays the cache
	 *
	 * @param array An array of records
	 * @param string The URL option
	 */
	function displayCache(&$rows, &$client, &$page) {
		?>
<table border="0" class="adminheading">
	<tbody><tr>
			<th class="cpanel"><?php echo _CACHE_MANAGEMENT?></th>
		</tr>
	</tbody>
</table>
<form action="index2.php" method="POST" name="adminForm">
	<table cellpadding="3" cellspacing="0" border="0" width="100%" class="adminlist">
		<thead>
			<tr>
				<th class="title" width="10">
		<?php echo _CACHE_NUM; ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows );?>);" />
				</th>
				<th class="title" nowrap="nowrap">
		<?php echo _GROUP; ?>
				</th>
				<th width="5%" align="center" nowrap="nowrap">
		<?php echo _CACHE_FILE_NUMBER; ?>
				</th>
				<th width="10%" align="center">
		<?php echo _CACHE_SIZE; ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="6">
		<?php echo $page->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
		$rc = 0;
					for ($i = 0, $n = count($rows); $i < $n; $i ++) {
						$row = & $rows[$i];
						?>
			<tr class="<?php echo "row$rc"; ?>" >
				<td style="text-align: center;">
			<?php echo $i + 1; ?>
				</td>
				<td>
					<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->group; ?>" onclick="isChecked(this.checked);" />
				</td>
				<td>
					<span class="bold">
			<?php echo $row->group; ?>
					</span>
				</td>
				<td align="center">
			<?php echo $row->count; ?>
				</td>
				<td align="center">
			<?php echo $row->size . " " ._CACHE_KB?>
				</td>
			</tr>
			<?php
			$rc = 1 - $rc;
					}
					?>
		</tbody>
	</table>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_cache" />
	<input type="hidden" name="client" value="<?php echo $client;?>" />
	<input type="hidden" name="chosen" value="">

</form>
		<?php
	}
}