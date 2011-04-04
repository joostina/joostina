<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

return "
{
    controls: {
	bold: {visible: true},
	italic: {visible: true},
	html  : { visible: true },
	insertImage: {visible: false  },
	insertTable: {visible: false  },
	h1: {visible: false  },
	h2: {visible: false  },
	h3: {visible: false  },
	createLink : {visible: false  },
      strikeThrough : { visible : false },
      underline     : { visible : false },
      
      separator00 : { visible : false },
      
      justifyLeft   : { visible : false },
      justifyCenter : { visible : false },
      justifyRight  : { visible : false },
      justifyFull   : { visible : false },
      
      separator01 : { visible : false },
      
      indent  : { visible : false },
      outdent : { visible : false },
      
      separator02 : { visible : false },
      
      subscript   : { visible : false },
      superscript : { visible : false },
      
      separator03 : { visible : false },
      
      undo : { visible : false },
      redo : { visible : false },
      
      separator04 : { visible : false },
      
      insertOrderedList    : { visible : false },
      insertUnorderedList  : { visible : false },
      insertHorizontalRule : { visible : false },

      separator07 : { visible : false },
      
      cut   : { visible : false },
      copy  : { visible : false },
      paste : { visible : false }
    }
  }";