<?php
require_once '../../init-nonpublic.php';

if (Input::exist()) {
    $comment = new Comment();
    $comment->setPath('../');
    $limit = 3;
    $comment->getComment('comment', Input::get('page'), array(
        'limit'     => $limit,
        'condition' => array(
            'target'   => 'post_id',
            'operator' => '=',
            'value'    => Input::get('post'),
        ),
    ));
    $output['pageleft'] = $comment->pageleft(Input::get('post'), Input::get('page'), $limit);
    $output['tag']      = $comment->generateComment(Input::get('post'));
    if ($output['pageleft'] == 0) {
        $output['tag'] .= "<h1 class='m-5 text-center text-muted'>Welp, there's nothing here...</h1>";
    }
    echo json_encode($output);
}