<?php
// advertisement /////////////////////////////////////////
function select_advertisements_byplan($plan)
{
    $date = date('Y-m-d');
    $column = array('plan', 'date', 'status');
    $value = array($plan, $date, 1);
    $query = sql_select('advertisements', $column, $value, 'path', '(plan=:v0) AND (start_date<:v1) AND (end_date<:v1) AND (status=:v2)', '');
    return $query;
}