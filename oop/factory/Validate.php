<?php
class Validate {
  private $_passed = false,
          $_err = array(),
          $_db = null;
  public function __construct() {
    $this->_db = DB::Run();
  }
  public function sanitize($input){
    return(strip_tags(htmlspecialchars($input)));
  }
  public function check($source,$items = array()) {
    foreach ($items as $item => $rules) {
      foreach ($rules as $condition => $con_value) {
        $value = trim($source[$item]);
        $item = $this->sanitize($item);
        if ($condition == 'required' && empty($value)) {
          $this->addError("$rules[name] is needed",$item);
        } elseif(!empty($value)) {
          switch ($condition) {
            case 'min':
              if (strlen($value) < $con_value) {
              $this->addError("The $rules[name] need to be more than $con_value characters long",$item);
              }
            break;
            case 'max':
              if (strlen($value) > $con_value) {
              $this->addError("The $rules[name] need to be less than $con_value characters long",$item);
              }
            break;
            case 'match':
                if ($value != $source[$con_value]) {
                  $this->addError("The $rules[name] does not match $rules[match_name]",$item);
                }
            break;
            case 'unique':
                    $check = DB::Run()->get($con_value,array($item,'=',$value));
                    if ($check->getCount()) {
                      $this->addError("$rules[name] already exist",$item);
                    }
            break;
            case 'charOnly' :
            if (preg_match('/[^a-zA-Zd]/i', $value))
              {
                $this->addError("$rules[name] can only have english letters",$item);
              }
            break;
            case 'charAndNums' :
            if ($con_value === true) {
              if (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $value))
              {
                $this->addError("$rules[name] must contain at least one letters or numbers",$item);
              }
            }
            break;
          }
        }

      }
    }
    if (empty($this->_err)) {
      $this->_passed = true;
    }
    return $this;
  }
  public function addError($input,$name) {
    $this->_err[$name] = $input;
  }
  public function getError(){return $this->_err;}
  public function passed(){return $this->_passed;}
}
