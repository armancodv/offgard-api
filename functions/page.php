<?php
// page /////////////////////////////////////////
function page_login()
{
    $error = array();
    array_push($error, valid_required('username'), valid_required('password')
        , valid_minlength('username', 3), valid_minlength('password', 3)
        , valid_maxlength('username', 100), valid_maxlength('password', 100)
        , valid_captcha(), valid_password());
    $error = array_filter($error, function ($var) {
        return !is_null($var);
    });
    if ($error != array()) {
        return_json(false, array('error' => reset($error)), 200);
    } else {
        $token = insert_login($GLOBALS['p']['username'], 1);
        $user = select_user($GLOBALS['p']['username']);
        return_json(true, array('username' => $GLOBALS['p']['username'], 'token' => $token, 'name' => $user['firstname'] . ' ' . $user['lastname'], 'email' => $user['email'], 'phone' => $user['phone'], 'success' => SUC_LOGIN), 200);
    }
}

////////
function page_logout()
{
    if (!check_login($_GET['username'], $_GET['token'])) {
        return_json(false, array('error' => ERR_LOGIN), 200);
    } else {
        insert_logout($_GET['username']);
        return_json(true, array(), 200);
    }
}

////////
function page_reset()
{
    $error = array();
    array_push($error, valid_captcha());
    $error = array_filter($error, function ($var) {
        return !is_null($var);
    });
    if ($error != array()) {
        return_json(false, array('error' => reset($error)), 200);
    } else {
        if (isset_user_byemail($GLOBALS['p']['email'])) {
            $user = select_user_byemail($GLOBALS['p']['email']);
            send_email($user['email'], $user['firstname'] . ' ' . $user['lastname'], 'reset password', URL . 'reset.php?username=' . urlencode($user['username']) . '&' . token_reset($user['username']), array());
            return_json(true, array('success' => SUC_RESET), 200);
        } else {
            return_json(false, array('error' => ERR_NOTISSETEMAIL), 200);
        }
    }
}

////////
function page_signup()
{
    $error = array();
    array_push($error, valid_required('username'), valid_required('password'), valid_required('firstname'), valid_required('lastname'), valid_required('email')
        , valid_minlength('username', 3), valid_minlength('password', 3), valid_minlength('firstname', 3), valid_minlength('lastname', 3), valid_minlength('email', 3)
        , valid_maxlength('username', 100), valid_maxlength('password', 100), valid_maxlength('firstname', 100), valid_maxlength('lastname', 100), valid_maxlength('email', 100), valid_maxlength('phone', 20)
        , valid_captcha(), valid_issetusername(), valid_issetemail());
    $error = array_filter($error, function ($var) {
        return !is_null($var);
    });
    if ($error != array()) {
        return_json(false, array('error' => reset($error)), 200);
    } else {
        insert_user($GLOBALS['p']['username'], $GLOBALS['p']['firstname'], $GLOBALS['p']['lastname'], $GLOBALS['p']['email'], $GLOBALS['p']['phone'], $GLOBALS['p']['password']);
        send_email($GLOBALS['p']['email'], $GLOBALS['p']['firstname'] . ' ' . $GLOBALS['p']['lastname'], 'verify', URL . 'verify.php?username=' . urlencode($GLOBALS['p']['username']) . '&email=' . urlencode($GLOBALS['p']['email']) . '&code=' . token_verify($GLOBALS['p']['username'], $GLOBALS['p']['email']), array());
        $token = insert_login($GLOBALS['p']['username'], 1);
        return_json(true, array('username' => $GLOBALS['p']['username'], 'token' => $token, 'name' => $GLOBALS['p']['firstname'] . ' ' . $GLOBALS['p']['lastname'], 'email' => $GLOBALS['p']['email'], 'phone' => $GLOBALS['p']['phone'], 'success' => SUC_SIGNUP), 200);
    }
}

