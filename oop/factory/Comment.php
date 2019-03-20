<?php
class Comment
{
    private $_db,
    $_output,
    $_path = '';
    public function __construct()
    {
        $this->_db = DB::run();
    }

    public function add($post, $input)
    {
        $this->_db->insert('comment', array(
            'usr_id'  => Session::get('id'),
            'post_id' => $post,
            'content' => $input,
        ));
    }
    public function remove($id)
    {
        $this->_db->multiCon('DELETE', 'comment', array(
            'comment_id' => $id,
        ));

    }
    public function getTime($time)
    {
        if (new DateTime() < new DateTime($time)) {
            return "0 seconds ago";
        }
        $datetime1 = new DateTime("now");
        $datetime2 = date_create($time);
        $diff      = date_diff($datetime1, $datetime2);
        $allowed   = array(
            'y' => 'year',
            'm' => 'month',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second');
        foreach ($diff as $val => $a) {
            if ($diff->{$val} > 0 && in_array($val, array_keys($allowed))) {
                return $diff->{$val} . " $allowed[$val]" . ($diff->{$val} > 1 ? "s" : '') . ' ago';
            }
        }
    }

    public function getCount($id)
    {
        return $this->_db->get('comment', array('post_id', '=', $id))->getCount();
    }

    public function setPath($path)
    {
        $this->_path = $path;
    }

    public function getComment($table, $page, $terms = array())
    {
        DB::Run()->getLimited($table, $page, $terms, array(
            'commented_time' => 'DESC',
        ));
        $this->_output = DB::Run()->getResult();
        if (isset($terms['limit'])) {
            $this->_limit = $terms['limit'];
        }
    }

    public function totalPage($id, $limit)
    {
        return ceil($this->getCount($id) / $limit);
    }
    public function pageLeft($id, $page, $limit)
    {
        if (count($this->_output) <= 0) {
            return 0;
        }
        return $this->totalPage($id, $limit) - $page;
    }
    public function generateComment($id)
    {
        $read = new Reader();
        $read->read('comment.txt', $this->_path);
        $comments = $this->_output;
        $output   = '';
        if (count($comments) == 0) {
            return "<h1 class='m-5 text-center text-muted'>Welp, there's nothing here...</h1>";
        }
        foreach ($comments as $comment) {
            $userData      = (new User($comment->usr_id))->getData();
            $usrImage      = $userData->profileImgPath;
            $userLink      = $userData->usr_id;
            $commenterName = $userData->usrnm;
            $time          = ($this->getTime($comment->commented_time) == '') ? "just now" : $this->getTime($comment->commented_time);
            $content       = $comment->content;
            $output .= $read->modify(array(
                '$usrImage'      => $usrImage,
                '$userLink'      => $userLink,
                '$commenterName' => $commenterName,
                '$time'          => $time,
                '$content'       => $content,
            ));

        }
        return $output;
    }

}