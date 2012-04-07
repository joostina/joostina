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
    <h1><?php echo joosAdminView::get_component_title() ?></h1>
</div>


<form action="index2.php" class="form-horizontal"  name="admin_form" id="admin_form" method="post">

    <div id="admin-form">
        <section id="admin-form_header">
            <div class="row">
                <div class="span6">
                    <h2><?php echo joosAdminView::get_component_header() ?></h2>
                </div>
                <div class="span6">
                    <div style="float: right" class="btn-group">
                        <?php echo joosAdminToolbarButtons::edit('cancel')?>
                    </div>

                    <div style="float: right; margin-right: 12px" class="btn-group">
                        <?php echo joosAdminToolbarButtons::edit('remove')?>
                    </div>

                    <div style="float: right; margin-right: 10px" class="btn-group">
                        <?php echo joosAdminToolbarButtons::edit('save')?>
                        <?php echo joosAdminToolbarButtons::edit('save_and_new')?>
                    </div>
                </div>
            </div>
        </section>

        <section id="admin-form_body">

            <fieldset>
                <?php echo implode('', $_elements);?>
            </fieldset>

        </section>
    </div>

    <?php
    //Выводим скрытые поля формы
    echo forms::hidden($obj->get_key_field(), $obj_data->{$obj->get_key_field()}) . "\t"; // id объекта
    echo forms::hidden('option', $option) . "\t";
    echo forms::hidden('model', joosAdminView::get_current_model()) . "\t";
    echo forms::hidden('menu', joosAutoadmin::get_active_menu_name());
    echo forms::hidden('task', '') . "\t";
    echo forms::hidden(joosCSRF::get_code(), 1) . "\t"; // элемент защиты от XSS
    ?>

</form>