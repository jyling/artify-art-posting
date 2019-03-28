<?php
class Search
{
    private $_data;

    // public function __construct()
    // {
    //     $this->_data = new \stdClass();
    // }
    public function get($terms = array(), $table = '',$limit = '',$page = '')
    {
        $db = DB::run();
        $db->haystackFind($terms, $table, $limit, $page);
        return $db->getResult();
    }

    public function getCount($terms = array(), $table = '')
    {
        $db = DB::run();
        $db->haystackFind($terms, $table);
        return $db->getCount();
    }

    public function combine($terms = array(),$limit ='',$page ='')
    {
        // $output = array();
        foreach ($terms as $table => $properties) {
            $this->_data[$table]['hits'] = $this->getCount($properties, $table);
            $this->_data[$table]['page'] = ceil($this->getCount($properties, $table,$limit,$page)/ $limit);
            $this->_data[$table]['data'] = $this->get($properties, $table,$limit,$page);
        }
        $this->_data = json_decode(json_encode($this->_data));
    }
    public function getResult()
    {
        return $this->_data;
    }
    public static function StringOverflow($input, $char = 16)
    {
        return substr($input, 0, $char - 3) . '...';
    }

    public function genCards($terms = array(), $table = '')
    {
        // var_dump($this->_data->$table);
        $result = $this->_data->{$table}->data;
        echo "<h4 class='mt-2'> Result Found: " . $this->_data->{$table}->hits . "</h4>";
        echo "<div class='card-deck'>";
        foreach ($result as $resultData => $msgs) {
            switch ($table) {
                case 'post':

                    $message_id  = $msgs->post_id;
                    $msg_content = (strlen($msgs->content) > 50) ? self::StringOverflow($msgs->content, 50) : $msgs->content;
                    $user        = new User($msgs->usr_id);
                    $fname       = $user->getData()->usrnm;
                    $name        = (strlen($fname) > 16) ? self::StringOverflow($fname) : $fname;
                    $title       = (strlen($msgs->title) > 16) ? self::StringOverflow($msgs->title) : $msgs->title;

                    $art  = Image::imgToBase64($msgs->artThumbnail);
                    $read = new Reader();
                    $read->read('messagebox.txt');
                    echo $read->modify(array(
                        '$name'      => $name,
                        '$fname'     => $fname,
                        '$artPath'   => $art,
                        '$id'        => $message_id,
                        '$post_id'   => "viewPost.php?post=" . $message_id,
                        '$titleLong' => $msgs->title,
                        '$title'     => $title,
                        '$msg'       => $msg_content,
                    ));
                    break;
                case 'usr':

                    $message_id = $msgs->usr_id;
                    $user       = new User($msgs->usr_id);
                    $fname      = $user->getData()->usrnm;
                    $title      = (strlen($fname) > 16) ? self::StringOverflow($fname) : $fname;

                    $art  = Image::imgToBase64($msgs->profileImgPath);
                    $read = new Reader();
                    $read->read('messagebox.txt');
                    echo $read->modify(array(
                        '$name'      => $msgs->nickname,
                        '$fname'     => $fname,
                        '$artPath'   => $art,
                        '$id'        => $message_id,
                        '$post_id'   => "viewProfile.php?user=" . $message_id,
                        '$titleLong' => $msgs->usrnm,
                        '$title'     => $title,
                        '$msg'       => '',
                    ));
                    break;

                default:
                    # code...
                    break;
            }
        }
        echo "</div>";
    }

}