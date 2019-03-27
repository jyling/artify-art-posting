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
    public function generateMsg($template = 'messagebox.txt', $con = true)
    {
        if (!empty($this->_output)) {
            if ($con) {
                $msgs = $this->_output;
                echo "<div class='card-deck'>";
                foreach ($msgs as $msg) {
                    $message_id  = $msg->post_id;
                    $msg_content = (strlen($msg->content) > 50) ? self::StringOverflow($msg->content, 50) : $msg->content;
                    $user        = new User($msg->usr_id);
                    $fname       = $user->getData()->usrnm;
                    $name        = (strlen($fname) > 16) ? self::StringOverflow($fname) : $fname;
                    $title       = (strlen($msg->title) > 16) ? self::StringOverflow($msg->title) : $msg->title;

                    $art  = Image::imgToBase64($msg->artThumbnail);
                    $read = new Reader();
                    $read->read($template);
                    echo $read->modify(array(
                        '$name'      => $name,
                        '$fname'     => $fname,
                        '$artPath'   => $art,
                        '$id'        => $message_id,
                        '$post_id'   => "viewPost.php?post=" . $message_id,
                        '$titleLong' => $msg->title,
                        '$title'     => $title,
                        '$msg'       => $msg_content,
                    ));
                }
                echo "</div>";
            } elseif (!$con) {
                $msgs = $this->_output;
                echo "<div class='card-deck'>";

                foreach ($msgs as $purchase => $attr) {
                    $author;
                    $post_id;
                    $msg;
                    $title;
                    $titleLong;

                    // print_r($attr);
                    if ($attr->post_id == null) {
                        $author    = 'Deleted';
                        $title     = 'Deleted';
                        $titleLong = 'Deleted by artist or staff';

                        $post_id = 'Deleted';
                    } else {
                        $post       = new Post($attr->post_id);
                        $postDetail = $post->getData();
                        $usr        = (new User($postDetail->usr_id))->getData();
                        $author     = (strlen($usr->usrnm) > 16) ? self::StringOverflow($usr->usrnm) : $usr->usrnm;
                        $msg        = (new Post($attr->post_id))->getData();
                        $title      = (strlen($msg->title) > 16) ? self::StringOverflow($msg->title) : $msg->title;
                        $titleLong  = $msg->title;
                        $post_id    = $postDetail->post_id;
                    }

                    $button = "<a target='_blank' href='downloader.php?url=" . $attr->purchase_id . "'>Download</a>";
                    $read   = new Reader();
                    $read->read('messagebox.txt');
                    // die(var_dump($post));
                    echo $read->modify(array(
                        '$name'      => $author,
                        '$fname'     => $author,
                        '$artPath'   => $attr->compressedImg,
                        '$id'        => $post_id,
                        '$post_id'   => "viewPost.php?post=" . $post_id,
                        '$titleLong' => $title,
                        '$title'     => $title,
                        '$msg'       => $button,
                    ));
                }}
            echo "</div>";
        } else {
            echo "<h1 class='m-5 text-muted'>Welp, there's nothing here...</h1>";
        }
    }

    public function totalPage($table = 'post', $terms = array())
    {
        return ceil(DB::Run()->get($table, $terms)->getCount() / $this->_limit);
    }
}