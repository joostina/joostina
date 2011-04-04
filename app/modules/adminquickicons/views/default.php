<?php
// запрет прямого доступа
defined('_JOOS_CORE') or die();
?>
<div class="quickicons">
<?php foreach ($items as $button): ?>
		<?php $alt = $button->alt_text ? $button->alt_text : $button->title;
		$icon_web_root = JPATH_SITE . Quickicons::get_ico_pach(); ?>
		<span>
			<a href="<?php echo $button->href; ?>">
				<img class="quickicon"  src="<?php echo $icon_web_root . $button->icon ?>" alt="<?php echo $alt ?>"  />
			</a>
		</span>	
<?php endforeach; ?>	

	<?php if (Jacl::isAllowed('quickicons', 'edit')) { ?>
		<a href="index2.php?option=quickicons">
			<img src="../media/images/icons/16x16/candy/applications-system.png" title="<?php echo __('Изменить кнопки быстрого доступа') ?>" />
		</a>
<?php } ?>		

</div>


