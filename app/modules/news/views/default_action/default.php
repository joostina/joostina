<?php
defined( '_JOOS_CORE' ) or exit();
?>

<?php if(!$news){ return false; }?>

<h3 class="module-title"><a href="#">Новости</a></h3>

<ul class="unstyled">
    <?php foreach($news as $item):?>
    <li>
        <div class="date">
            <big><?php echo joosDateTime::format($item->created_at, '%d')  ?></big>
            <small><?php echo joosDateTime::russian_date('F', strtotime($item->created_at)) ?></small>
        </div>
        <a href="<?php echo joosRoute::href('news_view', array('id' => $item->id ))  ?>">
            <?php echo $item->title ?>
        </a>
    </li>
    <?php endforeach;?>

</ul>
