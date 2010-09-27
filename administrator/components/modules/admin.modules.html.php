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

class HTML_modules {

	public static function showModules(&$rows,$myid,$client,&$pageNav,$option,&$lists,$search) {
		global $my;

		$cur_file_icons_path = JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE.'/images/ico';
		?>
<script type="text/javascript">
	function ch_get_positon(elID){
		SRAX.replaceHtml('mod-id-'+elID,'<img src="images/aload.gif" />');
		dax({
			url: 'ajax.index.php?option=com_modules&task=position&id='+elID,
			id:'publ-'+elID,
			callback:
				function(resp, idTread, status, ops){
				SRAX.replaceHtml('mod-id-'+elID,resp.responseText);
			}});
	}
	// смена позиции модуля
	function ch_sav_pos(elID,newPOS){
		SRAX.replaceHtml('mod-id-'+elID,'<img src="images/aload.gif" />');
		dax({
			url: 'ajax.index.php?option=com_modules&task=save_position&id='+elID+'&new_pos='+newPOS,
			id:'publ-'+elID,
			callback:
				function(resp, idTread, status, ops){
				if(resp.responseText==1)
					SRAX.replaceHtml('mod-id-'+elID,'<a href="#" onclick="ch_get_positon(\''+elID+'\');" >'+newPOS+'</a>');
				else
					SRAX.replaceHtml('mod-id-'+elID,'<img src="<?php echo $cur_file_icons_path;?>/error.png" />');
			}});
	}
</script>
<form action="index2.php" method="post" name="adminForm">
	<table class="adminheading">
		<tr>
			<th class="modules" rowspan="2">
						<?php echo _MODULES?> <small><small>[ <?php echo $client == 'admin'?_CONTROL_PANEL:_SITE; ?> ]</small></small>
			</th>
			<td width="right"><?php echo $lists['position']; ?></td>
			<td width="right"><?php echo $lists['type']; ?></td>
			<td align="right"><?php echo _FILTER?>:</td>
			<td>
				<input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="text_area" onChange="document.adminForm.submit();" />
			</td>
		</tr>

	</table>
	<table class="adminlist">
		<tr>
			<th width="20px">#</th>
			<th width="2%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" />
			</th>
			<th class="title"><?php echo _MODULE_NAME?></th>
			<th class="jtd_nowrap" width="10%"><?php echo _PUBLISHED?></th>
			<th colspan="2" align="center" width="5%"><?php echo _ORDERING?></th>
			<th width="2%"><?php echo _ORDER_DROPDOWN?></th>
			<th width="1%">
				<a href="javascript: saveorder( <?php echo count($rows) - 1; ?> )"><img src="<?php echo $cur_file_icons_path;?>/saveorder.png" border="0" width="16" height="16" alt="<?php echo _SAVE_ORDER?>" /></a>
			</th>
					<?php
					if(!$client) {
						?>
			<th class="jtd_nowrap" width="7%"><?php echo _ACCESS?></th>
						<?php
					}
					?>
			<th class="jtd_nowrap" width="7%"><?php echo _MODULE_POSITION?></th>
			<th class="jtd_nowrap" width="5%"><?php echo _PAGES?></th>
			<th class="jtd_nowrap" width="5%">ID</th>
			<th class="jtd_nowrap" width="10%" align="left"><?php echo _TYPE?></th>
		</tr>
				<?php
				$k = 0;
				$_n = count($rows);
				for($i = 0,$n = $_n; $i < $n; $i++) {
					$row = &$rows[$i];
					mosMakeHtmlSafe($row);
					$link		= 'index2.php?option=com_modules&client='.$client.'&task=editA&hidemainmenu=1&id='.$row->id;
					$access		= mosCommonHTML::AccessProcessing($row,$i,1);
					$checked	= mosCommonHTML::CheckedOutProcessing($row,$i);
					$title = $row->published ?  _PUBLISHED : _UNPUBLISHED;
					$img = $row->published ? 'publish_g.png' : 'publish_x.png';
					$img = $cur_file_icons_path.'/'.$img;
					?>
		<tr class="<?php echo "row$k"; ?>" id="tr-el-<?php echo $row->id;?>">
			<td align="right"><?php echo $pageNav->rowNumber($i); ?></td>
			<td><?php echo $checked; ?></td>
			<td align="left">
							<?php
							if($row->checked_out && ($row->checked_out != $my->id)) {
								echo $row->title;
							} else {
								?>
				<a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
								<?php
							}
							?>
			</td>
            <td align="center" class="td-state">
                <img class="img-mini-state" src="<?php echo $img;?>" obj_id="<?php echo $row->id;?>" obj_task="publish" alt="<?php echo $title?>" title="<?php echo $title?>" />
            </td>
			<td><?php echo $pageNav->orderUpIcon($i,($row->position == @$rows[$i - 1]->position)); ?></td>
			<td><?php echo $pageNav->orderDownIcon($i,$n,($row->position == @$rows[$i + 1]->position)); ?></td>
			<td align="center" colspan="2">
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
			</td>
						<?php
						if(!$client) {
							?>
			<td id="acc-id-<?php echo $row->id;?>" align="center"><?php echo $access; ?></td>
							<?php
						}
						?>
			<td id="mod-id-<?php echo $row->id; ?>" align="center">
							<?php
							if($row->checked_out && ($row->checked_out != $my->id))
								echo $row->position;
							else
								echo '<a href="#" onclick="ch_get_positon('.$row->id.');" >'.$row->position.'</a>';
							?>
			</td>
			<td align="center">
							<?php
							if(is_null($row->pages)) {
								echo _NO;
							} else
							if($row->pages > 0) {
								echo _PAGES_SOME;
							} else {
								echo _ALL;
							}
							?>
			</td>
			<td align="center"><?php echo $row->id; ?></td>
			<td align="left"><?php echo $row->module?$row->module:"User"; ?></td>
		</tr>
					<?php
					$k = 1 - $k;
				}
				?>
	</table>
			<?php echo $pageNav->getListFooter(); ?>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="client" value="<?php echo $client; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="hidemainmenu" value="0" />
	<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
</form>
		<?php
	}

