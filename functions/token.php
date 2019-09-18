<?php
// token /////////////////////////////////////////
function token_login($username, $date)
{
    return md5($username . $date . $date . md5($username . $date)) . md5($username . $date . md5($username));
}

////////
function token_verify($username, $email)
{
    return md5($username . $email . $email . md5($username . $email)) . md5($username . $email . md5($username));
}

////////
function token_reset($username)
{
    return md5($username . $username . $username . md5($username . $username)) . md5($username . $username . md5($username));
}

////////
function token_captcha()
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $code = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 5; $i++) {
        $n = rand(0, $alphaLength);
        $code[] = $alphabet[$n];
    }
    return implode($code);
}
////////