////////
function page_edituser()
{
    if (!check_login($_GET['username'], $_GET['token'])) {
        return_json(false, array('error' => ERR_LOGIN), 200);
    } else {
        $error = array();
        array_push($error, valid_required('password'), valid_required('firstname'), valid_required('lastname')
            , valid_minlength('password', 3), valid_minlength('firstname', 3), valid_minlength('lastname', 3)
            , valid_maxlength('password', 100), valid_maxlength('firstname', 100), valid_maxlength('lastname', 100));
        $error = array_filter($error, function ($var) {
            return !is_null($var);
        });
        if ($error != array()) {
            return_json(false, array('error' => reset($error)), 200);
        } else {
            edit_user($_GET['username'], $GLOBALS['p']['firstname'], $GLOBALS['p']['lastname'], $GLOBALS['p']['password']);
            return_json(true, array('email' => $GLOBALS['p']['email'], 'phone' => $GLOBALS['p']['phone'], 'success' => SUC_EDITUSER), 200);
        }
    }
}

////////
function page_captcha()
{
    $id = insert_captcha();
    return_json(true, array('captcha_id' => $id), 200);
}

////////
function page_myoffs()
{
    if (!check_login($_GET['username'], $_GET['token'])) {
        return_json(false, array('error' => ERR_LOGIN), 200);
    } else {
        $return = array();
        $query = select_offs_byuser($_GET['username']);
        while ($r = $query->fetch(PDO::FETCH_ASSOC)) {
            $r['id'] = intval($r['id']);
            $r['latitude'] = intval($r['latitude']);
            $r['longitude'] = intval($r['longitude']);
            $r['off_min'] = intval($r['off_min']);
            $r['off_max'] = intval($r['off_max']);
            if ($r['status'] == 1) $r['status'] = 'منتشر شده';
            else if ($r['status'] == 2) $r['status'] = 'در حال بررسی';
            else if ($r['status'] == 3) $r['status'] = 'عدم انتشار';
            array_push($return, $r);
        }
        return_json(true, array('result' => $return, 'date' => date('Y-m-d')), 200);
    }
}

////////
function page_offs()
{
    $return = array();
    $pg_data = pg_data($_GET['page'], 20, numberof_offs($GLOBALS['p']['city'], $GLOBALS['p']['category']));
    $query = select_offs($GLOBALS['p']['city'], $GLOBALS['p']['category'], $pg_data['query']);
    while ($r = $query->fetch(PDO::FETCH_ASSOC)) {
        $rate = show_rate_byoff($r['id']);
        $r['rate'] = $rate;
        array_push($return, $r);
    }
    return_json(true, array('result' => $return, 'page' => $pg_data, 'date' => date('Y-m-d')), 200);
}

////////
function page_addoff()
{
    if (!check_login($_GET['username'], $_GET['token'])) {
        return_json(false, array('error' => ERR_LOGIN), 200);
    } else {
        $error = array();
        array_push($error, valid_required('category_code'), valid_required('city_code'), valid_required('name'), valid_required('off_min'), valid_required('off_max')
            , valid_minlength('category_code', 3), valid_minlength('city_code', 3), valid_minlength('name', 3)
            , valid_maxlength('category_code', 100), valid_maxlength('city_code', 100), valid_maxlength('name', 100), valid_maxlength('description', 200), valid_maxlength('address', 200)
            , valid_minnum('off_min', 0), valid_minnum('off_max', 1)
            , valid_maxnum('off_min', 99), valid_maxnum('off_max', 99)
            , valid_integer('off_min'), valid_integer('off_max')
        );
        $error = array_filter($error, function ($var) {
            return !is_null($var);
        });
        if ($error != array()) {
            return_json(false, array('error' => reset($error)), 200);
        } else {
            $today = date('Y-m-d');
            $month = date('Y-m-d', strtotime('30 days', strtotime($today)));
            $two_month = date('Y-m-d', strtotime('60 days', strtotime($today)));
            if (($GLOBALS['p']['date_from'] == '') || (strtotime($GLOBALS['p']['date_from']) < $today)) $GLOBALS['p']['date_from'] = $today;
            if (($GLOBALS['p']['date_to'] == '') || (strtotime($GLOBALS['p']['date_to']) > $two_month)) $GLOBALS['p']['date_to'] = $month;
            insert_off($GLOBALS['p']['category_code'], $GLOBALS['p']['city_code'], $_GET['username'], $GLOBALS['p']['name'], '', $GLOBALS['p']['address'], $GLOBALS['p']['description'], $GLOBALS['p']['latitude'], $GLOBALS['p']['longitude'], $GLOBALS['p']['off_min'], $GLOBALS['p']['off_max'], $GLOBALS['p']['date_from'], $GLOBALS['p']['date_to']);
            return_json(true, array('success' => SUC_ADDOFF), 200);
        }
    }
}

