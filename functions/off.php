<?php
// off /////////////////////////////////////////
function select_off($id)
{
    $column = array('id', 'status');
    $value = array($id, 1);
    $query = sql_select('offs', $column, $value, '', '', '');
    return $r = $query->fetch(PDO::FETCH_ASSOC);
}

////////
function select_offs_byuser($username)
{
    $column = array();
    $value = array($username);
    $query = sql_select('offs', $column, $value, '', '(username=:v0) AND (status!=0)', '');
    return $query;
}

////////
function select_offs($city, $category, $page_query)
{
    $data = array(':status' => 1,':date' => date('Y-m-d'));
    if ($city != '') {
        $data[':city'] = $city;
        $city_string = ' AND (offs.city_code=:city)';
    } else $city_string = '';
    if ($category != '') {
        $data[':category'] = $category;
        $category_string = ' AND (offs.category_code=:category)';
    } else $category_string = '';
    $query = sql_query('SELECT offs.id, offs.name, offs.image, offs.address, offs.description,
                        offs.off_min, offs.off_max, offs.date_from, offs.date_to, users.firstname,
                        users.lastname, categories.name AS category_name, cities.name AS city_name
                        FROM offs, users, categories, cities
                        WHERE (users.username=offs.username) AND (categories.code=offs.category_code) AND
                        (cities.code=offs.city_code) AND (offs.status=:status) AND (offs.date_to>:date)'
        . $city_string . $category_string .$page_query, $data);
    return $query;
}

////////
function numberof_offs($city, $category)
{
    $data = array(':status' => 1,':date' => date('Y-m-d'));
    if ($city != '') {
        $data[':city'] = $city;
        $city_string = ' AND (offs.city_code=:city)';
    } else $city_string = '';
    if ($category != '') {
        $data[':category'] = $category;
        $category_string = ' AND (offs.category_code=:category)';
    } else $category_string = '';
    $query = sql_query('SELECT COUNT(*) AS number
                        FROM offs, users, categories, cities
                        WHERE (users.username=offs.username) AND (categories.code=offs.category_code) AND
                        (cities.code=offs.city_code) AND (offs.status=:status) AND (offs.date_to>:date)'
        . $city_string . $category_string, $data);
    $r = $query->fetch(PDO::FETCH_ASSOC);
    return $r['number'];
}

////////
function is_off_foruser($id, $username)
{
    $column = array('id', 'username', 'status');
    $value = array($id, $username, 1);
    $query = sql_isset('offs', $column, $value, '', '');
    return $query;
}

////////
function insert_off($category_code, $city_code, $username, $name, $image, $address, $description, $latitude, $longitude, $off_min, $off_max, $date_from, $date_to)
{
    $column = array('category_code', 'city_code', 'username', 'name', 'image', 'address', 'description', 'latitude', 'longitude', 'off_min', 'off_max', 'date_from', 'date_to');
    $value = array($category_code, $city_code, $username, $name, $image, $address, $description, $latitude, $longitude, $off_min, $off_max, $date_from, $date_to);
    return sql_insert('offs', $column, $value, '');
}

////////
function inactivate_off($id, $username)
{
    $where_column = array('id', 'username', 'status');
    $where_value = array($id, $username, 1);
    $change_column = array('status');
    $change_value = array(0);
    sql_update('offs', $where_column, $where_value, $change_column, $change_value, '', '', '');
}

////////
function edit_off($id, $category_code, $username, $name, $image, $address, $description, $latitude, $longitude, $off_min, $off_max, $date_from, $date_to)
{
    $where_column = array('id', 'username', 'status');
    $where_value = array($id, $username, 1);
    $change_column = array('category_code', 'name', 'image', 'address', 'description', 'latitude', 'longitude', 'off_min', 'off_max', 'date_from', 'date_to');
    $change_value = array($category_code, $name, $image, $address, $description, $latitude, $longitude, $off_min, $off_max, $date_from, $date_to);
    sql_update('offs', $where_column, $where_value, $change_column, $change_value, '', '', '');
}

////////
function inactivate_image($id, $username)
{
    $where_column = array('id', 'username', 'status');
    $where_value = array($id, $username, 1);
    $change_column = array('image');
    $change_value = array(NULL);
    sql_update('offs', $where_column, $where_value, $change_column, $change_value, '', '', '');
}

////////
function isset_image($off_id)
{
    $off = select_off($off_id);
    if($off['image']!='') {
        return true;
    } else {
        return false;
    }
}

////////
function insert_image($id, $image)
{
    $where_column = array('id', 'status');
    $where_value = array($id, 1);
    $change_column = array('image');
    $change_value = array($image);
    sql_update('offs', $where_column, $where_value, $change_column, $change_value, '', '', '');
}