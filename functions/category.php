<?php
// category /////////////////////////////////////////
function isset_category($code)
{
    $column = array('code', 'status');
    $value = array($code, 1);
    return sql_isset('categories', $column, $value, '', '');
}

////////
function select_category($code)
{
    $column = array('code', 'status');
    $value = array($code, 1);
    $query = sql_select('categories', $column, $value, '', '', '');
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
}

////////
function select_categories()
{
    $column = array('status');
    $value = array(1);
    $query = sql_select('categories', $column, $value, '', '', '');
    return $query;
}