<?php
/**
 * Promo
 *
 **/

defined('_JOOS_CORE') or die();

?>
<?php if (joosController::$controller == 'mainpage'): ?>
<!--[3]#promo::BEGIN-->
<div id="promo">
    <a id="play_scrinshots" href="#" title="Скриншоты Joostina CMS"></a>

    <div id="promo_text">
        <ul class="listreset">
            <li>Быстрая</li>
            <li>Удобная</li>
            <li>Нагрузоустойчивая</li>
            <li>Бесплатная</li>
            <li><a href="#" title="Узнать о возможностях Joostina CMS">Подробнее о возможностях</a></li>
        </ul>

        <a class="button_big" href="#" title="Скачать Joostina CMS">Скачать (ver. 1.3.0.1)</a>
        <a href="#" id="changelog">Что нового в этой версии?</a>
    </div>
</div><!--[3]#promo::END-->
<?php else: ?>

<!--[3]#promo::BEGIN-->
<div id="promo">
    <div id="promo_text">
        <a class="button_big" href="#" title="Скачать Joostina CMS">Скачать (ver. 1.3.0.1)</a>
        <a href="#" id="changelog">Что нового в этой версии?</a>
    </div>
</div><!--[3]#promo::END-->

<?php endif; 