////////
function page_editoff()
{
    if (!check_login($_GET['username'], $_GET['token'])) {
        return_json(false, array('error' => ERR_LOGIN), 200);
    } else {
        $error = array();
        array_push($error, valid_required('category_code'), valid_required('name'), valid_required('off_min'), valid_required('off_max')
            , valid_minlength('category_code', 3), valid_minlength('name', 3)
            , valid_maxlength('category_code', 100), valid_maxlength('name', 100), valid_maxlength('phone', 20), valid_maxlength('website', 100)
            , valid_minnum('off_min', 0), valid_minnum('off_max', 1)
            , valid_maxnum('off_min', 99), valid_maxnum('off_max', 99)
            , valid_integer('off_min'), valid_integer('off_max')
        );
        $error = array_filter($error, function ($var) {
            return !is_null($var);
        });
        if ($error != array()) {
            return_json(false, array('error' => reset($error)), 200);
        } else {
            $off = select_off($GLOBALS['p']['id']);
            $today = date('Y-m-d');
            $month = date('Y-m-d', strtotime('30 days', strtotime($today)));
            $two_month = date('Y-m-d', strtotime('60 days', strtotime($today)));
            if (($GLOBALS['p']['date_from'] == '') || (strtotime($GLOBALS['p']['date_from']) < $today)) $GLOBALS['p']['date_from'] = $today;
            if (($GLOBALS['p']['date_to'] == '') || (strtotime($GLOBALS['p']['date_to']) > $two_month)) $GLOBALS['p']['date_to'] = $month;
            edit_off($GLOBALS['p']['id'], $GLOBALS['p']['category_code'], $_GET['username'], $GLOBALS['p']['name'], $off['image'], $GLOBALS['p']['address'], $GLOBALS['p']['description'], $GLOBALS['p']['latitude'], $GLOBALS['p']['longitude'], $GLOBALS['p']['off_min'], $GLOBALS['p']['off_max'], $GLOBALS['p']['date_from'], $GLOBALS['p']['date_to']);
            return_json(true, array('success' => SUC_EDITOFF), 200);
        }
    }
}

////////
function page_deleteoff()
{
    if (!check_login($_GET['username'], $_GET['token'])) {
        return_json(false, array('error' => ERR_LOGIN), 200);
    } else {
        if (is_off_foruser($GLOBALS['p']['id'], $_GET['username'])) {
            $off = select_off($GLOBALS['p']['id']);
            if ($off['image'] != '') unlink($off['image']);
            inactivate_off($GLOBALS['p']['id'], $_GET['username']);
            return_json(true, array(), 200);
        } else {
            return_json(false, array('error' => ERR_ACCESS), 200);
        }
    }
}

////////
function page_comments()
{
    $return = array();
    $query = select_comments_byoff($GLOBALS['p']['off_id']);
    while ($r = $query->fetch(PDO::FETCH_ASSOC)) {
        array_push($return, array('username' => $r['username'], 'rate' => $r['rate']));
    }

    return_json(true, array('result' => $return), 200);
}

////////
function page_addcomment()
{
    if (!check_login($_GET['username'], $_GET['token'])) {
        return_json(false, array('error' => ERR_LOGIN), 200);
    } else {
        $error = array();
        array_push($error, valid_required('rate')
            , valid_minnum('rate', -1)
            , valid_maxnum('rate', 1)
            , valid_integer('rate')
        );
        $error = array_filter($error, function ($var) {
            return !is_null($var);
        });
        if ($error != array()) {
            return_json(false, array('error' => reset($error)), 200);
        } else {
            if (!isset_comment($GLOBALS['p']['off_id'], $_GET['username'])) {
                insert_comment($GLOBALS['p']['off_id'], $_GET['username'], $GLOBALS['p']['rate']);
            } else {
                edit_comment($GLOBALS['p']['off_id'], $_GET['username'], $GLOBALS['p']['rate']);
            }
            return_json(true, array(), 200);
        }
    }
}

