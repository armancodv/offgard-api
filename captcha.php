<?php
include('functions.php');
$captcha=select_captcha($_GET['id']);
if($captcha['code']!='') return_captcha($captcha['code']);