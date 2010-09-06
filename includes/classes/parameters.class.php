<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

class mosParameters {

	private $_params;
	private $_raw;
	private $_path;
	private $_type;
	private $_xmlElem;

	public function  __construct($text,$path = '',$type = 'component') {
		JDEBUG ? jd_inc('mosParameters') : null;

		$params = $this->parse($text);
		$this->_params = empty ($params) ? new stdClass : $params ;
		$this->_raw = $text;
		$this->_path = $path;
		$this->_type = $type;
	}

	public function toObject() {
		return $this->_params;
	}

	public function toArray() {
		return mosObjectToArray($this->_params);
	}

	public function set($key,$value = '') {
		return $this->_params->$key = $value;
	}

	public function def($key,$value = '') {
		return $this->set($key,$this->get($key,$value));
	}

	public  function get($key,$default = '') {
		if(isset($this->_params->$key)) {
			return $this->_params->$key === '' ? $default : $this->_params->$key;
		} else {
			return $default;
		}
	}

	public static function parse($txt,$process_sections = false,$asArray = false) {

		JDEBUG ? jd_inc('mosParameters::parse') : null;

		$r = json_decode( $txt ,$asArray);

		return $r;
	}

	public function render($name = 'params') {

		if($this->_path) {
			if(!is_object($this->_xmlElem)) {
				require_once (JPATH_BASE.'/includes/domit/xml_domit_lite_include.php');
				$xmlDoc = new DOMIT_Lite_Document();
				$xmlDoc->resolveErrors(true);
				if($xmlDoc->loadXML($this->_path,false,true)) {
					$root = $xmlDoc->documentElement;
					$tagName = $root->getTagName();
					$isParamsFile = ($tagName == 'mosinstall' || $tagName == 'mosparams');
					if($isParamsFile && $root->getAttribute('type') == $this->_type) {
						if($params = $root->getElementsByPath('params',1)) {
							$this->_xmlElem = &$params;
						}
					}
				}
			}
		}
		if(is_object($this->_xmlElem)) {
			$html = array();
			$element = &$this->_xmlElem;
			$html[] = '<table width="100%" class="paramlist">';

			if($description = $element->getAttribute('description')) {
				$html[] = '<tr><td colspan="2">'.$description.'</td></tr>';
			}
			$this->_methods = get_class_methods(get_class($this));

			foreach($element->childNodes as $param) {
				$result = $this->renderParam($param,$name);

				switch ($result[5]) {
					case 'newtable':
						$html[] = '</table>';
						$html[] = '<table width="100%" class="paramlist">';
						break;

					case 'tabs':
						$html[] = $result[1];
						break;

					default:
						$html[] = '<tr>';
						$html[] = trim($result[0])!='&nbsp;' ? '<td width="40%" align="right" valign="top" class="pkey"><span class="editlinktip">'.$result[0].'</span></td>' : '';
						$html[] = '<td '.(trim($result[0])=='&nbsp;' ? 'coolspan="2"':'').' >'.$result[1].'</td>';
						$html[] = '</tr>';
						break;
				}

			}
			if(count($element->childNodes) < 1) {
				$html[] = "<tr><td colspan=\"2\"><i>"._NO_PARAMS."</i></td></tr>";
			}
			$html[] = '</table>';

			return implode("\n",$html);
		} else {
			return "<textarea name=\"$name\" cols=\"40\" rows=\"10\" class=\"text_area\">$this->_raw</textarea>";
		}
	}

	public function renderParam(&$param,$control_name = 'params') {

		$result = array();

		$name = $param->getAttribute('name');
		$label = $param->getAttribute('label');

		$value = $this->get($name,$param->getAttribute('default'));
		$description = $param->getAttribute('description');

		$result[0] = $label ? $label : $name;

		if($result[0] == '@spacer') {
			$result[0] = '&nbsp;';
		} else {
			$result[0] = mosToolTip(addslashes($description),addslashes($result[0]),'','',$result[0],'#',0);
		}
		$type = $param->getAttribute('type');
		if(in_array('_form_'.$type,$this->_methods)) {
			$result[1] = call_user_func(array(&$this,'_form_'.$type),$name,$value,$param,$control_name, $label);
		} else {
			$result[1] = _HANDLER.' = '.$type;
		}

		if($description) {
			$result[2] = mosToolTip($description,$result[0]);
			$result[2] = '';
		} else {
			$result[2] = '';
		}
		$result[3]=$description;
		$result[4]=$label;
		$result[5]=$type;
		return $result;
	}

	private function _form_text($name,$value,&$node,$control_name) {
		$size = $node->getAttribute('size');
		return '<input type="text" name="'.$control_name.'['.$name.']" value="'.htmlspecialchars($value).'" class="text_area" size="'.$size.'" />';
	}

	private function _form_list($name,$value,&$node,$control_name) {

		$options = array();
		foreach($node->childNodes as $option) {
			$val = $option->getAttribute('value');
			$text = $option->gettext();
			$options[] = mosHTML::makeOption($val,$text);
		}

		return mosHTML::selectList($options,''.$control_name.'['.$name.']','class="inputbox"','value','text',$value);
	}

	private function _form_radio($name,$value,&$node,$control_name) {

		$options = array();
		foreach($node->childNodes as $option) {
			$val = $option->getAttribute('value');
			$text = $option->gettext();
			$options[] = mosHTML::makeOption($val,$text);
		}

		return mosHTML::radioList($options,''.$control_name.'['.$name.']','',$value);
	}

