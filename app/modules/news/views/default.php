<?php
defined( '_JOOS_CORE' ) or die();
?>
<h3 class="module-title"><a href="#">Новости</a></h3>

<ul class="unstyled">
    <?php foreach($news as $item):?>
    <li>
        <div class="date">
            <big><?php echo joosDateTime::format($item->created_at, '%d')  ?></big>
            <small><?php echo joosDateTime::format($item->created_at, '%B')  ?></small>
        </div>
        <a href="<?php echo joosRoute::href('news_view', array('id' => $item->id ))  ?>">
            <?php echo $item->title ?>
        </a>
    </li>
    <?php endforeach;?>

</ul>
