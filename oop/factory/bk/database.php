<?php
class Database {
  private $DATABASENAME = 'FYPTestGround';
  private $PASSWORD = '';
  private $DATABASEUSERNAME = 'root';
  private $DATABASESERVERNAME = 'localhost';
  private $conn;
  public function connect(){
    $this->conn = mysqli_connect($this->DATABASESERVERNAME,
                          $this->DATABASEUSERNAME,
                          $this->PASSWORD,
                          $this->DATABASENAME);
  }
  public function executeSql($sql){
    if ($this->conn === NULL) {
      echo "connection is not made";
      return 0;
    }
    else {
      mysqli_query($this->conn,$sql);
    }
  }
}
