<?php
// запрет прямого доступа
defined('_JOOS_CORE') or die();
?>
<div id="change_result"></div>
<table>
	<tr>
		<th>&nbsp;</th>
		<?php foreach ($acl_groups as $acl_group): ?>
			<th colspan="<?php echo count($acl_list[$acl_group]) ?>"><?php echo $acl_group ?></th>
		<?php endforeach; ?>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<?php foreach ($acls as $acl): ?>
			<td><?php echo sprintf('%s::%s', $acl->acl_group, $acl->acl_name) ?></td>
		<?php endforeach; ?>
	</tr>
	<?php foreach ($groups as $group) : ?>
		<tr>
			<td><?php echo $group->title ?></td>
			<?php foreach ($acls as $acl_value): ?>
				<td class="acl_state"><input name="acl[]" data-group-id="<?php echo $group->id ?>" type="checkbox" value="<?php echo $acl_value->id ?>" <?php echo (isset($acl_rules[$group->id][$acl_value->id])) ? 'checked="true"' : '' ?>></td>
				<?php endforeach; ?>
		</tr>
	<?php endforeach ?>
</table>