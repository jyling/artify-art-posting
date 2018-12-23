


<?php
require_once 'database.php';
class People{
  private $lname = '';
  private $fname = '';
  private $age = '0';
  private $gender = '';
  private $email = '';
  private $contactPhone = '';
  private $contactHome = '';
  private $password = '';


  public function sanitizeInput($input){
    return strip_tags(htmlspecialchars($input));
  }
  private function CharOnly($input) {

    if (!empty($input)){
      if (!preg_match('/[0-9]/',$input)) {
        return 1;
      }
      else {
        return 0;
      }
    }
  }
  private function NumsOnly($input) {
    if (preg_match('/[0-9]/',$input)) {
      return $input;
    }
    else {
      return 0;
    }
  }
  public function verifyPassword($pass) {
    if (password_verify($pass, $this->password)) {
      echo "<br>password correct";
    }
    else {
      echo "<br>password incorrect";
    }
  }
  public function GetInfo(){
    $user = array(
      'user' => array(
        'name' => array('fname' => $this->fname,
                        'lname' => $this->lname,
                        'fullname' => $this->getName()
                      ),
        'age' => $this->age,
        'email' => $this->email,
        'contact'=> json_decode($this->getContact()),
        'hash' => $this->gethash()
      ) ,
     );
    return json_encode($user, JSON_PRETTY_PRINT);
  }

  public function setFName($Strfname){
    $Strfname = $this->sanitizeInput($Strfname);
    if (!empty($Strfname)){
      if ($this->CharOnly($Strfname) === 0) {
        return 0;
      }
      else {
        $this->fname = $Strfname;
      }
    }
  }
  public function setLName($Strlname) {
    $Strfname = $this->sanitizeInput($Strlname);
    if (!empty($Strlname)){
      if ($this->CharOnly($Strlname) === 0) {
        return 0;
      }
      else {
        $this->lname = $Strlname;
      }
    }
  }
  public function setAge($StrAge){
    $input = $this->sanitizeInput($StrAge);
    if (!empty($Strfname)){
      if ($this->NumsOnly($StrAge) === 0) {
        return 0;
      }
      else {
        if ($StrAge > 12) {
          $this->age = $StrAge;
        }
        else {
          return 0;
        }
      }
    }
    else {
      return 0;
    }
  }
  public function setGender($input){
    switch (strtolower($input[0])) {
      case 'f':
        $this->gender = "Female";
        break;
      case 'm':
        $this->gender = "Male";
      default :
        return 0;
        break;
    }
  }
  public function setPassword($pass) {
    if (strlen($pass) < 8) {
      return 0;
    }
    $hashedPass = password_hash($pass,PASSWORD_DEFAULT, ['cost' => 10]);
    $this->setPasswordD($hashedPass);
  }
  public function setEmail($input) {
    $input = $this->sanitizeInput($input);
    if (!empty($input)){
    $cleanEmail = filter_var($input, FILTER_SANITIZE_EMAIL);
      if (filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
        $this->email = $cleanEmail;
      }
      else {
        return 0;
      }
    }
  }
  public function setContact($input) {
    // $mobile = array('010', '011', '012', '013', '014', '015', '016', '017', '018', '019');
    // $house = array('03','04','05','06','07','080','082','083','084','086','086','087', '088', '089', '09');
    $mobile = array('10', '11', '12', '13', '14', '15', '16', '17', '18', '19');
    $house = array('3','4','5','6','7','80','82','83','84','86','86','87', '88', '89', '9');
    if (!empty($input)) {
      $phone = $input;
      $pattern = "/^[0-9\_]/";
      $rawPhone = str_replace(array('-',' ','+'), '',$phone);
      if (preg_match($pattern,$phone) && strlen($rawPhone) > 8) {
        if ($rawPhone[0] == "0") {
            if (in_array(substr($rawPhone, 1,2),$mobile)) {
              $this->contactPhone = $rawPhone;
            }
            elseif (in_array(substr($rawPhone, 0,2),$house)) {
              $this->contactHome = $rawPhone;
            }
            else {
              return 0;
            }

          }
          else {
            return 0;
          }
      }
      else {
         return 0;
       }
    }
    else {
      return 0;
    }
  }

  public function setPasswordD($sethash) {
    $this->password = $sethash;
  }
  public function getFName(){return $this->fname;}
  public function getLName(){return $this->lname;}
  public function getName() {
    if ($this->fname !== '' && $this->lname !== '') {
      return $this->fname . ' ' . $this->lname;
    }
    else {
      return 0;
    }
  }
  public function getAge(){return $this->age;}
  public function getEmail() {
    if ($this->email !== '') {
      return $this->email;
    }
  }
  public function getContact() {
    $phone = $this->contactPhone;
    $home = $this->contactHome;
    $contact = array('user' => $this->getName());
    if (!empty($phone) || !empty($home)) {
      if (strlen($phone) > 8) {
        $contact['phone'] = $phone;
      }
      if (strlen($home) > 8) {
        $contact['home'] = $home;
      }
    }
    else {
      $contact->Status = 'No Contact found for this user';
    }
    return json_encode($contact);

  }
  public function gethash(){
    return $this->password;
  }
}

$ob = new People();
echo "<br>";
$ob -> setLName('test');
echo $ob -> getLName();
$ob -> setFName('Testing');
echo '<br>' . $ob -> getFName();
echo "<br>Full name : ". $ob->getName();
$ob->setEmail("samuel.ling@gmail.com");
echo '<br>'.$ob->getEmail();
$ob->setContact('05-6913770');
$ob->setContact('018-9014882');
echo "<br>";
print_r($ob->getContact());
echo "<br>";
$ob-> setPassword('1234');
$ob-> verifyPassword('1234');

// output ////
// test
// te2st <---- this shouldnt show up
 ?>
