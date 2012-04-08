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

        <div class="span4">
            <h3>Таблицы</h3>
            <div class="well">
                <form action="/" id="faker_form" class="form-horizontal">
                    <?php foreach($tables as $table):?>
                        <div class="control-group">
                            <label class="radio">
                                <input type="radio" value="<?php echo $table ?>" name="fakertable[]">
                                <?php echo $table ?>
                            </label>
                        </div>
                    <?php endforeach;?>
                </form>
            </div>
        </div>

        <div class="span8">
            <h3>Правила заполнения</h3>
            <div id="faker_results"></div>
        </div>

    </div>
</section>

    