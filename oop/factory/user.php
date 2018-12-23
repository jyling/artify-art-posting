<?php
require "people.php";
class User extends People {
  private $accountType;
  private $lastLogin;
  private $JoinedSince;
  private function isRealDate($date) {
    $dateAndTime = explode(' ', $date);
    if (false === strtotime($dateAndTime[0])) {
        return false;
    }
    list($year, $month, $day) = explode('-', $dateAndTime[0]);
    if (checkdate($month, $day, $year) === false) {
      return false;
    }
    $time = explode(':',$dateAndTime[1]);
    if ($time[0] > 23) {
      return false;
    }
    if ($time[1] > 59) {
      return false;
    }
    if ($time[2] > 59) {
      return false;
    }
    return true;
  }
  public function dateToJson() {
    $allDate = array();
    if (!empty($this->lastLogin)) {
      $dateAndTime = explode(' ', $this->lastLogin);
      $time12h = date('h:i:sa', strtotime($this->lastLogin));
      $allDate['lastLogin']['date'] = $dateAndTime[0];
      $allDate['lastLogin']['Fulldate24'] = $this->lastLogin;
      $allDate['lastLogin']['Fulldate12'] = $dateAndTime[0] . " " . $time12h;
      $allDate['lastLogin']['time24h'] = $dateAndTime[1];
      $allDate['lastLogin']['time12h'] = $time12h;
    }
    if (!empty(isset($this->JoinedSince))) {
      $dateAndTime = explode(' ', $this->JoinedSince);
      $time12h = date('h:i:sa', strtotime($this->JoinedSince));
      $allDate['joinedSince']['date'] = $dateAndTime[0];
      $allDate['joinedSince']['Fulldate24'] = $this->lastLogin;
      $allDate['joinedSince']['Fulldate12'] = $dateAndTime[0] . " " . $time12h;
      $allDate['joinedSince']['time24h'] = $dateAndTime[1];
      $allDate['joinedSince']['time12h'] = $time12h;
    }
    return json_encode($allDate);
  }

  public function setAccountType($input){
    $firstChar = strtolower($input[0]);
    switch ($firstChar) {
      case 'n':
        $this->accountType = "Normal";
        break;
      case 'a' :
        $this->accountType = "Admin";
        break;
      case 'm' :
        $this->accountType = "Moderator";
        break;
      default:
        $this->accountType = "Normal";
        break;
    }
  }
  public function setlastLogin($input){
    if ($this->isRealDate($input) === true) {
      $this->lastLogin = $input;
    }
    else {
      echo '<br>date and time is false';
    }
  }
  public function setJoinedSince($input){
    if ($this->isRealDate($input) === true) {
      $this->JoinedSince = $input;
    }
    else {
      echo '<br>date and time is false';
    }
  }
  public function getAccountType(){return $this->accountType;}
  public function getLastLogin($switch = false){
    switch ($switch) {
      case false:
        return json_decode(
          $this->dateToJson($this->lastLogin),true
          )['lastLogin']['Fulldate24'];
        break;
      case true:
        return json_decode(
          $this->dateToJson($this->lastLogin),true
          )['lastLogin']['Fulldate12'];
        break;
    }

  }
  public function getJoinedSince($switch = false){
    switch ($switch) {
      case false:
        return json_decode(
          $this->dateToJson($this->lastLogin),true
          )['joinedSince']['Fulldate24'];
        break;
      case true:
        return json_decode(
          $this->dateToJson($this->lastLogin),true
          )['joinedSince']['Fulldate12'];
        break;
    }
  }

}
class NormalUser extends User {
  private $database = new Database();
  public function AddUser($fname,$lname,$age,$gender,$password,$email){
    if ($this->setFName($fname) === 0) {
      echo "invalidFName";
    }
    else {
      if ($this->setLName($lname) === 0) {
        echo "invalidLName";
      }
      else {
        if ($this->setAge($age) === 0) {
          echo "invalid age or age is less than 12";
        }
        else {
          if ($this->setGender($gender) === 0) {
            echo "invalid Gender";
          }
          else {
            if ($this->setPassword($password) === 0) {
              echo "password is less than 8 character";
            }
            else {
              if ($this->setEmail($email)) {
                echo "InvalidEmail";
              }
              else {
                $sql = "SELECT * FROM `usr` WHERE `email` = $email";
                if () {
                  // code...
                }
              }
            }
          }
        }
      }
    }

  }
}
