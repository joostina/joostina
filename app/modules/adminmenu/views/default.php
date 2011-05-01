<?php
/**

 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

navigation_ul_li_recurse($menu_items, $level = 1);

function navigation_ul_li_recurse(array $items, $level = 1) {
	$gid = joosCore::user()->gid;
	?>
	<ul class="dropdown<?php echo $level > 1 ? $level : '' ?>">
		<?php foreach ($items as $item => $data) : ?>
			<?php $_has_children = (isset($data['children']) && is_array($data['children'])) ? 1 : 0 ?>
			<?php $_access_allow = (isset($data['access']) && !isset($data['access'][$gid])) ? 0 : 1 ?>

			<?php if ($_access_allow): ?>
				<li<?php echo (isset($data['type']) && $data['type'] == 'sep') ? ' class="sep"' : '' ?>>
					<span <?php echo $_has_children ? ' class="parent"' : '' ?>>
						<?php if (isset($data['href'])): ?>
							<?php echo sprintf('<a href="%s" %s title="%s">%s</a>', $data['href'], (isset($data['ico']) && $data['ico'] ? 'class="ico_bg ' . $data['ico'] . '"' : ''), (isset($data['title']) ? $data['title'] : $item), $item); ?>
						<?php elseif (isset($data['call_from'])): ?>
							<?php //echo $data['call_from']; ?>
						<?php endif; ?>
					</span>
					<?php $_has_children ? navigation_ul_li_recurse($data['children'], $level + 1) : null; ?>
				</li>
			<?php endif; ?>

		<?php endforeach; ?>
	</ul>
	<?php
}