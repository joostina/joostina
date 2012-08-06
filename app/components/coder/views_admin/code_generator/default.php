<?php
// запрет прямого доступа
defined('_JOOS_CORE') or exit;

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
        <div class="span2">
            <form action="#" method="post" id="componenter_form">
                <label for="">Название компонента:</label>
                <input type="text" name="component_name" value="component_name"/>
                <br/><br/>

                <label for="">Заголовок компонента:</label>
                <input type="text" name="component_title" value="Заголовок"/>
                <br/><br/>

                <label for="">Описание компонента:</label>
                <input type="text" name="component_description" value="Управляет объектами на сайте"/>
                <br/><br/>

                <label for="">Автор:</label>
                <input type="text" name="component_author" value="Joostina Team"/>
                <br/><br/>

                <label for="">Email:</label>
                <input type="text" name="component_authoremail" value="info@joostina.ru"/>
                <br/><br/>

                <label for="">Copyright:</label>
                <input type="text" name="component_copyright" value="(C) 2007-2012 Joostina Team"/>
                <br/><br/>
            </form>
            <button id="create_component">Сгенерировать</button>
	        <button id="create_fs">Создать</button>
        </div>
        <div class="span10">
            <div id="componenter_results" class="span10" cols="25" rows="30"></div>
        </div>
    </div>
</section>
