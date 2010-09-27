<?php

defined('_JOOS_CORE') or die();
global $task,$my, $mosConfig_mailfrom;
$iso = explode('=',_ISO);
echo '<?xml version="1.0" encoding="'.$iso[1].'"?'.'>'."\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $iso[1];?>" />
		<?php

		// загружаем верхнюю часть страницы со всеми js и css файлами, и обязательным использованием jquery
		mosShowHead(array('js'=>1,'css'=>1,'jquery'=>1));

		$block1_count = (mosCountModules('user1')>0) + (mosCountModules('user2')>0) + (mosCountModules('user3')>0);
		$block2_count = (mosCountModules('user4')>0) + (mosCountModules('user5')>0) + (mosCountModules('user6')>0);
		$block3_count = (mosCountModules('user7')>0) + (mosCountModules('user8')>0) + (mosCountModules('user9')>0);

		$body_class = 'inside';
		if($block1_count) {
	$body_class = 'mainpage';
}
?>
		<link href="<?php echo JPATH_SITE;?>/templates/<?php echo JTEMPLATE; ?>/css/template_css.css" rel="stylesheet" type="text/css" />
		<!--[if lte IE 7]><link href="<?php echo JPATH_SITE;?>/templates/<?php echo JTEMPLATE; ?>/css/fix/ie7.css" rel="stylesheet" type="text/css" /><![endif]-->
		<!--[if IE 8]><link href="<?php echo JPATH_SITE;?>/templates/<?php echo JTEMPLATE; ?>/css/fix/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->
	</head>
	<body class="<?php echo $body_class;?>">
		<div class="main_wrap">
			<div class="wrapper">
				<div class="header">
					<a href="<?php echo JPATH_SITE;?>" id="logo">&nbsp;</a>
					<div class="header_center"><?php mosLoadModules('header',-1); ?></div>
					<div class="header_right">
						<a title="Обратная связь" href="mailto:<?php echo $mosConfig_mailfrom;?>" id="mail" class="navbar">&nbsp;</a>
						<a title="Карта сайта" href="<?php echo sefRelToAbs('index.php?option=com_xmap&amp;Itemid=27'); ?>" id="map" class="navbar">&nbsp;</a>
					</div>
					<div class="top_menu_l"><div class="top_menu_r"><div class="top_menu_mid">
				<?php mosLoadModules('top',-1); ?>
							</div></div></div>
				</div><!--header:end-->
<?php if($block1_count) {
							$block1_width = 'w' .$block1_count; ?>
				<div class="block1" id="block_round">
						<?php if(mosCountModules('user1')) { ?>
                    <div class="block_<?php echo $block1_width ?>">
		<?php mosLoadModules('user1', -2); ?>
					</div>
		<?php } ?>
						<?php if(mosCountModules('user2')) { ?>
                    <div class="block_<?php echo $block1_width ?>">
		<?php mosLoadModules('user2', -2); ?>
					</div>
		<?php } ?>
						<?php if(mosCountModules('user3')) { ?>
                    <div class="block_<?php echo $block1_width ?>">
						<?php mosLoadModules('user3', -2); ?>
					</div>
							<?php } ?>
				</div><!--block1:end-->
	<?php } ?>
				<div class="content">
<?php mosMainbody(); ?> <br />
							<?php if($block2_count) {
	$block2_width = 'w' .$block2_count; ?>
                    <div class="block2">
							<?php if(mosCountModules('user4')) { ?>
						<div class="block_<?php echo $block2_width ?>">
									<?php mosLoadModules('user4', -2); ?>
						</div>
								<?php } ?>
							<?php if(mosCountModules('user5')) { ?>
						<div class="block_<?php echo $block2_width ?>">
									<?php mosLoadModules('user5', -2); ?>
						</div>
								<?php } ?>
	<?php if(mosCountModules('user6')) { ?>
						<div class="block_<?php echo $block2_width ?>">
		<?php mosLoadModules('user6', -2); ?>
						</div>
							<?php } ?>
                    </div><!--block2:end-->
	<?php } ?>
				</div><!--content:end-->
				<div class="col">
<?php mosLoadModules('left',-2); ?>
						<?php mosLoadModules('banner',-2); ?>
				</div><!--col:end-->
							<?php if($block3_count) {
	$block3_width = 'w' .$block3_count;?>
                <div class="block3">
                    <div class="block3_bottom">
	<?php if(mosCountModules('user7')) { ?>
						<div class="block_<?php echo $block3_width ?> w25">
		<?php mosLoadModules('user7', -2); ?>
						</div>
								<?php } ?>
	<?php if(mosCountModules('user8')) { ?>
						<div class="block_<?php echo $block3_width ?> w35">
		<?php mosLoadModules('user8', -2); ?>
						</div>
		<?php } ?>
	<?php if(mosCountModules('user9')) { ?>
						<div class="block_<?php echo $block3_width ?> w35" >
		<?php mosLoadModules('user9', -2); ?>
						</div>
		<?php } ?>
                    </div>
                </div><!--block3:end-->
					<?php } ?>
			</div><!--wrapper:end-->
		</div> <!--main_wrap:end-->
		<div class="footer">
			<div class="bottom">
				<a title="Работает на системе управления сайтами Joostina CMS" href="http://www.joostina.ru" target="_blank" id="about" class="bottom_bar">Работает на Joostina CMS</a>
<?php mosLoadModules('bottom',-1); ?>
			</div>
		</div><!--footer:end-->
		<script type="text/javascript">var _live_site = '<?php echo JPATH_SITE;?>';</script>
<?php
//подключаем js-скрипт
		$mainframe->addJS(JPATH_SITE.'/templates/'.JTEMPLATE.'/js/corners.js', 'js');
//подключаем js-файл шаблона
		$mainframe->addJS(JPATH_SITE.'/templates/'.JTEMPLATE.'/js/template.js', 'custom');
// подключаем js-файл компонента закладок
		$mainframe->addJS(JPATH_SITE.'/components/com_bookmarks/media/js/bookmarks.js', 'custom');

		JHTML::loadJqueryPlugins('jquery.impromptu',false,false,'custom');

		echo JHTML::css_file(JPATH_SITE.'/media/js/jquery.plugins/jquery.impromptu/jquery.impromptu.css');

// выводим js футера (первая ступень - в основном jQuery-плагины и вспомагательные скрипты)
mosShowFooter(array('js'=>1,'css'=>1));
// выводим js футера (вторая ступень - js компонентов, инициализации для плагинов и т.п. - 
//всё, что должно быть загружено после всех основных скриптов)
mosShowFooter(array('custom'=>1));
?>
	</body>
</html>