<?php
// запрет прямого доступа
defined('_JOOS_CORE') or die();
?>
<form  action="index2.php" method="post" name="admin_form" id="admin_form">

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
        <h2><?php echo joosAdminView::get_component_title() .' / '. joosAdminView::get_component_header() ?></h2>
    </div>

    <div class="row">
        <div class="span6">
            <div class="pagination" style="float: left; margin-right: 10px">
                <ul>
                    <li><?php echo joosAdminToolbarButtons::listing('create')?></li>
                </ul>
            </div>

            <div class="pagination js-btn-group-for_select">
                <ul>
                    <li class="disabled"><?php echo joosAdminToolbarButtons::listing('publish')?></li>
                    <li class="disabled"><?php echo joosAdminToolbarButtons::listing('unpublish')?></li>
                    <li class="disabled"><?php echo joosAdminToolbarButtons::listing('remove')?></li>
                </ul>
            </div>
        </div>
        <div class="span6">

            <div class="pagination" style="float: right">
                <?php echo $pagenav->get_pages_links();?>
            </div>

            <div class="pagination-limit_box" style="float: right; padding: 22px 20px 0 0; ">
                <?php echo $pagenav->get_limit_box();?>
            </div>

            <div style="float: right; padding: 30px 20px 0 0; font-weight: bold">
                <?php echo $pagenav->get_pages_counter();?>
            </div>
        </div>
    </div>

    <table class="table table-bordered table-admin">
        <thead>
            <th width="20px"><input type="checkbox" name="toggle" value="" class="js-select_all"></th>
            <?php echo joosAdminView::get_listing_param('table_headers')?>
        </thead>

        <tbody>
            <?php echo joosAdminView::get_listing_param('table_body')?>
        </tbody>
    </table>

    <input type="hidden" name="option" value="<?php echo $option ?>" />
    <input type="hidden" name="model" value="<?php echo joosAdminView::get_current_model() ?>"/>
    <input type="hidden" name="menu" value="<?php echo joosAutoadmin::get_active_menu_name() ?>"/>
    <input type="hidden" name="task" value="<?php echo $task ?>" name="task"/>
    <input type="hidden" name="boxchecked" value="false"/>
    <input type="hidden" name="<?php echo joosCSRF::get_code() ?>" value="1"/>

</form>





