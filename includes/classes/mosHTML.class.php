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

class mosHTML {

	public static function makeOption($value,$text = '',$value_name = 'value',$text_name = 'text') {
		$obj = new stdClass;
		$obj->$value_name = $value;
		$obj->$text_name = trim($text) ? $text : $value;
		return $obj;
	}

	public static function writableCell($folder,$relative = 1,$text = '',$visible = 1) {

		$writeable		= '<b><font color="green">'._WRITEABLE.'</font></b>';
		$unwriteable	= '<b><font color="red">'._UNWRITEABLE.'</font></b>';

		$ret = array();
		$ret[] ='<tr>';
		$ret[] = '<td class="item">';
		$ret[] = $text;
		if($visible) {
			$ret[] = $folder.'/';
		}
		$ret[] = '</td><td align="left">';
		$ret[] = $relative ? ( is_writable("../$folder") ? $writeable:$unwriteable ) : ( is_writable($folder) ? $writeable:$unwriteable );
		$ret[] = '</td></tr>';
		echo implode('', $ret);
	}

	public static function selectList(&$arr,$tag_name,$tag_attribs,$key,$text,$selected = null, $first_el_key = '*000', $first_el_text = '*000') {

		is_array($arr) ? reset($arr) : null;

		$html = "<select name=\"$tag_name\" $tag_attribs>";

		if ($first_el_key!='*000' && $first_el_text!='*000') {
			$html .= "\n\t<option value=\"$first_el_key\">$first_el_text</option>";
		}

		$count = count($arr);
		for($i = 0,$n = $count; $i < $n; $i++) {
			$k = $arr[$i]->$key;
			$t = $arr[$i]->$text;
			$id = (isset($arr[$i]->id)?@$arr[$i]->id:null);

			$extra = '';
			$extra .= $id?" id=\"".$arr[$i]->id."\"":'';
			if(is_array($selected)) {
				foreach($selected as $obj) {
					$k2 = $obj->$key;
					if($k == $k2) {
						$extra .= " selected=\"selected\"";
						break;
					}
				}
			} else {
				$extra .= ($k == $selected ? " selected=\"selected\"" : '' );
			}
			$html .= "\n\t<option value=\"".$k."\"$extra>".$t."</option>";
		}
		$html .= "\n</select>\n";

		return $html;
	}

	public static function integerSelectList($start,$end,$inc,$tag_name,$tag_attribs,$selected,$format ="") {
		$start = (int) $start;
		$end = (int) $end;
		$inc = (int) $inc;
		$arr = array();

		for($i = $start; $i <= $end; $i += $inc) {
			$fi = $format ? sprintf("$format",$i):"$i";
			$arr[] = mosHTML::makeOption($fi,$fi);
		}

		return mosHTML::selectList($arr,$tag_name,$tag_attribs,'value','text',$selected);
	}

	public static function monthSelectList($tag_name,$tag_attribs,$selected,$type = 0) {
		// месяца для выбора
		$arr_1 = array(
				mosHTML::makeOption('01',_JAN),
				mosHTML::makeOption('02',_FEB),
				mosHTML::makeOption('03',_MAR),
				mosHTML::makeOption('04',_APR),
				mosHTML::makeOption('05',_MAY),
				mosHTML::makeOption('06',_JUN),
				mosHTML::makeOption('07',_JUL),
				mosHTML::makeOption('08',_AUG),
				mosHTML::makeOption('09',_SEP),
				mosHTML::makeOption('10',_OCT),
				mosHTML::makeOption('11',_NOV),
				mosHTML::makeOption('12',_DEC)
		);
		// месяца с правильным склонением
		$arr_2 = array(
				mosHTML::makeOption('01',_JAN_2),
				mosHTML::makeOption('02',_FEB_2),
				mosHTML::makeOption('03',_MAR_2),
				mosHTML::makeOption('04',_APR_2),
				mosHTML::makeOption('05',_MAY_2),
				mosHTML::makeOption('06',_JUN_2),
				mosHTML::makeOption('07',_JUL_2),
				mosHTML::makeOption('08',_AUG_2),
				mosHTML::makeOption('09',_SEP_2),
				mosHTML::makeOption('10',_OCT_2),
				mosHTML::makeOption('11',_NOV_2),
				mosHTML::makeOption('12',_DEC_2)
		);
		$arr = $type ? $arr_2 : $arr_1;
		return mosHTML::selectList($arr,$tag_name,$tag_attribs,'value','text',$selected);
	}