	private function _form_mos_menu($name,$value,$node,$control_name) {
		$menuTypes = mosAdminMenus::menutypes();

		foreach($menuTypes as $menutype) {
			$options[] = mosHTML::makeOption($menutype,$menutype);
		}
		array_unshift($options,mosHTML::makeOption('',_ET_MENU));

		return mosHTML::selectList($options,''.$control_name.'['.$name.']','class="inputbox"','value','text',$value);
	}

	private function _form_filelist($name,$value,&$node,$control_name) {

		$path = JPATH_BASE.$node->getAttribute('directory');
		$filter = $node->getAttribute('filter');
		$files = mosReadDirectory($path,$filter);

		$options = array();
		foreach($files as $file) {
			$options[] = mosHTML::makeOption($file,$file);
		}

		if(!$node->getAttribute('hide_none')) {
			array_unshift($options,mosHTML::makeOption('-1',_USE_FILE));
		}
		if(!$node->getAttribute('hide_default')) {
			array_unshift($options,mosHTML::makeOption('',_DEFAULT_IMAGE));
		}

		return mosHTML::selectList($options,''.$control_name.'['.$name.']','class="inputbox"','value','text',$value,"param$name");
	}

	private function _form_imagelist($name,$value,&$node,$control_name) {
		$node->setAttribute('filter','\.png$|\.gif$|\.jpg$|\.bmp$|\.ico$');
		return $this->_form_filelist($name,$value,$node,$control_name);
	}

	private function _form_moduletemplates($name,$value,&$node,$control_name) {

		$options = array();

		$path = JPATH_BASE.DS.'modules'.DS.$node->getAttribute('directory').DS.'views';
		$files = mosReadDirectory($path,'\.php$');

		foreach($files as $file) {
			$options[] = mosHTML::makeOption( $file, _FILE_FROM_SYSTEM.$file );
		}

		mosMainFrame::getInstance()->setTemplate();
		$cur_template = mosMainFrame::getInstance()->getTemplate();

		$path = JPATH_BASE.DS.'templates'.DS.$cur_template.DS.'html/modules'.DS.$node->getAttribute('directory');
		$files = mosReadDirectory($path,'\.php$');

		foreach($files as $file) {
			$options[] = mosHTML::makeOption( $file, _FILE_FROM_TEMPLATE.$file );
		}

		if(!$node->getAttribute('hide_none')) {
			array_unshift($options,mosHTML::makeOption('-1',_USE_FILE));
		}
		if(!$node->getAttribute('hide_default')) {
			array_unshift($options,mosHTML::makeOption('',_DEFAULT_FILE));
		}

		return mosHTML::selectList($options,''.$control_name.'['.$name.']','class="inputbox"','value','text',$value,"param$name");
	}

	private function _form_textarea($name,$value,&$node,$control_name) {
		$rows = $node->getAttribute('rows');
		$cols = $node->getAttribute('cols');
		$value = str_replace('<br />',"\n",$value);

		return '<textarea name="'.$control_name.'['.$name.']" cols="'.$cols.'" rows="'.$rows.'" class="text_area">'.htmlspecialchars($value).'</textarea>';
	}

	private function _form_spacer($name,$value) {
		return ($value) ? $value : '<hr />';
	}

	private function _form_tabs($name,$value,$param,$control_name, $label) {

		$js  = JHTML::js_file( JPATH_SITE.'/media/js/tabs.js' );
		$css = JHTML::css_file( JPATH_SITE.'/media/js/tabs/tabpane.css' );

		$return = '';

		switch ($value) {
			case 'startPane':
				$return .= '<tr><td></td></tr></table>';
				$return .= $css;
				$return .= $js;
				$return .= '<div class="tab-page" id="'.$name.'">';
				$return .= '<script type="text/javascript">var tabPane1 = new WebFXTabPane( document.getElementById( "'.$name.'" ),0)</script>';
				break;

			case 'endPane':
				$return .= '</div><table>';
				break;

			case 'startTab':
				$return .= '<div class="tab-page" id="'.$name.'">';
				$return .= '<h2 class="tab">'.$label.'</h2>';
				$return .= '<script type="text/javascript">tabPane1.addTabPage( document.getElementById( "'.$name.'" ) );</script>';
				$return .= '<table width="100%" class="paramlist">';
				break;

			case 'endTab':
				$return .= '</table></div>';
				break;

			default:
				break;
		}

		return $return;
	}

	private function _form_sql_selector($name,$value,&$node,$control_name) {
		$sql = $node->getAttribute('sql');
		$rows = database::getInstance()->setQuery($sql)->loadObjectList();

		$default = $value ? $value : $node->getAttribute('default');

		$null[] = mosHTML::makeOption(-1, _SELECT_OBJ);
		$rows = array_merge( $null, $rows);
		return mosHTML::selectList($rows,$control_name.'['.$name.']','class="inputbox" size="1"','value','text', $default ? $default : -1 );

	}

	public static function textareaHandling(&$txt) {
		$total = count($txt);

		for($i = 0; $i < $total; $i++) {
			if(strstr($txt[$i],"\n")) {
				$txt[$i] = str_replace("\n",'<br />',$txt[$i]);
			}
		}
		return implode("\n",$txt);
	}
}

function mosParseParams( $txt ) {
	return mosParameters::parse($txt);
}