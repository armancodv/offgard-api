<?php
$sql_connection = sql_connect();
// MySQL /////////////////////////////////////////
function sql_connect()
{
    try {
        $sql_connection = new PDO('mysql:host=' . SQL_SERVER . ';dbname=' . SQL_SCHEMA . ';charset=utf8', SQL_USERNAME, SQL_PASSWORD);
        $sql_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $error) {
        $sql_connection = NULL;
        sql_error($error->getMessage());
    }
    return $sql_connection;
}

////////
function sql_disconnect()
{
    return NULL;
}

////////
function sql_error($error)
{
    $GLOBALS['sql_error'] = $error;
}

////////
function sql_insert($table, $column, $value, $extra)
{
    global $sql_connection;
    $column_string = '';
    $value_string = '';
    $data = array();
    for ($i = 0; $i < count($value); $i++) {
        $data[':v' . $i] = $value[$i];
        $column_string .= ',' . $column[$i];
        $value_string .= ',:v' . $i;
    }
    $column_string = substr($column_string, 1);
    $value_string = substr($value_string, 1);
    $query = $sql_connection->prepare('INSERT INTO ' . $table . ' (' . $column_string . ') VALUES (' . $value_string . ')' . $extra . ';');
    $query->execute($data);
    return $sql_connection->lastInsertId();
}

////////
function sql_update($table, $where_column, $where_value, $change_column, $change_value, $where_data_string, $change_data_string, $extra)
{
    global $sql_connection;
    $data = array();
    if ($where_data_string == '') {
        for ($i = 0; $i < count($where_value); $i++) {
            $data[':wv' . $i] = $where_value[$i];
            $where_data_string .= ' AND (' . $where_column[$i] . '=:wv' . $i . ')';
        }
        $where_data_string = substr($where_data_string, 5);
    } else {
        for ($i = 0; $i < count($where_value); $i++) {
            $data[':wv' . $i] = $where_value[$i];
        }
    }
    if ($change_data_string == '') {
        for ($i = 0; $i < count($change_value); $i++) {
            $data[':cv' . $i] = $change_value[$i];
            $change_data_string .= ' ,' . $change_column[$i] . '=:cv' . $i . '';
        }
        $change_data_string = substr($change_data_string, 2);
    } else {
        for ($i = 0; $i < count($change_value); $i++) {
            $data[':cv' . $i] = $change_value[$i];
        }
    }
    if ($where_data_string != '') {
        $query = $sql_connection->prepare('UPDATE ' . $table . ' SET ' . $change_data_string . ' WHERE ' . $where_data_string . '' . $extra . ';');
    } else {
        $query = $sql_connection->prepare('UPDATE ' . $table . ' SET ' . $change_data_string . $extra . ';');
    }
    $query->execute($data);
    return $sql_connection->lastInsertId();
}

////////
function sql_select($table, $column, $value, $output, $data_string, $extra)
{
    global $sql_connection;
    $data = array();
    if ($output == '') $output = '*';
    if ($data_string == '') {
        for ($i = 0; $i < count($value); $i++) {
            $data[':v' . $i] = $value[$i];
            $data_string .= ' AND (' . $column[$i] . '=:v' . $i . ')';
        }
        $data_string = substr($data_string, 5);
    } else {
        for ($i = 0; $i < count($value); $i++) {
            $data[':v' . $i] = $value[$i];
        }
    }
    if ($data_string != '') {
        $query = $sql_connection->prepare('SELECT ' . $output . ' FROM ' . $table . ' WHERE ' . $data_string . '' . $extra . ';');
    } else {
        $query = $sql_connection->prepare('SELECT ' . $output . ' FROM ' . $table . $extra . ';');
    }
    $query->execute($data);
    return $query;
}

////////
function sql_count($table, $column, $value, $data_string, $extra)
{
    $query = sql_select($table, $column, $value, 'COUNT(*) AS number', $data_string, $extra);
    $r = $query->fetch(PDO::FETCH_ASSOC);
    return $r['number'];
}

////////
function sql_isset($table, $column, $value, $data_string, $extra)
{
    $result = sql_count($table, $column, $value, $data_string, $extra);
    if ($result != 0) return TRUE;
    else return FALSE;
}

////////
function sql_query($query, $data)
{
    global $sql_connection;
    $query = $sql_connection->prepare($query);
    $query->execute($data);
    return $query;
}