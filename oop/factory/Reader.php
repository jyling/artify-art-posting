<?php
class Reader{
  private $_file = '',
          $_result = '',
          $_err = array();
  public function read($name, $loc = '', $params = 'f') {
      $target = (empty($loc))? Settings::get('reader') : $loc;
      $this->Retrive($target . $name, $params);
  }

  public function getContent(){
    return $this->_file;
  }

  private function Retrive($name,$param) {
    if (file_exists($name) && strlen($param) > 0) {
      $fstream = fopen($name, "r") or $this->writeError("Failed to Open File");
    switch (strtolower($param[0])) {
      case 'f':
          $this->_file = self::readWhole($fstream,$name);
        break;
      case 'c':
          $this->_file = self::readByChar($fstream);
        break;
      case 'l':
          $this->_file = self::readByLine($fstream);
        break;
      default:
          $this->_file = self::readWhole($fstream,$name);
        break;
      }
      fclose($fstream);
      return $this->_file;
    }
    else {
      $this->writeError('File not exist or no params');
    }
  }
  public function success(){
    return (empty($this->error))? true : false;
  }

  public function modify($param = array()){
    $this->_result = (empty($this->_file))? '' : $this->_file;
    if (!empty($this->_file)) {
      foreach ($param as $key => $value) {
      $this->_result = str_replace($key,$value,$this->_result);
      }
    }
    return $this->_result;
  }

  private function writeError($str) {
    array_push($this->_err,$str);
  }
  private function readByChar($myfile) {
    $result = '';
    while(!feof($myfile)) {
      $result .= fgetc($myfile);
    }
    return $result;
  }
  private function readByLine($myfile) {
    $result = '';
    while(!feof($myfile)) {
      $result .= fgets($myfile) . "<br>";
    }
    return $result;
  }
  private function readWhole($myfile,$name) {
    return fread($myfile,filesize($name));
  }
}
