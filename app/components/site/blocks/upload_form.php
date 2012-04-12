<?php
/**
 * Шаблон формы загрузки файлов
 * 
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

joosUpload::init( 'test_images' );

?>

<script type="text/javascript" src="<?php echo JPATH_SITE ?>/media/js/jquery.plugins/jquery.ui.widget.js"></script>
<script type="text/javascript" src="<?php echo JPATH_SITE ?>/media/js/jquery.plugins/jquery.fileupload/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="<?php echo JPATH_SITE ?>/media/js/jquery.plugins/jquery.fileupload.js"></script>


<form id="fileupload" action="<?php echo joosUpload::get_upload_url() ?>" method="POST" enctype="multipart/form-data" multiple>
    <input id="<?php echo joosUpload::get_input_name() ?>" type="file" name="<?php echo joosUpload::get_input_name() ?>" class="<?php echo joosUpload::get_class() ?>" multiple="">
    <input type="hidden" name="rules_name" value="<?php echo joosUpload::get_input_name() ?>" />
</form>

<script>
    $(function () {
        $('#fileupload').fileupload({
            dataType: 'json',
            autoUpload: true,
            acceptFileTypes: <?php echo joosUpload::get_accept_file_types() ?>,
            url: '<?php echo joosUpload::get_upload_url() ?>',
            done: function (e, data) {

                if( data.result.success == true ){

                    joosNotify( data.result.file_name + ' загружен','success');
                }
            }
        });
    });
</script>

