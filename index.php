<?php
include('functions.php');

// api /////////////////////////////////////////
header('Content-Type: application/json; charset=utf-8');
$GLOBALS['p'] = json_decode(file_get_contents('php://input'), true);
if (($_GET['p'] == 'captcha') && ($_GET['m'] == 'get')) {
    page_captcha();
} else if (($_GET['p'] == 'login') && ($_GET['m'] == 'post')) {
    page_login();
} else if (($_GET['p'] == 'login') && ($_GET['m'] == 'delete')) {
    page_logout();
} else if (($_GET['p'] == 'reset') && ($_GET['m'] == 'post')) {
    page_reset();
} else if (($_GET['p'] == 'user') && ($_GET['m'] == 'post')) {
    page_signup();
} else if (($_GET['p'] == 'user') && ($_GET['m'] == 'put')) {
    page_edituser();
} else if (($_GET['p'] == 'myoff') && ($_GET['m'] == 'get')) {
    page_myoffs();
} else if (($_GET['p'] == 'off') && ($_GET['m'] == 'get')) {
    page_offs();
} else if (($_GET['p'] == 'off') && ($_GET['m'] == 'post')) {
    page_addoff();
} else if (($_GET['p'] == 'off') && ($_GET['m'] == 'put')) {
    page_editoff();
} else if (($_GET['p'] == 'off') && ($_GET['m'] == 'delete')) {
    page_deleteoff();
} else if (($_GET['p'] == 'comment') && ($_GET['m'] == 'get')) {
    page_comments();
} else if (($_GET['p'] == 'comment') && ($_GET['m'] == 'post')) {
    page_addcomment();
} else if (($_GET['p'] == 'comment') && ($_GET['m'] == 'delete')) {
    page_deletecomment();
} else if (($_GET['p'] == 'image') && ($_GET['m'] == 'post')) {
    page_addimage();
} else if (($_GET['p'] == 'image') && ($_GET['m'] == 'delete')) {
    page_deleteimage();
} else if (($_GET['p'] == 'category') && ($_GET['m'] == 'get')) {
    page_categories();
} else if (($_GET['p'] == 'city') && ($_GET['m'] == 'get')) {
    page_cities();
} else if (($_GET['p'] == 'advertisement') && ($_GET['m'] == 'get')) {
    page_advertisements();
} else {
    page_notfound();
}