<?php
// pagination /////////////////////////////////////////
function pg_skip_elements($page, $element_per_page)
{
    return ($page - 1) * $element_per_page;
}

////////
function pg_last($element_per_page, $total)
{
    $last = floor($total / $element_per_page);
    if ($last * $element_per_page < $total) $last++;
    return $last;
}

////////
function page_url($page)
{
    $query = $_GET;
    $query['page'] = $page;
    $query_result = http_build_query($query);
    return $_SERVER['PHP_SELF'] . '?' . $query_result;
}

////////
function pg_data($page, $element_per_page, $total)
{
    if (isset($page)) $page = (int)$page;
    else $page = 1;
    $last_page = pg_last($element_per_page, $total);
    $skip_elements = pg_skip_elements($page, $element_per_page);
    if (($page > 2) && ($page < $last_page)) $show_first_page = true;
    else $show_first_page = false;
    if ($page > 1) $show_previous_page = true;
    else $show_previous_page = false;
    if (($page > 0) && ($page < $last_page - 1)) $show_last_page = true;
    else $show_last_page = false;
    if ($page < $last_page) $show_next_page = true;
    else $show_next_page = false;
    $data['page'] = $page;
    $data['element_per_page'] = $element_per_page;
    $data['total_elements'] = $total;
    $data['skip_elements'] = $skip_elements;
    $data['show_next_page'] = $show_next_page;
    $data['show_first_page'] = $show_first_page;
    $data['show_last_page'] = $show_last_page;
    $data['show_previous_page'] = $show_previous_page;
    $data['first_page'] = 1;
    $data['previous_page'] = $page - 1;
    $data['next_page'] = $page + 1;
    $data['last_page'] = $last_page;
    $data['query'] = ' LIMIT ' . $skip_elements . ',' . $element_per_page;
    return $data;
}