	public static function editModule( mosModule $row,&$orders2, array $lists, mosParameters $params,$option) {
		global $mosConfig_cachepath,$my;
		$row->title = htmlspecialchars($row->title);
		
		?>
<script language="javascript" type="text/javascript">
	function ch_apply(){
		SRAX.get('tb-apply').className='tb-load';
		dax({
			url: 'ajax.index.php?option=com_modules&task=apply',
			id:'publ-1',
			method:'post',
			form: 'adminForm',
			callback:
				function(resp){
				mess_cool(resp.responseText);
				SRAX.get('tb-apply').className='tb-apply';
			}});
	}
	function submitbutton(pressbutton) {
		if ( ( pressbutton == 'save' ) && ( document.adminForm.title.value == "" ) ) {
			alert("<?php echo _PLEASE_ENTER_MODULE_NAME?>");
		} else {
		<?php if($row->module == "") {
			getEditorContents('editor1','content');
		} ?>
				}
				submitform(pressbutton);
			}
			<!--
			var originalOrder = '<?php echo $row->ordering; ?>';
			var originalPos = '<?php echo $row->position; ?>';
			var orders = new Array();	// array in the format [key,value,text]
		<?php $i = 0;
		foreach($orders2 as $k => $items) {
			foreach($items as $v) {
				echo "\n orders[".$i++."] = new Array( \"$k\",\"$v->value\",\"$v->text\" );";
			}
		}
		?>
			//-->
</script>
<table class="adminheading">
	<tr>
		<th class="modules"><?php echo _MODULE .' '.($lists['client_id'] ? _CONTROL_PANEL_2 : _SITE_2). ($row->id ? ' # '.$row->id.', ' : '' ).' '.$row->title.'' ?>  -
			<small><?php echo $row->id ? _EDITING : _NEW ; ?></small></th>
	</tr>
</table>
<form action="index2.php" method="post" name="adminForm" id="adminForm">
	<table cellspacing="0" cellpadding="0" width="100%">
		<tr valign="top">
			<td width="60%">
				<table class="adminform">
					<tr>
						<th colspan="2"><?php echo _DETAILS?></th>
					</tr>
					<tr>
						<td width="100" class="key"><?php echo _CAPTION?>:</td>
						<td>
							<input class="text_area" type="text" name="title" size="35" value="<?php echo $row->title; ?>" />
						</td>
					</tr>
					<tr>
						<td width="100" class="key"><?php echo _SHOW_TITLE?>:</td>
						<td><?php echo $lists['showtitle']; ?></td>
					</tr>
					<tr>
						<td valign="top" class="key"><?php echo _MODULE_POSITION?>:</td>
						<td>
									<?php echo $lists['position']; ?>
							<script language="javascript" type="text/javascript">writeDynaList( 'class="inputbox" name="ordering" size="1"', orders, originalPos, originalPos, originalOrder );</script>
		
						</td>
					</tr>
					<tr>
						<td valign="top" class="key"><?php echo _ACCESS?>:</td>
						<td><?php echo $lists['access']; ?></td>
					</tr>
					<tr>
						<td valign="top" class="key"><?php echo _PUBLISHED?>:</td>
						<td><?php echo $lists['published']; ?></td>
					</tr>
					<tr>
						<td valign="top" class="key"><?php echo _CACHE_TO?>:</td>
						<td><?php echo self::cache_time_selector($row->cache_time); ?></td>
					</tr>
					<tr>
						<td valign="top" class="key"><?php echo _DESCRIPTION?>:</td>
						<td><?php echo $row->description; ?></td>
					</tr>
				</table>
				<br />
				<table class="adminform">
					<tr>
						<th><?php echo _PARAMETERS?></th>
					</tr>
					<tr>
						<td><?php echo $params->render(); ?></td>
					</tr>
				</table>
			</td>
			<td width="40%" >
				<table width="100%" class="adminform">
					<tr>
						<th><?php echo _MODULE_PAGE_MENU_ITEMS?></th>
					</tr>
					<tr>
						<td><?php echo _MENU_LINK?>:<br /><?php echo $lists['selections']; ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
				<?php
				if($row->module == "") {
					?>
		<tr>
			<td colspan="2">
				<table width="100%" class="adminform">
					<tr>
						<th colspan="2"><?php echo _MODULE_USER_CONTENT?></th>
					</tr>
					<tr>
						<td valign="top" align="left"><?php echo _CONTENT?>:</td>
						<td>
										<?php
										// parameters : areaname, content, hidden field, width, height, rows, cols
										editorArea('editor1',$row->content,'content','800','400','110','40');
										?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
					<?php
				}
				?>
	</table>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
	<input type="hidden" name="original" value="<?php echo $row->ordering; ?>" />
	<input type="hidden" name="module" value="<?php echo $row->module; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="client_id" value="<?php echo $lists['client_id']; ?>" />
			<?php
			if($row->client_id || $lists['client_id']) {
				echo '<input type="hidden" name="client" value="admin" />';
			}
			?>
	<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
</form>
		<?php
	}