	public static function daySelectList($tag_name,$tag_attribs,$selected) {
		$arr = array();

		for($i = 1; $i <= 31; $i++) {
			$pref = '';
			if($i <= 9) {
				$pref = '0';
			}
			$arr[] = mosHTML::makeOption($pref.$i,$pref.$i);
		}

		return mosHTML::selectList($arr,$tag_name,$tag_attribs,'value','text',$selected);
	}

	public static function yearSelectList($tag_name,$tag_attribs,$selected, $min = 1900, $max=null ) {

		$max = ( $max == null) ? date('Y',time()) : $max;

		$arr = array();
		for($i = $min; $i <= $max; $i++) {
			$arr[] = mosHTML::makeOption($i,$i);
		}
		return mosHTML::selectList($arr,$tag_name,$tag_attribs,'value','text',$selected);
	}

	public static function genderSelectList($tag_name,$tag_attribs,$selected) {
		$arr = array(
				mosHTML::makeOption('no_gender',_GENDER_NONE),
				mosHTML::makeOption('male',_MALE),
				mosHTML::makeOption('female',_FEMALE)
		);
		return mosHTML::selectList($arr,$tag_name,$tag_attribs,'value','text',$selected);
	}

	public static function treeSelectList(&$src_list,$src_id,$tgt_list,$tag_name,$tag_attribs,$key,$text,$selected) {
		// establish the hierarchy of the menu
		$children = array();
		// first pass - collect children
		foreach($src_list as $v) {
			$pt = $v->parent;
			$list = isset($children[$pt]) ? $children[$pt] : array();
			array_push($list,$v);
			$children[$pt] = $list;
		}
		// second pass - get an indent list of the items
		$ilist = mosTreeRecurse(0,'',array(),$children);

		// assemble menu items to the array
		$this_treename = '';
		foreach($ilist as $item) {
			if($this_treename) {
				if($item->id != $src_id && strpos($item->treename,$this_treename) === false) {
					$tgt_list[] = mosHTML::makeOption($item->id,$item->treename);
				}
			} else {
				if($item->id != $src_id) {
					$tgt_list[] = mosHTML::makeOption($item->id,$item->treename);
				} else {
					$this_treename = "$item->treename/";
				}
			}
		}
		// build the html select list
		return mosHTML::selectList($tgt_list,$tag_name,$tag_attribs,$key,$text,$selected);
	}

	public static function yesnoSelectList($tag_name,$tag_attribs,$selected,$yes = _YES,$no =_NO) {
		$arr = array(
				mosHTML::makeOption('0',$no),
				mosHTML::makeOption('1',$yes)
		);

		return mosHTML::selectList($arr,$tag_name,$tag_attribs,'value','text',$selected);
	}

	public static function radioList(&$arr,$tag_name,$tag_attribs,$selected = null,$key = 'value',$text = 'text') {
		reset($arr);

		$html = '';
		for($i = 0,$n = count($arr); $i < $n; $i++) {
			$k = $arr[$i]->$key;
			$t = $arr[$i]->$text;
			$id = isset($arr[$i]->id) ? @$arr[$i]->id : null;

			$extra = '';
			$extra .= $id?" id=\"".$arr[$i]->id."\"":'';
			if(is_array($selected)) {
				foreach($selected as $obj) {
					$k2 = $obj->$key;
					if($k == $k2) {
						$extra .= " selected=\"selected\"";
						break;
					}
				}
			} else {
				$extra .= ($k == $selected?" checked=\"checked\"":'');
			}
			$html .= "\n\t<input type=\"radio\" name=\"$tag_name\" id=\"$tag_name$k\" value=\"".$k."\"$extra $tag_attribs />";
			$html .= "\n\t<label for=\"$tag_name$k\">$t</label>";
		}
		$html .= "\n";

		return $html;
	}

