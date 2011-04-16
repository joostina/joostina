<?php
/**
 * Закладки
 *
 * */

//Запрет прямого доступа
defined('_JOOS_CORE') or die();

if (!$bookmarks) {
    echo 'Здесь ничего нет';
    return;
}
joosLoader::lib('text');


$js_code =
        <<<EOD
        $('#columner').masonry({
	singleMode: true, 
	itemSelector: '.box'
});
EOD;
joosDocument::instance()->add_js_code($js_code);

?>


<div id="columner">
    <?php if (isset($bookmarks['blogs'])) : ?>
    <div class="box col1 b-50">
        <h5 class="g-blocktitle_orange">Блоги</h5>
        <ul class="listreset m-blogs_list">
            <?php foreach ($bookmarks['blogs'] as $item): ?>
            <?php $view_href = joosRoute::href('blog_view', array('id' => $item->id, 'cat_slug' => $item->cat_slug)); ?>
            <?php $user_href = joosRoute::href('user_view', array('id' => $item->userid, 'username' => $item->username)); ?>
            <li class="m-blogs_list_item">
                <h4 class="title_item"><a class="title_item_link" href="<?php echo $view_href ?>"
                                          title="<?php echo $item->title ?>"><?php echo $item->title ?></a></h4>

                <div class="m-blogs_author">
                    <?php echo Bookmarks::addlink(null, array('class' => 'Blogs', 'id' => $item->id)) ?>
                    <a href="<?php echo $user_href ?>" class="m-blogs_author_link">
                        <img class="g-thumb_40 g-user_avatar" src="_temp/40x40.gif" alt=""/>
                        <span class="el-user"><?php echo $item->username ?></span>
                        <span class="el-date"><?php echo $item->created_at ?></span>
                    </a>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif;?>

    <?php if (isset($bookmarks['news'])) : ?>
    <div class="box">
        <?php $news_types = News::get_types_slug();?>
        <h3 class="g-blocktitle_grey">Новости</h3>
        <ul class="listreset listblock listdotted">
            <?php foreach ($bookmarks['news'] as $item): ?>
            <li class="news-list_item" style="width: 45%;">
                <?php $view_href = joosRoute::href('news_view', array('id' => $item->id, 'type' => $news_types[$item->type_id])); ?>
                <div class="news-list_item_date"><?php echo Bookmarks::addlink(null, array('class' => 'News', 'id' => $item->id)) ?>
                <b>25.09</b>
                    <small>2010</small>
                </div>
                <a class="news-list_item_link" href="<?php echo $view_href ?>"><?php echo $item->title ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif;?>

    <?php if (isset($bookmarks['video'])): ?>
    <div class="box col1">
        <h3 class="g-blocktitle_black">Видео</h3>
        <ul class="video-list">
            <?php  foreach ($bookmarks['video'] as $video): ?>
            <?php $video_kadr = explode('.jpg', strip_tags($video->src));
            $video_kadr = $video_kadr[1] . '.jpg' ?>
            <li class="video-list_item" style="width: 45%;">
                <a class="video-list_item_link"
                   href="<?php echo joosRoute::href('artists_video', array('name' => $video->slug, 'id' => $video->id)) ?>">
					<span class="video-list_item_img">
						<?php echo Bookmarks::addlink(null, array('class' => 'Zvideo', 'id' => $video->id)) ?>
                        <img src="<?php echo $video_kadr ?>" alt="" class="g-thumb_95x70"/>
					</span>
                    <strong class="video-list_item_title"><?php echo $video->name ?></strong>
                    <span class="time"><?php echo round($video->duration / 60, 2) ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

</div>