<?php
class Message
{
    private $_output = '',
    $_nameLen        = 16,
    $_limit          = '';
    public function getMsg($table, $page, $terms = array())
    {
        DB::Run()->getLimited($table, $page, $terms, array(
            'post_id' => 'DESC',
        ));
        $this->_output = DB::Run()->getResult();
        if (isset($terms['limit'])) {
            $this->_limit = $terms['limit'];
        }
    }
    public static function StringOverflow($input, $char = 16)
    {
        return substr($input, 0, $char - 3) . '...';
    }
    public function generateMsg($template = 'messagebox.txt')
    {
        if (!empty($this->_output)) {
            $msgs = $this->_output;
            echo "<div class='card-deck'>";
            foreach ($msgs as $msg) {
                $message_id  = $msg->post_id;
                $msg_content = (strlen($msg->content) > 50) ? self::StringOverflow($msg->content, 50) : $msg->content;
                $user        = new User($msg->usr_id);
                $fname       = $user->getData()->usrnm;
                $name        = (strlen($fname) > 16) ? self::StringOverflow($fname) : $fname;
                $art         = Image::imgToBase64($msg->artThumbnail);
                $read        = new Reader();
                $read->read($template);
                echo $read->modify(array(
                    '$name'    => $name,
                    '$fname'   => $fname,
                    '$artPath' => $art,
                    '$id'      => $message_id,
                    '$post_id' => $message_id,
                    '$msg'     => $msg_content,
                ));
            }
            echo "</div>";
        } else {
            echo "<h1 class='m-5 text-muted'>Welp, there's nothing here...</h1>";
        }
    }
    public function totalPage($terms = array())
    {
        return ceil(DB::Run()->get('post', $terms)->getCount() / $this->_limit);
    }
}