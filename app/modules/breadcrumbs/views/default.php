<?php
/**
 * Breadcrumbs - модуль вывода "хлебных крошек"
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

array_unshift($items, '<a href="' . JPATH_SITE . '">Главная</a>');
$last = count($items) - 1;
?>
<div class="path">
    <?php foreach ($items as $key => $item): ?>
    <?php echo $item; ?>
    <?php echo $key == $last ? '' : ' / ' ?>
    <?php endforeach; ?>
</div>