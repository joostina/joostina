<?php
/**

 *
 **/

//Запрет прямого доступа
defined('_JOOS_CORE') or die();

?>

<div class="carusel">

    <ul>
        <?php foreach ($items as $item): ?>
        <?php $view_href = joosRoute::href('category_view', array('slug' => $item->slug)); ?>
        <li>
            <a href="<?php echo $view_href?>"><?php echo $image = CategoriesDetails::get_image($item, 'thumb', array('height' => 32)) ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
