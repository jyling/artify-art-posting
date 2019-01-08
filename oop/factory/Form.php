<?php
class Form {
  private $_output = '',
          $_head = '',
          $_body = '',
          $_product = '';
  private function makeHead($formHead) {
    $header = '<form ';
    if (count($formHead) < 1) {
      $header .= "class='' method ='post' action=''";
    }
    else {
      foreach ($formHead as $characteristic => $value) {
        switch ($characteristic) {
          case 'action':
          $header .= "action = '$value'";
          break;
          case 'method':
          $header .= "method = '$value'";
          break;
          case 'class':
          $header .= "class = '$value'";
          break;
          default:
          $header .= "";
          break;
        }
      }
    }
      $header .= ">";
    return $header;
  }
  private function makeInput($inputs,$break) {
    $output = '';
    $breaks = ($break)? '<br>' : '';
    foreach ($inputs as $input => $properties) {
      $output .= '<input ';
      foreach ($properties as $property => $value) {
        switch ($property) {
          case 'name':
            $output .= "name = '$value' ";
          break;
          case 'id':
            $output .= "id = '$value' ";
          break;
          case 'placeholder':
            $output .= "placeholder = '$value'";
          break;
          case 'class':
            $output .= "class = '$value' ";
            break;
          case 'required':
            $output .= ($value)? 'required' : '';
          break;
          case 'autocomplete':
            $output .= (!$value)? "autocomplete = 'off'" : '';
            break;
          case 'value':
            $output .= "value = '$value' ";
            break;
          case 'type':
            $output .= "type = '$value'";
            break;
        }

      }
      $output .= '>' . $breaks;
    }
    return $output;
  }
  public function genForm($formhead = array(),$inputs = array(),$break = false) {
    $this->_head =  $this->makeHead($formhead);
    $this->_body = $this->makeInput($inputs,$break);
    $this->_product = $this->_head . $this->_body . "</form>";
    echo $this->_product;
  }
}
