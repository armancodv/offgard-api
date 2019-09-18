<?php
// user /////////////////////////////////////////
function insert_login($username, $status)
{
    $date = date("Y-m-d H:i:s");
    $token = token_login(strtolower($username), $date);
    $column = array('username', 'token', 'date', 'status');
    $value = array(strtolower($username), $token, $date, $status);
    sql_insert('logins', $column, $value, '');
    return $token;
}

////////
function insert_logout($username)
{
    $where_column = array('username', 'status');
    $where_value = array(strtolower($username), 1);
    $change_column = array('status');
    $change_value = array(2);
    sql_update('logins', $where_column, $where_value, $change_column, $change_value, '', '', '');
}

////////
function check_login($username, $token)
{
    $date = date("Y-m-d H:i:s");
    $date_1yearago = date("Y-m-d H:i:s", strtotime('-1 year', strtotime($date)));
    $column = array('username', 'token', 'date', 'status');
    $value = array(strtolower($username), $token, $date_1yearago, 1);
    return sql_isset('logins', $column, $value, '(username=:v0) AND (token=:v1) AND (date>=:v2) AND (status=:v3)', '');
}

////////
function check_password($username, $password)
{
    $column = array('username', 'password', 'status');
    $value = array(strtolower($username), md5($password), 1);
    return sql_isset('users', $column, $value, '', '');
}

////////
function isset_user($username)
{
    $column = array('username', 'status');
    $value = array(strtolower($username), 1);
    return sql_isset('users', $column, $value, '', '');
}

////////
function isset_user_byemail($email)
{
    $column = array('email', 'email_verified', 'status');
    $value = array(strtolower($email), 1, 1);
    return sql_isset('users', $column, $value, '', '');
}

////////
function select_user($username)
{
    $column = array('username', 'status');
    $value = array(strtolower($username), 1);
    $query = sql_select('users', $column, $value, '', '', '');
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
}

////////
function select_user_byemail($email)
{
    $column = array('email', 'status');
    $value = array(strtolower($email), 1);
    $query = sql_select('users', $column, $value, '', '', '');
    return $query;
}

////////
function insert_user($username, $firstname, $lastname, $email, $phone, $password)
{
    $column = array('username', 'firstname', 'lastname', 'email', 'phone', 'password');
    $value = array(strtolower($username), $firstname, $lastname, strtolower($email), $phone, md5($password));
    return sql_insert('users', $column, $value, '');
}

////////
function inactivate_user($username)
{
    $where_column = array('username', 'status');
    $where_value = array(strtolower($username), 1);
    $change_column = array('status');
    $change_value = array(0);
    sql_update('users', $where_column, $where_value, $change_column, $change_value, '', '', '');
}

////////
function edit_user($username, $firstname, $lastname, $password)
{
    $where_column = array('username', 'status');
    $where_value = array(strtolower($username), 1);
    $change_column = array('firstname', 'lastname', 'password');
    $change_value = array($firstname, $lastname, md5($password));
    sql_update('users', $where_column, $where_value, $change_column, $change_value, '', '', '');
}

////////
function verify_user($username)
{
    $where_column = array('username', 'status');
    $where_value = array(strtolower($username), 1);
    $change_column = array('email_verified');
    $change_value = array(1);
    sql_update('users', $where_column, $where_value, $change_column, $change_value, '', '', '');
}

////////
function change_password($username, $password)
{
    $where_column = array('username', 'status');
    $where_value = array(strtolower($username), 1);
    $change_column = array('password');
    $change_value = array(md5($password));
    sql_update('users', $where_column, $where_value, $change_column, $change_value, '', '', '');
}

////////
function password_generator()
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 10; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}
