<?php
/**
 * @JoostFREE
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

//mosLoadAdminModules('icon', 0);
$imgp = JPATH_SITE . '/media/images/icons/32x32/candy'
?>

<div class="cpanel">

    <div class="b b-50 b-left">
        <h3>Каталог</h3>
        <span><a href="index2.php?option=categories&group=content"><img src="<?php echo $imgp; ?>/folder-move.png"/>Категории
				каталога</a></span>
        <span><a href="index2.php?option=content&task=create"><img src="<?php echo $imgp; ?>/folder-documents.png"/>Добавить
				категорию</a></span>
    </div>

    <div class="b b-50 b-left">
        <h3>Новости</h3>
        <span><a href="index2.php?option=news"><img src="<?php echo $imgp; ?>/stock_copy.png"/>Все новости</a></span>
        <span><a href="index2.php?option=news&task=create"><img src="<?php echo $imgp; ?>/contact-new.png"/>Добавить
				новость</a></span>
    </div>

    <div class="b-clear" style="height:50px"></div>

    <div class="b b-50 b-left">
        <h3>Статичные страницы</h3>
        <span><a href="index2.php?option=pages"><img src="<?php echo $imgp; ?>/stock_copy.png"/>Страницы</a></span>
        <span><a href="index2.php?option=pages&task=create"><img src="<?php echo $imgp; ?>/stock_copy.png"/>Добавить
				страницу</a></span>
    </div>

    <div class="b b-50 b-left">
        <h3>Вопрос-ответ</h3>
        <span><a href="index2.php?option=faq"><img src="<?php echo $imgp; ?>/stock_copy.png"/>Все вопросы</a></span>
    </div>
</div>