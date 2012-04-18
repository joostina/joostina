<?php
// запрет прямого доступа
defined('_JOOS_CORE') or die();

?>

<div class="page-header">
    <ul class="nav nav-pills" style="float: right">
        <?php foreach(joosAdminView::get_submenu() as $submenu_item): ?>
        <li <?php echo ($submenu_item['active'] == false) ? '' : 'class="active"' ?>>
            <a href="<?php echo $submenu_item['href'] ?>">
                <?php echo $submenu_item['name'] ?>
            </a>
        </li>
        <?php endforeach ?>
    </ul>
    <h2><?php echo joosAdminView::get_component_title() .' / '. joosAdminView::get_component_header() ?></h2>
</div>
    
<table class="table table-striped table-bordered table-condensed table-admin">
    <thead>
	<tr>
		<th>&nbsp;</th>
		<?php foreach ($acl_groups as $acl_group): ?>
			<th colspan="<?php echo count($acl_list[$acl_group]) ?>"><?php echo $acl_group ?></th>
		<?php endforeach; ?>
	</tr>
	<tr>
		<th>&nbsp;</th>
		<?php foreach ($acls as $acl): ?>
			<th><?php echo sprintf('%s<br />%s::%s', $acl->title, $acl->acl_group, $acl->acl_name) ?></th>
		<?php endforeach; ?>
	</tr>
    </thead>
    <tbody>
	<?php foreach ($groups as $group) : ?>
		<tr>
			<td><?php echo $group->title ?></td>
			<?php foreach ($acls as $acl_value): ?>
				<td class="acl_state"><input name="acl[]" data-group-id="<?php echo $group->id ?>" type="checkbox" value="<?php echo $acl_value->id ?>" <?php echo (isset($acl_rules[$group->id][$acl_value->id])) ? 'checked="true"' : '' ?>></td>
				<?php endforeach; ?>
		</tr>
	<?php endforeach ?>
    </tbody>
</table>
    