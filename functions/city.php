<?php
// city /////////////////////////////////////////
function isset_city($code)
{
    $column = array('code', 'status');
    $value = array($code, 1);
    return sql_isset('cities', $column, $value, '', '');
}

////////
function select_city($code)
{
    $column = array('code', 'status');
    $value = array($code, 1);
    $query = sql_select('cities', $column, $value, '', '', '');
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
}

////////
function select_cities()
{
    $column = array('status');
    $value = array(1);
    $query = sql_select('cities', $column, $value, '', '', '');
    return $query;
}