<?php
/**
 *
 * */
defined('_JOOS_CORE') or die();

?>

<?php if ($category->level == 1): //Бренд ?>
<h4><b>О бренде</b></h4>
<h2><?php echo $category_details->desc_short ?></h2>
<?php echo $category_details->desc_full ?>
<?php endif; ?>

<?php if ($category->level == 2): //Категория бренда ?>
<h4><b><?php echo $category->name ?></b></h4>

<?php echo $category_details->desc_full ?>
<?php endif; ?>


<?php if ($category->level == 3): //Тпр-р-рууу. Конечная ?>
<h4><b><?php echo $category->name ?></b></h4>

<?php if ($items): //Записи ?>
    <?php foreach ($items as $item): ?>
        <div class="product">
            <h2><?php echo $item->title?></h2>

            <?php if ($item->image): ?>
            <div class="product_img">
                <a class="lightbox"
                   href="<?php echo JPATH_SITE_IMAGES . '/' . $item->image . '/medium.png' ?>"><?php echo Content::get_image($item) ?></a>
            </div>
            <?php endif;?>

            <div class="product_info">
                <?php echo $item->fulltext?>
            </div>
            <div class="cl"></div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

<?php endif; ?>




