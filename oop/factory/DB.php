<?php
class DB {
  private static $conn = null;
  private $_pdo,
          $_query,
          $_err = false,
          $_output,
          $_count = 0;
  private function __construct(){
    try {
      $Sets = new Settings();
      $host = $Sets->get('sql>host');
      $usr = $Sets->get('sql>dbusr');
      $dbname = $Sets->get('sql>dbnm');
      $pass = $Sets->get('sql>pwd');
      $this->_pdo = new PDO("mysql:host=$host;dbname=$dbname",$usr, $pass);
      $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die($e->getMessage());
    }

  }
  public static function Run(){
    if (!isset(self::$conn)) { // self is like super in java
      self::$conn = new DB();
    }
    else {
      return self::$conn;
    }
  }
  public function doThis($condition,$table,$terms = array()) {
    if (count($terms) === 3) {
      define('WHITELIST','=,<,>,>=,<=');
      $allowed = explode(",",WHITELIST);

      $term = $terms[0];
      $operator = $terms[1];
      $val = $terms[2];

      if (in_array($operator, $allowed)) {
        $sql = "$condition FROM `$table` WHERE `$term` $operator ?";
        if (!$this->query($sql,array($val))->error()) {
          return $this;
        }
      }
    }
    {
      return false;
    }

  }
  public function get($table,$condition = array()) {
    return $this->doThis('SELECT *', $table, $condition);
  }
  public function getFirst($table,$condition = array()) {
    return $this->doThis('SELECT *', $table, $condition)[0];
  }
  public function remove($table,$condition = array()) {
    return $this->doThis('DELETE', $table, $condition);
  }
  public function getResult(){
    return $this->_output;
  }
  public function insert($table,$data = array()){
    $this->_err = false;
    if (count($data)) {
      $keys = array_keys($data);
      $values = null;
      $counter = 1;

      $values = implode(',',array_fill(0, count($data), "?"));

      $sql = "INSERT INTO `$table` (`" . implode('`,`',$keys) ."`) VALUES ($values)";
      if (!$this->query($sql,$data)->error()) {
        return true;
      }
    }
    return false;
  }
  public function update($table,$id,$data = array()){
    $this->_err = false;
    if (count($data)) {
      $values = null;
      $counter = 1;
      //                                            *
      foreach ($data as $key => $val) {             #
        $values .= "`$key` = ?";                   #*#
        if ($counter < count($data)) {            #*#*#
         $values .= ', ';                       ####*##
        }                                       ##*###*##
        $counter++;                               #|||#
      }                                            ###
      $sql = "UPDATE `$table` SET $values WHERE `id` = $id";
      if (!$this->query($sql,$data)->error()) {
        return true;
      }
    }
    return false;
  }
  public function query($sql,$param = array()) {
    $this->_err = false;
    if ($this->_query = $this->_pdo->prepare($sql)) {
      if (count($param)) {
        $counter = 1;
        foreach ($param as $key) {
          $this->_query->bindValue($counter,$key);
          $counter++;
        }
      }
      if ($this->_query->execute()) {
        $this->_output = $this->_query->fetchALL(PDO::FETCH_OBJ);
        $this->_count = $this->_query->rowCount();
      }
      else {
        $this->_err = true;
      }
    }
    return $this;
  }
  public function error(){
    return $this->_err;
  }
  public function getCount(){
    return $this->_count;
  }
}