	public static function yesnoRadioList($tag_name,$tag_attribs,$selected,$yes = _YES,$no = _NO) {
		$arr = array(
				mosHTML::makeOption('0',$no),
				mosHTML::makeOption('1',$yes)
		);

		return mosHTML::radioList($arr,$tag_name,$tag_attribs,$selected);
	}

	public static function idBox($rowNum,$recId,$checkedOut = false,$name = 'cid') {
		return $checkedOut ? '' : '<input boxtype="idbox" type="checkbox" id="cb'.$rowNum.'" name="'.$name.'[]" value="'.$recId.'" onclick="isChecked(this.checked);" />';
	}

	public static function sortIcon($base_href,$field,$state = 'none') {
		$alts = array('none' => _SORT_NONE,'asc' => _SORT_ASC,'desc' =>_SORT_DESC,);
		$next_state = 'asc';
		if($state == 'asc') {
			$next_state = 'desc';
		} elseif($state == 'desc') {
			$next_state = 'none';
		}

		return '<a href="'.$base_href.'&field='.$field.'&order='.$next_state.'"><img src="'.JPATH_SITE.'/'.JADMIN_BASE.'/images/sort_'.$state.'.png" width="12" height="12" border="0" alt="'.$alts[$next_state].'" /></a>';
	}

	public static function CloseButton(&$params,$hide_js = null) {
		// displays close button in Pop-up window
		if($params->get('popup') && !$hide_js) {
			?>
<script language="javascript" type="text/javascript">
	<!--
	document.write('<div align="center" style="margin-top: 30px; margin-bottom: 30px;">');
	document.write('<a class="print_button" href="#" onclick="javascript:window.close();"><span class="small"><?php echo _PROMPT_CLOSE; ?></span></a>');
	document.write('</div>');
	//-->
</script>
			<?php
		}
	}

	public static function BackButton(&$params = null,$hide_js = null) {
		if( !$params ||  ($params->get('back_button')==1 && !$params->get('popup') && !$hide_js) || ($params->get('back_button') == -1 && Jconfig::getInstance()->config_back_button == 1 ) ) {
			include_once(JPATH_BASE.'/templates/system/back_button.php');
		}else {
			return false;
		}
	}

	/*
	public static function get_image($file, $directory = 'system', $front = 0) {

		$path = (!$front) ? '/'.JADMIN_BASE.'/templates/'.JTEMPLATE.'/images/'.$directory.'/' : '/templates/'.JTEMPLATE.'/images/elements/';

		$image = '';
		if(is_file(JPATH_BASE.$path.$file)) {
			$image = JPATH_SITE.$path.$file;
		} elseif(is_file(JPATH_BASE.DS.$directory.DS.$file)) {
			$image = JPATH_SITE.'/'.$directory.'/'.$file;
		}

		if($image) {
			$image = '<img src="'.$image.'" alt="" border="0" />';
			return $image;
		}

		return false;
	}
	*/

	// TODO, перемещено в библиотеку Text
	public static function cleanText(&$text) {
		mosMainFrame::addLib('text');
		return Text::cleanText($text);
	}

