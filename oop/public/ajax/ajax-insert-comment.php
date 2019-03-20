<?php
require_once '../../init-nonpublic.php';
// die(var_dump($_POST));

if ((new User())->getLogin()) {
    $ban = new Ban();
    if ($ban->has(Session::get('id'))) {
        if ($ban->valid(Session::get('id'))) {
            $output['error'] = "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
            You are currently been banned for " . $ban->banleft(Session::get('id')) . " , You can not post and comment
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
                </button>
            </div>";

            echo json_encode($output);
            die();
        }

    }

    if (Input::exist() && Input::has('input')) {
        $output  = array();
        $comment = new Comment();
        $vali    = new Validate();
        $result  = $vali->check($_POST, array(
            'input' => array(
                'name'     => 'comment field',
                'required' => true,
                'min'      => 3,
                'max'      => 200,
            ),
            'post'  => array(
                'required' => true,
                'min'      => 1,
            ),
        ));
        $post = new Post(Input::get('post'));
        if (!$post->find(Input::get('post'))) {
            $output['error'] = "
        <div class='alert alert-warning alert-dismissible fade show' role='alert'>
            Post does not exist
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
        </div>
        ";

        }
        if (!$result->passed()) {
            foreach ($result->getError() as $i => $b) {
                $output['error'] = "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                $b
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>
            ";
            }

        } else {

            $comment->add(Input::get('post'), Validate::sanitize(Input::get('input')));
            $output = array('insert' => true);
        }
        $comment = new Comment();

        $comment->setPath('../');
        $limit = 1;
        $comment->getComment('comment', 1, array(
            'limit'     => $limit,
            'condition' => array(
                'target'   => 'post_id',
                'operator' => '=',
                'value'    => Input::get('post'),
            ),
        ));
        $output['tag'] = '';

    }
} else {
    $output['error'] = "
    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
    You need to be logged in to do that
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
        </button>
</div>
    ";
}

echo json_encode($output);