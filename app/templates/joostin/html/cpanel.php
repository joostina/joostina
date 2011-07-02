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
?>
<div class="cpanel">

    <div class="b b-50 b-left">
        <h3>Каталог</h3>
        <span><a href="index2.php?option=categories&group=content"><img src="<?php echo joosHtml::ico('folder-move', '32x32') ?>"/>Категории
				каталога</a></span>
        <span><a href="index2.php?option=content&task=create"><img src="<?php echo joosHtml::ico('folder-documents', '32x32') ?>"/>Добавить
				категорию</a></span>
    </div>

    <div class="b b-50 b-left">
        <h3>Новости</h3>
        <span><a href="index2.php?option=news"><img src="<?php echo joosHtml::ico('stock_copy', '32x32') ?>"/>Все новости</a></span>
        <span><a href="index2.php?option=news&task=create"><img src="<?php echo joosHtml::ico('filenew', '32x32') ?>"/>Добавить
				новость</a></span>
    </div>

    <div class="b-clear" style="height:25px"></div>

	<div class="b b-50 b-left">
        <h3>Блоги</h3>
        <span><a href="index2.php?option=blog"><img src="<?php echo joosHtml::ico('stock_copy', '32x32') ?>"/>Все блогозаписи</a></span>
        <span><a href="index2.php?option=blog&task=create"><img src="<?php echo joosHtml::ico('filenew', '32x32') ?>"/>Добавить
				блогозапись</a></span>
    </div>

	<div class="b b-50 b-left">
        <h3>Статичные страницы</h3>
        <span><a href="index2.php?option=pages"><img src="<?php echo joosHtml::ico('stock_copy', '32x32') ?>"/>Страницы</a></span>
        <span><a href="index2.php?option=pages&task=create"><img src="<?php echo joosHtml::ico('filenew', '32x32') ?>"/>Добавить
				страницу</a></span>
    </div>


    <div class="b-clear" style="height:50px"></div>

    <div class="b b-50 b-left">
        <h3>Инструменты</h3>
        <span><a href="index2.php?option=coder"><img src="<?php echo joosHtml::ico('system-run', '32x32') ?>"/>Кодогенератор</a></span>
    </div>
</div>