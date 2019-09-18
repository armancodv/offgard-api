<?php
// return /////////////////////////////////////////
function return_json($status, $array, $status_code)
{
    http_response_code($status_code);
    $return = array('status' => $status);
    foreach ($array as $key => $item) {
        $return[$key] = $item;
    }
    echo json_encode($return);
}
////////