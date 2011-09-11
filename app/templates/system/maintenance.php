<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

joosRequest::send_headers_by_code(500);
?>
Техническое обслуживание