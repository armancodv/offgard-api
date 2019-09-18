<?php
// email /////////////////////////////////////////
/**
 * @param $to_email
 * @param $to_name
 * @param $subject
 * @param $body
 * @param $cc
 * @return bool
 * @throws phpmailerException
 */
function send_email($to_email, $to_name, $subject, $body, $cc)
{
    $mail = new PHPMailer;
    $mail->isSendmail();
    $mail->setLanguage('fa', 'PHPmailer/language/');
    $mail->CharSet = 'UTF-8';
    $mail->setFrom(EMAIL, H1_EN);
    $mail->addAddress($to_email, $to_name);
    foreach ($cc as $item) {
        $mail->AddCC($item['email'], $item['name']);
    }
    $mail->Subject = $subject;
    $mail->Body = email_begin() . nl2br($body) . email_end();
    $mail->IsHTML(true);
    if (!$mail->send()) {
        return FALSE;
    } else {
        return TRUE;
    }
}

////////
function email_begin()
{
    $return = '<html>'
        . '<body style="margin:0; background-color:#eeeeee; font-family:Tahoma ,Arial; font-size:12px;">'
        . '<table cellpadding="0" cellspacing="0" align="center" dir="rtl" style="background-color:#ffffff; width:750px; margin-top: 10px">'
        . '<tr style="height:120px;"><td style="background-color:#68A4C4; padding-right: 20px; color:#ffffff; padding-top: 10px" valign="middle"><font face="Tahoma, Arial"><b>' . H1 . '</b></font></td></tr>'
        . '<tr>'
        . '<td style="padding:20px 10px 20px 10px; vertical-align:text-top; border-top:1px solid #68A4C4; border-bottom:1px solid #eeeeee; font-size:14px;">'
        . '<font face="Tahoma, Arial">'
        . '<p>با سلام،</p><br>';
    return $return;
}

////////
function email_end()
{
    $return = '<br><p>با احترام.</p>'
        . '<p style="color:#777777;font-size:12px;">این ایمیل به صورت اتوماتیک توسط <b>شرکت آرمان سامان سپهر</b> ارسال شده است. لطفا به آن پاسخ ندهید.</p>'
        . '</font>'
        . '</td>'
        . '</tr>'
        . '<tr style="height:50px; background-color:#fbfbfb;">'
        . '<td style="padding:10px 10px 20px 10px; vertical-align:text-top; font-size:10px; color:#777777">'
        . '<font face="Tahoma, Arial">'
        . '<p style="font-weight:bold;">' . H1 . '</p>'
        . '<p>وبسایت: <a href="' . URL . '">' . URL . '</a></p>'
        . '</font>'
        . '</td>'
        . '</tr>'
        . '</table>'
        . '</body>'
        . '</html>';
    return $return;
}

////////
function email_verification($email)
{
    $query = select_user_byemail($email);
    $cc = array();
    while ($user = $query->fetch(PDO::FETCH_ASSOC)) {
        return send_email($email, $user['lastname'], 'Verification', ':برای تایید آدرس ایمیل روی لینک زیر کلیک نمایید
<a href="' . URL . 'verification.php?username=' . urlencode($user['username']) . '&email=' . urlencode($user['email']) . '&code=' . code_verification($user['username'], $email) . '" dir="ltr">' . info_url() . 'verification.php?username=' . urlencode($user['username']) . '&email=' . urlencode($user['email']) . '&code=' . code_verification($user['username'], $email) . '</a>'
            , $cc);
    }
}

////////
function email_forgot($username)
{
    $user = select_user($username);
    $cc = array();
    return send_email($user['email'], $user['lastname'], 'Forgot', 'نام کاربری:
' . $username . '
کلمه عبور:
' . $user['password'], $user['email'], $cc);
}