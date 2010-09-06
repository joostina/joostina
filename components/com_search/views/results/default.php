<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
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


//_xdump($topic_types);
//die();
?>
<div class="page_search page">
    <h5>Поиск</h5>

    <?php searchHTML::form($q); ?>

    <?php
    if ($lists) :
        for ($index = $pager->low; $index < ($pager->high + 1); $index++) {
            if (isset($lists[$index])) {
                $row = $lists[$index];

                if ($row->itemtype == 'topic') {
                    $section = sefRelToAbs('index.php?option=topic&task=' . $topic_types[$row->type_id][1], true);
                    $section = html::anchor($section, $topic_types[$row->type_id][0]);

                    $row->anons_image = Topic::anons_image($row);

                    $row->date_info = date_parse($row->created_at);
                    $row->date_info['month_name'] = Text::month_name($row->date_info['month']);
                } else {
                    $section = sefRelToAbs('index.php?option=games', true);
                    $section = html::anchor($section, 'Игры');

                    $row->anons_image = JPATH_SITE_IMAGES . '/attachments/games/' . File::makefilename($row->image_id) . '/game.png';
                }
    ?>
                <div class="news_item_wrap">
                    <span class="date">
                        <?php if($row->itemtype=='topic'): ?>
                        <em><?php echo $row->date_info['month_name'] ?></em>
                        <strong class="date"><?php echo $row->date_info['day'] ?></strong>
                        <?php echo $row->date_info['year'] ?>
                        <?php endif ?>
                    </span>

                    <div class="news_item">
                        <h2><?php echo $section . ' &rarr '; ?><?php echo html::anchor($row->href, $row->title); ?></h2>
                    <?php if ($row->anons_image): ?>
                            <a class="thumb" href="<?php echo $row->href ?>">
                                <img src="<?php echo $row->anons_image ?>" alt="<?php echo $row->title ?>"/>
                            </a>
                    <?php endif; ?>
                            <p><?php echo $row->text ?></p>
                    </div>
                </div>

    <?php
                }
            }

            echo $pager->output
    ?>
            <div class="pagination_wrap">
                <div class="paginator" id="paginator"></div>
            </div>

    <?php else: ?>
                <div class="alert">Ничего не нашлось</div>
    <?php endif; ?>

</div>

