<?php
include('functions.php');
$user = select_user($_GET['username']);
if ((isset_user($_GET['username'])) && ($_GET['code'] == token_reset($_GET['username']))) {
    $password = password_generator();
    change_password($_GET['username'], $password);
    echo 'reseted';
    echo 'new password: ' . $password;
} else {
    echo 'not reseted';
}