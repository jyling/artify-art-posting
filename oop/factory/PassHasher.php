<?php
class PassHasher{
  public function getHash($input, $cost = array('cost' => 10)) {
    return password_hash($input,PASSWORD_DEFAULT,$cost);
  }
  public function salty($len = 60) {
    return bin2hex(random_bytes($len));
  }
  public function unique(){
    return self::getHash(uniqid());
  }
  public function randHash($val = 30){
    return Bin2Hex(random_bytes($val));
  }
}
