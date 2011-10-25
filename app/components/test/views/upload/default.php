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

joosDocument::instance()
		->add_css(sprintf('%s/app/vendors/upload/media/css/fileuploader.css', JPATH_SITE))
		->add_js_file(sprintf('%s/app/vendors/upload/media/js/fileuploader.js', JPATH_SITE));

echo time();
?>
<div id="file-uploader-demo1">		
	<noscript>			
	<p>Please enable JavaScript to use file uploader.</p>
	<!-- or put a simple form for upload here -->
	</noscript>         
</div>
<script>        
	function createUploader(){            
		var uploader = new qq.FileUploader({
			element: document.getElementById('file-uploader-demo1'),
			action: '/ajax.index.php?option=test&task=upload',
			debug: true
		});           
	}

	window.onload = createUploader;     
</script>   