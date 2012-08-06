<?php
/**
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or exit;

?>
<div class="page_search">
    <h5>Поиск</h5>

    <div class="search_form">

        <form action="<?php echo joosRoute::href('search') ?>" method="post" name="search_form" id="search_form">
            <div class="search_input_wrap">
                <input type="text" class="inputbox search_input" size="30" maxlength="30" name="search" value="<?php echo $search_word; ?>" />
            </div>
            <button class="search_button" type="submit">&rarr;</button>
        </form>
    </div>

    <?php
    if ($search_result) :
        for ($index = $pager->low; $index < ($pager->high + 1); $index++) {
            if (isset($search_result[$index])) {
                $row = $search_result[$index];

                    $section = joosHTML::anchor(joosRoute::href('game_index'), 'Что-то');

                    $row->anons_image = JPATH_SITE_IMAGES . '/' . joosFile::make_file_location((int) $row->image_id) . '/image.png';

                    $row->view_href = joosRoute::href('opa_view', array('id'=> $row->id, 'alias' => $row->game_slug));

                ?>
                    <div class="item">
                        <h2><?php echo $section . ' &rarr; '; ?><?php echo joosHTML::anchor($row->view_href, $row->title); ?></h2>
                        <?php if ($row->anons_image): ?>
                            <a class="thumb" href="<?php echo $row->view_href ?>">
                                <img src="<?php echo $row->anons_image ?>" alt="<?php echo trim(strip_tags($row->title)) ?>"/>
                            </a>
                        <?php endif; ?>
                        <p><?php echo $row->text ?></p>
                    </div>

                <?php
            }
        }

        echo $pager->output;
        ?>

    <?php else: ?>
        <div class="alert">Ничего не нашлось</div>
    <?php endif; ?>

</div>
