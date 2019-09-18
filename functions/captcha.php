<?php
// captcha /////////////////////////////////////////
function return_captcha($code)
{
    $bg_path = './captcha/';
    $font_path = './captcha/';
    $captcha_config = array(
        'code' => '',
        'min_length' => 5,
        'max_length' => 5,
        'backgrounds' => array(
            $bg_path . '45-degree-fabric.png',
            $bg_path . 'cloth-alike.png',
            $bg_path . 'grey-sandbag.png',
            $bg_path . 'kinda-jean.png',
            $bg_path . 'polyester-lite.png',
            $bg_path . 'stitched-wool.png',
            $bg_path . 'white-carbon.png',
            $bg_path . 'white-wave.png'
        ),
        'fonts' => array(
            $font_path . 'times_new_yorker.ttf'
        ),
        'characters' => 'ABCDEFGHJKLMNPRSTUVWXYZabcdefghjkmnprstuvwxyz23456789',
        'min_font_size' => 28,
        'max_font_size' => 28,
        'color' => '#666',
        'angle_min' => 0,
        'angle_max' => 10,
        'shadow' => true,
        'shadow_color' => '#fff',
        'shadow_offset_x' => -1,
        'shadow_offset_y' => 1
    );
    // Restrict certain values
    if ($captcha_config['min_length'] < 1) $captcha_config['min_length'] = 1;
    if ($captcha_config['angle_min'] < 0) $captcha_config['angle_min'] = 0;
    if ($captcha_config['angle_max'] > 10) $captcha_config['angle_max'] = 10;
    if ($captcha_config['angle_max'] < $captcha_config['angle_min']) $captcha_config['angle_max'] = $captcha_config['angle_min'];
    if ($captcha_config['min_font_size'] < 10) $captcha_config['min_font_size'] = 10;
    if ($captcha_config['max_font_size'] < $captcha_config['min_font_size']) $captcha_config['max_font_size'] = $captcha_config['min_font_size'];

    $captcha_config['code'] = $code;

    // Pick random background, get info, and start captcha
    $background = $captcha_config['backgrounds'][mt_rand(0, count($captcha_config['backgrounds']) - 1)];
    list($bg_width, $bg_height, $bg_type, $bg_attr) = getimagesize($background);

    $captcha = imagecreatefrompng($background);

    $color = imagecolorallocate($captcha, 100, 100, 100);

    // Determine text angle
    $angle = mt_rand($captcha_config['angle_min'], $captcha_config['angle_max']) * (mt_rand(0, 1) == 1 ? -1 : 1);

    // Select font randomly
    $font = $captcha_config['fonts'][mt_rand(0, count($captcha_config['fonts']) - 1)];

    // Verify font file exists
    if (!file_exists($font)) throw new Exception('Font file not found: ' . $font);

    //Set the font size.
    $font_size = mt_rand($captcha_config['min_font_size'], $captcha_config['max_font_size']);
    $text_box_size = imagettfbbox($font_size, $angle, $font, $captcha_config['code']);

    // Determine text position
    $box_width = abs($text_box_size[6] - $text_box_size[2]);
    $box_height = abs($text_box_size[5] - $text_box_size[1]);
    $text_pos_x_min = 0;
    $text_pos_x_max = ($bg_width) - ($box_width);
    $text_pos_x = mt_rand($text_pos_x_min, $text_pos_x_max);
    $text_pos_y_min = $box_height;
    $text_pos_y_max = ($bg_height) - ($box_height / 2);
    if ($text_pos_y_min > $text_pos_y_max) {
        $temp_text_pos_y = $text_pos_y_min;
        $text_pos_y_min = $text_pos_y_max;
        $text_pos_y_max = $temp_text_pos_y;
    }
    $text_pos_y = mt_rand($text_pos_y_min, $text_pos_y_max);

    // Draw shadow
    if ($captcha_config['shadow']) {
        $shadow_color = imagecolorallocate($captcha, 30, 30, 30);
        imagettftext($captcha, $font_size, $angle, $text_pos_x + $captcha_config['shadow_offset_x'], $text_pos_y + $captcha_config['shadow_offset_y'], $shadow_color, $font, $captcha_config['code']);
    }

    // Draw text
    imagettftext($captcha, $font_size, $angle, $text_pos_x, $text_pos_y, $color, $font, $captcha_config['code']);

    // Output image
    header("Content-type: image/png");
    imagepng($captcha);
}

////////
function insert_captcha()
{
    $code = token_captcha();
    $column = array('code');
    $value = array($code);
    return sql_insert('captcha', $column, $value, '');
}

////////
function check_captcha($id, $code)
{
    $column = array('id', 'code', 'status');
    $value = array($id, $code, 1);
    $return = sql_isset('captcha', $column, $value, '', '');
    inactivate_captcha($id);
    return $return;
}

////////
function inactivate_captcha($id)
{
    $where_column = array('id', 'status');
    $where_value = array($id, 1);
    $change_column = array('status');
    $change_value = array(0);
    sql_update('captcha', $where_column, $where_value, $change_column, $change_value, '', '', '');
}
////////
function select_captcha($id)
{
    $column = array('id', 'status');
    $value = array($id, 1);
    $query = sql_select('captcha', $column, $value, '', '', '');
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
}
