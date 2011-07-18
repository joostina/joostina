<?php
/**
 * News - компонент новостей
 * Представление (шаблон вывода): Список новостей
 *
 * */
defined('_JOOS_CORE') or die();


?>
<h1>Новости /
    <small>архив</small>
</h1>
<div class="archive_years">
    <?php foreach ($years as $y): ?>
    <?php if ($y == $year): ?>
        <span><?php echo $y?></span>
        <?php else: ?>
        <a href="<?php echo joosRoute::href('news_archive_year', array('year' => $y));?>"><?php echo $y?></a>
        <?php endif; ?>

    <?php endforeach;?>
</div>

<div class="news_block">

    <?php foreach ($news_items as $item) : ?>
    <?php $href = joosRoute::href('news_view', array('id' => $item->id)); ?>
    <?php $image = News::get_image($item); ?>
    <div class="news_item">
        <div class="news_img">
            <a href="<?php echo $href;?>"><?php echo $image;?></a>
        </div>
        <div class="news_text">
            <h3><a href="<?php echo $href;?>"><?php echo $item->title;?></a></h3>

            <div class="date"><?php echo joosDateTime::format($item->created_at, '%d.%m.%Y') ?></div>
            <p><?php echo $item->introtext;?></p>
        </div>
    </div>
    <?php endforeach; ?>


</div>

<?php echo $pager->output; 