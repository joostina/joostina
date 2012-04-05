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
    <h1><?php echo joosAdminView::get_component_title() .' / '. joosAdminView::get_component_header() ?></h1>
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



    <?php
    		echo $header_extra['hidden_ellements'];
    		echo forms::hidden('option', $option);
    		echo forms::hidden('model', self::$model);
    		echo forms::hidden('menu', self::$submenu);
    		echo forms::hidden('task', $task);
    		echo forms::hidden('boxchecked', '');
    		echo forms::hidden('obj_name', get_class($obj));
    		echo forms::hidden(joosCSRF::get_code(), 1);
    ?>



</form>





