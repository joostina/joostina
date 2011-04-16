<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

?>
<?php $image = News::get_image($item, 'medium'); ?>
<div class="full_news">
    <h1><?php echo $item->title?></h1>

    <div class="date"><?php echo joosDate::format($item->created_at, '%d.%m.%Y') ?></div>


    <div class="news_block">
        <div class="news_img"><?php echo $image;?></div>
        <div class="news_text">

            <p><?php echo $item->introtext;?></p>

            <br/><a href="<?php echo joosRoute::href('news') ?>">Все новости</a><br/><br/>
        </div>
        <div class="cl"></div>
    </div>
</div>
<div class="cl"></div>

