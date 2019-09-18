<?php
// comment /////////////////////////////////////////
function isset_comment($off_id, $username)
{
    $column = array('off_id', 'username', 'status');
    $value = array($off_id, $username, 1);
    return sql_isset('comments', $column, $value, '', '');
}

////////
function select_comments_byoff($off_id)
{
    $column = array('off_id', 'status');
    $value = array($off_id, 1);
    $query = sql_select('comments', $column, $value, '', '', '');
    return $query;
}

////////
function show_rate_byoff($off_id)
{
    $column = array('off_id', 'status');
    $value = array($off_id, 1);
    $query = sql_select('comments', $column, $value, 'SUM(rate) AS summation, COUNT(*) AS count', '', '');
    $r = $query->fetch(PDO::FETCH_ASSOC);
    return $r;
}

////////
function insert_comment($off_id, $username, $rate)
{
    $column = array('off_id', 'username', 'rate');
    $value = array($off_id, $username, $rate);
    return sql_insert('comments', $column, $value, '');
}

////////
function inactivate_comment($id, $username)
{
    $where_column = array('id', 'username', 'status');
    $where_value = array($id, $username, 1);
    $change_column = array('status');
    $change_value = array(0);
    sql_update('comments', $where_column, $where_value, $change_column, $change_value, '', '', '');
}

////////
function edit_comment($id, $username, $rate)
{
    $where_column = array('id', 'username', 'status');
    $where_value = array($id, $username, 1);
    $change_column = array('rate');
    $change_value = array($rate);
    sql_update('comments', $where_column, $where_value, $change_column, $change_value, '', '', '');
}