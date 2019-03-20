<?php
require_once '../../init-nonpublic.php';

if (Input::exist()) {
    $comment = new Discussion();
    $comment->setPath('../');
    $target = (Input::has('target')) ? Session::get('id') : Input::get('target');
    $limit  = 3;
    $comment->getDiscussion('discussion', Input::get('page'), array(
        'limit'     => $limit,
        'condition' => array(
            'target'   => 'target_id',
            'operator' => '=',
            'value'    => $target,
        ),
    ));
    $output['pageleft'] = $comment->pageleft($target, Input::get('page'), $limit);
    $output['tag']      = $comment->generateDiscussion($target);
    if ($output['pageleft'] == 0) {
        $output['tag'] .= "<h1 class='m-5 text-center text-muted'>Welp, there's nothing here...</h1>";
    }
    echo json_encode($output);
}