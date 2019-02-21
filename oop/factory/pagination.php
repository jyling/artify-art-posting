<?php
class Pagination{
  private $_totalPage,
          $_currentPage,
          $_startPage,
          $_endPage,
          $_output = '',
          $_before,
          $_after,
          $_sensitivty = 4;
  public static function getPage($current){
    if (Input::has('page') && $current === 0) {
      return Input::get('page');
    }
    else {
      return 1;
    }
  }
  public function __construct($total,$current = 0){
    if ($total > 1) {
      $current = self::getPage($current);
      $this->_currentPage = $current;
      $this->_totalPage = $total;
      $this->_before = $current - 1;
      $this->_after = $current + 1;
      $this->sensitivty();
      $this->PointerLimiter();
      $this->_output = $this->generateTags();
      echo "$this->_output";
    }
    else {
      echo "No Data";
    }
  }
  public function pointerLimiter(){
    if ($this->_startPage <= 0) {
        $this->_endPage -= ($this->_startPage - 1);
        $this->_startPage = 1;
    }
    if ($this->_endPage > $this->_totalPage) {
      $this->_endPage = $this->_totalPage;
    }
  }
  public function sensitivty(){
    $this->_startPage = $this->_currentPage - $this->_sensitivty;
    $this->_endPage = $this->_currentPage + $this->_sensitivty;
  }
  public function generateTags(){
    $content = "<nav aria-label='Page navigation example'><ul class='pagination justify-content-center'>";
    if ($this->_startPage < $this->_currentPage) {
      $content .= "<li class='pagination-prev'><a href='$_SERVER[PHP_SELF]?page=1' class='page-link'>First</a></li>";
    }
    else {
      $content .= "<li class='pagination-prev disabled' tabindex='-1'><a href='#' class='page-link' disabled>First</a></li>";
    }
    if ($this->_before >= 1) {
      $content .= "<li class='pagination-prev'><a href='$_SERVER[PHP_SELF]?page=$this->_before' class='page-link'><</a></li>";
    }
    else {
      $content .= "<li class='pagination-prev disabled' tabindex='-1'><a href='#' class='page-link' disabled><</a></li>";
    }
    for($i=$this->_startPage; $i<=$this->_endPage; $i++) {
      $output = ($i < 10)? $output = "0" . $i : $i;
      if ($this->_currentPage == $i) {
        $content .= "<li class='page-item active'><a href='#' class='page-link' disabled>$output</a></li>";
      }
      else {
        $content .= "<li class='page-item'><a href='$_SERVER[PHP_SELF]?page=$i' class='page-link'>$output</a></li>";
      }
    }
    if ($this->_after <= $this->_totalPage) {
        $content .= "<li class='right-etc pagination-next'><a href='$_SERVER[PHP_SELF]?page=$this->_after' class='page-link'>></a></li>";
    }
    else {
      $content .= "<li class='right-etc pagination-next disabled'><a href='#' class='page-link' disabled>></a></li>";
    }
    if ($this->_currentPage < $this->_totalPage) {
      $content .= "<li class='right-etc pagination-next'><a href='$_SERVER[PHP_SELF]?page=$this->_totalPage' class='page-link'>Last</a></li>";
    }
    else {
      $content .= "<li class='right-etc pagination-next disabled' tabindex='-1'><a href='#' class='page-link' disabled>Last</a></li>";
    }
    return $content . "</ul><br><div class='marg0 text-center'> $this->_currentPage/$this->_totalPage Pages</div></nav>";
  }
}
?>
