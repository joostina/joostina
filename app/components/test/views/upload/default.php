<?php
/**
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

?>

<script type="text/javascript" src="<?php echo JPATH_SITE ?>/media/js/jquery.plugins/jquery.ui.widget.js"></script>
<script type="text/javascript" src="<?php echo JPATH_SITE ?>/media/js/jquery.plugins/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="<?php echo JPATH_SITE ?>/media/js/jquery.plugins/jquery.fileupload.js"></script>


<input id="fileupload" type="file" name="files[]" multiple>

<script>
	$(function () {
		$('#fileupload').fileupload({
			dataType: 'json',
			url: 'ajax.index.php?option=site&task=upload',
			done: function (e, data) {
				$.each(data.result, function (index, file) {
					$('<p/>').text(file.name).appendTo(document.body);
				});
			}
		});
	});
</script>