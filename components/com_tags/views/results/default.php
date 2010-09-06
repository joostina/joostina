<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

mosMainFrame::addLib('html');
require_once mosMainFrame::getInstance()->getPath('class', 'com_topic');
mosMainFrame::addLib('text');
$topic_types = Topic::get_types();

?>
<div class="page page_tags">
    <h5><a href="<?php echo sefRelToAbs('index.php?option=com_tags&task=cloud',true) ?>">Тэги</a></h5>
    <br/>
    <h1 style="clear: both;"><?php echo $tag;?></h1>
    
    <?php foreach ( $tags_results  as $row ) : ?>
    	<?php 
        $section = sefRelToAbs('index.php?option=topic&task=' . $topic_types[$row->type_id][1], true);
        $section = html::anchor($section, $topic_types[$row->type_id][0]);

        $row->anons_image = Topic::anons_image($row);

        $row->date_info = date_parse($row->created_at);
        $row->date_info['month_name'] = Text::month_name($row->date_info['month']);

		$row->href = sefRelToAbs('index.php?option=topic&task=view&id='.  sprintf('%s:%s', $row->id, $row->title ) );
		?>
		
	    <div class="news_item_wrap">
	        <span class="date">
	            <em><?php echo $row->date_info['month_name'] ?></em>
	            <strong class="date"><?php echo $row->date_info['day'] ?></strong>
	            <?php echo $row->date_info['year'] ?>
	        </span>
	
	        <div class="news_item">
	            <h2><?php echo $section . ' &rarr; '; ?><?php echo html::anchor($row->href, $row->title); ?></h2>
	        	<?php if ($row->anons_image): ?>
	                <a class="thumb" href="<?php echo $row->href ?>">
	                    <img src="<?php echo $row->anons_image ?>" alt="<?php echo $row->title ?>"/>
	                </a>
	        	<?php endif; ?>
          		<p><?php echo Text::word_limiter( $row->anons, 50 ) ?></p>
          		<span class="item_tags"><?php echo $row->tags_hrefs ?></span>
	        </div>
	    </div>        
        <?php endforeach; ?>
                
        <?php echo $pager->output; ?>
        <div class="pagination_wrap">
            <div class="paginator" id="paginator"></div>
        </div>
    
</div>