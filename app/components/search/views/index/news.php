<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

joosLoader::lib('text');

?>
			<h1>Поиск по сайту</h1>
			<form method="get" id="search_form" class="form_block" action="search/catalog/">
				<fieldset>
					<div class="search_block">
						<input type="submit" class="go" value="" />
						<input class="inp_01" value="Поиск по настроению YAX!"  onblur="javascript:if(this.value=='')this.value='Поиск по настроению YAX!'" onfocus="javascript:if(this.value=='Поиск по настроению YAX!')this.value=''" id="query" name="searchword" />
						<div class="cl"></div>
					</div>
					<div class="form_kitchen">
						<input type="radio" value="catalog" name="type" id="r_01" checked="checked" accesskey="i" /><label for="r_01">по каталогу</label><br /><br />
					</div>
					<div class="form_kitchen">
						<input type="radio" value="news" name="type" id="r_02" accesskey="i" /><label for="r_02">по сайту</label><br /><br />
					</div>
					<div class="cl"></div>
				</fieldset>
			</form>

			<?php if(!$results):?>
				<p>По вашему запросу ничего не найдено</p>
			<?php else: $count = count($results);  ?>

				<p>По запросу “<?php echo $searchword ?>” найдено
					<span><?php echo $total ?></span> <?php echo Text::declension($total, array('результат', 'результата', 'результатов')) ?>
				</p>
				<div class="news_block">

					<?php foreach ($results as $item) : ?>
						<?php $href = joosRoute::href('news_view', array('id' => $item->id)); ?>
							<div class="news_text">
								<div class="date"><?php echo joosDate::format($item->created_at, '%d/%m/%Y') ?></div>
								<h3><a href="<?php echo $href;?>"><?php echo $item->title;?></a></h3>
								<p><?php echo $item->introtext;?></p>
							</div>
					<?php endforeach; ?>
				</div>
				<?php echo $pager->output ?>
			<?php endif;?>