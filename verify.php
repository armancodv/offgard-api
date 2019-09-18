<?php
include('functions.php');
$user = select_user($_GET['username']);
if((isset_user($_GET['username']))&&(!isset_user_byemail($_GET['email']))&&($user['email']==$_GET['email'])&&($_GET['code']==token_verify($_GET['username'],$_GET['email']))) {
    verify_user($_GET['username']);
    echo 'verified';
} else {
    echo 'not verified';
}