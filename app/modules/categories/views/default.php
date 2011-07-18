<?php
/**

 *
 **/

//Запрет прямого доступа
defined('_JOOS_CORE') or die();

?>

<div class="brand_block">
    <div class="brand_container">
        <h2>Бренды</h2>

        <?php foreach ($items as $item): ?>
        <?php $view_href = joosRoute::href('category_view', array('slug' => $item->slug)); ?>

        <div class="brand">
            <a href="<?php echo $view_href?>"><?php echo $image = CategoriesDetails::get_image($item) ?></a>
            <br/>
            <?php echo $item->desc_short ?>
        </div>
        <?php endforeach; ?>


        <div class="cl"></div>
    </div>
</div>