	public static function cache_time_selector( $value = 0 ) {

		$options = array();
		$options[] = mosHTML::makeOption('0',_M_CACHE_0);
		$options[] = mosHTML::makeOption('60',_M_CACHE_60);
		$options[] = mosHTML::makeOption('300',_M_CACHE_300);
		$options[] = mosHTML::makeOption('600',_M_CACHE_600);
		$options[] = mosHTML::makeOption('900',_M_CACHE_900);
		$options[] = mosHTML::makeOption('1200',_M_CACHE_1200);
		$options[] = mosHTML::makeOption('1800',_M_CACHE_1800);
		$options[] = mosHTML::makeOption('3600',_M_CACHE_3600);
		$options[] = mosHTML::makeOption('7200',_M_CACHE_7200);
		$options[] = mosHTML::makeOption('9000',_M_CACHE_9000);
		$options[] = mosHTML::makeOption('7200',_M_CACHE_7200);
		$options[] = mosHTML::makeOption('18000',_M_CACHE_18000);
		$options[] = mosHTML::makeOption('43200',_M_CACHE_43200);
		$options[] = mosHTML::makeOption('86400',_M_CACHE_86400);
		$options[] = mosHTML::makeOption('172800',_M_CACHE_172800);
		$options[] = mosHTML::makeOption('604800',_M_CACHE_604800);

		return mosHTML::selectList($options,'cache_time','class="inputbox"','value','text',$value);
	}

}