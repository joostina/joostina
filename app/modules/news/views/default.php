<?php
/**
 * News - модуль "Новости"
 * Шаблон вывода
 *
 * @version 1.0
 * @package Joostina CMS
 * @subpackage Modules
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license see license.txt
 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();
?>
<div class="news_block">
    <h2><a href="<?php echo joosRoute::href('news') ?>">Новости</a></h2>
    <?php foreach ($items as $item): ?>
    <?php $view_href = joosRoute::href('news_view', array('id' => $item->id)); ?>
    <div class="news">
        <div class="date"><?php echo joosDate::format($item->created_at, '%d/%m/%Y') ?></div>
        <a href="<?php echo $view_href ?>"><?php echo $item->title ?></a>
    </div>
    <?php endforeach; ?>
</div>
<div class="cl"></div>