<?php
/**
 * Профиль пользователя - редактирование
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();
?>
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
			<?php foreach ($acls as $value): ?>
				<td><input name="field_null[0]" id="field_0_7" type="checkbox" value="NULL"></td>
				<?php endforeach; ?>
		</tr>
	<?php endforeach ?>
</table>