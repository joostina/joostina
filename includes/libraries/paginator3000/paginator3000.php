<?php

/*
 * @copyright Авторские права (C) 2010 raplos. Все права защищены.
 */

defined('_JOOS_CORE') or die();

class paginator3000 {

  /**
   * Items to be displayed per page
   * @var int
   */
  public $itemPerPage = 10;
  /**
   * The current page number
   * @var int
   */
  public $currentPage = 1;
  /**
   * Maximum Pager length
   * @var int
   */
  public $maxLength = 10;
  /**
   * Total items to be split in the pagination
   * @var int
   */
  public $totalItem;
  /**
   * Total pages in the pagination
   * @var int
   */
  public $totalPage;
  /**
   * The pages HTML output
   * @var string
   */
  public $output;
  /**
   * The URL prefix for all the pagination links
   * @var string
   */
  public $baseUrl;


  /**
   * Instanciate the Pager object
   *
   * @param string $baseURL Base URL to be appended to the page number
   * @param int $totalItems Total items to be paginate
   * @param int $itemPerPage Items to be shown in one page.
   * @param int $maxlength Number of links for the pager navigation
   * @param string $prevText Text for the Previous button link
   * @param string $nextText Text for the Next button link
   */
  function __construct($baseURL='', $totalItems=120, $itemPerPage=10, $maxlength=10) {
    $this->baseUrl = $baseURL;
    $this->totalItem = $totalItems;
    $this->maxLength = $maxlength;
    $this->itemPerPage = $itemPerPage;
  }

  /**
   * Paginate the list of items and prepare pager components to be use in View.
   *
   * @param int $page The current page number
   * @param int $itemPerPage Items per page
   */
  public function paginate($page, $itemPerPage=0) {
    if ($itemPerPage === 0) {
      $itemPerPage = $this->itemPerPage;
    } else {
      $this->itemPerPage = $itemPerPage;
    }

    $this->totalPage = ceil($this->totalItem / $itemPerPage);

    $this->currentPage = (int) $page;

    if ($this->currentPage < 1 || ! is_numeric($this->currentPage)) {
      $this->currentPage = 1;
    }

    if ($this->currentPage > $this->totalPage) {
      $this->currentPage = $this->totalPage;
    }

    $this->output = $this->totalPage<1 ? '' : "<script type=\"text/javascript\">var _p3000_show = true; _p3000_tp = $this->totalPage, p3000_ml = $this->maxLength, p3000_cp = $this->currentPage, _p3000_bu = '$this->baseUrl';</script>";

    if ($itemPerPage===0) {
      $itemPerPage = $this->itemPerPage;
    } else {
      $this->itemPerPage = $itemPerPage;
    }

    $this->totalPage = ceil($this->totalItem / $itemPerPage);

    $this->currentPage = (int) $page;

    if ($this->currentPage < 1 || ! is_numeric($this->currentPage))
      $this->currentPage = 1;

    if ($this->currentPage > $this->totalPage)
      $this->currentPage = $this->totalPage;


    $this->low = ($this->currentPage - 1) * $this->itemPerPage;
    $this->high = ($this->currentPage * $this->itemPerPage) - 1;
    $this->low = $this->low < 0 ? 0 : $this->low;
    $this->limit = $this->itemPerPage;
    $this->offset = $this->low;
  }

}

