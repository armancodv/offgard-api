<?php
// validation /////////////////////////////////////////
function valid_maxlength($key, $size)
{
    if (strlen($GLOBALS['p'][$key]) > $size) return $key . ':' . ERR_MAXLENGTH;
    else return null;
}

////////
function valid_minlength($key, $size)
{
    if (strlen($GLOBALS['p'][$key]) < $size) return $key . ':' . ERR_MINLENGTH;
    else return null;
}

////////
function valid_maxnum($key, $size)
{
    if ($GLOBALS['p'][$key] > $size) return $key . ':' . ERR_MAXNUM;
    else return null;
}

////////
function valid_minnum($key, $size)
{
    if ($GLOBALS['p'][$key] < $size) return $key . ':' . ERR_MINNUM;
    else return null;
}

////////
function valid_number($key)
{
    if (!is_numeric($GLOBALS['p'][$key])) return $key . ':' . ERR_NUMBER;
    else return null;
}

////////
function valid_integer($key)
{
    if (!(!is_int($GLOBALS['p'][$key]) ? (ctype_digit($GLOBALS['p'][$key])) : true)) return $key . ':' . ERR_INTEGER;
    else return null;
}

////////
function valid_email($key)
{
    if (!preg_match("/^[^ @]*@[^ @]+$/u", $GLOBALS['p'][$key])) return ERR_EMAIL;
    else return null;
}

////////
function valid_required($key)
{
    if ($GLOBALS['p'][$key] == '') return $key . ':' . ERR_REQUIRED;
    else return null;
}

////////
function valid_password()
{
    if (!check_password($GLOBALS['p']['username'], $GLOBALS['p']['password'])) return ERR_PASSWORD;
    else return null;
}

////////
function valid_issetusername()
{
    if (isset_user($GLOBALS['p']['username'])) return ERR_ISSETUSERNAME;
    else return null;
}

////////
function valid_issetemail()
{
    if (isset_user_byemail($GLOBALS['p']['email'])) return ERR_ISSETEMAIL;
    else return null;
}

////////
function valid_captcha()
{
    if (!check_captcha($GLOBALS['p']['captcha_id'], $GLOBALS['p']['captcha_code'])) return ERR_CAPTCHA;
    else return null;
}
////////
