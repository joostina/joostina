<?php
/**
 * Promo
 *
 * */
defined( '_JOOS_CORE' ) or die();
?>
<?php if ( joosController::$controller == 'mainpage' ): ?>

<div id="promo_scrinshots">
	<a id="play_scrinshots" href="#" title="Скриншоты Joostina CMS">
		<img src="<?php echo JPATH_SITE ?>/templates/joostina/images/scrinshots.png"/>
	</a>
</div>

<div id="promo_text" class="right_part">
	<ul class="listreset">
		<li>Быстрая</li>
		<li>Удобная</li>
		<li>Нагрузоустойчивая</li>
		<li>Бесплатная</li>
		<li><a class="g-italic" href="#" title="Узнать о возможностях Joostina CMS">Подробнее о возможностях</a></li>
	</ul>

	<div class="promo-download">
		<a href="#" title="Скачать Joostina CMS ver. 1.3.0.1">Скачать</a>
		<span>Версия 2.0 <br/> Дата: 12.02.1011</span>
	</div>

</div>

<?php else: ?>

<div id="promo_text">
	<a class="button_big" href="#" title="Скачать Joostina CMS">Скачать (ver. 1.3.0.1)</a>
	<a href="#" id="changelog">Что нового в этой версии?</a>
</div>


<?php endif; 