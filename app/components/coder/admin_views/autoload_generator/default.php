<?php
// запрет прямого доступа
defined('_JOOS_CORE') or die();

?>

<div class="page-header">
    <ul class="nav nav-pills" style="float: right">
        <?php foreach(joosAdminView::get_submenu() as $submenu_item): ?>
        <li <?php echo ($submenu_item['active'] == false) ? '' : 'class="active"' ?>>
            <a href="<?php echo $submenu_item['href'] ?>">
                <?php echo $submenu_item['name'] ?>
            </a>
        </li>
        <?php endforeach ?>
    </ul>
    <h2><?php echo joosAdminView::get_component_title()?></h2>
</div>

<section>
    <div class="row">
        <div class="span12">
            <textarea rows="30" class="span12"><?php echo $body ?></textarea>
        </div>
    </div>
</section>