////////
function page_deletecomment()
{
    if (!check_login($_GET['username'], $_GET['token'])) {
        return_json(false, array('error' => ERR_LOGIN), 200);
    } else {
        inactivate_comment($GLOBALS['p']['id'], $_GET['username']);
        return_json(true, array(), 200);
    }
}


////////
function page_addimage()
{
    if (!check_login($_GET['username'], $_GET['token'])) {
        return_json(false, array('error' => ERR_LOGIN), 200);
    } else {
        $error = array();
        $date2 = date("YmdHis");
        $uploaddir = 'upload/';
        $image = $uploaddir . urlencode($date2 . $_GET['username'] . md5($date2 . $_FILES['image']['name'] . md5($_FILES['image']['name'])) . '-' . basename($_FILES['image']['name']));
        if (!is_off_foruser($_GET['off_id'],$_GET['username'])) array_push($error, ERR_ACCESS);
        else if (($_FILES['image']['size'] > 500000)) array_push($error, ERR_IMAGESIZE);
        else if (($_FILES['image']['name'] != '') && (pathinfo($image, PATHINFO_EXTENSION) != 'jpg') && (pathinfo($image, PATHINFO_EXTENSION) != 'png') && (pathinfo($image, PATHINFO_EXTENSION) != 'jpeg') && (pathinfo($image, PATHINFO_EXTENSION) != 'gif')) array_push($error, ERR_IMAGEEXT);
        else if (($_FILES['image']['name'] != '') && (!move_uploaded_file($_FILES['image']['tmp_name'], $image))) array_push($error, ERR_IMAGEUPLOAD);
        $error = array_filter($error, function ($var) {
            return !is_null($var);
        });
        if ($error != array()) {
            return_json(false, array('error' => reset($error)), 200);
        } else {
            if (!isset_image($_GET['off_id'])) {
                insert_image($_GET['off_id'], $image);
            } else {
                $off = select_off($_GET['off_id']);
                unlink($off['image']);
                insert_image($_GET['off_id'], $image);
            }
            return_json(true, array(), 200);
        }
    }
}

////////
function page_deleteimage()
{
    if (!check_login($_GET['username'], $_GET['token'])) {
        return_json(false, array('error' => ERR_LOGIN), 200);
    } else {
        if (is_off_foruser($_GET['off_id'], $_GET['username'])) {
            $off = select_off($_GET['off_id']);
            if ($off['image'] != '') unlink($off['image']);
            inactivate_image($_GET['off_id'], $_GET['username']);
            return_json(true, array(), 200);
        } else {
            return_json(false, array('error' => ERR_ACCESS), 200);
        }
    }
}

////////
function page_categories()
{
    $return = array();
    $query = select_categories();
    while ($r = $query->fetch(PDO::FETCH_ASSOC)) {
        array_push($return, array('code' => $r['code'], 'name' => $r['name']));
    }
    return_json(true, array('result' => $return), 200);
}

////////
function page_cities()
{
    $return = array();
    $query = select_cities();
    while ($r = $query->fetch(PDO::FETCH_ASSOC)) {
        array_push($return, array('code' => $r['code'], 'name' => $r['name']));
    }
    return_json(true, array('result' => $return), 200);
}

////////
function page_advertisements()
{
    $return = array();
    $query = select_advertisements_byplan('a1');
    while ($r = $query->fetch(PDO::FETCH_ASSOC)) {
        array_push($return['a1'], array('path' => $r['path']));
    }
    $query = select_advertisements_byplan('b1');
    while ($r = $query->fetch(PDO::FETCH_ASSOC)) {
        array_push($return['b1'], array('path' => $r['path']));
    }
    return_json(true, array('result' => $return), 200);
}

////////
function page_notfound()
{
    return_json(false, array('error' => ERR_404), 404);
}
////////