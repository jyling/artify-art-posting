<?php
class Message {
  private $_output = '',
          $_nameLen = 16,
          $_limit = '';
  public function getMsg($table,$page,$terms = array()) {
    DB::Run()->getLimited($table,$page,$terms);
    $this->_output = DB::Run()->getResult();
    if (isset($terms['limit'])) {
      $this->_limit = $terms['limit'];
    }
  }
  public function StringOverflow($input){
    return substr($input,0,16 - 3) . '...';
  }
  public function generateMsg($template = 'messagebox.txt') {
    if (!empty($this->_output)) {
      $msgs = $this->_output;
      echo "<div class='card-deck'>";
      foreach ($msgs as $msg) {
        $message_id = $msg->message_id;
        $msg_content = $msg->msg;
        $fname = $msg->usrname;
        $name = (strlen($fname) > 16)? $this->StringOverflow($fname) : $fname;
        $read = new Reader();
        $read->read($template);
        echo $read->modify(array(
          '$name' => $name,
          '$fname' => $fname,
          '$id' => $message_id,
          '$msg' => $msg_content
        ));
      }
      echo "</div>";
    }
  }
  public function totalPage() {
    return ceil(DB::Run()->getAll('msgdummy') / $this->_limit);
  }
}
?>
