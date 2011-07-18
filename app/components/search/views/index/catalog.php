<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

?>
<h1>Поиск по каталогу</h1>
<form method="get" id="search_form" class="form_block" action="search/catalog/">
    <fieldset>
        <div class="search_block">
            <input type="submit" class="go" value=""/>
            <input class="inp_01" value="Поиск по настроению YAX!"
                   onblur="javascript:if(this.value=='')this.value='Поиск по настроению YAX!'"
                   onfocus="javascript:if(this.value=='Поиск по настроению YAX!')this.value=''" id="query"
                   name="searchword"/>

            <div class="cl"></div>
        </div>
        <div class="form_kitchen">
            <input type="radio" value="catalog" name="type" id="r_01" checked="checked" accesskey="i"/><label
                for="r_01">по каталогу</label><br/><br/>
        </div>
        <div class="form_kitchen">
            <input type="radio" value="news" name="type" id="r_02" accesskey="i"/><label for="r_02">по
            сайту</label><br/><br/>
        </div>
        <div class="cl"></div>
    </fieldset>
</form>

<?php if (!$results): ?>
<p>По вашему запросу ничего не найдено</p>
<?php else: $count = count($results); ?>

<p>По запросу “<?php echo $searchword ?>” найдено
    <span><?php echo $total ?></span> <?php echo joosText::declension($total, array('результат', 'результата', 'результатов')) ?>
</p>
<div class="text_block_bg">
    <?php $i = 1; foreach ($results as $item):
    $item->image_path = $item->image;
    ?>
    <div class="text_block_01">
        <a href="<?php echo joosRoute::href('content_view', array('slug' => $item->slug))?>">
            <?php echo Content::get_image($item) ?>
        </a>

        <div class="block_name">
            <?php echo $i?> <a
                href="<?php echo joosRoute::href('content_view', array('slug' => $item->slug))?>"><?php echo $item->title?></a>
            <br/><?php echo isset($item->sku) ? $item->sku : '' ?>
        </div>
    </div>
    <?php ++$i; endforeach;?>
</div>
<?php endif; 