<?php
// запрет прямого доступа
defined('_JOOS_CORE') or exit();

/*
$return = '<div class="page_title"><h1 class="title"><span>' . joosAdminView::get_component_title() . '</span></h1>';
$return .= joosAutoadmin::submenu() . '</div>';
$return .= '<div class="page_subtitle"><h2>' . $header . '</h2>' . self::toolbar($task) . '</div>';

return ;
*/

// пункты дополнительного меню компонента
?>

<div class="page-header">
    <ul class="nav nav-pills" style="float: right">
        <?php foreach(joosAdminView::get_submenu() as $submenu_item): ?>
        <li class="active"><a href="<?php echo $submenu_item['href'] ?>"  <?php echo ($submenu_item['active'] == false) ? '' : 'class="active"' ?>><?php echo $submenu_item['name'] ?></a></li>
        <?php endforeach ?>
    </ul>
    <h1><?php echo joosAdminView::get_component_title() .' / '. joosAdminView::get_component_header() ?></h1>
</div>

<div class="row">
    <div class="span6">
        <div class="pagination" style="float: left; margin-right: 10px">
            <ul>
                <li><a href="components-users-add-edit_user.html" title="Добавить"><i class="icon-plus-sign"></i></a></li>
            </ul>
        </div>

        <div class="pagination js-btn-group-for_select">
            <ul>
                <li class="disabled"><a href="#" title="Разрешить"><i class="icon-ok"></i></a></li>
                <li class="disabled"><a href="#"><i class="icon-remove" title="Запретить"></i></a></li>
                <li class="disabled"><a href="#"><i class="icon-trash" title="Удалить"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="span6">

        <div class="pagination"  style="float: right">
            <ul>
                <li class="disabled"><a href="#">«</a></li>
                <li class="active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">»</a></li>
            </ul>
        </div>

        <div style="float: right; padding: 30px 20px 0 0; font-weight: bold">1-25 из 520</div>
    </div>
</div>