	public static function PrintIcon($row,&$params,$hide_js,$link,$status = null) {
		global $cpr_i;

		if(!isset($cpr_i)) {
			$cpr_i = '';
		}

		if($params->get('print') && !$hide_js) {
			// use default settings if none declared
			if(!$status) {
				$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
			}
			// checks template image directory for image, if non found default are loaded
			if($params->get('icons')) {
				$image = mosAdminMenus::ImageCheck('printButton.png','/images/M_images/',null,null,_PRINT,'print'.$cpr_i);
				$cpr_i++;
			} else {
				$image = _ICON_SEP.'&nbsp;'._PRINT.'&nbsp;'._ICON_SEP;
			}
			if($params->get('popup') && !$hide_js) {
				?>
<script language="javascript" type="text/javascript">
	<!--
	document.write('<a href="#" class="print_button" onclick="javascript:window.print(); return false;" title="<?php echo _PRINT; ?>">');
	document.write('<?php echo $image; ?>');
	document.write('</a>');
	//-->
</script>
				<?php
			} else {
				?>
				<?php if(!Jconfig::getInstance()->config_index_print) { ?>
<span style="display:none"><![CDATA[<noindex>]]></span><a href="#" rel="nofollow" target="_blank" onclick="window.open('<?php echo $link; ?>','win2','<?php echo $status; ?>'); return false;" title="<?php echo _PRINT; ?>"><?php echo $image; ?></a><span style="display:none"><![CDATA[</noindex>]]></span>
						<?php } else { ?>
<a href="<?php echo $link; ?>" target="_blank" title="<?php echo _PRINT; ?>"><?php echo $image; ?></a>
					<?php } ; ?>

				<?php
			}
		}
	}

	public static function emailCloaking($mail,$mailto = 1,$text = '',$email = 1) {
		// convert text
		$mail = mosHTML::encoding_converter($mail);
		// split email by @ symbol
		$mail = explode('@',$mail);
		$mail_parts = explode('.',$mail[1]);
		// random number
		$rand = rand(1,100000);
		$replacement = "\n <script language='JavaScript' type='text/javascript'>";
		$replacement .= "\n <!--";
		$replacement .= "\n var prefix = '&#109;a' + 'i&#108;' + '&#116;o';";
		$replacement .= "\n var path = 'hr' + 'ef' + '=';";
		$replacement .= "\n var addy".$rand." = '".@$mail[0]."' + '&#64;';";
		$replacement .= "\n addy".$rand." = addy".$rand." + '".implode("' + '&#46;' + '",
				$mail_parts)."';";
		if($mailto) {
			// special handling when mail text is different from mail addy
			if($text) {
				if($email) {
					// convert text
					$text = mosHTML::encoding_converter($text);
					// split email by @ symbol
					$text = explode('@',$text);
					$text_parts = explode('.',$text[1]);
					$replacement .= "\n var addy_text".$rand." = '".@$text[0]."' + '&#64;' + '".
							implode("' + '&#46;' + '",@$text_parts)."';";
				} else {
					$replacement .= "\n var addy_text".$rand." = '".$text."';";
				}
				$replacement .= "\n document.write( '<a ' + path + '\'' + prefix + ':' + addy".
						$rand." + '\'>' );";
				$replacement .= "\n document.write( addy_text".$rand." );";
				$replacement .= "\n document.write( '<\/a>' );";
			} else {
				$replacement .= "\n document.write( '<a ' + path + '\'' + prefix + ':' + addy".
						$rand." + '\'>' );";
				$replacement .= "\n document.write( addy".$rand." );";
				$replacement .= "\n document.write( '<\/a>' );";
			}
		} else {
			$replacement .= "\n document.write( addy".$rand." );";
		}
		$replacement .= "\n //-->";
		$replacement .= '\n </script>';
		$replacement .= "<script language='JavaScript' type='text/javascript'>";
		$replacement .= "\n <!--";
		$replacement .= "\n document.write( '<span style=\'display: none;\'>' );";
		$replacement .= "\n //-->";
		$replacement .= "\n </script>";
		$replacement .= _CLOAKING;
		$replacement .= "\n <script language='JavaScript' type='text/javascript'>";
		$replacement .= "\n <!--";
		$replacement .= "\n document.write( '</' );";
		$replacement .= "\n document.write( 'span>' );";
		$replacement .= "\n //-->";
		$replacement .= "\n </script>";

		return $replacement;
	}

	public static function encoding_converter($text) {
		$text = str_replace('a','&#97;',$text);
		$text = str_replace('e','&#101;',$text);
		$text = str_replace('i','&#105;',$text);
		$text = str_replace('o','&#111;',$text);
		return str_replace('u','&#117;',$text);
	}
}