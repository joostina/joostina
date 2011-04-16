<?php
/**
 * News - компонент новостей
 * Представление (шаблон вывода): Список новостей
 *
 * */
defined('_JOOS_CORE') or die();


?>
<h1>Новости</h1>
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

            <div class="date"><?php echo joosDate::format($item->created_at, '%d.%m.%Y') ?></div>
            <p><?php echo $item->introtext;?></p>
        </div>
    </div>
    <?php endforeach; ?>


</div>
<a class="archive-link" href="<?php echo joosRoute::href('news_archive') ?>">Архив новостей</a>
<?php echo $pager->output; ?>