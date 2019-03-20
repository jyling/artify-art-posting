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
        $comment = new Discussion();
        $vali    = new Validate();
        $result  = $vali->check($_POST, array(
            'input'  => array(
                'name'     => 'comment field',
                'required' => true,
                'min'      => 3,
                'max'      => 200,
            ),
            'target' => array(
                'required' => true,
                'min'      => 1,
            ),
        ));
        $post = new User(Input::get('target'));
        if (!$post->find(Input::get('target'))) {
            $output['error'] = "
        <div class='alert alert-warning alert-dismissible fade show' role='alert'>
            User does not exist
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

            $comment->add(Input::get('target'), Validate::sanitize(Input::get('input')));
            $output = array('insert' => true);
        }
        $comment = new Discussion();

        $comment->setPath('../');
        $limit = 1;
        $comment->getDiscussion('discussion', 1, array(
            'limit'     => $limit,
            'condition' => array(
                'target'   => 'target_id',
                'operator' => '=',
                'value'    => Input::get('target'),
            ),
        ));
        $output['tag'] = $comment->generateDiscussion(Input::get('target'));

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