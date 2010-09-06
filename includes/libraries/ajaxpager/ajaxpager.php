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

class ajaxPager {

	var $into = null;
	var $callback = null;
	var $totalItems = null;
	var $itemPerPage = null;
	var $display = null;
	var $handler = null;

	var $currentPage = null;
	var $offset = null;
	var $limit = null;
	var $output = null;


	function  __construct() {}

	function first_load($into ='', $callback = array(), $totalItems=120, $itemPerPage=10, $display=10, $handler = 'pagenav') {

		$this->into = $into;
		$this->callback = $callback;
		$this->totalItems = $totalItems;
		$this->display = $display;
		$this->itemPerPage = $itemPerPage;
		$this->handler = $handler;

		$this->ajaxPaginate(1);

		?>
<script type="text/javascript">
    $(document).ready(function(){
        $(".<?php echo $this->handler; ?>").paginate({
            count 		: <?php echo $this->totalPages; ?>,
            start 		: 1,
            display     : <?php echo $this->display; ?>,
            mouse		: 'press',
            onChange    : <?php echo $this->_callback($this->callback);?>
        });

    });
</script>            

		<?php
	}

	function other_load($params) {
		$this->totalItems = mosGetParam($params, 'totalItems');
		$this->itemPerPage = mosGetParam($params, 'itemPerPage');
		$this->ajaxPaginate(mosGetParam($params, 'page'));
	}

	function ajaxPaginate($page) {

		$this->totalPages = ceil($this->totalItems/$this->itemPerPage);

		$this->currentPage = (int) $page;
		$this->offset = ($this->currentPage-1) * $this->itemPerPage;
		$this->offset = $this->offset < 0 ? 0 : $this->offset;

		$this->limit = $this->itemPerPage;
	}

	function _callback($callback) {

		if(!count($callback)) {
			return 'function(){return false;}';
		}

		foreach($callback as $key=>$v) {
			$arr[] = $key.": '".$v."'";
		}

		$js = 'function(page){
                    $.get( _live_site + "/ajax.index.php", { ';
		$js .=  implode(',',$arr);
		$js .=  ', page: page';
		$js .= ', totalItems:'.$this->totalItems ;
		$js .= ', itemPerPage:'.$this->itemPerPage ;
		$js .= '},';
		$js .= 'function(data){
                            $(".'.$this->into.'").html(data);
                       });  ';
		$js .= '}';

		return $js;
	}
}